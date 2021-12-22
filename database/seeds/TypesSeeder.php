<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Type;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'id'         => '1',
                'type'  => 'Composer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '2',
                'type'  => 'Drummer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '3',
                'type'  => 'Instrument',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '4',
                'type'  => 'Performer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '5',
                'type'  => 'Producer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '6',
                'type'  => 'Singer',
                'created_at' => now(),
                'updated_at' => now(),
            ],            
            [
                'id'         => '7',
                'type'  => 'Writer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '8',
                'type'  => 'Other',
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ];
        Type::insert($types);
    }
}
