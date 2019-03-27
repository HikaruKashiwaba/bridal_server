<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ErrorLog;
use DB;
use File;
use Illuminate\Support\Facades\Log;

class ErrorLogController extends Controller
{
    //エラーログの登録
    public function register(Request $request) {
        $params = $request->input('errorLog');
        Log::debug($params);

        DB::beginTransaction();
        try {
            $log = new ErrorLog;
            $log->member_id = $params['member_id'];
            $log->site_type = $params['site_type'];
            $log->function_name = $params['function_name'];
            $log->contents = $params['contents'];
            $log->save();

            DB::commit();
            $result = [
                'code' => 'OK',
                'message' => 'エラーログの登録が成功しました'
            ];
            return response()->json($result, 200);
        } catch(Exception $e) {
            DB::rollBack();
            $result = [
                'code' => 'NG',
                'message' => $e->getMessage()
            ];
            return response()->json($result, 500);
        }
    }
}
