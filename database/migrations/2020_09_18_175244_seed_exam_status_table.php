<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedExamStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = array(
            array(  'exam_status_code' => 'N',
                    'exam_status' => 'NONE'
                ),
            array(  'exam_status_code' => 'O',
                    'exam_status' => 'ONGOING'
            ),
            array(  'exam_status_code' => 'F',
                    'exam_status' => 'FINISHED'
            )
        );

        DB::table('exam_status')->insert($data);
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
