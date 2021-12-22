<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeclineReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();
        $reasons = [
            [
                'id' => '1',
                'key'  => 'decline_reason',
                'value'  => '1. Details are missing
2. Not valid customer
3. Need more information',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];
        DB::table('settings')->insert($reasons);
    }
}
