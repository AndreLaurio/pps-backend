<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_items', function (Blueprint $table) {
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('item_no')->unsigned();
            $table->bigInteger('group_no')->unsigned();
            $table->bigInteger('group_item_no')->unsigned();

            $table->string('item_type_code', 3);
            $table->string('question_type_code', 3);
            
            $table->text('text');
            $table->integer('points')->default(0);
            $table->boolean('is_points_per_answer')->default(false);

            $table->tinyInteger('mcq_max_selection')->default(0);
            $table->boolean('included_in_total_score')->default(false);
            $table->bigInteger('relative_item_no')->unsigned();

            $table->primary(array('exam_id', 'item_no', 'group_no', 'group_item_no'));
            $table->index(array('exam_id', 'item_no', 'group_no', 'group_item_no', 'item_type_code', 
                                'question_type_code', 'included_in_total_score', 'relative_item_no'), 'index_exam_items');

            $table->foreign('exam_id')
                  ->references('exam_id')->on('exams');

            $table->foreign('item_type_code')
                  ->references('item_type_code')->on('item_types');

            $table->foreign('question_type_code')
                  ->references('question_type_code')->on('question_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_items');
    }
}
