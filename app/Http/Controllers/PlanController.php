<?php

namespace App\Http\Controllers;

use App\Plan;
use App\PlanContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;

class PlanController extends Controller {

    /*  ユーザに紐づくプラン一覧を取得する*/
    public function getPlanList(Request $request, string $memberId) {
        $items = Plan::where('member_id', $memberId)->get();
        for ($i = 0; $i < count($items); $i++) {
        //     $items[$i]->image;
        //     $items[$i]->planContent;
        //     Log::debug($items[$i]);
        //     if ($items[$i]['zexy_flg'] == '1') {
        //       $items[$i]->planZexy;
        //     }
        //     if ($items[$i]['weddingpark_flg'] == '1') {
        //       $items[$i]->planWeddingpark;
        //     }
        //     if ($items[$i]['mynavi_flg'] == '1') {
        //       $items[$i]->planMynavi;
        //     }
        //     if ($items[$i]['gurunavi_flg'] == '1') {
        //       $items[$i]->planGurunavi;
        //     }
        //     if ($items[$i]['rakuten_flg'] == '1') {
        //       $items[$i]->planRakuten;
        //     }
        //     if ($items[$i]['minna_flg'] == '1') {
        //       $items[$i]->planMinna;
        //     }
        //     $items[$i]->plan_event_date = FairEventDate::where('fair_id', $items[$i]->id)
        //         ->where('del_flg', '0')->get();
        }
        Log::debug($items[0]);
        return response()->json(['records' => $items], 200);
    }

    /**
     * プラン登録
     * プラン内容の登録・更新・削除
     *
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function store(Request $request, string $memberId) {
        DB::beginTransaction();
        try {
            $params = json_decode(file_get_contents('php://input'), true);
        
            Log::debug('store-->');
            Log::debug("-- Parameters START -->");
            Log::debug($params);
            Log::debug("-- Parameters E N D -->");

            $plan = new Plan;
            Log::debug($plan);
            Log::debug($memberId);
            $plan->member_id = $memberId;

            $plan->plan_name = $params['plan_name'];
            $plan->plan_title = $params['plan_title'];
            $plan->plan_detail = $params['plan_detail'];
            $plan->price = $params['price'];
            $plan->remarks = $params['remarks'];
            $plan->number_people = $params['number_people'];
            $plan->plus_one = $params['plus_one'];
            $plan->minus_one = $params['minus_one'];
            $plan->published_start = $params['published_start'];
            $plan->published_end = $params['published_end'];
            $plan->style = $params['style'];
            $plan->weddingpark_flg = $params['weddingpark_flg'];
            $plan->mynavi_flg = $params['mynavi_flg'];
            $plan->gurunavi_flg = $params['gurunavi_flg'];
            $plan->zexy_flg = $params['zexy_flg'];
            $plan->minna_flg = $params['minna_flg'];
            $plan->save();

            // パラメータに含まれるフェア内容情報を取得する
            $planContents = $params['plan_content'];
            //新規登録
            for ($j = 0; $j < count($planContents); $j++) {
                $planContent = $planContents[$j];
                $plan_content = new PlanContent;
                $plan_content->order_id = $planContent['order_id'];
                $plan_content->check_box = $planContent['check_box'];
                $plan_content->privilege_text = $planContent['privilege_text'];
                $plan_content->save();
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
        return response()->json(['dummy' => 'ok'], 200);
    }
}