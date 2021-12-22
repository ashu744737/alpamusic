<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();
        $categories = [
            [
                'id' => '1',
                'name' => 'Rock',
                'hr_name' => 'סלע',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'name' => 'Pop Music',
                'hr_name' => 'מוסיקת פופ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'name' => 'Classical',
                'hr_name' => 'קלַאסִי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Category::insert($categories);
    }
}
