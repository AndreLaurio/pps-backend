<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamineeChangeTabHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinee_change_tab_history', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('exam_id')->unsigned();

            $table->dateTime('change_tab_date')->useCurrent();

            $table->primary(['user_id', 'exam_id', 'change_tab_date'], 'ecth_primary');

            $table->foreign('user_id', 'ecth_user_id_foreign')
                  ->references('user_id')->on('users');
            $table->foreign('exam_id', 'ecth_exam_id_foreign')
                  ->references('exam_id')->on('exams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examinee_change_tab_history');
    }
}
