<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use DB;

class GroupController extends Controller
{
    const DELETED = 1;

    //グループの追加登録・更新・削除を行う
    public function updateGroup(string $memberId, Request $request) {
        $params = json_decode(file_get_contents('php://input'), true);
        try {
            DB::beginTransaction();
            $group_all = Group::where('member_id', $params['member_id'])->get();
            //グループの送信内容に新規登録がある場合
            if (count($group_all) < count($params['group'])) {
                for ($i = 1; $i <= count($params['group']); $i++) {
                    //削除フラグが立っていない
                    if ($params['group'][$i - 1]['delete_flg'] != self::DELETED) {
                        $group = Group::where('member_id', $params['member_id'])->where('id', $params['group'][$i - 1]['group_id'])->first();
                        //新規登録の場合
                        if (is_null($group)) {
                            $group = new Group;
                            $group->id = $i;
                        }
                        $group->member_id = $params['member_id'];
                        $group->group_name = $params['group'][$i - 1]['group_name'];
                        $group->save();
                    //削除フラグが立っている場合
                    } else {
                        $group->delete();
                    }
                }
            //グループの送信内容に新規登録がない
            } else {
                for ($i = 1; $i <= count($params['group']); $i++) {
                    $group = Group::where('member_id', $params['member_id'])->where('id', $params['group'][$i - 1]['group_id'])->first();
                    if ($params['group'][$i - 1]['delete_flg'] != self::DELETED) {
                        $group->member_id = $params['member_id'];
                        $group->group_name = $params['group'][$i - 1]['group_name'];
                        $group->save();
                    } else {
                        $group->delete();
                    }
                }
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
