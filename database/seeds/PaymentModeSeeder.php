<?php

use Illuminate\Database\Seeder;

class PaymentModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_modes')->truncate();
        $modes = [
            [
                'id' => '1',
                'mode_name'  => 'Cash',
                'hr_mode_name' => 'מזומן',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'mode_name'  => 'Cheque',
                'hr_mode_name' => 'צק',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '3',
                'mode_name'  => 'Credit Card',
                'hr_mode_name' => 'אשראי',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '4',
                'mode_name'  => 'wireTransfer',
                'hr_mode_name' => 'העברה בנקאית',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        \App\PaymentMode::insert($modes);
    }
}
