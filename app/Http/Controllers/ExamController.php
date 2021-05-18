<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exam;
use App\ExamItem;
use App\ExamChoice;
use App\ExamGroup;
use App\QuestionType;
use Illuminate\Support\Facades\Log;
use DB;

class ExamController extends Controller
{
    public function getFresh() {
        $data = [
            'exam' => [
                'exam_title' => '',
                'exam_desc' => '',
                'time_duraction' => 0,
                'passing_score' => 0,
                'total_score' => 0,
                'total_num_questions' => 0,
                'is_randomized' => false,
                'is_active' => true,
                'version' => '1.0',
                'exam_items' => array()
            ],

            'exam_item' => [
                'group_no' => 0,
                'group_item_no' => 0,
                'item_type_code' => 'QTN',
                'question_type_code' => '',
                'text' => '',
                'points' => 1,
                'mcq_max_selection' => 0,
                'included_in_total_score' => 1,
                'choices' => [],
                'message' => '',
                'alert' => false
            ],

            'exam_group' => [
                'group_no' => 0,
                'group_list_type_code' => 'NMR'
            ],

            'question_types' => QuestionType::all(),

            'exam_choice' => [
                'label' => '',
                'is_correct' => false
            ]
        ];

        return $data;
    }

    public function create(Request $request) {
        
        $exam = new Exam();
        
        $exam->exam_title = $request->input('exam_title');
        $exam->exam_desc = $request->input('exam_desc');
        $exam->time_duration = $request->input('time_duration');
        $exam->passing_score = $request->input('passing_score');
        // $exam->total_score = $request->input('total_score');
        $exam->total_score = count($request->input('exam_items'));
        // $exam->total_num_questions = $request->input('total_num_questions');
        $exam->total_num_questions = count($request->input('exam_items'));
        $exam->is_randomized = $request->input('is_randomized');
        $exam->is_active = $request->input('is_active');
        $exam->version = $request->input('version');

        $exam->exam_code = $request->input('exam_code');

        $exam->save();
        $exam->exam_id = $exam->id;

        $exam_controller = new ExamController();
        $exam_controller->addExamItems($exam->exam_id, $request->input('exam_items'));
        $exam_controller->addExamGroups($exam->exam_id, $request->input('exam_groups'));
    }

    public function addExamItems($exam_id, $exam_items) {

        foreach($exam_items as $item_no => $exam_item) {

            $exam_item = (object) $exam_item;

            $item = new ExamItem();

            $item->exam_id = $exam_id;
            $item->item_no = $item_no;
            $item->group_no = $exam_item->group_no;
            $item->group_item_no = $item_no;
            $item->item_type_code = $exam_item->item_type_code;
            $item->question_type_code = $exam_item->question_type_code;
            $item->text = $exam_item->text;
            $item->relative_item_no = 0;

            if ($item->question_type_code == 'MCQ') {
                $item->mcq_max_selection = $exam_item->mcq_max_selection;
            }

            if ($item->question_type_code == 'TOF') {
                $item->tof_answer = $exam_item->tof_answer;
            }

            $item->save();


            if ($item->question_type_code == 'MCQ' || $item->question_type_code == 'SCQ') {
                
                foreach((array) $exam_item->choices as $choice_no => $choice) {
                    
                    $choice = (object) $choice;

                    $exam_choice = new ExamChoice();

                    $exam_choice->exam_id = $exam_id;
                    $exam_choice->item_no = $item->item_no;
                    $exam_choice->choice_no = $choice_no;
                    $exam_choice->label = $choice->label;
                    $exam_choice->is_correct = $choice->is_correct;

                    $exam_choice->save();
                }
            }
        }
    }

    public function addExamGroups($exam_id, $exam_groups) {

        foreach($exam_groups as $exam_group) {

            $exam_group = (object) $exam_group;

            $group = new ExamGroup();

            $group->exam_id = $exam_id;
            $group->group_no = $exam_group->group_no;
            $group->group_list_type_code = $exam_group->group_list_type_code;

            $group->save();
        }
    }

    public function getAll() {
        return Exam::getAll();
    }

    public function get($exam_id) {
        
        $exam = DB::table('exams')
                    ->where('exam_id', $exam_id)
                    ->first();
        
        $exam->exam_items = DB::table('exam_items')
                                ->where('exam_id', $exam_id)
                                ->get();
        
        foreach($exam->exam_items as $exam_item) {

            // $exam_item = (object) $exam_item;

            if (in_array($exam_item->question_type_code, ['SCQ', 'MCQ'])) {

                $exam_item->choices = DB::table('exam_choices')
                                            ->where([
                                                ['exam_id', '=', $exam->exam_id],
                                                ['item_no', '=', $exam_item->item_no],
                                            ])
                                            ->get();
            }
        }

        $exam->exam_groups = DB::table('exam_groups')
                                    ->where('exam_id', $exam->exam_id)
                                    ->get();

        $exam = (array) $exam;
        return $exam;
    }

