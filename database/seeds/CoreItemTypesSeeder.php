<?php

use Illuminate\Database\Seeder;

class CoreItemTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
}
