<?php

use Illuminate\Database\Seeder;

class CoreQuestionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
}
