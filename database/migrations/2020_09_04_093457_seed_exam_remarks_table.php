<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedExamRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = array(
            array(  'exam_remarks_code' => 'P',
                    'exam_remarks' => 'PASSED'
            ),
            array(  'exam_remarks_code' => 'F',
                    'exam_remarks' => 'FAILED'
            )
        );

        DB::table('exam_remarks')->insert($data);
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
