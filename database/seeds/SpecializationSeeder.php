<?php

use App\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('specializations')->truncate();
        $specializationS = [
            [
                'id' => '1',
                'name' => 'Finance',
                'hr_name' => 'חקירות כלכליות',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'name' => 'Deliveries',
                'hr_name' => 'מסירות',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'name' => 'Criminal',
                'hr_name' => 'חקירות פליליות ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'name' => 'Business Intelegence',
                'hr_name' => 'מודיעין עיסקי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '5',
                'name' => 'Covert',
                'hr_name' => 'חקירות סמויות',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '6',
                'name' => 'Other',
                'hr_name' => 'אחר',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '7',
                'name' => 'Address Locating ',
                'hr_name' => 'איתורים',
                'created_at' => now(),
                'updated_at' => now(), 
            ],
        ];
        Specialization::insert($specializationS);
    }
}
