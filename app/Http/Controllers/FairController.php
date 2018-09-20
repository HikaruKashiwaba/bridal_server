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

    const FAIR_SITE_NAME = [
        'fair_zexy',
        'fair_weddingpark',
        'fair_mynavi',
        'fair_gurunavi',
        'fair_minna',
        'fair_rakuten'
    ];

    const FAIR_FLG_NAME = [
        'zexy_flg',
        'weddingpark_flg',
        'mynavi_flg',
        'gurunavi_flg',
        'minna_flg',
        'rakuten_flg'
    ];

    const FAIR_SITE_TYPE = [
        'ZEXY' => '1',
        'WEDDINGPARK' => '2',
        'MYNAVI' => '3',
        'GURUNAVI' => '4',
        'MINNA' => '5',
        'RAKUTEN' => '6'
    ];


    const REGISTERED = '1';

    const NO_REGISTRATION = '0';
    const NEW_REGISTER = '1';
    const UPDATE_RECORD_REGISTERED = '2';
    const DELETED = '3';


    /*  ユーザに紐づくフェア一覧を取得する  */
    public function getFairList(string $id)
    {
        $items = Fair::where('member_id', $id)->get();
        return response()->json(['records' => $items], 200);
    }


    /*  一つのフェア詳細情報を取得する  */
    public function getFair(string $id, string $fairId)
    {
        //Fairからユーザと各サイト登録情報を取得する
        $items = Fair::where('id', $id)->where('member_id', $fairId)->first();
        Log::debug($items);
        //$itemsが持つ、各サイトの登録フラグを元にフェア情報を取得する
        //ゼクシイに登録してある場合
        if ($items->zexy_flg== self::REGISTERED) {
            $items->fairZexy = FairZexy::where('fair_id', $fairId)->first();
            $items->fairZexy->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', self::FAIR_SITE_TYPE['ZEXY'])->get();
        }
        //ウェディングパークに登録してある場合
        if ($items->weddingpark_flg == self::REGISTERED) {
            $items->fairWeddingPark = FairWeddingPark::where('fair_id', $fairId)->first();
            $items->fairWeddingPark->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', self::FAIR_SITE_TYPE['WEDDINGPARK'])->get();
        }
        //マイナビに登録してある場合
        if ($items->mynavi_flg == self::REGISTERED) {
            $items->fairMynavi = FairMynavi::where('fair_id', $fairId)->first();
            $items->fairMynavi->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', self::FAIR_SITE_TYPE['MYNAVI'])->get();
        }
        //ぐるなびに登録してある場合
        if ($items->gurunavi_flg == self::REGISTERED) {
            $items->fairGurunavi = FairGurunavi::where('fair_id', $fairId)->first();
            $items->fairGurunavi->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', self::FAIR_SITE_TYPE['GURUNAVI'])->get();
        }
        //みんなのウェディングに登録してある場合
        if ($items->minna_flg == self::REGISTERED) {
            $items->fairMinna = FairMinna::where('fair_id', $fairId)->first();
            $items->fairMinna->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', self::FAIR_SITE_TYPE['MINNA'])->get();
        }
        //楽天に登録してある場合
        if ($items->rakuten_flg == self::REGISTERED) {
            $items->fairRakuten = FairRakuten::where('fair_id', $fairId)->first();
            $items->fairRakuten->fairContent = FairContent::where('fair_id', $fairId)->where('site_type', self::FAIR_SITE_TYPE['RAKUTEN'])->get();
        }

        return response()->json(['fair' => $items], 200);
        // return response()->json(['id' => $id, 'fair_id' => $fairId, 'data' => $items], 200);
        // return response()->json(['dummy' => 'ok'], 200);
    }


    /**
     * フェア登録
     * フェア内容の登録・更新・削除
     *
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = json_decode(file_get_contents('php://input'), true);
            Log::debug($params);
            //フェアの登録
            $fair = Fair::find($params['id']);
            //新規登録の場合
            if (is_null($fair)) {
                $fair = new Fair;
            }

            $fair->member_id = $params['member_id'];
            $fair->title = $params['title'];
            $fair->image_id = $params['image_id'];
            $fair->start_hour = $params['start_hour'];
            $fair->start_minute = $params['start_minute'];
            $fair->end_Hour = $params['end_hour'];
            $fair->end_Minute = $params['end_minute'];

            //フェアの各サイト登録フラグを書き換える
            for ($i = 0; $i < count(self::FAIR_FLG_NAME); $i++) {
                //登録なし、もしくは削除する場合      
                if ($params[self::FAIR_FLG_NAME[$i]] == self::NO_REGISTRATION || $params[self::FAIR_FLG_NAME[$i]] == self::DELETED) {
                    $fair[self::FAIR_FLG_NAME[$i]] == self::NO_REGISTRATION;
                //新規登録・登録更新処理
                } else {
                    $fair[self::FAIR_FLG_NAME[$i]] = self::REGISTERED;
                }

                $fair->save();

                //フェア内容の登録処理
                //フェア内容の新規登録、もしくは更新の場合
                if ($params[self::FAIR_FLG_NAME[$i]] == self::NEW_REGISTER || $params[self::FAIR_FLG_NAME[$i]] == self::UPDATE_RECORD_REGISTERED) {
                    //更新処理で、なおかつフェア内容登録が減っている場合は余分な部分をまず削除する
                    if ($params[self::FAIR_FLG_NAME[$i]] == self::UPDATE_RECORD_REGISTERED) {
                        $fair_content_before_update = FairContent::find($params['id'])
                                        ->where('site_type', $params[self::FAIR_SITE_NAME[$i]]['fair_content'][0]['site_type'])
                                        ->where('order_id', '>', count($params[self::FAIR_SITE_NAME[$i]]['fair_content']))->get();
                        for ($i = 0; $i < count($fair_content_before_update); $i++) {
                            $fair_content_before_update[$i]->delete();
                        }
                    }

                    //FairContentのオブジェクトを取得する
                    //新規登録
                    if ($params[self::FAIR_FLG_NAME[$i]] == self::NEW_REGISTER) {
                        $fair_content = new FairContent;
                    //更新
                    } else {
                        $fair_content = FairContent::find($params['id'])->where('site_type', $params[self::FAIR_SITE_NAME[$i]]['fair_content'][0]['site_type'])->first();
                    }

                    //個別のフェア内容の更新処理
                    for ($j = 0; $j < count($params[self::FAIR_SITE_NAME[$i]]['fair_content']); $j++) {
                        $fair_content->fair_id = $fair['id'];
                        $fair_content->site_type = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['site_type'];
                        $fair_content->order_id = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['order_id'];
                        $fair_content->content = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['content'];
                        $fair_content->other_title = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['other_title'];
                        $fair_content->reserve_status = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['reserve_status'];
                        $fair_content->reserve_count = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['reserve_count'];
                        $fair_content->reserve_unit = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['reserve_unit'];
                        $fair_content->price_status = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['price_status'];
                        $fair_content->price = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['price'];
                        $fair_content->price_per_person = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['price_per_person'];
                        $fair_content->required_time = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['required_time'];
                        $fair_content->title = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['title'];
                        $fair_content->content_detail = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['content_detail'];
                        $fair_content->event_kbn1 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['event_kbn1'];
                        $fair_content->event_kbn2 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['event_kbn2'];
                        $fair_content->image_id = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['image_id'];
                        $fair_content->start_hour1 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_hour1'];
                        $fair_content->start_minute1 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_minute1'];
                        $fair_content->end_hour1 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_hour1'];
                        $fair_content->end_minute1 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_minute1'];
                        $fair_content->start_hour2 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_hour2'];
                        $fair_content->start_minute2 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_minute2'];
                        $fair_content->end_hour2 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_hour2'];
                        $fair_content->end_minute2 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_minute2'];
                        $fair_content->start_hour3 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_hour3'];
                        $fair_content->start_minute3 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_minute3'];
                        $fair_content->end_hour3 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_hour3'];
                        $fair_content->end_minute3 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_minute3'];
                        $fair_content->start_hour4 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_hour4'];
                        $fair_content->start_minute4 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_minute4'];
                        $fair_content->end_hour4 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_hour4'];
                        $fair_content->end_minute4 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_minute4'];
                        $fair_content->start_hour5 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_hour5'];
                        $fair_content->start_minute5 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['start_minute5'];
                        $fair_content->end_hour5 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_hour5'];
                        $fair_content->end_minute5 = $params[self::FAIR_SITE_NAME[$i]]['fair_content'][$j]['end_minute5'];

                        //オブジェクトがNULLであればそのサイトのフェア内容は存在しないということ
                        if (!is_null($fair_content)) {
                            $fair_content->save();
                        }
                    }
                //フェア内容を削除する場合
                } elseif ($params[self::FAIR_FLG_NAME[$i]] == self::DELETED) {
                    $fair_content = FairContent::find($params['id'])->where('site_type', $params['fair_content'][$i]['site_type'])->get();
                    for ($i = 0; $i < count($fair_content); $i++) {
                        $fair_content[$i]->delete();
                    }
                }
            }
            
            //$fair->save();
            
            //fair_zexyの更新
            if ($params['zexy_flg'] == self::NO_REGISTRATION) {
            //新規登録の場合はモデルオブジェクトを取得する
            } elseif ($params['zexy_flg'] == self::NEW_REGISTER) {
                $fair_zexy = new FairZexy;
                //$fair_zexy->created_at = $params['member_id'];
                //$fair_zexy->updated_at = $params['member_id'];
            //更新の場合
            } else {
                $fair_zexy = FairZexy::where('fair_id', $params['fair_zexy']['fair_id'])->first();
            }
            if ($params['zexy_flg'] == self::NEW_REGISTER || $params['zexy_flg'] == self::UPDATE_RECORD_REGISTERED) {
                //$fair_zexy->fair_id = $params['fair_zexy']['fair_id'];
                $fair_zexy->fair_id = $fair['id'];
                $fair_zexy->master_id = $params['fair_zexy']['master_id'];
                $fair_zexy->fair_type = $params['fair_zexy']['fair_type'];
                $fair_zexy->realtime_reserve_flg = $params['fair_zexy']['realtime_reserve_flg'];
                $fair_zexy->required_time = $params['fair_zexy']['required_time'];
                $fair_zexy->short_title = $params['fair_zexy']['short_title'];
                $fair_zexy->description = $params['fair_zexy']['description'];
                $fair_zexy->﻿multi_part_flg = $params['fair_zexy']['multipart_flg'];
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
                $fair_zexy->part1 = $params['fair_zexy']['part1'];
                $fair_zexy->start_hour1 = $params['fair_zexy']['start_hour1'];
                $fair_zexy->start_minute1 = $params['fair_zexy']['start_minute1'];
                $fair_zexy->end_hour1 = $params['fair_zexy']['end_hour1'];
                $fair_zexy->end_minute1 = $params['fair_zexy']['end_minute1'];
                $fair_zexy->start_hour2 = $params['fair_zexy']['start_hour2'];
                $fair_zexy->start_minute2 = $params['fair_zexy']['start_minute2'];
                $fair_zexy->end_hour2 = $params['fair_zexy']['end_hour2'];
                $fair_zexy->end_minute2 = $params['fair_zexy']['end_minute2'];
                $fair_zexy->start_hour3 = $params['fair_zexy']['start_hour3'];
                $fair_zexy->start_minute3 = $params['fair_zexy']['start_minute3'];
                $fair_zexy->end_hour3 = $params['fair_zexy']['end_hour3'];
                $fair_zexy->end_minute3 = $params['fair_zexy']['end_minute3'];
                $fair_zexy->start_hour4 = $params['fair_zexy']['start_hour4'];
                $fair_zexy->start_minute4 = $params['fair_zexy']['start_minute4'];
                $fair_zexy->end_hour4 = $params['fair_zexy']['end_hour4'];
                $fair_zexy->end_minute4 = $params['fair_zexy']['end_minute4'];
                $fair_zexy->start_hour5 = $params['fair_zexy']['start_hour5'];
                $fair_zexy->start_minute5 = $params['fair_zexy']['start_minute5'];
                $fair_zexy->end_hour5 = $params['fair_zexy']['end_hour5'];
                $fair_zexy->end_minute5 = $params['fair_zexy']['end_minute5'];
                $fair_zexy->reflect_status = $params['fair_zexy']['reflect_status'];

                $fair_zexy->save();
            } elseif ($params['zexy_flg'] == self::DELETED) {
                $fair_zexy->delete();
            }

            //fair_weddingparkの更新
            if ($params['weddingpark_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['weddingpark_flg'] == self::NEW_REGISTER) {
                $fair_weddingpark = new FairWeddingPark;
                //$fair_weddingpark->create_user = $params['member_id'];
                //$fair_weddingpark->update_user = $params['member_id'];
            } else {
                $fair_weddingpark = FairWeddingPark::find($params['fair_id']);
            }

            if ($params['weddingpark_flg'] == self::NEW_REGISTER || $params['weddingpark_flg'] == self::UPDATE_RECORD_REGISTERED) {
                //$fair_weddingpark->fair_id = $params['fair_weddingpark']['fair_id'];
                $fair_weddingpark->fair_id = $fair['id'];
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
                //$fair_weddingpark->create_user = $params['fair_weddingpark'][''];
                //$fair_weddingpark->update_user = $params['fair_weddingpark'][''];

                $fair_weddingpark->save();
            } elseif ($params['weddingpark_flg'] == self::DELETED) {
                $fair_weddingpark->delete();
            }

            ////fair_mynaviの更新
            if ($params['mynavi_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['mynavi_flg'] == self::NEW_REGISTER) {
                $fair_mynavi = new FairMynavi;
                //$fair_mynavi->create_user = $params['member_id'];
                //$fair_mynavi->update_user = $params['member_id'];
            } else {
                $fair_mynavi = FairMynavi::find($params['fair_id']);
            }

            if ($params['mynavi_flg'] == self::NEW_REGISTER || $params['mynavi_flg'] == self::UPDATE_RECORD_REGISTERED) {
                //$fair_mynavi->id = $params['fair_mynavi']['id'];
                //$fair_mynavi->fair_id = $params['fair_mynavi']['fair_id'];
                $fair_mynavi->fair_id = $fair['id'];
                $fair_mynavi->master_id = $params['fair_mynavi']['master_id'];
                $fair_mynavi->description = $params['fair_mynavi']['description'];
                $fair_mynavi->reserve_way = $params['fair_mynavi']['reserve_way'];
                $fair_mynavi->﻿multi_part_check	= $params['fair_mynavi']['multi_part_check'];
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
                $fair_mynavi->start_hour1 = $params['fair_mynavi']['start_hour1'];
                $fair_mynavi->start_minute1 = $params['fair_mynavi']['start_minute1'];
                $fair_mynavi->end_hour1 = $params['fair_mynavi']['end_hour1'];
                $fair_mynavi->end_minute1 = $params['fair_mynavi']['end_minute1'];
                $fair_mynavi->start_hour2 = $params['fair_mynavi']['start_hour2'];
                $fair_mynavi->start_minute2 = $params['fair_mynavi']['start_minute2'];
                $fair_mynavi->end_hour2 = $params['fair_mynavi']['end_hour2'];
                $fair_mynavi->end_minute2 = $params['fair_mynavi']['end_minute2'];
                $fair_mynavi->start_hour3 = $params['fair_mynavi']['start_hour3'];
                $fair_mynavi->start_minute3 = $params['fair_mynavi']['start_minute3'];
                $fair_mynavi->end_hour3 = $params['fair_mynavi']['end_hour3'];
                $fair_mynavi->end_minute3 = $params['fair_mynavi']['end_minute3'];
                $fair_mynavi->start_hour4 = $params['fair_mynavi']['start_hour4'];
                $fair_mynavi->start_minute4 = $params['fair_mynavi']['start_minute4'];
                $fair_mynavi->end_hour4 = $params['fair_mynavi']['end_hour4'];
                $fair_mynavi->end_minute4 = $params['fair_mynavi']['end_minute4'];
                $fair_mynavi->start_hour5 = $params['fair_mynavi']['start_hour5'];
                $fair_mynavi->start_minute5 = $params['fair_mynavi']['start_minute5'];
                $fair_mynavi->end_hour5 = $params['fair_mynavi']['end_hour5'];
                $fair_mynavi->end_minute5 = $params['fair_mynavi']['end_minute5'];
                $fair_mynavi->reflect_status = $params['fair_mynavi']['reflect_status'];
                //$fair_mynavi->create_user = $params['fair_mynavi']['id'];
                //$fair_mynavi->update_user = $params['fair_mynavi']['id'];

                $fair_mynavi->save();
            } elseif ($params['mynavi_flg'] == self::DELETED) {
                $fair_mynavi->delete();
            }

            //fair_gurunaviの更新
            if ($params['gurunavi_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['gurunavi_flg'] == self::NEW_REGISTER) {
                $fair_gurunavi = new FairGurunavi;
                //$fair_gurunavi->create_user = $params['member_id'];
                //$fair_gurunavi->update_user = $params['member_id'];
            } else {
                $fair_gurunavi = FairGurunavi::find($params['fair_id']);
            }

            if ($params['gurunavi_flg'] == self::NEW_REGISTER || $params['gurunavi_flg'] == self::UPDATE_RECORD_REGISTERED) {
                //$fair_gurunavi->id = $params['fair_gurunavi']['id'];
                //$fair_gurunavi->fair_id = $params['fair_gurunavi']['fair_id'];
                $fair_gurunavi->fair_id = $fair['id'];
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
                $fair_gurunavi->counsel_type = $params['fair_gurunavi']['counsel_type'];
                $fair_gurunavi->reserve_button_flg = $params['fair_gurunavi']['reserve_button_flg'];
                //$fair_gurunavi->reflect_status = $params['fair_gurunavi']['reflect_status'];
                //$fair_gurunavi->deleted_at = $params['fair_gurunavi']['deleted_at'];
                //$fair_gurunavi->create_user = $params['fair_gurunavi']['create_user'];
                //$fair_gurunavi->update_user = $params['fair_gurunavi']['update_user'];

                $fair_gurunavi->save();
            } elseif ($params['gurunavi_flg'] == self::DELETED) {
                $fair_gurunavi->delete();
            }

            //fair_rakutenの更新
            if ($params['rakuten_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['rakuten_flg'] == self::NEW_REGISTER) {
                $fair_rakuten = new FairRakuten;
                //$fair_rakuten->create_user = $params['member_id'];
                //$fair_rakuten->update_user = $params['member_id'];
            } else {
                $fair_rakuten = FairMynavi::find($params['fair_id']);
            }

            if ($params['rakuten_flg'] == self::NEW_REGISTER || $params['rakuten_flg'] == self::UPDATE_RECORD_REGISTERED) {
                //$fair_rakuten->id = $params['fair_rakuten']['id'];
                //$fair_rakuten->fair_id = $params['fair_rakuten']['fair_id'];
                $fair_rakuten->fair_id = $fair['id'];
                $fair_rakuten->description = $params['fair_rakuten']['description'];
                $fair_rakuten->net_reserve_period_day = $params['fair_rakuten']['net_reserve_period_day'];
                $fair_rakuten->net_reserve_period_time = $params['fair_rakuten']['net_reserve_period_time'];
                $fair_rakuten->phone_reserve_flg = $params['fair_rakuten']['phone_reserve_flg'];
                $fair_rakuten->part_count = $params['fair_rakuten']['part_count'];
                $fair_rakuten->start_hour1 = $params['fair_rakuten']['start_hour1'];
                $fair_rakuten->start_minute1 = $params['fair_rakuten']['start_minute1'];
                $fair_rakuten->end_hour1 = $params['fair_rakuten']['end_hour1'];
                $fair_rakuten->end_minute1 = $params['fair_rakuten']['end_minute1'];
                $fair_rakuten->start_hour2 = $params['fair_rakuten']['start_hour2'];
                $fair_rakuten->start_minute2 = $params['fair_rakuten']['start_minute2'];
                $fair_rakuten->end_hour2 = $params['fair_rakuten']['end_hour2'];
                $fair_rakuten->end_minute2 = $params['fair_rakuten']['end_minute2'];
                $fair_rakuten->start_hour3 = $params['fair_rakuten']['start_hour3'];
                $fair_rakuten->start_minute3 = $params['fair_rakuten']['start_minute3'];
                $fair_rakuten->end_hour3 = $params['fair_rakuten']['end_hour3'];
                $fair_rakuten->end_minute3 = $params['fair_rakuten']['end_minute3'];
                $fair_rakuten->start_hour4 = $params['fair_rakuten']['start_hour4'];
                $fair_rakuten->start_minute4 = $params['fair_rakuten']['start_minute4'];
                $fair_rakuten->end_hour4 = $params['fair_rakuten']['end_hour4'];
                $fair_rakuten->end_minute4 = $params['fair_rakuten']['end_minute4'];
                $fair_rakuten->start_hour5 = $params['fair_rakuten']['start_hour5'];
                $fair_rakuten->start_minute5 = $params['fair_rakuten']['start_minute5'];
                $fair_rakuten->end_hour5 = $params['fair_rakuten']['end_hour5'];
                $fair_rakuten->end_minute5 = $params['fair_rakuten']['end_minute5'];
                //$fair_rakuten->reflect_status = $params['fair_rakuten']['reflect_status'];
                //$fair_rakuten->create_user = $params['fair_rakuten']['id'];
                //$fair_rakuten->update_user = $params['fair_rakuten']['id'];

                $fair_rakuten->save();
            } elseif ($params['rakuten_flg'] == self::DELETED) {
                $fair_rakuten->delete();
            }

            //fair_minnaの更新
            if ($params['minna_flg'] == self::NO_REGISTRATION) {
            } elseif ($params['minna_flg'] == self::NEW_REGISTER) {
                $fair_minna = new FairMinna;
                //$fair_minna->create_user = $params['member_id'];
                //$fair_minna->update_user = $params['member_id'];
            } else {
                $fair_minna = FairMinna::find($params['fair_id']);
            }

            if ($params['minna_flg'] == self::NEW_REGISTER || $params['minna_flg'] == self::UPDATE_RECORD_REGISTERED) {
                //$fair_minna->id = $params['fair_minna']['id'];
                //$fair_minna->fair_id = $params['fair_minna']['fair_id'];
                $fair_minna->fair_id = $fair['id'];
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
                //$fair_minna->create_user = $params['fair_minna']['id'];
                //$fair_minna->update_user = $params['fair_minna']['id'];

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
