<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTypeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(ClientTypeSeeder::class);
        $this->call(ContactTypeSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CountryHebrewSeeder::class);
        $this->call(DeclineReasonsSeeder::class);
        $this->call(DeliveryAreaSeeder::class);
        $this->call(DocumentTypeSeeder::class);
        $this->call(PaymentModeSeeder::class);
        $this->call(PaymentTermSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(TypesSeeder::class);
        $this->call(SpecializationSeeder::class);
        $this->call(UserTypePermissionsSeeder::class);
        $this->call(SubjectTypeSeeder::class);
    }
}
