<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedQuestionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = array(
            array(  'question_type_code' => 'NNE',
                    'question_type' => 'NONE'
            ),
            array(  'question_type_code' => 'SCQ',
                    'question_type' => 'SINGLE-CHOICE QUESTION'
            ),
            array(  'question_type_code' => 'MCQ',
                    'question_type' => 'MULTIPLE-CHOICE QUESTION'
            ),
            array(  'question_type_code' => 'FTQ',
                    'question_type' => 'FREE-TEXT QUESTION'
            )
        );

        DB::table('question_types')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
