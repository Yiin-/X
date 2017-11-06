<?php

use Illuminate\Database\Seeder;

use App\Domain\Model\Documents\Passive\Country;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Service\Currency\CurrencyRateService;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CurrencyRateService $currencyRateService)
    {
        $currencies = [
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£', 'precision' => '2'],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'precision' => '2'],
            ['name' => 'South African Rand', 'code' => 'ZAR', 'symbol' => 'R', 'precision' => '2'],
            ['name' => 'Danish Krone', 'code' => 'DKK', 'symbol' => 'kr.', 'precision' => '2'],
            ['name' => 'Israeli Shekel', 'code' => 'ILS', 'symbol' => 'NIS', 'precision' => '2'],
            ['name' => 'Swedish Krona', 'code' => 'SEK', 'symbol' => 'kr', 'precision' => '2'],
            ['name' => 'Kenyan Shilling', 'code' => 'KES', 'symbol' => 'KSh ', 'precision' => '2'],
            ['name' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => 'C$', 'precision' => '2'],
            ['name' => 'Philippine Peso', 'code' => 'PHP', 'symbol' => 'P ', 'precision' => '2'],
            ['name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => 'Rs. ', 'precision' => '2'],
            ['name' => 'Australian Dollar', 'code' => 'AUD', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'Singapore Dollar', 'code' => 'SGD', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Norske Kroner', 'code' => 'NOK', 'symbol' => 'kr', 'precision' => '2'],
            ['name' => 'New Zealand Dollar', 'code' => 'NZD', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'Vietnamese Dong', 'code' => 'VND', 'symbol' => '', 'precision' => '0'],
            ['name' => 'Swiss Franc', 'code' => 'CHF', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Guatemalan Quetzal', 'code' => 'GTQ', 'symbol' => 'Q', 'precision' => '2'],
            ['name' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol' => 'RM', 'precision' => '2'],
            ['name' => 'Brazilian Real', 'code' => 'BRL', 'symbol' => 'R$', 'precision' => '2'],
            ['name' => 'Thai Baht', 'code' => 'THB', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Nigerian Naira', 'code' => 'NGN', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Argentine Peso', 'code' => 'ARS', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'Bangladeshi Taka', 'code' => 'BDT', 'symbol' => 'Tk', 'precision' => '2'],
            ['name' => 'United Arab Emirates Dirham', 'code' => 'AED', 'symbol' => 'DH ', 'precision' => '2'],
            ['name' => 'Hong Kong Dollar', 'code' => 'HKD', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Indonesian Rupiah', 'code' => 'IDR', 'symbol' => 'Rp', 'precision' => '2'],
            ['name' => 'Mexican Peso', 'code' => 'MXN', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'Egyptian Pound', 'code' => 'EGP', 'symbol' => 'E£', 'precision' => '2'],
            ['name' => 'Colombian Peso', 'code' => 'COP', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'West African Franc', 'code' => 'XOF', 'symbol' => 'CFA ', 'precision' => '2'],
            ['name' => 'Chinese Renminbi', 'code' => 'CNY', 'symbol' => 'RMB ', 'precision' => '2'],
            ['name' => 'Rwandan Franc', 'code' => 'RWF', 'symbol' => 'RF ', 'precision' => '2'],
            ['name' => 'Tanzanian Shilling', 'code' => 'TZS', 'symbol' => 'TSh ', 'precision' => '2'],
            ['name' => 'Netherlands Antillean Guilder', 'code' => 'ANG', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Trinidad and Tobago Dollar', 'code' => 'TTD', 'symbol' => 'TT$', 'precision' => '2'],
            ['name' => 'East Caribbean Dollar', 'code' => 'XCD', 'symbol' => 'EC$', 'precision' => '2'],
            ['name' => 'Ghanaian Cedi', 'code' => 'GHS', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Bulgarian Lev', 'code' => 'BGN', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Aruban Florin', 'code' => 'AWG', 'symbol' => 'Afl. ', 'precision' => '2'],
            ['name' => 'Turkish Lira', 'code' => 'TRY', 'symbol' => 'TL ', 'precision' => '2'],
            ['name' => 'Romanian New Leu', 'code' => 'RON', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Croatian Kuna', 'code' => 'HRK', 'symbol' => 'kn', 'precision' => '2'],
            ['name' => 'Saudi Riyal', 'code' => 'SAR', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Japanese Yen', 'code' => 'JPY', 'symbol' => '¥', 'precision' => '0'],
            ['name' => 'Maldivian Rufiyaa', 'code' => 'MVR', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Costa Rican Colón', 'code' => 'CRC', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Pakistani Rupee', 'code' => 'PKR', 'symbol' => 'Rs ', 'precision' => '0'],
            ['name' => 'Polish Zloty', 'code' => 'PLN', 'symbol' => 'zł', 'precision' => '2'],
            ['name' => 'Sri Lankan Rupee', 'code' => 'LKR', 'symbol' => 'LKR', 'precision' => '2'],
            ['name' => 'Czech Koruna', 'code' => 'CZK', 'symbol' => 'Kč', 'precision' => '2'],
            ['name' => 'Uruguayan Peso', 'code' => 'UYU', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'Namibian Dollar', 'code' => 'NAD', 'symbol' => '$', 'precision' => '2'],
            ['name' => 'Tunisian Dinar', 'code' => 'TND', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Russian Ruble', 'code' => 'RUB', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Mozambican Metical', 'code' => 'MZN', 'symbol' => 'MT', 'precision' => '2'],
            ['name' => 'Omani Rial', 'code' => 'OMR', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Ukrainian Hryvnia', 'code' => 'UAH', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Macanese Pataca', 'code' => 'MOP', 'symbol' => 'MOP$', 'precision' => '2'],
            ['name' => 'Taiwan New Dollar', 'code' => 'TWD', 'symbol' => 'NT$', 'precision' => '2'],
            ['name' => 'Dominican Peso', 'code' => 'DOP', 'symbol' => 'RD$', 'precision' => '2'],
            ['name' => 'Chilean Peso', 'code' => 'CLP', 'symbol' => '$', 'precision' => '0'],
            ['name' => 'Icelandic Króna', 'code' => 'ISK', 'symbol' => 'kr', 'precision' => '2'],
            ['name' => 'Papua New Guinean Kina', 'code' => 'PGK', 'symbol' => 'K', 'precision' => '2'],
            ['name' => 'Jordanian Dinar', 'code' => 'JOD', 'symbol' => '', 'precision' => '2'],
            ['name' => 'Myanmar Kyat', 'code' => 'MMK', 'symbol' => 'K', 'precision' => '2'],
            ['name' => 'Peruvian Sol', 'code' => 'PEN', 'symbol' => 'S/ ', 'precision' => '2'],
        ];

        foreach ($currencies as $currency) {
            $country = Country::where('currency_code', $currency['code'])->first();
            if ($country) {
                $currency['iso_3166_2'] = $country->iso_3166_2;
            }
            Currency::create($currency);
        }

        $currencyRateService->updateRates();
    }
}
