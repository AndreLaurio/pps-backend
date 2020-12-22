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
Route::post('/changephoto/{user_id}', 'AccountController@changePhoto');

Route::get('/exams', 'ExamController@getAll');
Route::get('/exam/{exam_id}', 'ExamController@get');
Route::get('/exam/desc/{exam_id}', 'ExamController@getDesc');
Route::get('/exam/items/{exam_id}', 'ExamController@getItems');

Route::post('/exam/take/intro', 'TakeExamController@saveIntroSession');
Route::post('/exam/take/session/get', 'TakeExamController@getSession');
Route::post('/exam/take/session/set', 'TakeExamController@setSession');
Route::post('/exam/check', 'TakeExamController@checkAnswer');

Route::post('/examinee/exams', 'ExamineeController@getExams');
Route::post('/examinee/exam/result', 'ExamExamineeController@getResult');
Route::get('/exam/examinees/{exam_id}', 'ExamExamineeController@get');
Route::get('/exam/examinees/not/{exam_id}', 'ExamExamineeController@getExamineesNotInExam');
Route::post('/exam/examinee/delete', 'ExamExamineeController@delete');
Route::post('/exam/examinee/add', 'ExamExamineeController@add');

Route::post('/exam/results', 'ExamExamineeController@getResults');

Route::post('/exam/examinees/count', 'ExamExamineeController@getExamineesCount');
Route::post('/exam/delete', 'ExamController@delete');

Route::post('/exam/edit/get', 'ExamController@getForEdit');
Route::post('/exam/edit', 'ExamController@edit');
Route::post('/exam/examinee/answer/view', 'ExamExamineeController@getExamineeAnswer');

Route::post('/exam/take/save_point', 'TakeExamController@examSavePoint');

// Change Tab
Route::post('/exam/take/change-tab', 'TakeExamController@changeTab');
Route::post('/exam/examinee/change-tab-history/get', 'ExamExamineeController@getChangeTabHistory');