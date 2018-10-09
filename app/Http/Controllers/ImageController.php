<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use DB;
use File;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    //画像を受け取る
    public function upload(string $memberId, Request $request) {
        //ファイルのバリデーション
        $this->validate($request, [
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg',
                // 'dimensions:min_width=120,min_height=120,max_width=400,max_height=400',
            ]
        ]);

        $file = $request->file('image');
       Log::debug($file);

        //バリデーション突破
        if ($file->isValid([])) {
            //新たにランダムなファイル名を命名する
            // ぐるなびが「jpg」以外エラーになるため拡張子は固定
            $new_file_name = uniqid() . ".jpg";
            //仮のファイル置き場に移動させる
            $file->move(public_path() . "/var/tmp/fair", $new_file_name);
            //初回のみ仮のファイル置き場作成
            if (!file_exists(public_path() . "/img")) {
                mkdir(public_path() . "/img");
            }
            if (!file_exists(public_path() . "/img/fair")) {
                mkdir(public_path() . "/img/fair");
            }
            if (!file_exists(public_path() . "/img/fair/" . $memberId)) {
                mkdir(public_path() . "/img/fair/" . $memberId);
            }

            //画像のパスをDBに登録する
            $image_all = Image::where('member_id', $memberId)->get();
            $image = new Image;
            $image->member_id = $memberId;
            $image->file_name = $new_file_name;
            $image->save();
            Log::debug($image);

            //本番用ファイル置き場に移動する
            File::move(public_path() . "/var/tmp/fair/" . $new_file_name, public_path() . "/img/fair/" . $memberId . "/" . $new_file_name);

            return response()->json(['id' => $image->id, 'file_name' => $image->file_name], 200);
        }
    }

    //画像一覧の情報を返却する
    public function getAllImage($memberId) {
        $image_all = Image::where('member_id', $memberId)->get();
        Log::debug('member_id ='.$memberId);
        Log::debug($image_all);
        return response()->json($image_all, 200);
    }

    //画像一覧の情報を返却する
    public function getAllImageWithCount($memberId) {
        $image_all = Image::where('member_id', $memberId)->get();
        Log::debug('member_id ='.$memberId);

        $count = count($image_all);
        $response = array();
        for ($i = 0; $i < $count; $i++) {
            $rec = $image_all[$i];
            $rec['fair_count'] = $image_all[$i]->fairs()->count();

            $referCount = $image_all[$i]->fairContents()->count() + $image_all[$i]->fairContents2()->count() + $image_all[$i]->fairContents3()->count();
            $rec['fair_content_count'] = $referCount;
        }
        Log::debug($image_all);
        return response()->json($image_all, 200);
    }

    //個別の画像リクエストに応じて画像を取得して返す
    public function getImage($memberId, $fileId) {
        $image = Image::where('member_id', $memberId)->where('id', $fileId)->first();
        //画像がない場合
        if ($image == null) {
            return response()->json(['message' => 'no images']);
        }
        return response()->download(public_path() . '/img/fair/' . $memberId . '/' . $image->file_name);
    }

    //個別の画像リクエストに応じて画像データを更新する
    public function updateImage($memberId, $fileId, Request $request) {
      DB::beginTransaction();

      try {
          $image = null;
          Log::debug('member_id='.$memberId);
          Log::debug('file_id='.$fileId);

          //画像情報の更新
          $image = Image::where('member_id', $memberId)->where('id', $fileId)->first();

          if ($image != null) {
            $image['image_zexy_id'] = $request->input('imageZexyId');
            $image['image_gurunavi_id'] = $request->input('imageGurunaviId');
            $image['image_rakuten_id'] = $request->input('imageRakutenId');
            $image->save();
          }

          $result = [
              'code' => 'OK',
              'message' => ''
          ];
          DB::commit();
      } catch(Exception $e) {
          DB::rollBack();
          $result = [
              'code' => 'NG',
              'message' => $e->getMessage()
          ];
      }
      return response()->json($result, 200);
    }

    //個別の画像リクエストに応じて画像データを更新する
    public function deleteImage($memberId, $fileId) {
      DB::beginTransaction();

      try {
          $image = null;

          //画像情報の更新
          $image = Image::where('member_id', $memberId)->where('id', $fileId)->first();

          if ($image != null) {
            $image->delete();
          }

          $result = [
              'code' => 'OK',
              'message' => ''
          ];
          DB::commit();
      } catch(Exception $e) {
          DB::rollBack();
          $result = [
              'code' => 'NG',
              'message' => $e->getMessage()
          ];
      }
      return response()->json($result, 200);
    }
}
