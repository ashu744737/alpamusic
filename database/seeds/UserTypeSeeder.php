<?php

use App\UserTypes;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('user_types')->truncate();
        $usertypes = [
            [
                'id'         => '1',
                'type_name'  => 'Admin',
                'hr_type_name' => 'מנהל מערכת',
                'type'       => 'Internal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '2',
                'type_name'  => 'Client',
                'hr_type_name' => 'לקוח',
                'type'       => 'External',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '3',
                'type_name'  => 'Investigator',
                'hr_type_name' => 'חוקר',
                'type'       => 'External',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '4',
                'type_name'  => 'Delivery Boy',
                'hr_type_name' => 'שליח',
                'type'       => 'External',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '5',
                'type_name'  => 'Company Admin',
                'hr_type_name' => 'מנהל חברה',
                'type'       => 'Internal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '6',
                'type_name'  => 'Accountant',
                'hr_type_name' => 'הנהלת חשבונות',
                'type'       => 'Internal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '7',
                'type_name'  => 'Secretary',
                'hr_type_name' => 'מזכירה',
                'type'       => 'Internal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '8',
                'type_name'  => 'Station Manager',
                'hr_type_name' => 'מנהל תחנה',
                'type'       => 'Internal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        UserTypes::insert($usertypes);
    }
}
