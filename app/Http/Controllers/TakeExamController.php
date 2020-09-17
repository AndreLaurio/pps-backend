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

        DB::table('users')
            ->where('user_id', $request->input('user_id'))
            ->update([
                'session_exam_id' => $request->input('exam_id')
            ]);
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

        $exam = (object) $request->input('exam');
        $now = now(new DateTimeZone('Asia/Manila'));

        DB::table('examinee_exams')
            ->insert([
                'user_id' => $request->input('user_id'),
                'examinee_no' => $request->input('user_id'),
                'exam_id' => $exam->exam_id,
                'time_start' => $now,
                'passing_score' => $exam->passing_score,
                'total_score' => $exam->total_score,
                'exam_string' => json_encode($exam)
            ]);

        DB::table('users')
            ->where('user_id', $request->input('user_id'))
            ->update([
                'session_no_takes' => DB::raw('session_no_takes+1'),
                'session_taken_on' => $now,
            ]);
    }

    public function checkAnswer(Request $request) {

        $exam_answer = (object) $request->input('exam');
        $user_id = $request->input('user_id');

        $exam = DB::table('examinee_exams')
                    ->where([
                        ['user_id', '=', $user_id],
                        ['exam_id', '=', $exam_answer->exam_id]
                    ])
                    ->select('exam_string')
                    ->first();

        $exam = json_decode($exam->exam_string);

        $score = 0;

        foreach($exam->exam_items as $i => $item) {
            
            $ai = (object) $exam_answer->exam_items[$i];

            if ($item->question_type_code == 'SCQ') {

                $choice = (object) $item->choices[$ai->answer];

                if ($choice->is_correct == true) {
                    $score++;
                }
                $exam->exam_items[$i]->answer = $ai->answer;
                Log::error('SCQ');
            }

            if ($item->question_type_code == 'MCQ') {

                $ac = $ai->choices;
                $flag = 0;

                foreach($item->choices as $c => $choice) {

                    $achoice = (object) $ac[$c];

                    if (!property_exists($achoice, 'is_selected')) {
                        $achoice->is_selected = false;
                    }

                    if ($achoice->is_selected == true && $choice->is_correct == true) {
                        $flag++;
                    }
                }

                if ($flag == $item->mcq_max_selection) {
                    $score++;
                }
                Log::error('MCQ');
            }

            if ($item->question_type_code == 'FTQ') {
                
            }
        }

        Log::info($score);
        
    }
}
