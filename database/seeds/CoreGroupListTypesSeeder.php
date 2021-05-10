<?php

use Illuminate\Database\Seeder;

class CoreGroupListTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
}
