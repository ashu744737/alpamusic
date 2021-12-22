<?php

use App\ClientTypes;
use Illuminate\Database\Seeder;

class ClientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('client_types')->truncate();
        $clienttypes = [
            [
                'id' => '1',
                'type_name'  => 'Banks',
                'hr_type_name' => 'בנקים ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'type_name'  => 'Insurance Companies',
                'hr_type_name' => 'חברות ביטוח',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'type_name'  => 'Private Companies',
                'hr_type_name' => 'חברות פרטיות',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'type_name'  => 'Abroad',
                'hr_type_name' => 'לקוח חו״ל',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '5',
                'type_name'  => 'Office of Inquiry',
                'hr_type_name' => 'משרד חקירות',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '6',
                'type_name'  => 'Appraisers Offices',
                'hr_type_name' => 'שמאים',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '7',
                'type_name'  => 'Private',
                'hr_type_name' => 'לקוח פרטי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '8',
                'type_name'  => 'Attorneys',
                'hr_type_name' => 'עו״ד',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '9',
                'type_name'  => 'Business and Real Estate',
                'hr_type_name' => 'עיסקי ונדל״ן',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        ClientTypes::insert($clienttypes);
    }
}
