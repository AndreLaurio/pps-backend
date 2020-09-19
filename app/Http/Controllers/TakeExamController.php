<?php

namespace App\Http\Controllers;

use App\ExamChoice;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ExamController;
use DateTimeZone;

class TakeExamController extends Controller
{
    public function saveIntroSession(Request $request) {

        $user_id = $request->input('user_id');
        $exam_id = $request->input('exam_id');
        
        // Check if the exam is already taken
        $exam_takes = DB::table('examinee_exams')
                        ->where([
                            ['user_id', '=', $user_id],
                            ['exam_id', '=', $exam_id],
                            ['exam_status_code', '=', 'F']
                        ])
                        ->select(DB::raw('COUNT(*) as num'))
                        ->first();

        // Check if there's another exam going on
        $session = DB::table('users')
                        ->where('user_id', $user_id)
                        ->select(   'session_exam_id',
                                    'session_no_takes',
                                    'session_taken_on')
                        ->first();
        
        if ($session->session_no_takes > 0) {

            if ($session->session_exam_id == $exam_id) {

                $response = [
                    'status' => 'success',
                    'message' => 'You\'re currently taking the examination.'
                ];
            }
            else {

                $exam = DB::table('exams')
                            ->where('exam_id', $session->session_exam_id)
                            ->select('exam_title')
                            ->first();

                $response = [
                    'status' => 'error',
                    'message' => "You're currently taking '" . $exam->exam_title . "' examination."
                ];
            }
        }
        else if (($session->session_exam_id == 0 || $session->session_exam_id != $exam_id) && $exam_takes->num == 1) {

            $response = [
                'status' => 'error',
                'message' => 'You already taken this examination.'
            ];
        }
        else {

            DB::table('users')
                ->where('user_id', $user_id)
                ->update([
                    'session_exam_id' => $exam_id
                ]);
            
            $response = [
                'status' => 'success',
                'message' => 'Your exam session is successfully saved else.'
            ];
        }
        return $response;
    }

    public function getSession(Request $request) {

        $session = DB::table('users')
                        ->where('user_id', $request->input('user_id'))
                        ->select(   'session_exam_id',
                                    'session_taken_on',
                                    'session_no_takes')
                        ->first();
        
        $session = (array) $session;
        
        return $session;                               
    }

    public function setSession(Request $request) {

        $user_id = $request->input('user_id');
        $exam = (object) $request->input('exam');
        $now = now(new DateTimeZone('Asia/Manila'));

        $session = DB::table('users')
                        ->where('user_id', $user_id)
                        ->select('session_no_takes')
                        ->first();
        
        if ($session->session_no_takes > 0) {

            DB::table('users')
                ->where('user_id', $user_id)
                ->update([
                    'session_no_takes' => $session->session_no_takes + 1
                ]);
        }
        else {
            
            DB::table('examinee_exams')
                ->where([
                    ['user_id', '=', $user_id],
                    ['exam_id', '=', $exam->exam_id],
                ])
                ->update([
                    'exam_status_code' => 'O',
                    'time_start' => $now,
                    'passing_score' => $exam->passing_score,
                    'total_score' => $exam->total_score,
                    'exam_string' => json_encode($exam)
                ]);
            

            DB::table('users')
                ->where('user_id', $request->input('user_id'))
                ->update([
                    'session_no_takes' => $session->session_no_takes + 1,
                    'session_taken_on' => $now,
                ]);
        }

        DB::table('examinee_exam_logs')
            ->insert([
                'user_id' => $user_id,
                'exam_id' => $exam->exam_id,
                'take_no' => $session->session_no_takes + 1,
                'taken_on' => $now
            ]);
    }

    public function checkAnswer(Request $request) {

        $exam_answer = (object) $request->input('exam');
        $user_id = $request->input('user_id');
        $now = now(new DateTimeZone('Asia/Manila'));

        $exam = DB::table('examinee_exams')
                    ->where([
                        ['user_id', '=', $user_id],
                        ['exam_id', '=', $exam_answer->exam_id],
                    ])
                    ->select('exam_string', 'examinee_no')
                    ->first();

        $examinee_no = $exam->examinee_no;
        $exam = json_decode($exam->exam_string);

        $score = 0;

        // Checking answer
        foreach($exam->exam_items as $i => $item) {
            
            $ai = (object) $exam_answer->exam_items[$i];

            // SCQ
            if ($item->question_type_code == 'SCQ') {

                if (property_exists($ai, 'answer')) {

                    $choice = (object) $item->choices[$ai->answer];

                    if ($choice->is_correct == true) {
                        $score++;
                    }
                    $exam->exam_items[$i]->answer = $ai->answer;
                }
            }

            // MCQ
            if ($item->question_type_code == 'MCQ') {

                $ac = $ai->choices;
                $flag = 0;
                $count_selected = 0;

                foreach($item->choices as $c => $choice) {

                    $achoice = (object) $ac[$c];

                    if (!property_exists($achoice, 'is_selected')) {
                        $achoice->is_selected = false;
                    }

                    if ($achoice->is_selected == true && $choice->is_correct == true) {
                        $flag++;
                    }

                    if ($achoice->is_selected == true) {
                        $count_selected++;
                    }

                    $exam->exam_items[$i]->choices[$c]->is_selected = $achoice->is_selected;
                }

                if ($count_selected <= $item->mcq_max_selection && $flag == $item->mcq_max_selection) {
                    $score++;
                }
            }

            // FTQ
            if ($item->question_type_code == 'FTQ') {
                
                if (!property_exists($ai, 'answer')) {
                    $ai->answer = '';
                }

                if (trim($ai->answer) != "") {
                    $score++;
                }

                $exam->exam_items[$i]->answer = $ai->answer;
            }
        }

        $exam_remarks_code = ($score >= $exam->passing_score) ? 'P' : 'F';

        $session = DB::table('users')
                        ->where('user_id', $user_id)
                        ->select('session_no_takes')
                        ->first();

        DB::table('examinee_exams')
            ->where([
                ['user_id', '=', $user_id],
                ['exam_id', '=', $exam->exam_id],
            ])
            ->update([
                'exam_status_code' => 'F',
                'exam_remarks_code' => $exam_remarks_code,
                'overall_score' => $score,
                'time_end' => $now,
                'exam_string' => json_encode($exam),
                'session_no_takes' => $session->session_no_takes
            ]);

        DB::table('users')
            ->where('user_id', $user_id)
            ->update([
                'session_exam_id' => 0,
                'session_no_takes' => 0,
                'session_taken_on' => null
            ]);

        $response = [
            'overall_score' => $score,
            'total_score' => $exam->total_score,
            'examinee_no' => $examinee_no
        ];

        return $response;
    }
}
