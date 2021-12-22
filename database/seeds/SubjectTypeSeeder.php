<?php

use Illuminate\Database\Seeder;

class SubjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subject_types')->truncate();
        $subject_types = [
            [
                'id' => '1',
                'name' => 'Main',
                'hr_name' => 'נבדק ראשי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'name' => 'Spouse',
                'hr_name' => 'בן / בת זוג',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'name' => 'Company',
                'hr_name' => 'חברה',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'name' => 'Other',
                'hr_name' => 'אחר',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        \App\SubjectTypes::insert($subject_types);
    }
}
