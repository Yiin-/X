<?php

use Illuminate\Database\Seeder;

use App\Domain\Model\Documents\Passive\GatewayType;

class GatewayTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gateway_types = [
            ['alias' => 'credit_card', 'name' => 'Credit Card'],
            ['alias' => 'bank_transfer', 'name' => 'Bank Transfer'],
            ['alias' => 'paypal', 'name' => 'PayPal'],
            ['alias' => 'bitcoin', 'name' => 'Bitcoin'],
            ['alias' => 'dwolla', 'name' => 'Dwolla'],
            ['alias' => 'custom', 'name' => 'Custom'],
        ];

        foreach ($gateway_types as $gateway_type) {
            $record = GatewayType::where('name', '=', $gateway_type['name'])->first();
            if (! $record) {
                GatewayType::create($gateway_type);
            }
        }
    }
}
