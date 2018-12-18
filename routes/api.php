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

Route::post('/login', "LoginController@login");

Route::get('/company', 'CompanyController@index');

Route::get('/company/create', 'CompanyController@create');

Route::post('/company', 'CompanyController@store');

Route::get('company/{id}', 'CompanyController@deleteCompany');

Route::get('/member', 'MemberController@index');

Route::get('/fair', 'FairController@index');
//フェア一覧取得
Route::get('fairs/{memberId}', 'FairController@getFairList');
//一件のフェア情報取得
Route::get('fairs/{memberId}/{fairId}', 'FairController@getFair');
//フェア登録
Route::post('fairs/{memberId}/register', 'FairController@store');
//一件のフェア情報取得
Route::post('fairs/{memberId}/{fairId}', 'FairController@update');
//フェア削除
Route::delete('fair/{id}', 'FairController@deleteFairInfo');
//一件のフェア情報取得
Route::post('fairs/{memberId}/{fairId}/reflect', 'FairController@reflectFairInfo');
//開催日変更
Route::put('eventdate/{id}', 'FairController@updateCalendar');
//アカウント登録
Route::get('/account/{memberId}', 'AccountController@getAccount');
//アカウント登録
Route::post('/account/{memberId}', 'AccountController@updateAccount');
//プラン登録
Route::post('/plan/{memberId}/register', 'PlanController@store');
//プラン一覧取得
Route::get('/plan/{memberId}/list', 'PlanController@getPlanList');
//プラン詳細取得
Route::get('/plan/{memberId}/{planId}/detail', 'PlanController@getPlan');
//プラン更新取得
Route::post('/plan/{memberId}/{planId}/update', 'PlanController@update');

Route::post('/company/info', 'AccountController@getAccountInfo');

Route::post('/test', 'FairController@test');

Route::post('/group/{memberId}', 'GroupController@updateGroup');

Route::get('/group/{memberId}', 'GroupController@getGroup');

Route::post('/images/upload/{memberId}', 'ImageController@upload');

Route::get('/images/{memberId}', 'ImageController@getAllImageWithCount');

Route::get('/images/{memberId}/{fileId}', 'ImageController@getImage');

Route::post('/images/{memberId}/{fileId}', 'ImageController@updateImage');

Route::delete('/images/{memberId}/{fileId}', 'ImageController@deleteImage');

Route::get('/event/group/{memberId}', 'EventDateController@getGroupEventDate');

Route::post('/event/group/{memberId}', 'EventDateController@updateGroupEventDate');

Route::get('/event/fair/{memberId}', 'EventDateController@getFairEventDate');

Route::post('/event/fair/{memberId}', 'EventDateController@updateFairEventDate');

Route::get('/holiday', 'EventDateController@getHoliday');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
