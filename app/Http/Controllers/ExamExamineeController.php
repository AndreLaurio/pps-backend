<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exam;
use App\Examinee;

class ExamExamineeController extends Controller
{
    public function get($exam_id) {
        $examinees = DB::table('examinee_exams AS ee')
                        ->join('users AS u', 'ee.user_id', '=', 'u.user_id')
                        ->join('exam_status AS es', 'ee.exam_status_code', '=', 'es.exam_status_code')
                        ->where('ee.exam_id', $exam_id)
                        ->select(
                            'ee.user_id',
                            'u.last_name',
                            'u.first_name',
                            'u.email',
                            'ee.exam_status_code',
                            'es.exam_status'
                        )
                        ->orderByRaw('u.last_name ASC, u.first_name ASC')
                        ->get();
        
        return $examinees;
    }

    public function getExamineesNotInExam($exam_id) {
        $examinees = DB::table('users AS u')
                        ->where([
                            ['user_type_id', 2],
                            ['is_approved', 1]
                        ])
                        ->select(
                            'u.user_id',
                            'u.last_name',
                            'u.first_name',
                            'u.email'
                        )
                        ->groupByRaw('u.user_id')
                        ->orderByRaw('u.last_name ASC, u.first_name ASC')
                        ->get();
        
        return $examinees;
    }

    public function delete(Request $request) {

        DB::table('examinee_exams')
            ->where([
                ['user_id', '=', $request->input('user_id')],
                ['exam_id', '=', $request->input('exam_id')]
            ])
            ->delete();

        DB::table('examinee_exam_logs')
            ->where([
                ['user_id', '=', $request->input('user_id')],
                ['exam_id', '=', $request->input('exam_id')]
            ])
            ->delete();

        $session = DB::table('users')
                        ->where([
                            ['user_id', '=', $request->input('user_id')],
                            ['session_exam_id', '=', $request->input('exam_id')]
                        ])
                        ->select(
                            DB::raw('COUNT(*) as num')
                        )
                        ->first();

        if ($session->num > 0) {
            DB::table('users')
                ->where('user_id', $request->input('user_id'))
                ->update([
                    'session_exam_id' => 0,
                    'session_no_takes' => 0,
                    'session_taken_on' => null
                ]);
        }
    }

    public function addCode(Request $request){
        $user_id = $request->input('user_id');
        $exam_code = $request->input('exam_code');

        //find natin yung exam_id ng exam_code
        $exam_id = Exam::where('exam_code',$exam_code)->value('exam_id');

        //add the user to the table
        $addExaminee = new Examinee();
        $addExaminee->exam_id = $exam_id;
        $addExaminee->examinee_no = $user_id;
        $addExaminee->user_id = $user_id;
        $addExaminee->exam_string = '';
        $addExaminee->default_exam_string = '';
        $addExaminee->save();

    }
    public function add(Request $request) {

        $count_examinee = DB::table('examinee_exams')
                            ->where([
                                ['user_id', '=', $request->input('user_id')],
                                ['exam_id', '=', $request->input('exam_id')]
                            ])
                            ->select(DB::raw('COUNT(*) AS num'))
                            ->first();
        
        if ($count_examinee->num == 0) {

            DB::table('examinee_exams')
                ->insert([
                    'user_id' => $request->input('user_id'),
                    'exam_id' => $request->input('exam_id'),
                    'examinee_no' => $request->input('user_id'),
                    'exam_string' => '',
                    'default_exam_string' => ''
                ]);

            $response = [
                'status' => 'success',
                'message' => 'Examinee is successfully added.'
            ];
        }
        else {
            $response = [
                'status' => 'error',
                'message' => 'Examinee already had the exam.'
            ];
        }

        return $response;
    }

    public function getResult(Request $request) {
        
        $result = DB::table('examinee_exams')
                    ->where([
                        ['user_id', '=', $request->input('user_id')],
                        ['exam_id', '=', $request->input('exam_id')]
                    ])
                    ->select(
                        'examinee_no',
                        'overall_score',
                        'total_score'
                    )
                    ->first();
        
        $result->status = 'success';
        return response()->json($result);
    }

    public function getResults(Request $request) {
        DB::enableQueryLog();
        $result = DB::table('examinee_exams AS ee')
                    ->join('users AS u', 'ee.user_id', '=', 'u.user_id')
                    ->join('exam_remarks AS er', 'ee.exam_remarks_code', '=', 'er.exam_remarks_code')
                    ->leftJoin('examinee_change_tab_history AS ecth', [
                        ['ee.user_id', '=', 'ecth.user_id'],
                        ['ee.exam_id', '=', 'ecth.exam_id']
                    ])
                    ->where('ee.exam_id', $request->input('exam_id'))
                    ->select(
                        'u.user_id',
                        'u.last_name',
                        'u.first_name',
                        'u.middle_name',
                        'u.extension_name',
                        'u.email',
                        'ee.overall_score',
                        'ee.total_score',
                        'er.exam_remarks',
                        'ee.exam_status_code',
                        DB::raw('COUNT(ecth.change_tab_date) AS change_tab_count'),
                        'ee.exam_id'
                    )
                    ->groupByRaw('ecth.user_id')
                    ->orderByRaw('u.last_name ASC, u.first_name DESC')
                    ->get();
        
        Log::error(DB::getQueryLog());
        return $result;
    }

    public function getExamineesCount(Request $request) {

        $examinee_count = DB::table('examinee_exams')
                            ->where('exam_id', $request->input('exam_id'))
                            ->select(DB::raw('COUNT(*) AS num'))
                            ->first();
        
        if ($examinee_count->num == 0) {
            $message = "There is no examinee taking the exam.";
        }
        else if ($examinee_count->num == 1) {
            $message = "There is 1 examinee taking/taken the exam.";
        }
        else {
            $message = "There are " . $examinee_count->num . " taking/taken the exam.";
        } 

        $response = [
            'status' => 'success',
            'message' => $message
        ];

        return $response;
    }

    public function getExamineeAnswer(Request $request) {

        $data = DB::table('examinee_exams')
                    ->where([
                        ['user_id', '=', $request->input('user_id')],
                        ['exam_id', '=', $request->input('exam_id')]
                    ])
                    ->first();

        $result = [
            'examinee_no' => $data->examinee_no,
            'overall_score' => $data->overall_score,
            'total_score' => $data->total_score
        ];

        $exam = json_decode($data->exam_string);

        $response = [
            'result' => $result,
            'exam' => $exam
        ];

        return $response;
    }

    public function getChangeTabHistory(Request $request) {

        return DB::table('examinee_change_tab_history')
                ->where([
                    ['user_id', '=', $request->input('user_id')],
                    ['exam_id', '=', $request->input('exam_id')]
                ])
                ->get();
    }
    
}