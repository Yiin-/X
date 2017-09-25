<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(IndustriesTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(CompanySizesTableSeeder::class);
        $this->call(TimezonesTableSeeder::class);
        $this->call(GatewayTypesTableSeeder::class);
        $this->call(PaymentTypesTableSeeder::class);
    }
}
