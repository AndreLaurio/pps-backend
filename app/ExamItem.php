<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamItem extends Model
{
    protected $table = 'exam_items';
    public $timestamps = false;

    // public  $item_no,
    //         $group_no = 0,
    //         $group_item_no,
    //         $item_type_code,
    //         $question_type_code,
    //         $text,
    //         $points = 1,
    //         $is_points_per_answer = false,
    //         $mcq_max_selection = 0,
    //         $included_in_total_score = 0,
    //         $relative_item_no = -1;
}
