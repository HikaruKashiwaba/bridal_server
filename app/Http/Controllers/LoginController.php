<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $header = $request->header('X-Api-Authorization');

        $str =  base64_decode($header);

        $params = explode(":", $str);

        $member = Member::where('login_id', $params[0])->where('password', $params[1])->first();

        if (is_null($member)) {
            return ['errors' => array(array('code' => 'NG', 'message' => 'notFound'))];
        } else {
            return response()->json(['memberId' => $member->id], 200);
        }

        /*
        $member = Member::find(1);

        if (is_null($member)) {
            return response()->json(['code' => 'NG', 'message' => 'notFound'], 200);
        } else {
            return response()->json(['memberId' => '1'], 200);
        }
        */


        //$header = $request->header('X-Api-Authorization');

        //$str =  base64_decode($header);

        /*
        if (Auth::attempt(['login_id' => $id, 'password' => $pass])) {
            //成功

        } else {
            //失敗
        }
        */
        //$user = Auth::user();
        //$sort = $request->sort;
        //$items =
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
