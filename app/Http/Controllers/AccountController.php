<?php

namespace App\Http\Controllers;

use DB;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{

    public function updateAccount(string $memberId, Request $request) {
        DB::beginTransaction();

        try {
            $params = $request->input('account');
            $json_count = count($params);
            $account = null;

            //アカウント情報の更新
            for($i = 0; $i < $json_count; $i++){
                $account = Account::firstOrNew(
                  ['member_id' => $memberId, 'site_type' => $params[$i]['site_type']]
                );

                $account['login_id'] = $params[$i]['login_id'];
                $account['merchant_id'] = $params[$i]['merchant_id'];
                $account['password'] = $params[$i]['password'];
                $account->save();
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

    public function getAccount(string $memberId) {
        $items = Account::where('member_id', $memberId)->get();
        return $items;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
    }
}
