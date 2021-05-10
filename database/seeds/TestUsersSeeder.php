<?php

use Illuminate\Database\Seeder;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'first_name' => 'Joshua',
                'last_name' => 'Mamangun',
                'email' => 'admin1@gmail.com',
                'password' => Hash::make('vixam'),
                'is_approved' => 1,
                'approved_by' => null,
                'user_type_id' => 1,
            ],
            [
                'first_name' => 'Andre',
                'last_name' => 'Laurio',
                'email' => 'examiner1@gmail.com',
                'password' => Hash::make('vixam'),
                'is_approved' => 1,
                'approved_by' => 1,
                'user_type_id' => 2,
            ],
            [
                'first_name' => 'Ronalyn',
                'last_name' => 'Collados',
                'email' => 'examinee1@gmail.com',
                'password' => Hash::make('vixam'),
                'is_approved' => 1,
                'approved_by' => 2,
                'user_type_id' => 2,
            ],
            [
                'first_name' => 'Princess Zarrie',
                'last_name' => 'Equinan',
                'email' => 'examinee2@gmail.com',
                'password' => Hash::make('vixam'),
                'is_approved' => 1,
                'approved_by' => 2,
                'user_type_id' => 2,
            ],
            [
                'first_name' => 'Jessica May',
                'last_name' => 'Costales',
                'email' => 'examinee3@gmail.com',
                'password' => Hash::make('vixam'),
                'is_approved' => 1,
                'approved_by' => 2,
                'user_type_id' => 2,
            ],
        ];

        DB::table('users')->insert($data);
    }
}
