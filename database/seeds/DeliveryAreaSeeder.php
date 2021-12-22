<?php

use App\DeliveryArea;
use Illuminate\Database\Seeder;

class DeliveryAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('delivery_areas')->truncate();
        $deliveryareas = [
            [
                'id' => '1',
                'area_name' => 'All',
                'hr_area_name' => 'כללי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'area_name' => 'North',
                'hr_area_name' => 'צפון',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'area_name' => 'Sharon North',
                'hr_area_name' => 'צפון השרון',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'area_name' => 'Sharon',
                'hr_area_name' => 'שרון',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '5',
                'area_name' => 'Center',
                'hr_area_name' => 'מרכז',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '6',
                'area_name' => 'Shfela',
                'hr_area_name' => 'שפלה',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '7',
                'area_name' => 'Darom',
                'hr_area_name' => 'דרום ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '8',
                'area_name' => 'Eilat',
                'hr_area_name' => 'אילת ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '9',
                'area_name' => 'Abroad',
                'hr_area_name' => 'חו״ל',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DeliveryArea::insert($deliveryareas);
    }
}
