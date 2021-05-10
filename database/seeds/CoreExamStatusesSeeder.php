<?php

use Illuminate\Database\Seeder;

class CoreExamStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            [
                'exam_status_code' => 'N',
                'exam_status' => 'NONE'
            ],
            [
                'exam_status_code' => 'O',
                'exam_status' => 'ONGOING'
            ],
            [
                'exam_status_code' => 'F',
                'exam_status' => 'FINISHED'
            ]
        );

        DB::table('exam_status')->insert($data);
    }
}
