<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedItemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = array(
            array(  'item_type_code' => 'QTN',
                    'item_type' => 'QUESTION'
                ),
            array(  'item_type_code' => 'TXT',
                    'item_type' => 'TEXT'
            ),
            array(  'item_type_code' => 'GRP',
                    'item_type' => 'GROUP'
            )
        );

        DB::table('item_types')->insert($data);
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
