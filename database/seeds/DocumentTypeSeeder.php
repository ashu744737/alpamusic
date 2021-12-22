<?php

use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_types')->truncate();
        $document_types = [
            [
                'id' => '1',
                'name' => 'Case Report',
                'hr_name' => 'דוח תיק',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'name' => 'Investigation Document',
                'hr_name' => 'מסמך חקירה',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'name' => 'Government',
                'hr_name' => 'מסמך ממשלתי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'name' => 'Bank',
                'hr_name' => 'מסמך בנקאי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '5',
                'name' => 'Police',
                'hr_name' => 'מסמך משטרתי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '6',
                'name' => 'Health',
                'hr_name' => 'מסמך רפואי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '7',
                'name' => 'Video',
                'hr_name' => 'וידיאו',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '8',
                'name' => 'Audio',
                'hr_name' => 'אודיו',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '9',
                'name' => 'Other',
                'hr_name' => 'אחר',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        \App\DocumentTypes::insert($document_types);
    }
}
