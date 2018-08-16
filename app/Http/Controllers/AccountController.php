<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function updateAccount() {
        DB::beginTransaction();
        try {

            $json_count = count($params['account']);
            $account = null;

            //アカウント情報の更新
            for($i = 0; $i < $json_count; $i++){
                $account = Account::find($params['account'][$i]['id']) ?? new Account;

                $account->member_id = $params['account'][$i]['memberId'];
                $account->site_type = $params['account'][$i]['siteType'];
                $account->login_id = $params['account'][$i]['loginId'];
                $account->merchant_id = $params['account'][$i]['merchantId'];
                $account->password = $params['account'][$i]['password'];
                $fair_zexy->reflect_status = $params['fairZexy']['reflectStatus'];
                $fair_zexy->create_user = $params['account'][$i]['memberId'];
                $fair_zexy->update_user = $params['account'][$i]['memberId'];

                $account->save();
            }


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
        return response()->json($result, 200);
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
