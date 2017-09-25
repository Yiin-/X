<?php

use Illuminate\Database\Seeder;
use App\Domain\Model\Documents\Passive\CompanySize;

class CompanySizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanySize::create(['name' => '1 - 3']);
        CompanySize::create(['name' => '4 - 10']);
        CompanySize::create(['name' => '11 - 50']);
        CompanySize::create(['name' => '51 - 100']);
        CompanySize::create(['name' => '101 - 500']);
        CompanySize::create(['name' => '500+']);
    }
}
