<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
