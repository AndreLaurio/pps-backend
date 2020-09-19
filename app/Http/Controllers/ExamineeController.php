<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamineeController extends Controller
{
    public function getExams(Request $request) {
        
        $exams = DB::table('examinee_exams AS ee')
                    ->join('exams AS e', 'ee.exam_id', '=', 'e.exam_id')
                    ->where('ee.user_id', $request->input('user_id'))
                    ->select(
                        'ee.exam_id',
                        'e.exam_title',
                        'e.exam_desc',
                        'ee.exam_status_code'
                    )
                    ->get();
        Log::error(json_encode($request));
        return $exams;
    }
}
