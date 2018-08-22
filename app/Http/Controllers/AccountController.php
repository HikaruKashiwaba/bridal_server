<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    const SITE_TYPE_ARRAY = new array('Zexy', 'WeddingPark', 'Mynavi', 'Gurunavi', 'rakuten', 'Minna');


    /*  登録ID,PW更新処理  */
    public function updateAccount(Request $request) {

        $params = json_decode(file_get_contents('php://input'), true);

        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($params['account']); $i++) {

                $account = Account::where('member_id', $params['memberId'])->where('site_type', $i)->get();
                if (is_null($account)) {
                    $account = new Account;
                }
                $account->id = $params[$i]['id'];
                $account->member_id = $params[$i]['memberId'];
                $account->site_type = $params[$i]['siteType'];
                $account->login_id = $params[$i]['loginId'];
                $account->marchant_id = $params[$i]['merchantId'];
                $account->password = $params[$i]['password'];

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


    /*  登録されたID,PWを画面に送信する　*/
    public function getAccountInfo (Request $request) {
        try {
            DB::beginTransaction();

            for ($i = 0; $i < count(SITE_TYPE_ARRAY); $i++) {
                $account = new Account;
                $account->siteType = Account::where('member_id', $params['memberId'])->where('site_type', $i);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $account = [
                'result' => 'NG'
            ];
        }
        return response()->json($account, 200);
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
