<?php

namespace App\Http\Controllers;

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


    const NO_REGISTRATION = '0';
    const REGISTERED = '1';
    const NEW_REGISTER = '1';
    const UPDATE = '2';
    const DELETED = '3';


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
        }
        Log::debug($items[0]);
        return response()->json(['records' => $items], 200);
    }


    /*  一つのフェア詳細情報を取得する  */
    public function getFair(string $memberId, string $fairId) {
        //Fairからユーザと各サイト登録情報を取得する
        $fair = Fair::where('id', $fairId)->where('member_id', $memberId)->first();
        $fair->image;
        Log::debug($fair);
        //$fairが持つ、各サイトの登録フラグを元にフェア情報を取得する
        //ゼクシイに登録してある場合
        if ($fair->zexy_flg== self::REGISTERED) {
            $fair->fair_zexy = FairZexy::where('fair_id', $fairId)->first();
            $fair->fair_zexy->attentionPointImage;
            $fair->fair_zexy->fair_content = FairContent::where('fair_id', $fairId)->where('site_type', self::SITE_TYPE['ZEXY'])->get();
            $fair_content_parts = null;
            for ($i = 0; $i < count($fair->fair_zexy->fair_content); $i++) {
                $fair_content = $fair->fair_zexy->fair_content[$i];
                $fair_content->image1;
                $fair_content->image2;
                $fair_content->image3;
                $fair_content_part = FairContentPart::where('fair_content_id', $fair_content['id'])->get();

                for ($j = 0; $j < count($fair_content_part); $j++) {
                    $fair_content_part[$j]->fairContentDetail;
                }
                Log::debug($fair_content_part);

                if ($fair_content_parts == null) {
                    $fair_content_parts = $fair_content_part;
                } else {
                    $fair_content_parts = $fair_content_parts->concat($fair_content_part);
                }
            }
            $fair->fair_zexy->fair_content_part = $fair_content_parts;
        }
        Log::debug($fair->fair_zexy);

        //ウェディングパークに登録してある場合
        if ($fair->weddingpark_flg == self::REGISTERED) {
            $fair->fair_weddingpark = FairWeddingPark::where('fair_id', $fairId)->first();
            $fair->fair_weddingpark->fair_content = FairContent::where('fair_id', $fairId)->where('site_type', self::SITE_TYPE['WEDDINGPARK'])->get();
            for ($i = 0; $i < count($fair->fair_weddingpark->fair_content); $i++) {
                $fair->fair_weddingpark->fair_content[$i]->image1;
            }
        }
        //マイナビに登録してある場合
        if ($fair->mynavi_flg == self::REGISTERED) {
            $fair->fair_mynavi = FairMynavi::where('fair_id', $fairId)->first();
            $fair->fair_mynavi->fair_content = FairContent::where('fair_id', $fairId)->where('site_type', self::SITE_TYPE['MYNAVI'])->get();
        }
        //ぐるなびに登録してある場合
        if ($fair->gurunavi_flg == self::REGISTERED) {
            $fair->fair_gurunavi = FairGurunavi::where('fair_id', $fairId)->first();
            $fair->fair_gurunavi->fair_content = FairContent::where('fair_id', $fairId)->where('site_type', self::SITE_TYPE['GURUNAVI'])->get();
        }
        //楽天に登録してある場合
        if ($fair->rakuten_flg == self::REGISTERED) {
            $fair->fair_rakuten = FairRakuten::where('fair_id', $fairId)->first();
            $fair->fair_rakuten->fair_content = FairContent::where('fair_id', $fairId)->where('site_type', self::SITE_TYPE['RAKUTEN'])->get();
        }
        //みんなのウェディングに登録してある場合
        if ($fair->minna_flg == self::REGISTERED) {
            $fair->fair_minna = FairMinna::where('fair_id', $fairId)->first();
            $fair->fair_minna->fair_content = FairContent::where('fair_id', $fairId)->where('site_type', self::SITE_TYPE['MINNA'])->get();
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
            }
            //新規登録の場合
            if (is_null($fair)) {
                $fair = new Fair;
                $fair->member_id = $params['member_id'];
                $fair->group_id = $params['group_id'];
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
            for ($i = 0; $i < count(self::FAIR_FLG); $i++) {
                if ($params[self::FAIR_FLG[$i]] == self::NO_REGISTRATION
                        || $params[self::FAIR_FLG[$i]] == self::DELETED) {
                    //登録なし、もしくは削除する場合
                    $fair[self::FAIR_FLG[$i]] == self::NO_REGISTRATION;
                } else {
                    //新規登録・登録更新処理
                    $fair[self::FAIR_FLG[$i]] = self::REGISTERED;
                }
            }
            $fair->save();
            /*フェアの登録 */

            /*フェア内容の登録 */
            Log::debug(self::SITE_TYPE);
            $siteNames = array_keys(self::SITE_TYPE);
            for ($i = 0; $i < count(self::FAIR_FLG); $i++) {
                if ($params[self::FAIR_FLG[$i]] == self::DELETED) {
                    //フェア内容を削除する場合
                    $fair_content_list = FairContent::where('fair_id', $params['id'])
                        ->where('site_type', SITE_TYPE[$siteNames[$i]])->get();
                    for ($j = 0; $j < count($fair_content_list); $j++) {
                        $fair_content_list[$j]->delete();
                    }
                    continue;
                }
                elseif ($params[self::FAIR_FLG[$i]] != self::REGISTERED
                        && $params[self::FAIR_FLG[$i]] != self::UPDATE) {
                    continue;
                }

                // パラメータに含まれるフェア内容情報を取得する
                $fairContents = $params[self::FAIR_TABLE_NAME[$i]]['fair_content'];
                Log::debug("-- Fair Contents [".self::FAIR_TABLE_NAME[$i]."] START -->");
                Log::debug($fairContents);
                Log::debug("-- Fair Contents [".self::FAIR_TABLE_NAME[$i]."] E N D -->");

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
                            $fair_content->site_type = $fairContent['site_type'];
                            $fair_content->order_id = $fairContent['order_id'];
                            $fair_content->content = $fairContent['content'];
                        }

                        $fair_content->other_title = $fairContent['other_title'];
                        $fair_content->reserve_status = $fairContent['reserve_status'];
                        $fair_content->reserve_count = $fairContent['reserve_count'];
                        $fair_content->reserve_unit = $fairContent['reserve_unit'];
                        $fair_content->price_status = $fairContent['price_status'];
                        $fair_content->price = $fairContent['price'];
                        $fair_content->price_per_person = $fairContent['price_per_person'];
                        $fair_content->required_time = $fairContent['required_time'];
                        $fair_content->content_detail = $fairContent['content_detail'];
                        $fair_content->event_kbn1 = $fairContent['event_kbn1'];
                        $fair_content->event_kbn2 = $fairContent['event_kbn2'];
                        if ($fairContent['image_id'] != 0) {
                            $fair_content->image_id = $fairContent['image_id'];
                        }
                        if ($fairContent['image_id2'] != 0) {
                            $fair_content->image_id2 = $fairContent['image_id2'];
                        }
                        if ($fairContent['image_id3'] != 0) {
                            $fair_content->image_id3 = $fairContent['image_id3'];
                        }
                        $fair_content->start_hour1 = $fairContent['start_hour1'];
                        $fair_content->start_minute1 = $fairContent['start_minute1'];
                        $fair_content->end_hour1 = $fairContent['end_hour1'];
                        $fair_content->end_minute1 = $fairContent['end_minute1'];
                        $fair_content->start_hour2 = $fairContent['start_hour2'];
                        $fair_content->start_minute2 = $fairContent['start_minute2'];
                        $fair_content->end_hour2 = $fairContent['end_hour2'];
                        $fair_content->end_minute2 = $fairContent['end_minute2'];
                        $fair_content->start_hour3 = $fairContent['start_hour3'];
                        $fair_content->start_minute3 = $fairContent['start_minute3'];
                        $fair_content->end_hour3 = $fairContent['end_hour3'];
                        $fair_content->end_minute3 = $fairContent['end_minute3'];
                        $fair_content->start_hour4 = $fairContent['start_hour4'];
                        $fair_content->start_minute4 = $fairContent['start_minute4'];
                        $fair_content->end_hour4 = $fairContent['end_hour4'];
                        $fair_content->end_minute4 = $fairContent['end_minute4'];
                        $fair_content->start_hour5 = $fairContent['start_hour5'];
                        $fair_content->start_minute5 = $fairContent['start_minute5'];
                        $fair_content->end_hour5 = $fairContent['end_hour5'];
                        $fair_content->end_minute5 = $fairContent['end_minute5'];
                        $fair_content->save();

                        $fairContents[$j]['id'] = $fair_content->id;
                    }
                }
            }
            /*フェア内容の登録 */

            /* フェア(ゼクシィ)の登録 */
            $fair_zexy = null;
            if ($params['zexy_flg'] == self::NO_REGISTRATION) {
            //新規登録の場合はモデルオブジェクトを取得する
            } elseif ($params['zexy_flg'] == self::NEW_REGISTER) {
                $fair_zexy = new FairZexy;
                $fair_zexy->fair_id = $fair['id'];
            //更新の場合
            } else {
                $fair_zexy = FairZexy::where('fair_id', $fair['id'])->first();
            }

            if (!is_null($fair_zexy)) {
                if ($params['zexy_flg'] == self::DELETED) {
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

                    // 部毎フェア内容の登録
                    $fairContentParts = $fairZexy['fair_content_part'];
                    //個別のフェア内容の更新処理
                    for ($i = 0; $i < count($fairContentParts); $i++) {
                        $fairContentPart = $fairContentParts[$i];
                        if (is_null($fairContentPart['fair_content_id'])) {
                            $fair_content_part = new FairContentPart;
                        } else {
                            $fair_content_part = FairContentPart::firstOrNew([
                                'fair_content_id' => $fairContentPart['fair_content_id'],
                                'part' => $fairContentPart['part']
                            ]);
                        }

                        if (is_null($fair_content_part->fair_content_id)) {
                            for ($j = 0; $j < count($fairContents); $j++) {
                                if ($fairContentPart['content'] == $fairContents[$j]['content']) {
                                    $fair_content_part->fair_content_id = $fairContents[$j]['id'];
                                    break;
                                }
                            }
                        }
                        $fair_content_part->part = $fairContentPart['part'];
                        $fair_content_part->valid_flg = $fairContentPart['valid_flg'];
                        $fair_content_part->save();

                        // フェア詳細を取得
                        $fairContentDetails = $fairContentPart['fair_content_detail'];
                        for ($j = 0; $j < count($fairContentDetails); $j++) {
                            $fairContentDetail = $fairContentDetails[$j];
                            if (is_null($fairContentDetail['fair_content_id'])) {
                                $fair_content_detail = new FairContentDetail;
                            } else {
                                $fair_content_detail = FairContentDetail::firstOrNew([
                                    'fair_content_id' => $fairContentDetail['fair_content_id'],
                                    'fair_content_part_id' => $fairContentDetail['fair_content_part_id']
                                ]);
                            }
                            $fair_content_detail->fair_content_id = $fair_content_part->fair_content_id;
                            $fair_content_detail->fair_content_part_id = $fair_content_part->id;
                            $fair_content_detail->order_no = $fairContentDetail['order_no'];
                            $fair_content_detail->start_hour = $fairContentDetail['start_hour'];
                            $fair_content_detail->start_minute = $fairContentDetail['start_minute'];
                            $fair_content_detail->end_hour = $fairContentDetail['end_hour'];
                            $fair_content_detail->end_minute = $fairContentDetail['end_minute'];
                            $fair_content_detail->title = $fairContentDetail['title'];
                            $fair_content_detail->zebra_reserve_count = $fairContentDetail['zebra_reserve_count'];
                            $fair_content_detail->save();
                        }
                    }
                }
            }
            /* フェア(ゼクシィ)の登録 */

            /* フェア(ウェディングパーク)の登録 */
            $fair_weddingpark = null;
            if ($params['weddingpark_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['weddingpark_flg'] == self::NEW_REGISTER) {
                $fair_weddingpark = new FairWeddingPark;
                $fair_weddingpark->fair_id = $fair['id'];
            } else {
                $fair_weddingpark = FairWeddingPark::find($params['fair_id']);
            }

            if (!is_null($fair_weddingpark)) {
                if ($params['weddingpark_flg'] == self::DELETED) {
                    $fair_weddingpark->delete();
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
            $fair_mynavi = null;
            if ($params['mynavi_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['mynavi_flg'] == self::NEW_REGISTER) {
                $fair_mynavi = new FairMynavi;
                $fair_mynavi->fair_id = $fair['id'];
            } else {
                $fair_mynavi = FairMynavi::find($fair['id']);
            }

            if (!is_null($fair_mynavi)) {
                if ($params['mynavi_flg'] == self::DELETED) {
                    $fair_mynavi->delete();
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
            if ($params['gurunavi_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['gurunavi_flg'] == self::NEW_REGISTER) {
                $fair_gurunavi = new FairGurunavi;
                $fair_gurunavi->fair_id = $fair['id'];
            } else {
                $fair_gurunavi = FairGurunavi::find($fair['id']);
            }

            if (!is_null($fair_gurunavi)) {
                if ($params['gurunavi_flg'] == self::DELETED) {
                    $fair_gurunavi->delete();
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
                    $fair_gurunavi->save();
                }
            }
            /* フェア(ぐるなび)の登録 */

            /* フェア(楽天)の登録 */
            $fair_rakuten = null;
            if ($params['rakuten_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['rakuten_flg'] == self::NEW_REGISTER) {
                $fair_rakuten = new FairRakuten;
                $fair_rakuten->fair_id = $fair['id'];
            } else {
                $fair_rakuten = FairRakuten::find($fair['id']);
            }

            if (!is_null($fair_rakuten)) {
                if ($params['rakuten_flg'] == self::DELETED) {
                    $fair_rakuten->delete();
                }
                else {
                    $fairRakuten = $params['fair_rakuten'];
                    $fair_rakuten->description = $fairRakuten['description'];
                    $fair_rakuten->net_reserve_period_day = $fairRakuten['net_reserve_period_day'];
                    $fair_rakuten->net_reserve_period_time = $fairRakuten['net_reserve_period_time'];
                    $fair_rakuten->phone_reserve_flg = $fairRakuten['phone_reserve_flg'];
                    $fair_rakuten->save();
                }
            }
            /* フェア(楽天)の登録 */

            /* フェア(みんなのウェディング)の登録 */
            $fair_minna = null;
            if ($params['minna_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['minna_flg'] == self::NEW_REGISTER) {
                $fair_minna = new FairMinna;
                $fair_minna->fair_id = $fair['id'];
            } else {
                $fair_minna = FairMinna::find($fair['id']);
            }

            if (!is_null($fair_minna)) {
                if ($params['minna_flg'] == self::DELETED) {
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

    /**
     * フェア登録
     * フェア内容の登録・更新・削除
     *
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function update(string $memberId, string $fairId, Request $request) {
        DB::beginTransaction();
        try {
            $params = json_decode(file_get_contents('php://input'), true);
            Log::debug('update-->');
            Log::debug($params);
            //フェアの登録
            $fair = Fair::firstOrNew(['id' => $fairId]);

            $fair->member_id = $params['member_id'];
            $fair->group_id = $params['group_id'];
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
            for ($i = 0; $i < count(self::FAIR_FLG); $i++) {
                //登録なし、もしくは削除する場合
                if ($params[self::FAIR_FLG[$i]] == self::NO_REGISTRATION || $params[self::FAIR_FLG[$i]] == self::DELETED) {
                    $fair[self::FAIR_FLG[$i]] == self::NO_REGISTRATION;
                //新規登録・登録更新処理
                } else {
                    $fair[self::FAIR_FLG[$i]] = self::REGISTERED;
                }
                Log::debug('update '.self::FAIR_FLG[$i].' -->');

                //フェア内容の登録処理
                //フェア内容の新規登録、もしくは更新の場合
                if ($params[self::FAIR_FLG[$i]] == self::NEW_REGISTER || $params[self::FAIR_FLG[$i]] == self::UPDATE) {
                    //更新処理で、なおかつフェア内容登録が減っている場合は余分な部分をまず削除する
                    if ($params[self::FAIR_FLG[$i]] == self::UPDATE) {
                        Log::debug($params['id']);
                        //$fair_content_before_update = FairContent::find($params['id'])
                        $fair_content_before_update = FairContent::where('fair_id', $fairId)
                            ->where('site_type', $params[self::FAIR_TABLE_NAME[$i]]['fair_content'][0]['site_type'])
                            ->where('order_id', '>', count($params[self::FAIR_TABLE_NAME[$i]]['fair_content']))->get();
                        for ($i = 0; $i < count($fair_content_before_update); $i++) {
                            $fair_content_before_update[$i]->delete();
                        }
                    }

                    //FairContentのオブジェクトを取得する
                    //新規登録
                    //if ($params[self::FAIR_FLG[$i]] == self::NEW_REGISTER) {
                    //    $fair_content = new FairContent;
                    //更新
                    //} else {
                    //    $fair_content = FairContent::find($params['id'])->where('site_type', $params[self::FAIR_TABLE_NAME[$i]]['fair_content'][0]['site_type'])->first();
                    //}

                    //Log::debug($params[self::FAIR_FLG[$i]]);
                    //Log::debug(count($params[self::FAIR_TABLE_NAME[$i]]['fair_content']));
                    $fairContents = $params[self::FAIR_TABLE_NAME[$i]]['fair_content'];

                    //個別のフェア内容の更新処理
                    for ($j = 0; $j < count($fairContents); $j++) {
                        //新規登録
                        $fair_content = FairContent::firstOrNew([
                            'fair_id' => $fair['id'],
                            'site_type' => $fairContents[$j]['site_type'],
                            'order_id' => $fairContents[$j]['order_id']
                        ]);
                        Log::debug('firstOrNew fair_content -->');
                        Log::debug($fair_content);
                        $fair_content->content = $fairContents[$j]['content'];
                        $fair_content->other_title = $fairContents[$j]['other_title'];
                        $fair_content->reserve_status = $fairContents[$j]['reserve_status'];
                        $fair_content->reserve_count = $fairContents[$j]['reserve_count'];
                        $fair_content->reserve_unit = $fairContents[$j]['reserve_unit'];
                        $fair_content->price_status = $fairContents[$j]['price_status'];
                        $fair_content->price = $fairContents[$j]['price'];
                        $fair_content->price_per_person = $fairContents[$j]['price_per_person'];
                        $fair_content->required_time = $fairContents[$j]['required_time'];
                        $fair_content->content_detail = $fairContents[$j]['content_detail'];
                        $fair_content->event_kbn1 = $fairContents[$j]['event_kbn1'];
                        $fair_content->event_kbn2 = $fairContents[$j]['event_kbn2'];
                        if ($fairContents[$j]['image_id'] != 0) {
                          $fair_content->image_id = $fairContents[$j]['image_id'];
                        }
                        if ($fairContents[$j]['image_id2'] != 0) {
                          $fair_content->image_id2 = $fairContents[$j]['image_id2'];
                        }
                        if ($fairContents[$j]['image_id3'] != 0) {
                          $fair_content->image_id3 = $fairContents[$j]['image_id3'];
                        }
                        $fair_content->start_hour1 = $fairContents[$j]['start_hour1'];
                        $fair_content->start_minute1 = $fairContents[$j]['start_minute1'];
                        $fair_content->end_hour1 = $fairContents[$j]['end_hour1'];
                        $fair_content->end_minute1 = $fairContents[$j]['end_minute1'];
                        $fair_content->start_hour2 = $fairContents[$j]['start_hour2'];
                        $fair_content->start_minute2 = $fairContents[$j]['start_minute2'];
                        $fair_content->end_hour2 = $fairContents[$j]['end_hour2'];
                        $fair_content->end_minute2 = $fairContents[$j]['end_minute2'];
                        $fair_content->start_hour3 = $fairContents[$j]['start_hour3'];
                        $fair_content->start_minute3 = $fairContents[$j]['start_minute3'];
                        $fair_content->end_hour3 = $fairContents[$j]['end_hour3'];
                        $fair_content->end_minute3 = $fairContents[$j]['end_minute3'];
                        $fair_content->start_hour4 = $fairContents[$j]['start_hour4'];
                        $fair_content->start_minute4 = $fairContents[$j]['start_minute4'];
                        $fair_content->end_hour4 = $fairContents[$j]['end_hour4'];
                        $fair_content->end_minute4 = $fairContents[$j]['end_minute4'];
                        $fair_content->start_hour5 = $fairContents[$j]['start_hour5'];
                        $fair_content->start_minute5 = $fairContents[$j]['start_minute5'];
                        $fair_content->end_hour5 = $fairContents[$j]['end_hour5'];
                        $fair_content->end_minute5 = $fairContents[$j]['end_minute5'];

                        //オブジェクトがNULLであればそのサイトのフェア内容は存在しないということ
                        if (!is_null($fair_content)) {
                            Log::debug('update fair_content -->');
                            $fair_content->save();
                        }
                    }
                //フェア内容を削除する場合
                } elseif ($params[self::FAIR_FLG[$i]] == self::DELETED) {
                    $fair_content = FairContent::find($params['id'])->where('site_type', $params['fair_content'][$i]['site_type'])->get();
                    for ($i = 0; $i < count($fair_content); $i++) {
                        $fair_content[$i]->delete();
                    }
                }
            }
            Log::debug('update fair -->');
            Log::debug($fair);
            $fair->save();

            //fair_zexyの更新
            $fair_zexy = FairZexy::firstOrNew(['fair_id' => $fair['id']]);
            if ($params['zexy_flg'] == self::NEW_REGISTER || $params['zexy_flg'] == self::UPDATE) {
                $fair_zexy->master_id = $params['fair_zexy']['master_id'];
                $fair_zexy->fair_type = $params['fair_zexy']['fair_type'];
                $fair_zexy->realtime_reserve_flg = $params['fair_zexy']['realtime_reserve_flg'];
                $fair_zexy->required_time = $params['fair_zexy']['required_time'];
                $fair_zexy->short_title = $params['fair_zexy']['short_title'];
                $fair_zexy->description = $params['fair_zexy']['description'];
                //$fair_zexy->multipart_flg = $params['fair_zexy']['multipart_flg'];
                $fair_zexy->place = $params['fair_zexy']['place'];
                $fair_zexy->parking = $params['fair_zexy']['parking'];
                $fair_zexy->target = $params['fair_zexy']['target'];
                $fair_zexy->content_other = $params['fair_zexy']['content_other'];
                $fair_zexy->tel_number1 = $params['fair_zexy']['tel_number1'];
                $fair_zexy->tel_type1 = $params['fair_zexy']['tel_type1'];
                $fair_zexy->tel_staff1 = $params['fair_zexy']['tel_staff1'];
                $fair_zexy->tel_number2 = $params['fair_zexy']['tel_number2'];
                $fair_zexy->tel_type2 = $params['fair_zexy']['tel_type2'];
                $fair_zexy->tel_staff2 = $params['fair_zexy']['tel_staff2'];
                $fair_zexy->benefit = $params['fair_zexy']['benefit'];
                $fair_zexy->benefit_period = $params['fair_zexy']['benefit_period'];
                $fair_zexy->benefit_remarks	= $params['fair_zexy']['benefit_remarks'];
                $fair_zexy->catch_copy = $params['fair_zexy']['catch_copy'];
                $fair_zexy->attention_point	= $params['fair_zexy']['attention_point'];
                $fair_zexy->attention_point_staff = $params['fair_zexy']['attention_point_staff'];
                $fair_zexy->attention_point_staff_job = $params['fair_zexy']['attention_point_staff_job'];
                $fair_zexy->attention_point_image_id = $params['fair_zexy']['attention_point_image_id'];
                $fair_zexy->question = $params['fair_zexy']['question'];
                $fair_zexy->required_question_flg = $params['fair_zexy']['required_question_flg'];
                $fair_zexy->reception_time = $params['fair_zexy']['reception_time'];
                $fair_zexy->reception_staff	= $params['fair_zexy']['reception_staff'];
                $fair_zexy->reserve_way = $params['fair_zexy']['reserve_way'];
                $fair_zexy->request_change_config = $params['fair_zexy']['request_change_config'];
                $fair_zexy->request_change_count = $params['fair_zexy']['request_change_count'];
                $fair_zexy->net_reserve_day = $params['fair_zexy']['net_reserve_day'];
                $fair_zexy->net_reserve_time = $params['fair_zexy']['net_reserve_time'];
                $fair_zexy->phone_reserve_day1 = $params['fair_zexy']['phone_reserve_day1'];
                $fair_zexy->phone_reserve_day2 = $params['fair_zexy']['phone_reserve_day2'];
                $fair_zexy->post_start_day = $params['fair_zexy']['post_start_day'];
                $fair_zexy->post_end_day = $params['fair_zexy']['post_end_day'];
                $fair_zexy->change_start_day = $params['fair_zexy']['change_start_day'];
                $fair_zexy->reflect_status = $params['fair_zexy']['reflect_status'];

                Log::debug('update fair_zexy -->');
                $fair_zexy->save();

            } elseif ($params['zexy_flg'] == self::DELETED) {
                Log::debug('delete fair_zexy -->');
                $fair_zexy->delete();
            }

            //fair_weddingparkの更新
            $fair_weddingpark = FairWeddingPark::firstOrNew(['fair_id' => $fair['id']]);
            if ($params['weddingpark_flg'] == self::NEW_REGISTER || $params['weddingpark_flg'] == self::UPDATE) {
                $fair_weddingpark->master_id = $params['fair_weddingpark']['master_id'];
                $fair_weddingpark->description = $params['fair_weddingpark']['description'];
                $fair_weddingpark->price = $params['fair_weddingpark']['price'];
                $fair_weddingpark->price_per_person = $params['fair_weddingpark']['price_per_person'];
                $fair_weddingpark->price_remarks = $params['fair_weddingpark']['price_remarks'];
                $fair_weddingpark->pc_url = $params['fair_weddingpark']['pc_url'];
                $fair_weddingpark->pc_insert_url_flg = $params['fair_weddingpark']['pc_insert_url_flg'];
                $fair_weddingpark->pc_ga_flg = $params['fair_weddingpark']['pc_ga_flg'];
                $fair_weddingpark->phone_url = $params['fair_weddingpark']['phone_url'];
                $fair_weddingpark->phone_insert_url_flg = $params['fair_weddingpark']['phone_insert_url_flg'];
                $fair_weddingpark->phone_ga_flg	= $params['fair_weddingpark']['phone_ga_flg'];
                $fair_weddingpark->required_hour = $params['fair_weddingpark']['required_hour'];
                $fair_weddingpark->required_minute = $params['fair_weddingpark']['required_minute'];
                $fair_weddingpark->benefit = $params['fair_weddingpark']['benefit'];
                $fair_weddingpark->reflect_status = $params['fair_weddingpark']['reflect_status'];
                Log::debug('update fair_wepa -->');
                $fair_weddingpark->save();
            } elseif ($params['weddingpark_flg'] == self::DELETED) {
                Log::debug('delete fair_wepa -->');
                $fair_weddingpark->delete();
            }

            ////fair_mynaviの更新
            $fair_mynavi = FairMynavi::firstOrNew(['fair_id' => $fair['id']]);
            if ($params['mynavi_flg'] == self::NEW_REGISTER || $params['mynavi_flg'] == self::UPDATE) {
                $fair_mynavi->master_id = $params['fair_mynavi']['master_id'];
                $fair_mynavi->description = $params['fair_mynavi']['description'];
                $fair_mynavi->reserve_way = $params['fair_mynavi']['reserve_way'];
                //$fair_mynavi->﻿multipart_check	= $params['fair_mynavi']['multipart_check'];
                $fair_mynavi->place = $params['fair_mynavi']['place'];
                $fair_mynavi->place_remarks = $params['fair_mynavi']['place_remarks'];
                $fair_mynavi->place_other = $params['fair_mynavi']['place_other'];
                $fair_mynavi->net_reserve_period_day = $params['fair_mynavi']['net_reserve_period_day'];
                $fair_mynavi->net_reserve_period_time = $params['fair_mynavi']['net_reserve_period_time'];
                $fair_mynavi->phone_reserve_period_day = $params['fair_mynavi']['phone_reserve_period_day'];
                $fair_mynavi->phone_reserve_period_time = $params['fair_mynavi']['phone_reserve_period_time'];
                $fair_mynavi->target = $params['fair_mynavi']['target'];
                $fair_mynavi->content_other = $params['fair_mynavi']['content_other'];
                $fair_mynavi->benefit_flg = $params['fair_mynavi']['benefit_flg'];
                $fair_mynavi->limited_benefit_flg = $params['fair_mynavi']['limited_benefit_flg'];
                $fair_mynavi->benefit = $params['fair_mynavi']['benefit'];
                $fair_mynavi->required_hour = $params['fair_mynavi']['required_hour'];
                $fair_mynavi->required_minute = $params['fair_mynavi']['required_minute'];
                $fair_mynavi->prevent_selection_flg = $params['fair_mynavi']['prevent_selection_flg'];
                $fair_mynavi->reflect_status = $params['fair_mynavi']['reflect_status'];
                $fair_mynavi->save();
            } elseif ($params['mynavi_flg'] == self::DELETED) {
                $fair_mynavi->delete();
            }

            //fair_gurunaviの更新
            $fair_gurunavi = FairGurunavi::firstOrNew(['fair_id' => $fair['id']]);
            if ($params['gurunavi_flg'] == self::NEW_REGISTER || $params['gurunavi_flg'] == self::UPDATE) {
                $fair_gurunavi->reserve_way = $params['fair_gurunavi']['reserve_way'];
                $fair_gurunavi->benefit_flg = $params['fair_gurunavi']['benefit_flg'];
                $fair_gurunavi->specify_time_flg = $params['fair_gurunavi']['specify_time_flg'];
                $fair_gurunavi->display_end_day = $params['fair_gurunavi']['display_end_day'];
                $fair_gurunavi->limited_gurunavi_flg = $params['fair_gurunavi']['limited_gurunavi_flg'];
                $fair_gurunavi->one_person_flg = $params['fair_gurunavi']['one_person_flg'];
                $fair_gurunavi->catch_copy = $params['fair_gurunavi']['catch_copy'];
                $fair_gurunavi->description	= $params['fair_gurunavi']['description'];
                $fair_gurunavi->capacity = $params['fair_gurunavi']['capacity'];
                $fair_gurunavi->image_description = $params['fair_gurunavi']['image_description'];
                $fair_gurunavi->attention_point = $params['fair_gurunavi']['attention_point'];
                $fair_gurunavi->event_kbn = $params['fair_gurunavi']['event_kbn'];
                $fair_gurunavi->price_status = $params['fair_gurunavi']['price_status'];
                $fair_gurunavi->price = $params['fair_gurunavi']['price'];
                $fair_gurunavi->tax_included = $params['fair_gurunavi']['tax_included'];
                $fair_gurunavi->tax_calculation = $params['fair_gurunavi']['tax_calculation'];
                $fair_gurunavi->keyword_text = $params['fair_gurunavi']['keyword_text'];
                $fair_gurunavi->counsel_type = $params['fair_gurunavi']['counsel_type'];
                $fair_gurunavi->reserve_button_flg = $params['fair_gurunavi']['reserve_button_flg'];
                $fair_gurunavi->save();
            } elseif ($params['gurunavi_flg'] == self::DELETED) {
                $fair_gurunavi->delete();
            }

            //fair_rakutenの更新
            $fair_rakuten = FairRakuten::firstOrNew(['fair_id' => $fair['id']]);
            if ($params['rakuten_flg'] == self::NEW_REGISTER || $params['rakuten_flg'] == self::UPDATE) {
                $fair_rakuten->description = $params['fair_rakuten']['description'];
                $fair_rakuten->net_reserve_period_day = $params['fair_rakuten']['net_reserve_period_day'];
                $fair_rakuten->net_reserve_period_time = $params['fair_rakuten']['net_reserve_period_time'];
                $fair_rakuten->phone_reserve_flg = $params['fair_rakuten']['phone_reserve_flg'];
                $fair_rakuten->part_count = $params['fair_rakuten']['part_count'];
                $fair_rakuten->save();
            } elseif ($params['rakuten_flg'] == self::DELETED) {
                $fair_rakuten->delete();
            }

            //fair_minnaの更新
            $fair_minna = FairMinna::firstOrNew(['fair_id' => $fair['id']]);;
            if ($params['minna_flg'] == self::NEW_REGISTER || $params['minna_flg'] == self::UPDATE) {
                $fair_minna->disp_sub_flg = $params['fair_minna']['disp_sub_flg'];
                $fair_minna->event_kbn = $params['fair_minna']['event_kbn'];
                $fair_minna->description = $params['fair_minna']['description'];
                $fair_minna->benefit = $params['fair_minna']['benefit'];
                $fair_minna->reserve_flg = $params['fair_minna']['reserve_flg'];
                $fair_minna->price_flg = $params['fair_minna']['price_flg'];
                $fair_minna->reservation_description = $params['fair_minna']['reservation_description'];
                $fair_minna->price_description = $params['fair_minna']['price_description'];
                $fair_minna->post_year = $params['fair_minna']['post_year'];
                $fair_minna->post_month = $params['fair_minna']['post_month'];
                $fair_minna->post_day = $params['fair_minna']['post_day'];
                $fair_minna->post_time = $params['fair_minna']['post_time'];
                $fair_minna->reservable_period = $params['fair_minna']['reservable_period'];
                $fair_minna->reflect_status = $params['fair_minna']['reflect_status'];
                $fair_minna->save();
            } elseif ($params['minna_flg'] == self::DELETED) {
                $fair_minna->delete();
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

    public function reflectFairInfo(string $id) {
        // $fair = Fair::find($id);
        //
        // if (is_null($fair)) {
        //     return response()->json(['code' => 'NG', 'message' => 'notFound'], 200);
        // }
        // DB::beginTransaction();
        // try {
        //     $params = json_decode(file_get_contents('php://input'), true);
        //
        //     //フェアの更新
        //     $fair->reflect_status = $params['reflect_status'];
        //
        //     $fair->save();
        //
        //     $json_count = count($params['fairContent']);
        //     $fair_content = null;
        //
        //     //フェア内容の更新
        //     for($i = 0; $i < $json_count; $i++){
        //         $fair_content = FairContent::find($params['fairContent'][$i]['id']);
        //         $fair_content->reflect_status = $params['fairContent'][$i]['reflectStatus'];
        //
        //         $fair_content->save();
        //     }
        //
        //     //フェア(ウェディングパーク)の更新
        //     $fair_weddingpark = FairWeddingpark::find($params['fairWeddingpark']['id']);
        //     $fair_weddingpark->reflect_status = $params['fairWeddingpark']['reflectStatus'];
        //
        //     $fair_weddingpark->save();
        //
        //     //フェア(マイナビ)の更新
        //     $fair_mynavi = FairMynavi::find($params['fairMynavi']['id']);
        //     $fair_mynavi->reflect_status = $params['fairMynavi']['reflectStatus'];
        //
        //     $fair_mynavi->save();
        //
        //     //フェア(ぐるなび)の更新
        //     $fair_gurunavi = FairGurunavi::find($params['fairGurunavi']['id']);
        //     $fair_gurunavi->reflect_status = $params['fairGurunavi']['reflectStatus'];
        //
        //     $fair_gurunavi->save();
        //
        //     //フェア(楽天)の更新
        //     $fair_rakuten = FairRakuten::find($params['fairRakuten']['id']);
        //     $fair_rakuten->reflect_status = $params['fairRakuten']['reflectStatus'];
        //
        //     $fair_rakuten->save();
        //
        //     //フェア(ゼクシィ)の更新
        //     $fair_zexy = FairZexy::find($params['fairZexy']['id']);
        //     $fair_zexy->reflect_status = $params['fairZexy']['reflectStatus'];
        //
        //     $fair_zexy->save();
        //
        //     //フェア(みんなのウェディング)の更新
        //     $fair_minna = FairMinna::find($params['fairMinna']['id']);
        //     $fair_minna->reflect_status = $params['fairMinna']['reflectStatus'];
        //
        //     $fair_minna->save();
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
