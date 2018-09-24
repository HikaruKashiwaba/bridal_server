<?php

namespace App\Http\Controllers;

use DB;
use App\Account;
use App\Fair;
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
      $groups = Group::where('member_id',  $memberId)->get();
      $res = [];

      Log::debug($groups);

      for ($i = 0; $i < count($groups); $i++) {
          $event_date = GroupEventDate::where('group_id', $groups[$i]['id'])->orderBy('date')->get();
          Log::debug($event_date);
          $group_event_date = [];
          $group_event_date['group_id'] = $groups[$i]['id'];
          $group_event_date['group_name'] = $groups[$i]['group_name'];
          $group_event_date['group_event_date'] = $event_date->toArray();
          $res[$i] = $group_event_date;
      }
      Log::debug($res);
      Log::debug('EventDateController.getGroupEventDate-  END  ->');
      return response()->json($res, 200);
  }

    //フェア開催日を取得する
    public function getFairEventDate(string $memberId, Request $request) {
      Log::debug('EventDateController.getGroupEventDate- START ->');
        $groups = Group::where('member_id',  $memberId)->get();
        $res = [];
        Log::debug($groups);

        for ($i = 0; $i < count($groups); $i++) {
            $group = [];
            $group['group_id'] = $groups[$i]['id'];
            $group['group_name'] = $groups[$i]['group_name'];
            $group['fairs'] = [];

            $fairs = Fair::where('group_id', $groups[$i]['id'])->orderBy('id')->get();
            Log::debug($fairs);
            for ($j = 0; $j < count($fairs); $j++) {
                $event_date = FairEventDate::where('fair_id', $fairs[$j]['id'])->orderBy('date')->get();
                Log::debug($event_date);

                $fair = [];
                $fair['id'] = $fairs[$j]['id'];
                $fair['title'] = $fairs[$j]['title'];
                $fair['fair_event_date'] = $event_date->toArray();
                $group['fairs'][$j] = $fair;
            }
            $res[$i] = $group;
        }

        // グループに属さないフェアを抽出
        $group = [];
        $group['group_id'] = -1;
        $group['group_name'] = 'グループなし';
        $group['fairs'] = [];

        $fairs = Fair::whereNull('group_id')->orderBy('id')->get();
        Log::debug($fairs);
        for ($j = 0; $j < count($fairs); $j++) {
            $event_date = FairEventDate::where('fair_id', $fairs[$j]['id'])->orderBy('date')->get();
            Log::debug($event_date);

            $fair = [];
            $fair['id'] = $fairs[$j]['id'];
            $fair['title'] = $fairs[$j]['title'];
            $fair['fair_event_date'] = $event_date->toArray();
            $group['fairs'][$j] = $fair;
        }
        $res[count($groups)] = $group;

        Log::debug($res);
        Log::debug('EventDateController.getGroupEventDate-  END  ->');
        return response()->json($res, 200);
    }

    //フェア開催日を取得する
    public function updateGroupEventDate(string $memberId, Request $request) {
      Log::debug('EventDateController.updateGroupEventDate- START ->');
      DB::beginTransaction();

      try {
          $params = $request->input('groups');
          $json_count = count($params);
          $account = null;
          Log::debug($params);

          //アカウント情報の更新
          for($i = 0; $i < $json_count; $i++){
              $groupId = $params[$i]['group_id'];
              // パラメータからグループ配下の開催日の一覧を取得
              $eventDateList = $params[$i]['group_event_date'];

              // グループ配下のフェアを取得する
              $fairList = Fair::where('group_id', $groupId)->get();
              Log::debug($fairList);
              Log::debug($params);

              // 開催日の登録を行う
              for($j = 0; $j < count($eventDateList); $j++) {
                // 登録済みのレコードがあれば取得し、無ければ新規に生成する
                $eventDate = GroupEventDate::firstOrNew(
                  ['member_id' => $memberId, 'group_id' => $groupId, 'date' => $eventDateList[$j]['date']]
                );
                Log::debug($eventDate);
                Log::debug('ログ');

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
    public function updateFairEventDate(string $memberId, Request $request) {
      Log::debug('EventDateController.updateFairEventDate- START ->');
      DB::beginTransaction();

      try {
          $params = $request->input('groups');
          $json_count = count($params);
          $account = null;
          Log::debug($params);

          //アカウント情報の更新
          for($i = 0; $i < $json_count; $i++){
              $groupId = $params[$i]['group_id'];
              // パラメータからグループ配下の開催日の一覧を取得
              $fairList = $params[$i]['fairs'];
              // ロックされたフェアが存在する日付を保持するリスト
              $lockedFairEvendDate = [];
              // グループ配下のフェアを取得する
              Log::debug($fairList);

              // 開催日の登録を行う
              for($j = 0; $j < count($fairList); $j++) {
                  $fairId = $fairList[$j]['id'];
                  // パラメータからフェア配下の開催日の一覧を取得
                  $eventDateList = $fairList[$j]['fair_event_date'];

                  // 開催日の登録を行う
                  for($k = 0; $k < count($eventDateList); $k++) {
                      // 登録済みのレコードがあれば取得し、無ければ新規に生成する
                      $eventDate = FairEventDate::firstOrNew(
                          ['member_id' => $memberId, 'fair_id' => $fairId, 'date' => $eventDateList[$k]['date']]
                      );
                      Log::debug($eventDate);
                      Log::debug('ログ');

                      if ($eventDate['id'] != null && $eventDateList[$k]['del_flg'] == '1') {
                          // 未反映の場合のみ、del_flgが弥ので、その場合はレコードを削除
                          $eventDate->delete();
                      } else {
                          $eventDate['representative_flg'] = $eventDateList[$k]['representative_flg'];
                          $eventDate['reflect_flg'] = $eventDateList[$k]['reflect_flg'];
                          $eventDate['lock_flg'] = $eventDateList[$k]['lock_flg'];
                          $eventDate['stop_flg'] = $eventDateList[$k]['stop_flg'];
                          $eventDate->save();

                          // グループ設定と違う設定をしているフェアが存在する
                          if ($groupId != '-1' && eventDateList[$k]['lock_flg'] == '1') {
                              $lockedFairEvendDate[$eventDateList[$k]['date']] = '';
                          }
                      }
                  }
              }

              // 日付リストを取得
              $eventDates = array_keys($lockedFairEvendDate);
              for($j = 0; $j < count($eventDates); $j++) {
                  $eventDate = GroupEventDate::firstOrNew(
                      ['member_id' => $memberId, 'group_id' => $groupId, 'date' => $eventDates[$j]]
                  );
                  $eventDate['lock_flg'] = '1';
                  $eventDate->save();
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
