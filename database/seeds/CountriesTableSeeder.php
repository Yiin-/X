<?php

use Illuminate\Database\Seeder;
use App\Domain\Model\Documents\Passive\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = Countries::getList();

        foreach ($countries as $country) {
            if ($record = Country::whereCountryCode($country['country-code'])->first()) {
                $record->name = $country['name'];
                $record->full_name = ((isset($country['full_name'])) ? $country['full_name'] : null);
                $record->save();
            } else {
                Country::create([
                    'capital' => ((isset($country['capital'])) ? $country['capital'] : null),
                    'citizenship' => ((isset($country['citizenship'])) ? $country['citizenship'] : null),
                    'country_code' => $country['country-code'],
                    'currency' => ((isset($country['currency'])) ? $country['currency'] : null),
                    'currency_code' => ((isset($country['currency_code'])) ? $country['currency_code'] : null),
                    'currency_sub_unit' => ((isset($country['currency_sub_unit'])) ? $country['currency_sub_unit'] : null),
                    'full_name' => ((isset($country['full_name'])) ? $country['full_name'] : null),
                    'iso_3166_2' => $country['iso_3166_2'],
                    'iso_3166_3' => $country['iso_3166_3'],
                    'name' => $country['name'],
                    'region_code' => $country['region-code'],
                    'sub_region_code' => $country['sub-region-code'],
                    'eea' => (bool) $country['eea'],
                ]);
            }
        }
    }
}
