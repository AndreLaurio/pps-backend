<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_groups', function (Blueprint $table) {
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('group_no')->unsigned();
            $table->string('group_list_type_code', 3);

            $table->primary(array('exam_id', 'group_no'));
            $table->index(array('exam_id', 'group_no'));

            $table->foreign('exam_id')
                  ->references('exam_id')->on('exams');

            $table->foreign('group_list_type_code')
                  ->references('group_list_type_code')->on('group_list_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_groups');
    }
}
