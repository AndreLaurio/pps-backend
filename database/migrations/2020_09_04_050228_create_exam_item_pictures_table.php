<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamItemPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_item_pictures', function (Blueprint $table) {
            $table->bigInteger('exam_id')->unsigned();
            $table->bigInteger('item_no')->unsigned();
            $table->bigInteger('picture_no')->unsigned();

            $table->string('picture_path', 100);

            $table->primary(array('exam_id', 'item_no', 'picture_no'));
            $table->index(array('exam_id', 'item_no', 'picture_no'));

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
        Schema::dropIfExists('exam_item_pictures');
    }
}
