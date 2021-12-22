<?php

use App\ContactTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_types')->truncate();
        $contacttypes = [
            [
                'id' => '1',
                'type_name'  => 'Main Office',
                'hr_type_name' => 'משרד ראשי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'type_name'  => 'Branch Office',
                'hr_type_name' => 'סניף ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'type_name'  => 'Sales Office',
                'hr_type_name' => 'משרד מכירות ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'type_name'  => 'Home',
                'hr_type_name' => 'בית ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '5',
                'type_name'  => 'Other',
                'hr_type_name' => 'אחר',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '6',
                'type_name'  => 'Contact',
                'hr_type_name' => 'איש קשר',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '7',
                'type_name'  => 'Finance',
                'hr_type_name' => 'כספים',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '8',
                'type_name'  => 'Office Manager',
                'hr_type_name' => 'מנהל משרד',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('contact_types')->insert($contacttypes);
    }
}
