<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToExamineeExams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examinee_exams', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropForeign('examinee_exams_examinee_no_foreign');

            $table->bigInteger('user_id')->unsigned();
            $table->primary(array('user_id'));

            $table->foreign('user_id')
                  ->references('user_id')->on('users');

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
