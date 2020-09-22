<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Exam extends Model
{
    protected $table = 'exams';
    public $timestamps = false;
    
    // public  $exam_title,
    //         $exam_desc,
    //         $time_duration,
    //         $passing_score,
    //         $total_score,
    //         $total_num_questions,
    //         $is_randomized,
    //         $is_active,
    //         $version;

    // public $exam_items = array();

    public function scopeGetAll(){

        $query = DB::table('exams')
                    ->select(   'exam_id', 
                                'exam_title', 
                                'exam_desc', 
                                'time_duration', 
                                'passing_score',
                                'total_score',
                                'total_num_questions',
                                'is_randomized',
                                DB::raw('DATE_FORMAT(updated_on, "%Y %b %d, %h:%i %p") AS updated_on_f'))
                    ->where('is_active', true)
                    // ->order_by('exam_title', 'ASC')
                    ->paginate(1);

        return $query;
    }

}
