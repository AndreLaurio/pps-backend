<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = array(
            array(  'user_type_id' => 0,
                    'user_type' => 'ADMIN'
                ),
            array(  'user_type_id' => 0,
                    'user_type' => 'EXAMINEE'
                )
        );

        DB::table('user_types')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
