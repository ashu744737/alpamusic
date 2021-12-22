<?php

use Illuminate\Database\Seeder;

class PaymentTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_terms')->truncate();
        $terms = [
            [
                'id' => '1',
                'term_name'  => 'Immediately',
                'hr_term_name' => 'שוטף',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'term_name'  => 'Immediately + 15',
                'hr_term_name' => 'שוטף + 15 ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'term_name'  => 'Immediately + 30',
                'hr_term_name' => 'שוטף + 30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'term_name'  => 'Immediately + 60',
                'hr_term_name' => 'שוטף + 60',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '5',
                'term_name'  => 'Immediately + 90',
                'hr_term_name' => 'שוטף + 90',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        \App\PaymentTerm::insert($terms);
    }
}
