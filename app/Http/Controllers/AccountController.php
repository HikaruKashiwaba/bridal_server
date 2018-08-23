<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use DB;

class AccountController extends Controller
{
    const FAIR_SITE_TYPE = [
        'ZEXY' => 1,
        'WEDDINGPARK' => 2,
        'MYNAVI' => 3,
        'GURUNAVI' => 4,
        'RAKUTEN' =>5,
        'MINNA' => 6
    ];

    const SITE_TYPE_ARRAY = ['zexy', 'weddingpark', 'mynavi', 'gurunavi', 'rakuten', 'minna'];


    /*  登録ID,PW更新処理  */
    public function updateAccount(Request $request) {

        $params = json_decode(file_get_contents('php://input'), true);

        try {
            DB::beginTransaction();

            //zexyのID,PW管理
            if (array_key_exists('zexy', $params)) {
                $account_zexy = Account::where('member_id', $params['zexy']['member_id'])->where('site_type', self::FAIR_SITE_TYPE['ZEXY'])->first();
                //新規での登録がどうかを判断する
                if (is_null($account_zexy)) {
                    $account_zexy = new Account;
                }
                $account_zexy->member_id = $params['zexy']['member_id'];
                $account_zexy->site_type = $params['zexy']['site_type'];
                $account_zexy->login_id = $params['zexy']['login_id'];
                $account_zexy->password = $params['zexy']['password'];
                $account_zexy->save();
            }

            //weddingparkのID,PW管理
            if (array_key_exists('weddingpark', $params)) {
                $account_weddingpark = Account::where('member_id', $params['weddingpark']['member_id'])->where('site_type', self::FAIR_SITE_TYPE["WEDDINGPARK"])->first();
                if (is_null($account_weddingpark)) {
                    $account_weddingpark = new Account;
                }
                $account_weddingpark->member_id = $params['weddingpark']['member_id'];
                $account_weddingpark->site_type = $params['weddingpark']['site_type'];
                $account_weddingpark->login_id = $params['weddingpark']['login_id'];
                $account_weddingpark->password = $params['weddingpark']['password'];
                $account_weddingpark->save();
            }

            //mynaviのID,PW管理
            if (array_key_exists('mynavi', $params)) {
                $account_mynavi = Account::where('member_id', $params['mynavi']['member_id'])->where('site_type', self::FAIR_SITE_TYPE["MYNAVI"])->first();
                if (is_null($account_mynavi)) {
                    $account_mynavi = new Account;
                }
                $account_mynavi->member_id = $params['mynavi']['member_id'];
                $account_mynavi->site_type = $params['mynavi']['site_type'];
                $account_mynavi->login_id = $params['mynavi']['login_id'];
                $account_mynavi->password = $params['mynavi']['password'];
                $account_mynavi->save();
            }

            //gurunaviのID,PW管理
            if (array_key_exists('gururanvi', $params)) {
                $account_gurunavi = Account::where('member_id', $params['gurunavi']['member_id'])->where('site_type', self::FAIR_SITE_TYPE["GURUNAVI"])->first();
                if (is_null($account_gurunavi)) {
                    $account_gurunavi = new Account;
                }
                $account_gurunavi->member_id = $params['gurunavi']['member_id'];
                $account_gurunavi->site_type = $params['gurunavi']['site_type'];
                $account_gurunavi->login_id = $params['gurunavi']['login_id'];
                $account_gurunavi->password = $params['gurunavi']['password'];
                $account_gurunavi->merchant_id = $params['gurunavi']['merchant_id'];
                $account_gurunavi->save();
            }

            //rakutenのID,PW管理
            if (array_key_exists('rakuten', $params)) {
                $account_rakuten = Account::where('member_id', $params['rakuten']['member_id'])->where('site_type', self::FAIR_SITE_TYPE["RAKUTEN"])->first();
                if (is_null($account_rakuten)) {
                    $account_rakuten = new Account;
                }
                $account_rakuten->member_id = $params['rakuten']['member_id'];
                $account_rakuten->site_type = $params['rakuten']['site_type'];
                $account_rakuten->login_id = $params['rakuten']['login_id'];
                $account_rakuten->password = $params['rakuten']['password'];
                $account_rakuten->save();
            }

            //minnaのID,PW管理
            if (array_key_exists('minna', $params)) {
                $account_minna = Account::where('member_id', $params['minna']['member_id'])->where('site_type', self::FAIR_SITE_TYPE["MINNA"])->first();
                if (is_null($account_minna)) {
                    $account_minna = new Account;
                }
                $account_minna->member_id = $params['minna']['member_id'];
                $account_minna->site_type = $params['minna']['site_type'];
                $account_minna->login_id = $params['minna']['login_id'];
                $account_minna->password = $params['minna']['password'];
                $account_minna->save();
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
            $account = new Account;
            DB::beginTransaction();
            /*---------------------------
             * member_idの取得方法確認訂正
             *---------------------------*/
            $account->zexy = Account::where('member_id', $params['member_id'])->where('site_type', self::FAIR_SITE_TYPE['ZEXY'])->first(['login_id', 'password']);
            $account->weddingpark = Account::where('member_id', $params['member_id'])->where('site_type', self::FAIR_SITE_TYPE['WEDDINGPARK'])->first(['login_id', 'password']);
            $account->mynavi = Account::where('member_id', $params['member_id'])->where('site_type', self::FAIR_SITE_TYPE['MYNAVI'])->first(['login_id', 'password']);
            $account->gurunavi = Account::where('member_id', $params['member_id'])->where('site_type', self::FAIR_SITE_TYPE['GURUNAVI'])->first(['login_id', 'password']);
            $account->rakuten = Account::where('member_id', $params['member_id'])->where('site_type', self::FAIR_SITE_TYPE['RAKUTEN'])->first(['login_id', 'password']);
            $account->minna = Account::where('member_id', $params['member_id'])->where('site_type', self::FAIR_SITE_TYPE['MINNA'])->first(['login_id', 'password']);
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
