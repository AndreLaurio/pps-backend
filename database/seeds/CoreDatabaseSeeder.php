<?php

use Illuminate\Database\Seeder;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CoreExamRemarksSeeder::class,
            CoreExamStatusesSeeder::class,
            CoreGroupListTypesSeeder::class,
            CoreItemTypesSeeder::class,
            CoreQuestionTypesSeeder::class,
            CoreUserTypesSeeder::class,
        ]);
    }
}
