<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedGroupListTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = array(
            array(  'group_list_type_code' => 'NMR',
                    'group_list_type' => 'NUMERIC'
                ),
            array(  'group_list_type_code' => 'APB',
                'group_list_type' => 'ALPHABETICAL'
            ),
            array(  'group_list_type_code' => 'RMN',
                    'group_list_type' => 'ROMAN NUMERAL'
            )
        );

        DB::table('group_list_types')->insert($data);
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
