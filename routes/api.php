<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('tryLogin', 'LoginController@login');

Route::get('/company', 'CompanyController@index');

Route::get('/company/create', 'CompanyController@create');

Route::post('/company', 'CompanyController@store');

Route::get('company/{id}', 'CompanyController@deleteCompany');

Route::get('/member', 'MemberController@index');

Route::get('/fair', 'FairController@index');
//フェア一覧取得
Route::get('list/{id}', 'FairController@getFairList');
//一件のフェア情報取得
Route::get('fair/{id}', 'FairController@getFair');
//フェア登録
Route::post('/fair', 'FairController@store');
//フェア更新
Route::put('fair/{id}', 'FairController@updateFairInfo');
//フェア削除
Route::delete('fair/{id}', 'FairController@deleteFairInfo');
//開催日変更
Route::put('eventdate/{id}', 'FairController@updateCalendar');
//アカウント登録
Route::get('/account/{memberId}', 'AccountController@getAccount');
//アカウント登録
Route::post('/account/{memberId}', 'AccountController@updateAccount');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
