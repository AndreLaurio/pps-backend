<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id('exam_id');
            $table->string('exam_title', 50);
            $table->longText('exam_desc');
            $table->bigInteger('time_duration')->default(0);
            $table->double('passing_score', 15, 2)->default(0);
            $table->double('total_score', 15, 2)->default(0);
            $table->integer('total_num_questions')->default(0);
            $table->boolean('is_randomized')->default(false);
            $table->boolean('is_active')->default(false);
            $table->string('version')->default('1.0');

            $table->index(array('exam_id', 'exam_title', 'is_active', 'version'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
