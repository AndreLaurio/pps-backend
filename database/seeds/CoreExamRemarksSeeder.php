<?php

use Illuminate\Database\Seeder;

class CoreExamRemarksSeeder extends Seeder
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
                'exam_remarks_code' => 'F',
                'exam_remarks' => 'FAILED'
            ],
            [
                'exam_remarks_code' => 'P',
                'exam_remarks' => 'PASSED'
            ],
        );

        DB::table('exam_remarks')->insert($data);
    }
}
