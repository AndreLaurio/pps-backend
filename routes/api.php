<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/accounts', 'AccountApprovalController@index');
Route::put('/accounts/{user_id}', 'AccountApprovalController@update');

Route::get('/exam/fresh', 'ExamController@getFresh');
Route::post('/exam/create', 'ExamController@create');

Route::put('/changepw/{user_id}', 'AccountController@changePassword');
Route::put('/changedetails/{user_id}', 'AccountController@changeDetails');

Route::get('/exams', 'ExamController@getAll');
Route::get('/exam/{exam_id}', 'ExamController@get');
Route::get('/exam/desc/{exam_id}', 'ExamController@getDesc');
Route::get('/exam/items/{exam_id}', 'ExamController@getItems');

Route::post('/exam/take/intro', 'TakeExamController@saveIntroSession');
Route::post('/exam/take/session/get', 'TakeExamController@getSession');
Route::post('/exam/take/session/set', 'TakeExamController@setSession');
Route::post('/exam/check', 'TakeExamController@checkAnswer');