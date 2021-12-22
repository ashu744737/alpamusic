<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = \App\User::where([
            'name' => 'Admin',
            'email' => 'admin@admin.com'
        ])->first();
        if ($default) {
            $default->forceDelete();
        }

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'type_id' => '1',
            'status' => 'approved'
        ]);
    }
}