    public function getDesc($exam_id) {

        $exam = DB::table('exams AS ex')
            ->where('exam_id', $exam_id)
            ->first(); 

        $exam = (array) $exam;
        return $exam;
    }

    public function getItems($exam_id) {
        
        $exam = (object) [];
        $exam->exam_id = $exam_id;

        $exam->exam_items = DB::table('exam_items')
                                ->where('exam_id', $exam->exam_id)
                                ->get();
        
        foreach($exam->exam_items as $exam_item) {

            // $exam_item = (object) $exam_item;

            if (in_array($exam_item->question_type_code, ['SCQ', 'MCQ'])) {

                $exam_item->choices = DB::table('exam_choices')
                                            ->where([
                                                ['exam_id', '=', $exam->exam_id],
                                                ['item_no', '=', $exam_item->item_no],
                                            ])
                                            ->get();
            }
        }

        $exam->exam_groups = DB::table('exam_groups')
                                    ->where('exam_id', $exam->exam_id)
                                    ->get();

        $exam = (array) $exam;
        
        return $exam;
    }

    public function delete(Request $request) {

        $exam_id = $request->input('exam_id');
        
        DB::table('examinee_exams')
            ->where('exam_id', $exam_id)
            ->delete();
        
        DB::table('examinee_exam_logs')
            ->where('exam_id', $exam_id)
            ->delete();

        DB::table('exam_groups')
            ->where('exam_id', $exam_id)
            ->delete();

        DB::table('exam_choices')
            ->where('exam_id', $exam_id)
            ->delete();
        
        DB::table('exam_items')
            ->where('exam_id', $exam_id)
            ->delete();

        DB::table('exams')  
            ->where('exam_id', $exam_id)
            ->delete();
    }

    public function getForEdit(Request $request) {

        $exam_id = $request->input('exam_id');
        
        $exam_controller = new ExamController();

        $exam = $exam_controller->get($exam_id);
        $response = $exam_controller->getFresh();
        $response['exam'] = $exam;

        return $response;
    }

    public function edit(Request $request) {

        $exam_id = $request->input('exam_id');

        DB::table('exams')
            ->where('exam_id', $exam_id)
            ->update([
                'exam_title' => $request->input('exam_title'),
                'exam_desc' => $request->input('exam_desc'),
                'time_duration' => $request->input('time_duration'),
                'passing_score' => $request->input('passing_score'),
                'total_score' => count($request->input('exam_items')),
                'total_num_questions' => count($request->input('exam_items')),
                'is_randomized' => $request->input('is_randomized'),
                'is_active' => $request->input('is_active'),
                'version' => $request->input('version')
            ]);
            
        // delete the prev items and groups
        DB::table('exam_groups')
            ->where('exam_id', $exam_id)
            ->delete();

        DB::table('exam_choices')
            ->where('exam_id', $exam_id)
            ->delete();
        
        DB::table('exam_items')
            ->where('exam_id', $exam_id)
            ->delete();
        
        $exam_controller = new ExamController();
        $exam_controller->addExamItems($exam_id, $request->input('exam_items'));
        $exam_controller->addExamGroups($exam_id, $request->input('exam_groups'));
    }

    public function index(Request $request)
    {
        try {
            $exams = DB::table('exams')
                ->orderByRaw('exam_title ASC')
                ->paginate(1000)
                ->toArray();
            Log::debug(json_encode($exams));
            foreach($exams['data'] as $exam) {
                $exam->examinees_count = DB::table('examinee_exams')
                    ->where('exam_id', $exam->exam_id)
                    ->selectRaw('COUNT(*) AS examinees_count')
                    ->first()
                    ->examinees_count;
            }

            return response()->json([
                'code' => 200,
                'exams' => $exams,
                'message' => 'Getting exams is successful'
            ]);
        }
        catch(QueryException $e) {
            Log::debug($e);
            return response()->json([], 500);
        }
    }

    public function delete2(Request $request, $examId) {

        try {

            DB::beginTransaction();
        
            DB::table('examinee_exams')
                ->where('exam_id', $examId)
                ->delete();
               
            DB::table('examinee_exam_logs')
                ->where('exam_id', $examId)
                ->delete();
            
            DB::table('examinee_change_tab_history')
                ->where('exam_id', $examId)
                ->delete();
    
            DB::table('exam_groups')
                ->where('exam_id', $examId)
                ->delete();
    
            DB::table('exam_choices')
                ->where('exam_id', $examId)
                ->delete();
            
            DB::table('exam_items')
                ->where('exam_id', $examId)
                ->delete();
    
            DB::table('exams')  
                ->where('exam_id', $examId)
                ->delete();

            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Deleting exam is successful.'
            ]);
        }
        catch(QueryException $e) {
            DB::rollback();
            Log::debug($e);
            return response()->json([], 500);
        }
       
    }
}

