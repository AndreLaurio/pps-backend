<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');

            $table->renameColumn('id', 'user_id');

            $table->tinyInteger('user_type_id')->unsigned();

            $table->string('examinee_no', 10)->nullable();

            $table->unique('examinee_no');
            $table->index('examinee_no');

            $table->foreign('user_type_id')
                  ->references('user_type_id')->on('user_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
