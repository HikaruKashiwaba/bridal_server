<?php

namespace App\Http\Controllers;

use App\Fair;
use App\FairContent;
use App\FairWeddingpark;
use App\FairMynavi;
use App\FairGurunavi;
use App\FairRakuten;
use App\FairZexy;
use App\FairMinna;
use App\Account;
use Illuminate\Http\Request;

class FairController extends Controller
{
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */

    const FAIR_SITE_TYPE = new Array(
        'ZEXY' => '1',
        'WEDDINGPARK' => '2',
        'MYNAVI' => '3',
        'GURUNAVI' => '4',
        'MINNA' => '5',
        'RAKUTEN' => '6'
    );

    const FAIR_NAME = ['fair_zexy', 'fair_weddingpark', 'fair_mynavi', 'fair_gurunavi', 'fair_minna', 'fair_rakuten'];

    const FAIR_MODEL_NAME = new Array(
        'fair_zexy' => 'FairZexy',
        'fair_weddingpark' => 'FairWeddingpark',
        'fair_mynavi' => 'FairMynavi',
        'fair_gurunavi' => 'FairGurunavi',
        'fair_minna' => 'FairMinna',
        'fair_rakuten' => 'FairRakuten'
    );

    const FAIR_FLG_NAME = new Array(
        'fair_zexy' => 'zexy_flg',
        'fair_weddingpark' => 'weddingpark_flg',
        'fair_mynavi' => 'mynavi_flg',
        'fair_gurunavi' => 'gurunavi_flg',
        'fair_minna' => 'minna_flg',
        'fair_rakuten' => 'rakuten_flg'
    );


    const REGISTERED = '1'

    const NO_REGISTRATION = '0';
    const NEW_REGISTER = '1';
    const UPDATE_RECORD_REGISTERED = '2';
    const DELETED = '3';

    const SITE_COUNT = 6;




    //ユーザに紐づくフェア一覧を取得する
    public function getFairList(string $id)
    {
        $items = Fair::where('member_id', $id)->get();
        return response()->json(['records' => $items], 200);
    }

