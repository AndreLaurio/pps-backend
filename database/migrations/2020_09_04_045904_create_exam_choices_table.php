<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_choices', function (Blueprint $table) {
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('item_no')->unsigned();
            $table->bigInteger('choice_no')->unsigned();

            $table->text('label');
            $table->boolean('is_correct')->default(false);

            $table->primary(array('exam_id', 'item_no', 'choice_no'));
            $table->index(array('exam_id', 'item_no', 'choice_no', 'is_correct'));

            $table->foreign('exam_id')
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
        Schema::dropIfExists('exam_choices');
    }
}
