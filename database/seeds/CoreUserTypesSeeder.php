<?php

use Illuminate\Database\Seeder;

class CoreUserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            [
                'user_type_id' => 1,
                'user_type' => 'ADMIN'
            ],
            [
                'user_type_id' => 2,
                'user_type' => 'EXAMINER'
            ],
            [
                'user_type_id' => 3,
                'user_type' => 'EXAMINEE'
            ],
        );

        DB::table('user_types')->insert($data);
    }
}