    //一つのフェア情報を取得する
    public function getFair(string $id, string $fairId)
    {
        //Fairからユーザと各サイト登録情報を取得する
        $items = Fair::where('member_id', $id)->where('id', $fairId);
        //$itemsが持つ、各サイトの登録フラグを元にフェア情報を取得する
        //ゼクシイに登録してある場合
        if ($items->'zexy_flag' == REGISTERED) {
            $items->fairZexy = FairZexy::where('id', $id)->where('fair_id', $fairId);
            $items->fairZexy->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', FAIR_SITE_TYPE['ZEXY']);
        }
        //ウェディングパークに登録してある場合
        if ($items->'weddingpark_flag' == REGISTERED) {
            $items->fairWeddingPark = FairWeddingPark::where('id', $id)->where('fair_id', $fairId);
            $items->fairWeddingPark->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', FAIR_SITE_TYPE['WEDDINGPARK']);
        }
        //マイナビに登録してある場合
        if ($items->'mynavi_flag' == REGISTERED) {
            $items->fairMynavi = FairMynavi::where('id', $id)->where('fair_id', $fairId);
            $items->fairMynavi->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', FAIR_SITE_TYPE['MYNAVI']);
        }
        //ぐるなびに登録してある場合
        if ($items->'gurunavi_flag' == REGISTERED) {
            $items->fairGurunavi = FairGurunavi::where('id', $id)->where('fair_id', $fairId);
            $items->fairGurunavi->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', FAIR_SITE_TYPE['GURUNAVI']);
        }
        //みんなのウェディングに登録してある場合
        if ($items->'minna_flag' == REGISTERED) {
            $items->fairMinna = FairMinna::where('id', $id)->where('fair_id', $fairId);
            $items->fairMinna->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', FAIR_SITE_TYPE['MINNA']);
        }
        //楽天に登録してある場合
        if ($items->'rakuten_flag' == REGISTERED) {
            $items->fairRakuten = FairRakuten::where('id', $id)->where('fair_id', $fairId);
            $items->fairRakuten->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', FAIR_SITE_TYPE['RAKUTEN']);
        }

        return response()->json(['data', $items], 200);


    /**
     * フェアを登録する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = json_decode(file_get_contents('php://input'), true;

            //フェアの登録
            $fair = Fair::find($params['id']);
            $fair->member_id = $params['memberId'];
            $fair->title = $params['title'];
            $fair->image_id = $params['imageId'];
            $fair->start_hour = $params['startHour'];
            $fair->start_minute = $params['startMinute'];
            $fair->end_Hour = $params['endHour'];
            $fair->end_Minute = $params['endMinute'];
            if ($params['weddingParkFlg'] == NO_REGISTRATION || $params['weddingParkFlg'] == DELETED) {
                $fair->weddingpark_flg = NO_REGISTRATION;
            } else {
                $fair->weddingpark_flg = REGISTERED;
            }
            if ($params['mynaviFlg'] == NO_REGISTRATION || $params['mynaviFlg'] == DELETED) {
                $fair->mynavi_flg = NO_REGISTRATION;
            } else {
                $fair->mynavi_flg = REGISTERED;
            }
            if ($params['gurunaviFlg'] == NO_REGISTRATION || $params['gurunaviFlg'] == DELETED) {
                $fair->gurunavi_flg = NO_REGISTRATION;
            } else {
                $fair->gurunavi_flg = REGISTERED;
            }
            if ($params['rakutenFlg'] == NO_REGISTRATION || $params['rakutenFlg'] == DELETED) {
                $fair->rakuten_flg = NO_REGISTRATION;
            } else {
                $fair->rakuten_flg = REGISTERED;
            }
            if ($params['zexyFlg'] == NO_REGISTRATION || $params['zexyFlg'] == DELETED) {
                $fair->zexy_flg = NO_REGISTRATION;
            } else {
                $fair->zexy_flg = REGISTERED;
            }
            if ($params['minnaFlg'] == NO_REGISTRATION || $params['minnaFlg'] == DELETED) {
                $fair->minna_flg = NO_REGISTRATION;
            } else {
                $fair->minna_flg = REGISTERED;
            }
            $fair->reflect_status = $params['reflectStatus'];
            $fair->create_user = $params['memberId'];
            $fair->update_user = $params['memberId'];
            //登録
            $fair->save();

            //フェアの登録IDを保管しておく
            $last_insert_id = $fair->id;

            // $json_count = count($params['fairContent']);
            $fair_content = null;
            //フェア内容の登録
            //サイト数分ループさせる
            for ($j = 0; $j < SITE_COUNT; $j++) {
                //画面からの送信内容を各サイトごとに取得する
                $items = $params[FAIR_NAME[j]];
                if (!is_null($items) && $params[]) {
                    $fair_each = FAIR_MODEL_NAME[FAIR_NAME[j]]::find($last_insert_id);

                    //各サイトごとのコンテントを登録もしくは削除・更新を行う
                    $json_count = count($items['fairContent']);
                    for($i = 0; $i < $json_count; $i++){
                        $fair_content = FairContent::find($last_insert_id);

                        $fair_content->fair_id = $last_insert_id;
                        $fair_content->site_type = $params['fairContent'][$i]['siteType'];
                        $fair_content->order_id = $params['fairContent'][$i]['orderId'];
                        $fair_content->content = $params['fairContent'][$i]['content'];
                        $fair_content->other_title = $params['fairContent'][$i]['otherTitle'];
                        $fair_content->reserve_status = $params['fairContent'][$i]['reserveStatus'];
                        $fair_content->reserve_count = $params['fairContent'][$i]['reserveCount'];
                        $fair_content->reserve_unit = $params['fairContent'][$i]['reserveUnit'];
                        $fair_content->price_status = $params['fairContent'][$i]['priceStatus'];
                        $fair_content->price = $params['fairContent'][$i]['price'];
                        $fair_content->price_per_person = $params['fairContent'][$i]['pricePerPerson'];
                        $fair_content->required_time = $params['fairContent'][$i]['requiredTime'];
                        $fair_content->title = $params['fairContent'][$i]['title'];
                        $fair_content->content_detail = $params['fairContent'][$i]['contentDetail'];
                        $fair_content->event_kbn1 = $params['fairContent'][$i]['eventKbn1'];
                        $fair_content->event_kbn2 = $params['fairContent'][$i]['eventKbn2'];
                        $fair_content->image_id = $params['fairContent'][$i]['imageId'];
                        $fair_content->start_hour1 = $params['fairContent'][$i]['startHour1'];
                        $fair_content->start_minute1 = $params['fairContent'][$i]['startMinute1'];
                        $fair_content->end_hour1 = $params['fairContent'][$i]['endHour1'];
                        $fair_content->end_minute1 = $params['fairContent'][$i]['endMinute1'];
                        $fair_content->start_hour2 = $params['fairContent'][$i]['startHour2'];
                        $fair_content->start_minute2 = $params['fairContent'][$i]['startMinute2'];
                        $fair_content->end_hour2 = $params['fairContent'][$i]['endHour2'];
                        $fair_content->end_minute2 = $params['fairContent'][$i]['endMinute2'];
                        $fair_content->start_hour3 = $params['fairContent'][$i]['startHour3'];
                        $fair_content->start_minute3 = $params['fairContent'][$i]['startMinute3'];
                        $fair_content->end_hour3 = $params['fairContent'][$i]['endHour3'];
                        $fair_content->end_minute3 = $params['fairContent'][$i]['endMinute3'];
                        $fair_content->start_hour4 = $params['fairContent'][$i]['startHour4'];
                        $fair_content->start_minute4 = $params['fairContent'][$i]['startMinute4'];
                        $fair_content->end_hour4 = $params['fairContent'][$i]['endHour4'];
                        $fair_content->end_minute4 = $params['fairContent'][$i]['endMinute4'];
                        $fair_content->start_hour5 = $params['fairContent'][$i]['startHour5'];
                        $fair_content->start_minute5 = $params['fairContent'][$i]['startMinute5'];
                        $fair_content->end_hour5 = $params['fairContent'][$i]['endHour5'];
                        $fair_content->end_minute5 = $params['fairContent'][$i]['endMinute5'];
                        // $fair_content-> = $params['fairContent'][$i][''];

                        //登録
                        $fair_content->save();
                }
            }





















            // if (!is_null($params['fairWeddingpark'])) {
            //     //フェア(ウェディングパーク)の登録
            //     $fair_weddingpark = new FairWeddingPark;
            //     $fair_weddingpark->fair_id = $last_insert_id;
            //     $fair_weddingpark->price = $params['fairWeddingpark']['price'];
            //     $fair_weddingpark->price_per_person = $params['fairWeddingpark']['pricePerPerson'];
            //     $fair_weddingpark->pc_url = $params['fairWeddingpark']['pcUrl'];
            //     $fair_weddingpark->phone_url = $params['fairWeddingpark']['phoneUrl'];
            //     $fair_weddingpark->reflect_status = $params['fairWeddingpark']['reflectStatus'];
            //     $fair_weddingpark->create_user = $params['memberId'];
            //     $fair_weddingpark->update_user = $params['memberId'];
            //     //登録
            //     $fair_weddingpark->save();
            // }
            //
            // if (!is_null($params['fairMynavi'])) {
            //     //フェア(マイナビ)の登録
            //     $fair_mynavi = new FairMynavi;
            //     $fair_mynavi->fair_id = $last_insert_id;
            //     $fair_mynavi->benefit_flg = $params['fairMynavi']['benefitFlg'];
            //     $fair_mynavi->limited_benefit_flg = $params['fairMynavi']['limitedBenefitFlg'];
            //     $fair_mynavi->place_remarks = $params['fairMynavi']['placeRemarks'];
            //     $fair_mynavi->place_other = $params['fairMynavi']['placeOther'];
            //     $fair_mynavi->prevent_selection_flg = $params['fairMynavi']['preventSelectionFlg'];
            //     $fair_mynavi->reflect_status = $params['fairMynavi']['reflectStatus'];
            //     $fair_mynavi->create_user = $params['memberId'];
            //     $fair_mynavi->update_user = $params['memberId'];
            //     //登録
            //     $fair_mynavi->save();
            // }
            //
            // if (!is_null($params['fairGurunavi'])) {
            //     //フェア(ぐるなび)の登録
            //     $fair_gurunavi = new FairGurunavi;
            //     $fair_gurunavi->fair_id = $last_insert_id;
            //     $fair_gurunavi->limited_gurunavi_flg = $params['fairGurunavi']['limitedGurunaviFlg'];
            //     $fair_gurunavi->one_person_flg = $params['fairGurunavi']['onePersonFlg'];
            //     $fair_gurunavi->catch_copy = $params['fairGurunavi']['catchCopy'];
            //     $fair_gurunavi->capacity = $params['fairGurunavi']['capacity'];
            //     $fair_gurunavi->attention_point = $params['fairGurunavi']['attentionPoint'];
            //     $fair_gurunavi->reflect_status = $params['fairGurunavi']['reflectStatus'];
            //     $fair_gurunavi->create_user = $params['memberId'];
            //     $fair_gurunavi->update_user = $params['memberId'];
            //     //登録
            //     $fair_gurunavi->save();
            // }
            //
            // if (!is_null($params['fairRakuten'])) {
            //     //フェア(楽天)の登録
            //     $fair_rakuten = new FairRakuten;
            //     $fair_rakuten->fair_id = $last_insert_id;
            //     $fair_rakuten->fair_content_detail = $params['fairRakuten']['fairContentDetail'];
            //     $fair_rakuten->reflect_status = $params['fairRakuten']['reflectStatus'];
            //     $fair_rakuten->create_user = $params['memberId'];
            //     $fair_rakuten->update_user = $params['memberId'];
            //     //登録
            //     $fair_rakuten->save();
            // }
            //
            // if (!is_null($params['fairZexy'])) {
            //     //フェア(ゼクシィ)の登録
            //     $fair_zexy = new FairZexy;
            //     $fair_zexy->fair_id = $last_insert_id;
            //     $fair_zexy->fair_type = $params['fairZexy']['fairType'];
            //     $fair_zexy->realtime_reserve_flg = $params['fairZexy']['realtimeReserveFlg'];
            //     $fair_zexy->reserve_unit = $params['fairZexy']['reserveUnit'];
            //     $fair_zexy->parking = $params['fairZexy']['parking'];
            //     $fair_zexy->tel_number1 = $params['fairZexy']['telNumber1'];
            //     $fair_zexy->tel_type1 = $params['fairZexy']['telType1'];
            //     $fair_zexy->tel_staff1 = $params['fairZexy']['telStaff1'];
            //     $fair_zexy->tel_number2 = $params['fairZexy']['telNumber2'];
            //     $fair_zexy->tel_type2 = $params['fairZexy']['telType2'];
            //     $fair_zexy->tel_staff2 = $params['fairZexy']['telStaff2'];
            //     $fair_zexy->benefit_period = $params['fairZexy']['benefitPeriod'];
            //     $fair_zexy->benefit_remarks = $params['fairZexy']['benefitRemarks'];
            //     $fair_zexy->question = $params['fairZexy']['question'];
            //     $fair_zexy->required_question_flg = $params['fairZexy']['requiredQuestionFlg'];
            //     $fair_zexy->reception_time = $params['fairZexy']['receptionTime'];
            //     $fair_zexy->reception_Staff = $params['fairZexy']['receptionStaff'];
            //     $fair_zexy->reflect_status = $params['fairZexy']['reflectStatus'];
            //     $fair_zexy->create_user = $params['memberId'];
            //     $fair_zexy->update_user = $params['memberId'];
            //     //登録
            //     $fair_zexy->save();
            // }
            //
            // if (!is_null($params['fairMinna'])) {
            //     //フェア(みんなのウェディング)の登録
            //     $fair_minna = new FairMinna;
            //     $fair_minna->fair_id = $last_insert_id;
            //     $fair_minna->reservation_description = $params['fairMinna']['reservationDescription'];
            //     $fair_minna->reflect_status = $params['fairMinna']['reflectStatus'];
            //     $fair_minna->create_user = $params['memberId'];
            //     $fair_minna->update_user = $params['memberId'];
            //     //登録
            //     $fair_minna->save();
            // }

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

    public function update(string $id)
    {
        DB::beginTransaction();
        try {
            $params = json_decode(file_get_contents('php://input'), true);

            //フェアの更新する情報を取得
            $fair = Fair::find($id);

            if (is_null($fair)) {
                return response()->json(['code' => 'NG', 'message' => 'notFound'], 200);
            }

            $fair->member_id = $params['memberId'];
            $fair->title = $params['title'];
            $fair->image_id = $params['imageId'];
            $fair->start_hour = $params['startHour'];
            $fair->start_minute = $params['startMinute'];
            $fair->end_Hour = $params['endHour'];
            $fair->end_Minute = $params['endMinute'];
            $fair->weddingpark_flg = $params['weddingparkFlg'];
            $fair->mynavi_flg = $params['mynaviFlg'];
            $fair->gurunavi_flg = $params['gurunaviFlg'];
            $fair->rakuten_flg = $params['rakutenFlg'];
            $fair->zexy_flg = $params['zexyFlg'];
            $fair->minna_flg = $params['minnaFlg'];
            $fair->reflect_status = $params['reflectStatus'];
            $fair->create_user = $params['memberId'];
            $fair->update_user = $params['memberId'];


            // $fair->event_date = $params['eventDate'];
            // $fair->description_short = $params['descriptionShort'];
            // $fair->description_medium = $params['descriptionMedium'];
            // $fair->description_long = $params['descriptionLong'];
            // $fair->reserve_way = $params['reserveWay'];
            // $fair->price_status = $params['priceStatus'];
            // $fair->price_remarks = $params['priceRemarks'];
            // $fair->picture_path = $params['picturePath'];
            // $fair->need_reservation = $params['needReservation'];
            // $fair->place = $params['place'];
            // $fair->required_time = $params['requiredTime'];
            // $fair->benefit = $params['benefit'];
            // $fair->target = $params['target'];
            // $fair->content_other = $params['contentOther'];
            // $fair->fair_other_title = $params['fair_otherTitle'];
            //
            // $fair->mynavi_flg = $params['mynaviFlg'];
            // $fair->gurunavi_flg = $params['gurunaviFlg'];
            // $fair->rakuten_flg = $params['rakutenFlg'];
            // $fair->zexy_flg = $params['zexyFlg'];
            // $fair->minna_flg = $params['minnaFlg'];
            // $fair->reflect_status = $params['reflectStatus'];
            // $fair->create_user = $params['memberId'];
            // $fair->update_user = $params['memberId'];
            //更新
            $fair->save();
            //フェアの登録IDを保管しておく
            $last_insert_id = $fair->id;

            $json_count = count($params['fairContent']);

            $fair_content = null;

            //フェア内容の更新
            for($i = 0; $i < $json_count; $i++){
                //新規で追加されたものは登録
                if (is_null($params['fairContent'][$i]['id'])) {
                    $fair_content = new FairContent;
                } else {
                    $fair_content = FairContent::find($params['fairContent'][$i]['id']) ?? new FairContent;
                    //フェア内容を削除する場合
                    if ($params['fairContent'][$i]['deleteFlg'] === '1') {
                        $fair_content->delete();
                        continue;
                    }
                }
                $fair_content->fair_id = $last_insert_id;
                $fair_content->order_id = $params['fairContent'][$i]['order_id'];
                $fair_content->content = $params['fairContent'][$i]['content'];
                $fair_content->start_time = $params['fairContent'][$i]['startTime'];
                $fair_content->end_time = $params['fairContent'][$i]['endTime'];
                $fair_content->price_status = $params['fairContent'][$i]['priceStatus'];
                $fair_content->price = $params['fairContent'][$i]['price'];
                $fair_content->price_per_person = $params['fairContent'][$i]['pricePerPerson'];
                $fair_content->need_reservation = $params['fairContent'][$i]['needReservation'];
                $fair_content->caption_short = $params['fairContent'][$i]['captionShort'];
                $fair_content->caption_long = $params['fairContent'][$i]['captionLong'];
                $fair_content->remarks_short = $params['fairContent'][$i]['remarksShort'];
                $fair_content->remarks_medium = $params['fairContent'][$i]['remarksMedium'];
                $fair_content->remarks_long = $params['fairContent'][$i]['remarksLong'];
                $fair_content->picture_path = $params['fairContent'][$i]['picturePath'];
                $fair_content->required_time = $params['fairContent'][$i]['requiredTime'];
                $fair_content->reflect_status = $params['fairContent'][$i]['reflectStatus'];
                $fair_content->create_user = $params['memberId'];
                $fair_content->update_user = $params['memberId'];
                //登録or更新
                $fair_content->save();
            }

            if (!is_null($params['fairWeddingpark'])) {
                $fair_weddingpark = null;
                //フェア(ウェディングパーク)の更新する情報を取得
                if (is_null($params['fairWeddingpark']['id'])) {
                    $fair_weddingpark = new FairWeddingpark;
                } else {
                    $fair_weddingpark = FairWeddingpark::find($params['fairWeddingpark']['id']) ?? new FairWeddingpark;
                }

                //フェア(ウェディングパーク)を削除する場合
                if ($params['fairWeddingpark']['deleteFlg'] === '1') {
                    $fair_weddingpark->delete();
                } else {
                    $fair_weddingpark->fair_id = $last_insert_id;
                    $fair_weddingpark->price = $params['fairWeddingpark']['price'];
                    $fair_weddingpark->price_per_person = $params['fairWeddingpark']['pricePerPerson'];
                    $fair_weddingpark->pc_url = $params['fairWeddingpark']['pcUrl'];
                    $fair_weddingpark->phone_url = $params['fairWeddingpark']['phoneUrl'];
                    $fair_weddingpark->reflect_status = $params['fairWeddingpark']['reflectStatus'];
                    $fair_weddingpark->create_user = $params['memberId'];
                    $fair_weddingpark->update_user = $params['memberId'];
                    //更新
                    $fair_weddingpark->save();
                }
            }

            if (!is_null($params['fairMynavi'])) {
                $fair_mynavi = null;
                //フェア(マイナビ)の更新する情報を取得
                if (is_null($params['fairMynavi']['id'])) {
                    $fair_mynavi = new FairMynavi;
                } else {
                    $fair_mynavi = FairMynavi::find($params['fairMynavi']['id']) ?? new FairMynavi;
                }

                //フェア(マイナビ)を削除する場合
                if ($params['fairMynavi']['deleteFlg'] === '1') {
                    $fair_mynavi->delete();
                } else {
                    $fair_mynavi->fair_id = $last_insert_id;
                    $fair_mynavi->benefit_flg = $params['fairMynavi']['benefitFlg'];
                    $fair_mynavi->limited_benefit_flg = $params['fairMynavi']['limitedBenefitFlg'];
                    $fair_mynavi->place_remarks = $params['fairMynavi']['placeRemarks'];
                    $fair_mynavi->place_other = $params['fairMynavi']['placeOther'];
                    $fair_mynavi->prevent_selection_flg = $params['fairMynavi']['preventSelectionFlg'];
                    $fair_mynavi->reflect_status = $params['fairMynavi']['reflectStatus'];
                    $fair_mynavi->create_user = $params['memberId'];
                    $fair_mynavi->update_user = $params['memberId'];
                    //更新
                    $fair_mynavi->save();
                }
            }

            if (!is_null($params['fairGurunavi'])) {
                $fair_gurunavi = null;
                //フェア(ぐるなび)の更新する情報を取得
                if (is_null($params['fairGurunavi']['id'])) {
                    $fair_gurunavi = new FairGurunavi;
                } else {
                    $fair_gurunavi = FairGurunavi::find($params['fairGurunavi']['id']) ?? new FairGurunavi;
                }

                //フェア(ぐるなび)を削除する場合
                if ($params['fairGurunavi']['deleteFlg'] === '1') {
                    $fair_gurunavi->delete();
                } else {
                    $fair_gurunavi->fair_id = $last_insert_id;
                    $fair_gurunavi->limited_gurunavi_flg = $params['fairGurunavi']['limitedGurunaviFlg'];
                    $fair_gurunavi->one_person_flg = $params['fairGurunavi']['onePersonFlg'];
                    $fair_gurunavi->catch_copy = $params['fairGurunavi']['catchCopy'];
                    $fair_gurunavi->capacity = $params['fairGurunavi']['capacity'];
                    $fair_gurunavi->attention_point = $params['fairGurunavi']['attentionPoint'];
                    $fair_gurunavi->reflect_status = $params['fairGurunavi']['reflectStatus'];
                    $fair_gurunavi->create_user = $params['memberId'];
                    $fair_gurunavi->update_user = $params['memberId'];
                    //更新
                    $fair_gurunavi->save();
                }
            }

            if (!is_null($params['fairRakuten'])) {
                $fair_rakuten = null;
                //フェア(楽天)の更新する情報を取得
                if (is_null($params['fairRakuten']['id'])) {
                    $fair_rakuten = new FairRakuten;
                } else {
                    $fair_rakuten = FairRakuten::find($params['fairGurunavi']['id']) ?? new FairRakuten;
                }

                //フェア(楽天)を削除する場合
                if ($params['fairRakuten']['deleteFlg'] === '1') {
                    $fair_rakuten->delete();
                } else {
                    $fair_rakuten->fair_id = $last_insert_id;
                    $fair_rakuten->fair_content_detail = $params['fairRakuten']['fairContentDetail'];
                    $fair_rakuten->reflect_status = $params['fairRakuten']['reflectStatus'];
                    $fair_rakuten->create_user = $params['memberId'];
                    $fair_rakuten->update_user = $params['memberId'];
                    //更新
                    $fair_rakuten->save();
                }
            }

            if (!is_null($params['fairZexy'])) {
                $fair_zexy = null;
                //フェア(ゼクシィ)の更新する情報を取得
                if (is_null($params['fairZexy']['id'])) {
                    $fair_zexy = new FairZexy;
                } else {
                    $fair_zexy = FairZexy::find($params['fairZexy']['id']) ?? new FairZexy;
                }

                //フェア(ゼクシィ)を削除する場合
                if ($params['fairZexy']['deleteFlg'] === '1') {
                    $fair_zexy->delete();
                } else {
                    $fair_zexy->fair_id = $last_insert_id;
                    $fair_zexy->fair_type = $params['fairZexy']['fairType'];
                    $fair_zexy->realtime_reserve_flg = $params['fairZexy']['realtimeReserveFlg'];
                    $fair_zexy->reserve_unit = $params['fairZexy']['reserveUnit'];
                    $fair_zexy->parking = $params['fairZexy']['parking'];
                    $fair_zexy->tel_number1 = $params['fairZexy']['telNumber1'];
                    $fair_zexy->tel_type1 = $params['fairZexy']['telType1'];
                    $fair_zexy->tel_staff1 = $params['fairZexy']['telStaff1'];
                    $fair_zexy->tel_number2 = $params['fairZexy']['telNumber2'];
                    $fair_zexy->tel_type2 = $params['fairZexy']['telType2'];
                    $fair_zexy->tel_staff2 = $params['fairZexy']['telStaff2'];
                    $fair_zexy->benefit_period = $params['fairZexy']['benefitPeriod'];
                    $fair_zexy->benefit_remarks = $params['fairZexy']['benefitRemarks'];
                    $fair_zexy->question = $params['fairZexy']['question'];
                    $fair_zexy->required_question_flg = $params['fairZexy']['requiredQuestionFlg'];
                    $fair_zexy->reception_time = $params['fairZexy']['receptionTime'];
                    $fair_zexy->reception_Staff = $params['fairZexy']['receptionStaff'];
                    $fair_zexy->reflect_status = $params['fairZexy']['reflectStatus'];
                    $fair_zexy->create_user = $params['memberId'];
                    $fair_zexy->update_user = $params['memberId'];
                    //更新
                    $fair_zexy->save();
                }
            }

            if (!is_null($params['fairMinna'])) {
                $fair_minna = null;
                //フェア(みんなのウェディング)の更新する情報を取得
                if (is_null($params['fairMinna']['id'])) {
                    $fair_minna = new FairMinna;
                } else {
                    $fair_minna = FairMinna::find($params['fairMinna']['id']) ?? new FairMinna;
                }

                //フェア(みんなのウェディング)を削除する場合
                if ($params['fairMinna']['deleteFlg'] === '1') {
                    $fair_minna->delete();
                } else {
                    $fair_minna->fair_id = $last_insert_id;
                    $fair_minna->reservation_description = $params['fairMinna']['reservationDescription'];
                    $fair_minna->reflect_status = $params['fairMinna']['reflectStatus'];
                    $fair_minna->create_user = $params['memberId'];
                    $fair_minna->update_user = $params['memberId'];
                    //更新
                    $fair_minna->save();
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

    //フェア削除
    public function deleteFairInfo(string $id) {
        // $fair = Fair::find($id);
        //
        // if (is_null($fair)) {
        //     return response()->json(['code' => 'NG', 'message' => 'notFound'], 200);
        // }
        // DB::beginTransaction();
        // try {
        //     $fairContent = FairContent::where('fair_id', $fair->id);
        //
        //     if (!is_null($fairContent)) {
        //         $fairContent->delete();
        //     }
        //
        //     $fairWeddingpark = FairWeddingpark::where('fair_id', $fair->id)->first();
        //
        //     if (!is_null($fairWeddingpark)) {
        //         $fairWeddingpark->delete();
        //     }
        //
        //     $fairMynavi = FairMynavi::where('fair_id', $fair->id)->first();
        //
        //     if (!is_null($fairMynavi)) {
        //         $fairMynavi->delete();
        //     }
        //
        //     $fairGurunavi = FairGurunavi::where('fair_id', $fair->id)->first();
        //
        //     if (!is_null($fairGurunavi)) {
        //         $fairGurunavi->delete();
        //     }
        //
        //     $fairRakuten = FairRakuten::where('fair_id', $fair->id)->first();
        //
        //     if (!is_null($fairRakuten)) {
        //         $fairRakuten->delete();
        //     }
        //
        //     $fairZexy = FairZexy::where('fair_id', $fair->id)->first();
        //
        //     if (!is_null($fairZexy)) {
        //         $fairZexy->delete();
        //     }
        //
        //     $fairMinna = FairMinna::where('fair_id', $fair->id)->first();
        //
        //     if (!is_null($fairMinna)) {
        //         $fairMinna->delete();
        //     }
        //
        //     $fair->delete();
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
