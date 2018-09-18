<?php

namespace App\Http\Controllers;

use DB;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    const DELETED = 1;

    //グループの追加登録・更新・削除を行う
    public function updateGroup(string $memberId, Request $request) {
        $params = $request->input('group');
        Log::debug($params);
        try {
            DB::beginTransaction();

            //グループの送信内容に新規登録がある場合
            if ($params['id'] != null) {
              if ($params['del_flg'] == '1') {
                //削除フラグが立っている場合
                $group->delete();
              } else {
                $group = Group::where('id', $params['id'])->firstOrFail();
                $group->member_id = $memberId;
                $group->group_name = $params['group_name'];
                $group->save();
              }
            } else {
                $group = new Group;
                $group->member_id = $memberId;
                $group->group_name = $params['group_name'];
                $group->save();
            }
            $result = ['result' => 'OK'];
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $result = ['result' => 'NG'];
        }
        return response()->json($result, 200);
    }

    //グループ名表示
    public function getGroup(string $memberId) {
        $items = Group::where('member_id', $memberId)->get();
        return $items;
    }
}
