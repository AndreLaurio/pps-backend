<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamineeExamLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinee_exam_logs', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('exam_id')->unsigned();
            $table->smallInteger('take_no')->default(0);
            $table->dateTime('taken_on')->useCurrent();

            $table->primary(array('user_id', 'exam_id', 'take_no'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examinee_exam_logs');
    }
}
