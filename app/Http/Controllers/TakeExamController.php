<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

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
}
