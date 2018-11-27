<?php

namespace App\Http\Controllers;

use App\GroupEventDate;
use App\Fair;
use App\FairContent;
use App\FairContentPart;
use App\FairContentDetail;
use App\FairWeddingpark;
use App\FairMynavi;
use App\FairGurunavi;
use App\FairRakuten;
use App\FairZexy;
use App\FairMinna;
use App\FairEventDate;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;

class FairController extends Controller
{
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */

    const FAIR_TABLE_NAME = [
        'fair_zexy',
        'fair_weddingpark',
        'fair_mynavi',
        'fair_gurunavi',
        'fair_minna',
        'fair_rakuten'
    ];

    const FAIR_FLG = [
        'zexy_flg',
        'weddingpark_flg',
        'mynavi_flg',
        'gurunavi_flg',
        'minna_flg',
        'rakuten_flg'
    ];

    const SITE_TYPE = [
        'ZEXY' => '1',
        'WEDDINGPARK' => '2',
        'MYNAVI' => '3',
        'GURUNAVI' => '4',
        'RAKUTEN' => '5',
        'MINNA' => '6'
    ];


    const UNCHECKED = '0';
    const ENABLED = '1';
    const DISABLED = '2';


    /*  ユーザに紐づくフェア一覧を取得する  */
    public function getFairList(string $memberId) {
        $items = Fair::where('member_id', $memberId)->get();
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]->image;
            $items[$i]->fairContent;
            if ($items[$i]['zexy_flg'] == '1') {
              $items[$i]->fairZexy;
            }
            if ($items[$i]['weddingpark_flg'] == '1') {
              $items[$i]->fairWeddingpark;
            }
            if ($items[$i]['mynavi_flg'] == '1') {
              $items[$i]->fairMynavi;
            }
            if ($items[$i]['gurunavi_flg'] == '1') {
              $items[$i]->fairGurunavi;
            }
            if ($items[$i]['rakuten_flg'] == '1') {
              $items[$i]->fairRakuten;
            }
            if ($items[$i]['minna_flg'] == '1') {
              $items[$i]->fairMinna;
            }
            $items[$i]->fairEventDate;
            Log::debug($items[$i]->fairEventDate);
        }
        Log::debug($items[0]);
        return response()->json(['records' => $items], 200);
    }


    /*  一つのフェア詳細情報を取得する  */
    public function getFair(string $memberId, string $fairId) {
        //Fairからユーザと各サイト登録情報を取得する
        $fair = Fair::where('id', $fairId)->where('member_id', $memberId)->first();
        $fair->image;
        $fair->fairContent;
        Log::debug($fair);
        //$fairが持つ、各サイトの登録フラグを元にフェア情報を取得する
        //ゼクシイに登録してある場合
        if ($fair->zexy_flg != self::UNCHECKED) {
            $fair->fair_zexy = FairZexy::where('fair_id', $fairId)->first();
            $fair->fair_zexy->attentionPointImage;
        }
        Log::debug($fair->fair_zexy);

        //ウェディングパークに登録してある場合
        if ($fair->weddingpark_flg != self::UNCHECKED) {
            $fair->fair_weddingpark = FairWeddingPark::where('fair_id', $fairId)->first();
        }
        //マイナビに登録してある場合
        if ($fair->mynavi_flg != self::UNCHECKED) {
            Log::debug("マイナビデータ取得");
            $fair->fair_mynavi = FairMynavi::where('fair_id', $fairId)->first();
            Log::debug($fair->fair_mynavi);
        }
        //ぐるなびに登録してある場合
        if ($fair->gurunavi_flg != self::UNCHECKED) {
            $fair->fair_gurunavi = FairGurunavi::where('fair_id', $fairId)->first();
        }
        //楽天に登録してある場合
        if ($fair->rakuten_flg != self::UNCHECKED) {
            $fair->fair_rakuten = FairRakuten::where('fair_id', $fairId)->first();
        }
        //みんなのウェディングに登録してある場合
        if ($fair->minna_flg != self::UNCHECKED) {
            $fair->fair_minna = FairMinna::where('fair_id', $fairId)->first();
        }
        Log::debug('開催日取得');
        $fair->fairEventDate;

        Log::debug($fair);

        return response()->json(['fair' => $fair], 200);
    }


    /**
     * フェア登録
     * フェア内容の登録・更新・削除
     *
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function store(string $memberId, Request $request) {
        DB::beginTransaction();

        $isNew = false;

        try {
            $params = json_decode(file_get_contents('php://input'), true);
            Log::debug('store-->');
            Log::debug("-- Parameters START -->");
            Log::debug($params);
            Log::debug("-- Parameters E N D -->");

            /*フェアの登録 */
            // 登録用のモデルを取得もしくは生成する
            $fair = null;
            if (!is_null($params['id'])) {
                $fair = Fair::find($params['id']);
                Log::debug("Update fair");
            }
            //新規登録の場合
            if (is_null($fair)) {
                $fair = new Fair;
                $fair->member_id = $params['member_id'];
                $fair->group_id = $params['group_id'];
                $isNew = true;
            }
            Log::debug($fair);

            $fair->title = $params['title'];
            $fair->image_id = $params['image_id'];
            $fair->start_hour = $params['start_hour'];
            $fair->start_minute = $params['start_minute'];
            $fair->end_Hour = $params['end_hour'];
            $fair->end_Minute = $params['end_minute'];
            $fair->part1 = $params['part1'];
            $fair->start_hour1 = $params['start_hour1'];
            $fair->start_minute1 = $params['start_minute1'];
            $fair->end_hour1 = $params['end_hour1'];
            $fair->end_minute1 = $params['end_minute1'];
            $fair->part2 = $params['part2'];
            $fair->start_hour2 = $params['start_hour2'];
            $fair->start_minute2 = $params['start_minute2'];
            $fair->end_hour2 = $params['end_hour2'];
            $fair->end_minute2 = $params['end_minute2'];
            $fair->part3 = $params['part3'];
            $fair->start_hour3 = $params['start_hour3'];
            $fair->start_minute3 = $params['start_minute3'];
            $fair->end_hour3 = $params['end_hour3'];
            $fair->end_minute3 = $params['end_minute3'];
            $fair->part4 = $params['part4'];
            $fair->start_hour4 = $params['start_hour4'];
            $fair->start_minute4 = $params['start_minute4'];
            $fair->end_hour4 = $params['end_hour4'];
            $fair->end_minute4 = $params['end_minute4'];
            $fair->part5 = $params['part5'];
            $fair->start_hour5 = $params['start_hour5'];
            $fair->start_minute5 = $params['start_minute5'];
            $fair->end_hour5 = $params['end_hour5'];
            $fair->end_minute5 = $params['end_minute5'];

            //フェアの各サイト登録フラグを書き換える
            $fair->zexy_flg = $params['zexy_flg'];
            $fair->weddingpark_flg = $params['weddingpark_flg'];
            $fair->mynavi_flg = $params['mynavi_flg'];
            $fair->gurunavi_flg = $params['gurunavi_flg'];
            $fair->minna_flg = $params['minna_flg'];
            Log::debug("**フラグ更新**");
            Log::debug("weddingpark_flg=".$params['weddingpark_flg']);
            Log::debug($fair);
            $fair->save();
            /*フェアの登録 */

            /*フェア内容の登録 */
            Log::debug(self::SITE_TYPE);
            $siteNames = array_keys(self::SITE_TYPE);
            // パラメータに含まれるフェア内容情報を取得する
            $fairContents = $params['fair_content'];
            Log::debug("-- Fair Contents START -->");
            Log::debug($fairContents);
            Log::debug("-- Fair Contents E N D -->");

            //個別のフェア内容の更新処理
            for ($j = 0; $j < count($fairContents); $j++) {
                $fairContent = $fairContents[$j];
                $fair_content = null;
                // フェア内容のIDがパラメータに含まれる場合、登録済み想定でデータを取得する
                if (!is_null($fairContent['id'])) {
                    $fair_content = $fair_content = FairContent::find($fairContent['id']);
                    if (!$fairContent['fairKbnChecked']) {
                        if (!is_null($fair_content)) {
                            $fair_content->delete();
                        }
                        continue;
                    }
                }

                Log::debug('fairKbnChecked='.$fairContent['fairKbnChecked']);
                Log::debug($fair_content);
                if ($fairContent['fairKbnChecked']) {
                    if (is_null($fair_content)) {
                        $fair_content = new FairContent;
                        $fair_content->fair_id = $fair['id'];
                        $fair_content->order_id = $fairContent['order_id'];
                    }

                    $fair_content->other_title = $fairContent['other_title'];
                    $fair_content->reserve_status = $fairContent['reserve_status'];
                    $fair_content->reserve_count = $fairContent['reserve_count'];
                    $fair_content->reserve_unit = $fairContent['reserve_unit'];
                    $fair_content->price_status = $fairContent['price_status'];
                    $fair_content->price = $fairContent['price'];
                    $fair_content->price_per_person = $fairContent['price_per_person'];
                    $fair_content->caption = $fairContent['caption'];
                    $fair_content->content_detail = $fairContent['content_detail'];
                    if ($fairContent['image_id'] != 0) {
                        $fair_content->image_id = $fairContent['image_id'];
                    }
                    if ($fairContent['image_id2'] != 0) {
                        $fair_content->image_id2 = $fairContent['image_id2'];
                    }
                    if ($fairContent['image_id3'] != 0) {
                        $fair_content->image_id3 = $fairContent['image_id3'];
                    }
                    $fair_content->save();
                    $fairContents[$j]['id'] = $fair_content->id;
                }
            }
            /*フェア内容の登録 */

            /* フェア(ゼクシィ)の登録 */
            $fair_zexy = FairZexy::where('fair_id', $fair['id'])->first();
            if ($params['zexy_flg'] == self::ENABLED) {
                if (is_null($fair_zexy)) {
                    Log::debug('Create fair_zexy');
                    $fair_zexy = new FairZexy;
                    $fair_zexy->fair_id = $fair['id'];
                }
            }

            if (!is_null($fair_zexy)) {
                if ($params['zexy_flg'] == self::UNCHECKED) {
                    Log::debug('Delete fair_zexy');
                    $fair_zexy->delete();
                }
                else {
                    $fairZexy = $params['fair_zexy'];
                    $fair_zexy->master_id = $fairZexy['master_id'];
                    $fair_zexy->fair_type = $fairZexy['fair_type'];
                    $fair_zexy->realtime_reserve_flg = $fairZexy['realtime_reserve_flg'];
                    $fair_zexy->required_time = $fairZexy['required_time'];
                    $fair_zexy->short_title = $fairZexy['short_title'];
                    $fair_zexy->description = $fairZexy['description'];
                    $fair_zexy->place_kbn = $fairZexy['place_kbn'];
                    $fair_zexy->place = $fairZexy['place'];
                    $fair_zexy->parking = $fairZexy['parking'];
                    $fair_zexy->target = $fairZexy['target'];
                    $fair_zexy->content_other = $fairZexy['content_other'];
                    $fair_zexy->tel_number1 = $fairZexy['tel_number1'];
                    $fair_zexy->tel_type1 = $fairZexy['tel_type1'];
                    $fair_zexy->tel_staff1 = $fairZexy['tel_staff1'];
                    $fair_zexy->tel_number2 = $fairZexy['tel_number2'];
                    $fair_zexy->tel_type2 = $fairZexy['tel_type2'];
                    $fair_zexy->tel_staff2 = $fairZexy['tel_staff2'];
                    $fair_zexy->benefit = $fairZexy['benefit'];
                    $fair_zexy->benefit_period = $fairZexy['benefit_period'];
                    $fair_zexy->benefit_remarks	= $fairZexy['benefit_remarks'];
                    $fair_zexy->catch_copy = $fairZexy['catch_copy'];
                    $fair_zexy->attention_point	= $fairZexy['attention_point'];
                    $fair_zexy->attention_point_staff = $fairZexy['attention_point_staff'];
                    $fair_zexy->attention_point_staff_job = $fairZexy['attention_point_staff_job'];
                    $fair_zexy->attention_point_image_id = $fairZexy['attention_point_image_id'];
                    $fair_zexy->question = $fairZexy['question'];
                    $fair_zexy->required_question_flg = $fairZexy['required_question_flg'];
                    $fair_zexy->reception_time = $fairZexy['reception_time'];
                    $fair_zexy->reception_staff	= $fairZexy['reception_staff'];
                    $fair_zexy->reserve_way = $fairZexy['reserve_way'];
                    $fair_zexy->request_change_config = $fairZexy['request_change_config'];
                    $fair_zexy->request_change_count = $fairZexy['request_change_count'];
                    $fair_zexy->net_reserve_day = $fairZexy['net_reserve_day'];
                    $fair_zexy->net_reserve_time = $fairZexy['net_reserve_time'];
                    $fair_zexy->phone_reserve_day1 = $fairZexy['phone_reserve_day1'];
                    $fair_zexy->phone_reserve_day2 = $fairZexy['phone_reserve_day2'];
                    $fair_zexy->post_start_day = $fairZexy['post_start_day'];
                    $fair_zexy->post_end_day = $fairZexy['post_end_day'];
                    $fair_zexy->change_start_day = $fairZexy['change_start_day'];
                    $fair_zexy->reflect_status = $fairZexy['reflect_status'];
                    $fair_zexy->save();
                }
            }
            /* フェア(ゼクシィ)の登録 */

            /* フェア(ウェディングパーク)の登録 */
            $fair_weddingpark = FairWeddingPark::where('fair_id', $fair['id'])->first();
            if ($params['weddingpark_flg'] == self::ENABLED) {
                if (is_null($fair_weddingpark)) {
                    Log::debug('Create fair_weddingpark');
                    $fair_weddingpark = new FairWeddingPark;
                    $fair_weddingpark->fair_id = $fair['id'];
                }
            }

            if (!is_null($fair_weddingpark)) {
                if ($params['weddingpark_flg'] == self::UNCHECKED) {
                    $fair_weddingpark->delete();
                    Log::debug('Delete fair_weddingpark');
                }
                else {
                    $fairWeddingpark = $params['fair_weddingpark'];
                    $fair_weddingpark->master_id = $fairWeddingpark['master_id'];
                    $fair_weddingpark->description = $fairWeddingpark['description'];
                    $fair_weddingpark->price = $fairWeddingpark['price'];
                    $fair_weddingpark->price_per_person = $fairWeddingpark['price_per_person'];
                    $fair_weddingpark->price_remarks = $fairWeddingpark['price_remarks'];
                    $fair_weddingpark->pc_url = $fairWeddingpark['pc_url'];
                    $fair_weddingpark->pc_insert_url_flg = $fairWeddingpark['pc_insert_url_flg'];
                    $fair_weddingpark->pc_ga_flg = $fairWeddingpark['pc_ga_flg'];
                    $fair_weddingpark->phone_url = $fairWeddingpark['phone_url'];
                    $fair_weddingpark->phone_insert_url_flg = $fairWeddingpark['phone_insert_url_flg'];
                    $fair_weddingpark->phone_ga_flg	= $fairWeddingpark['phone_ga_flg'];
                    $fair_weddingpark->required_hour = $fairWeddingpark['required_hour'];
                    $fair_weddingpark->required_minute = $fairWeddingpark['required_minute'];
                    $fair_weddingpark->benefit = $fairWeddingpark['benefit'];
                    $fair_weddingpark->reflect_status = $fairWeddingpark['reflect_status'];
                    $fair_weddingpark->save();
                }
            }
            /* フェア(ウェディングパーク)の登録 */

            /* フェア(マイナビ)の登録 */
            $fair_mynavi = FairMynavi::where('fair_id', $fair['id'])->first();
            Log::debug('mynavi_flg='.$params['mynavi_flg']);
            if ($params['mynavi_flg'] == self::ENABLED) {
                if (is_null($fair_mynavi)) {
                    Log::debug('Create fair_mynavi');
                    $fair_mynavi = new FairMynavi;
                    $fair_mynavi->fair_id = $fair['id'];
                }
            }

            if (!is_null($fair_mynavi)) {
                if ($params['mynavi_flg'] == self::UNCHECKED) {
                    $fair_mynavi->delete();
                    Log::debug('Delete fair_mynavi');
                }
                else {
                    $fairMynavi = $params['fair_mynavi'];
                    $fair_mynavi->master_id = $fairMynavi['master_id'];
                    $fair_mynavi->description = $fairMynavi['description'];
                    $fair_mynavi->reserve_way = $fairMynavi['reserve_way'];
                    $fair_mynavi->place = $fairMynavi['place'];
                    $fair_mynavi->place_remarks = $fairMynavi['place_remarks'];
                    $fair_mynavi->place_other = $fairMynavi['place_other'];
                    $fair_mynavi->net_reserve_period_day = $fairMynavi['net_reserve_period_day'];
                    $fair_mynavi->net_reserve_period_time = $fairMynavi['net_reserve_period_time'];
                    $fair_mynavi->phone_reserve_period_day = $fairMynavi['phone_reserve_period_day'];
                    $fair_mynavi->phone_reserve_period_time = $fairMynavi['phone_reserve_period_time'];
                    $fair_mynavi->target = $fairMynavi['target'];
                    $fair_mynavi->content_other = $fairMynavi['content_other'];
                    $fair_mynavi->benefit_flg = $fairMynavi['benefit_flg'];
                    $fair_mynavi->limited_benefit_flg = $fairMynavi['limited_benefit_flg'];
                    $fair_mynavi->benefit = $fairMynavi['benefit'];
                    $fair_mynavi->required_hour = $fairMynavi['required_hour'];
                    $fair_mynavi->required_minute = $fairMynavi['required_minute'];
                    $fair_mynavi->prevent_selection_flg = $fairMynavi['prevent_selection_flg'];
                    $fair_mynavi->save();
                }
            }
            /* フェア(マイナビ)の登録 */

            /* フェア(ぐるなび)の登録 */
            $fair_gurunavi = null;
            $fair_gurunavi = FairGurunavi::where('fair_id', $fair['id'])->first();
            if ($params['gurunavi_flg'] == self::ENABLED) {
                if (is_null($fair_gurunavi)) {
                    Log::debug('Create fair_gurunavi');
                    $fair_gurunavi = new FairGurunavi;
                    $fair_gurunavi->fair_id = $fair['id'];
                }
            }

            if (!is_null($fair_gurunavi)) {
                if ($params['gurunavi_flg'] == self::UNCHECKED) {
                    $fair_gurunavi->delete();
                    Log::debug('Delete fair_gurunavi');
                }
                else {
                    $fairGurunavi = $params['fair_gurunavi'];
                    $fair_gurunavi->reserve_way = $fairGurunavi['reserve_way'];
                    $fair_gurunavi->benefit_flg = $fairGurunavi['benefit_flg'];
                    $fair_gurunavi->specify_time_flg = $fairGurunavi['specify_time_flg'];
                    $fair_gurunavi->display_end_day = $fairGurunavi['display_end_day'];
                    $fair_gurunavi->limited_gurunavi_flg = $fairGurunavi['limited_gurunavi_flg'];
                    $fair_gurunavi->one_person_flg = $fairGurunavi['one_person_flg'];
                    $fair_gurunavi->catch_copy = $fairGurunavi['catch_copy'];
                    $fair_gurunavi->description	= $fairGurunavi['description'];
                    $fair_gurunavi->capacity = $fairGurunavi['capacity'];
                    $fair_gurunavi->image_description = $fairGurunavi['image_description'];
                    $fair_gurunavi->attention_point = $fairGurunavi['attention_point'];
                    $fair_gurunavi->event_kbn = $fairGurunavi['event_kbn'];
                    $fair_gurunavi->price_status = $fairGurunavi['price_status'];
                    $fair_gurunavi->price = $fairGurunavi['price'];
                    $fair_gurunavi->tax_included = $fairGurunavi['tax_included'];
                    $fair_gurunavi->tax_calculation = $fairGurunavi['tax_calculation'];
                    $fair_gurunavi->keyword_text = $fairGurunavi['keyword_text'];
                    $fair_gurunavi->counsel_type = $fairGurunavi['counsel_type'];
                    $fair_gurunavi->reserve_button_flg = $fairGurunavi['reserve_button_flg'];
                    $fair_gurunavi->reflect_status = $fairGurunavi['reflect_status'];
                    $fair_gurunavi->save();
                }
            }
            /* フェア(ぐるなび)の登録 */

            /* フェア(みんなのウェディング)の登録 */
            $fair_minna = null;
            $fair_minna = FairMinna::where('fair_id', $fair['id'])->first();
            if ($params['minna_flg'] == self::ENABLED) {
                if (is_null($fair_minna)) {
                    Log::debug('Create fair_minna');
                    $fair_minna = new FairMinna;
                    $fair_minna->fair_id = $fair['id'];
                }
            }

            if (!is_null($fair_minna)) {
                if ($params['minna_flg'] == self::UNCHECKED) {
                    $fair_minna->delete();
                }
                else {
                    $fairMinna = $params['fair_minna'];
                    $fair_minna->disp_sub_flg = $fairMinna['disp_sub_flg'];
                    $fair_minna->event_kbn = $fairMinna['event_kbn'];
                    $fair_minna->description = $fairMinna['description'];
                    $fair_minna->benefit = $fairMinna['benefit'];
                    $fair_minna->reserve_flg = $fairMinna['reserve_flg'];
                    $fair_minna->price_flg = $fairMinna['price_flg'];
                    $fair_minna->reservation_description = $fairMinna['reservation_description'];
                    $fair_minna->price_description = $fairMinna['price_description'];
                    $fair_minna->post_year = $fairMinna['post_year'];
                    $fair_minna->post_month = $fairMinna['post_month'];
                    $fair_minna->post_day = $fairMinna['post_day'];
                    $fair_minna->post_time = $fairMinna['post_time'];
                    $fair_minna->reservable_period = $fairMinna['reservable_period'];
                    $fair_minna->reflect_status = $fairMinna['reflect_status'];
                    $fair_minna->save();
                }
            }
            /* フェア(みんなのウェディング)の登録 */

            if ($isNew && !is_null($fair->group_id)) {
                $todayStr = date('Ymd');
                // グループに紐づく開催日を登録する
                $eventDate = GroupEventDate::where('member_id', $memberId)
                    ->where('group_id', $fair->group_id)
                    ->where('date', '>=', $todayStr)->get();

                for($i = 0; $i < count($eventDate); $i++) {
                    $fairEventDate = new FairEventDate;
                    $fairEventDate->member_id = $memberId;
                    $fairEventDate->fair_id = $fair['id'];
                    $fairEventDate->date = $eventDate[$i]['date'];
                    $fairEventDate->representative_flg = '0';
                    $fairEventDate->reflect_flg = '0';
                    $fairEventDate->stop_flg = '0';
                    $fairEventDate->del_flg = '0';
                    $fairEventDate->lock_flg = '0';
                    $fairEventDate->save();
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
        // return response()->json($result, 200);
        return response()->json(['dummy' => 'ok'], 200);
    }

    /* 「停止する」ボタン押下からのフェア削除処理  */
    public function deleteFairInfo(string $id) {
        $fair = Fair::where('member_id', $id)->first();

        if (is_null($fair)) {
            return response()->json(['code' => 'NG', 'message' => 'notFound'], 200);
        }
        DB::beginTransaction();
        try {
            $fairContent = FairContent::where('fair_id', $fair->id)->get();
            if (!is_null($fairContent)) {
                for ($i = 0; $i < count($fairContent); $i++) {
                    $fairContent[$i]->delete();
                }
            }
            $fairWeddingpark = FairWeddingpark::where('fair_id', $fair->id)->first();
            if (!is_null($fairWeddingpark)) {
                $fairWeddingpark->delete();
            }
            $fairMynavi = FairMynavi::where('fair_id', $fair->id)->first();
            if (!is_null($fairMynavi)) {
                $fairMynavi->delete();
            }
            $fairGurunavi = FairGurunavi::where('fair_id', $fair->id)->first();
            if (!is_null($fairGurunavi)) {
                $fairGurunavi->delete();
            }
            $fairRakuten = FairRakuten::where('fair_id', $fair->id)->first();
            if (!is_null($fairRakuten)) {
                $fairRakuten->delete();
            }
            $fairZexy = FairZexy::where('fair_id', $fair->id)->first();
            if (!is_null($fairZexy)) {
                $fairZexy->delete();
            }
            $fairMinna = FairMinna::where('fair_id', $fair->id)->first();
            if (!is_null($fairMinna)) {
                $fairMinna->delete();
            }
            $fair->delete();

            DB::commit();
            $result = [
                'code' => 'OK',
                'message' => ''
            ];
        } catch(Exception $e) {
            DB::rollBack();
            $result = [
                'code' => 'NG',
                'message' => $e->getMessage()
            ];
        }
        // return response()->json($result, 200);
        return response()->json(['dummy' => 'ok'], 200);
    }

    public function updateCalendar(string $id) {
        // $fair = Fair::find($id);
        //
        // if (is_null($fair)) {
        //     return response()->json(['code' => 'NG', 'message' => 'notFound'], 200);
        // }
        // DB::beginTransaction();
        // try {
        //     $fair->event_date = $params['eventDate'];
        //
        //     $fair->save();
        //
        //     DB::commit();

            $result = [
                'code' => 'OK',
                'message' => ''
            ];

        // } catch(Exception $e) {
        //     DB::rollBack();
        //     $result = [
        //         'code' => 'NG',
        //         'message' => $e->getMessage()
        //     ];
        // }
        return response()->json($result, 200);
    }

    public function reflectFairInfo(string $memberId, string $fairId, Request $request) {
      DB::beginTransaction();

      $result = [];

      try {
          $params = json_decode(file_get_contents('php://input'), true);
          Log::debug('reflectFairInfo-->');
          Log::debug($params);
          //フェアのフラグ更新
          $fair = Fair::where('id', $fairId)->first();
          if (!is_null($fair)) {
              $fair->reflect_status = $params['reflect_status'];
              $fair->save();

              //fair_zexyの更新
              if ($fair.zexy_flg == '1') {
                  $fair_zexy = FairZexy::where('fair_id', $fairId)->first();
                  if (!is_null($fair_zexy)) {
                      $fair_zexy->master_id = $params['fair_zexy']['master_id'];
                      $fair_zexy->reflect_status = $params['fair_zexy']['reflect_status'];
                      $fair_zexy->save();
                  }
              }

              //fair_weddingparkの更新
              if ($fair.weddingpark_flg == '1') {
                  $fair_weddingpark = FairWeddingPark::where('fair_id', $fairId)->first();
                  if (!is_null($fair_weddingpark)) {
                      $fair_weddingpark->master_id = $params['fair_weddingpark']['master_id'];
                      $fair_weddingpark->reflect_status = $params['fair_weddingpark']['reflect_status'];
                      $fair_weddingpark->save();
                  }
              }

              //fair_mynaviの更新
              if ($fair.mynavi_flg == '1') {
                  $fair_mynavi = FairMynavi::where('fair_id', $fairId)->first();
                  if (!is_null($fair_mynavi)) {
                      $fair_mynavi->master_id = $params['fair_mynavi']['master_id'];
                      $fair_mynavi->reflect_status = $params['fair_mynavi']['reflect_status'];
                      $fair_mynavi->save();
                  }
              }

              //fair_gurunaviの更新
              if ($fair.gurunavi_flg == '1') {
                  $fair_gurunavi = FairGurunavi::where('fair_id', $fairId)->first();
                  if (!is_null($fair_gurunavi)) {
                      $fair_gurunavi->reflect_status = $params['fair_gurunavi']['reflect_status'];
                      $fair_gurunavi->save();
                  }
              }

              //minna_flgの更新
              if ($fair.minna_flg == '1') {
                $fair_minna = FairMinna::where('fair_id', $fairId)->first();
                if (!is_null($fair_minna)) {
                    $fair_minna->reflect_status = $params['fair_minna']['reflect_status'];
                    $fair_minna->save();
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
        return response()->json($result, 200);
    }

    /*
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }


    public function show(Fair $fair)
    {
        //
    }


    public function edit(Fair $fair)
    {
        //
    }


    public function update(Request $request, Fair $fair)
    {
        //
    }

    public function destroy(Fair $fair)
    {
        //
    }
    */
}
