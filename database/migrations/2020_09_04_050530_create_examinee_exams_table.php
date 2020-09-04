<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamineeExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinee_exams', function (Blueprint $table) {
            $table->string('examinee_no', 10);
            $table->bigInteger('exam_id')->unsigned();

            $table->string('exam_remarks_code', 3)->default('F');

            $table->double('overall_score', 15, 2)->default(0);
            $table->double('total_score', 15, 2)->default(0);
            $table->double('passing_score', 15, 2)->default(0);

            $table->dateTime('time_start')->useCurrent();
            $table->dateTime('time_end')->useCurrent();

            $table->longText('exam_string');
            $table->tinyInteger('result_printed_count')->default(0);

            $table->primary(array('examinee_no', 'exam_id', 'time_start'));
            $table->unique(array('examinee_no', 'exam_id', 'exam_remarks_code', 'overall_score', 'time_start', 'time_end'), 
                                'index_examinee_exams');

            $table->foreign('examinee_no')
                  ->references('examinee_no')->on('users');
            $table->foreign('exam_id')
                  ->references('exam_id')->on('exams');
            $table->foreign('exam_remarks_code')
                  ->references('exam_remarks_code')->on('exam_remarks');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examinee_exams');
    }
}
