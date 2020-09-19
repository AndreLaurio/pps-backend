<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExamStatusToExamineeExams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examinee_exams', function (Blueprint $table) {
            $table->string('exam_status_code', 3)->default('N');

            $table->foreign('exam_status_code')
                  ->references('exam_status_code')->on('exam_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('examinee_exams', function (Blueprint $table) {
            //
        });
    }
}
