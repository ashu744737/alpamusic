<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();
        $products = array(
            array('name' => 'Custom usage', 'price' => '200', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Covert entry', 'price' => '250', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Listening exposure', 'price' => '250', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Surveillance', 'price' => '300', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Special investigation', 'price' => '400', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Comprehensive Economic', 'price' => '300', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Limited', 'price' => '200', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Business Activity', 'price' => '300', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Vehicle conception', 'price' => '200', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Vehicle locating', 'price' => '350', 'created_at' => now(), 'updated_at' => now()),
        );
        \App\Product::insert($products);
    }
}
