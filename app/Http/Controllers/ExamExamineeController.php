<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                        ->where('user_type_id', 2)
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
                    'exam_string' => ''
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

        $result = DB::table('examinee_exams AS ee')
                    ->join('users AS u', 'ee.user_id', '=', 'u.user_id')
                    ->join('exam_remarks AS er', 'ee.exam_remarks_code', '=', 'er.exam_remarks_code')
                    ->where('ee.exam_id', $request->input('exam_id'))
                    ->select(
                        'u.user_id',
                        'u.last_name',
                        'u.first_name',
                        'u.email',
                        'ee.overall_score',
                        'ee.total_score',
                        'er.exam_remarks'
                    )
                    ->orderByRaw('u.last_name ASC, u.first_name DESC')
                    ->get();
        
        
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
}
