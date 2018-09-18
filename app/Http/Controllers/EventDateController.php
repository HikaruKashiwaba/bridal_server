<?php

namespace App\Http\Controllers;

use DB;
use App\Account;
use App\Fiar;
use App\FairEventDate;
use App\Group;
use App\GroupEventDate;
use App\PublicHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventDateController extends Controller
{
  //フェア開催日を取得する
  public function getGroupEventDate(string $memberId, Request $request) {
    Log::debug('EventDateController.getGroupEventDate- START ->');
      $group_list = Group::where('member_id',  $memberId)->get();
      $res = [];

      Log::debug($group_list);

      for ($i = 0; $i < count($group_list); $i++) {
          $event_date = GroupEventDate::where('group_id', $group_list[$i]['id'])->get();
          Log::debug($event_date);
          $group_event_date = [];
          $group_event_date['group_id'] = $group_list[$i]['id'];
          $group_event_date['group_name'] = $group_list[$i]['group_name'];
          $group_event_date['group_event_date'] = $event_date->toArray();
          $res[$i] = $group_event_date;
      }
      Log::debug($res);
      Log::debug('EventDateController.getGroupEventDate-  END  ->');
      return response()->json($res, 200);
  }

    //フェア開催日を取得する
    public function getFairEventDate(string $memberId, string $groupId, Request $request) {
      Log::debug('EventDateController.getFairEventDate- START ->');
        $fair_list = Fiar::where('member_id',  $memberId)->where(['group_id',  $groupId])->get();
        $res = [];

        for ($i = 0; $i < count($fair_list); $i++) {
            $event_date = FairEventDate::where('fair_id', $fair_list[$i]['id'])->get();
            $fair_event_date = [];
            $fair_event_date['fair_id'] = $fair_list[$i]['id'];
            $fair_event_date['fair_event_date'] = $event_date->toArray();
            $res[$i] = $fair_event_date;
        }
        Log::debug('EventDateController.getFairEventDate-  END  ->');
        return response()->json($res, 200);
    }

    //フェア開催日を取得する
    public function updateGroupEventDate(string $memberId, string $groupId, Request $request) {
      Log::debug('EventDateController.updateGroupEventDate- START ->');
      DB::beginTransaction();

      try {
          $params = $request->input('groups');
          $json_count = count($params);
          $account = null;
          Log::debug($params);

          // 全サイトのアカウントを取得
          $items = Account::where('member_id', $memberId)->get();

          //アカウント情報の更新
          for($i = 0; $i < $json_count; $i++){
              $groupId = $params[$i]['groupId'];
              // パラメータからグループ配下の開催日の一覧を取得
              $eventDateList = $params[$i]['eventDate'];

              // グループ配下のフェアを取得する
              $fairList = Fair::where('group_id', $groupId)->get();
              Log::debug($fairList);

              // 開催日の登録を行う
              for($j = 0; $j < count($eventDateList); $j++) {
                // 登録済みのレコードがあれば取得し、無ければ新規に生成する
                $eventDate = GroupEventDate::firstOrNew(
                  ['member_id' => $memberId, 'group_id' => $groupId, 'date' => $eventDateList[$j]['date']]
                );
                Log::debug($eventDate);

                if ($eventDate['id'] != null && $eventDateList[$j]['del_flg'] == '1') {
                  // 未反映の場合のみ、del_flgが弥ので、その場合はレコードを削除
                  $eventDate->delete();

                  for($k = 0; $k < count($fairList); $k++) {
                    $fairEventDate = FairEventDate::firstOrNew(
                      ['member_id' => $memberId, 'fair_id' => $fairList[$k]['id'], 'date' => $eventDateList[$j]['date']]
                    );
                    Log::debug($fairEventDate);

                    // レコード登録済み、未反映、個別設定でない場合削除する
                    if ($fairEventDate['id'] != null
                        && $fairEventDate['reflect_flg'] == '0'
                        && $fairEventDate['lock_flg'] == '0') {
                        $fairEventDate->delete();
                    }
                  }
                } else {
                  $eventDate['reflect_flg'] = $eventDateList[$j]['reflect_flg'];
                  $eventDate['lock_flg'] = $eventDateList[$j]['lock_flg'];
                  $eventDate['stop_flg'] = $eventDateList[$j]['stop_flg'];
                  $eventDate->save();

                  for($k = 0; $k < count($fairList); $k++) {
                    $fairEventDate = FairEventDate::firstOrNew(
                      ['member_id' => $memberId, 'fair_id' => $fairList[$k]['id'], 'date' => $eventDateList[$j]['date']]
                    );
                    Log::debug($fairEventDate);
                    // レコード未登録、または個別設定でない場合登録する
                    if ($fairEventDate['id'] == null
                        || $fairEventDate['lock_flg'] == '0') {
                          $fairEventDate['reflect_flg'] = $eventDateList[$j]['reflect_flg'];
                          $fairEventDate['stop_flg'] = $eventDateList[$j]['stop_flg'];
                          $fairEventDate->save();
                    }
                  }
                }
              }
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
      Log::debug('EventDateController.updateGroupEventDate-  END  ->');
      return response()->json($result, 200);
    }

    //フェア開催日を取得する
    public function getHoliday(Request $request) {
      $items = PublicHoliday::all();
      return $items;
    }

}
