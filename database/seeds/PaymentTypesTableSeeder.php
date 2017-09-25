<?php

use Illuminate\Database\Seeder;

use App\Domain\Model\Documents\Passive\GatewayType;
use App\Domain\Model\Documents\Passive\PaymentType;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gatewayTypeCreditCard = GatewayType::where('alias', 'credit_card')->first()->id;
        $gatewayTypeBankTransfer = GatewayType::where('alias', 'bank_transfer')->first()->id;
        $gatewayTypePayPal = GatewayType::where('alias', 'paypal')->first()->id;

        $paymentTypes = [
            ['name' => 'Apply Credit'],
            ['name' => 'Bank Transfer', 'gateway_type_id' => $gatewayTypeBankTransfer],
            ['name' => 'Cash'],
            ['name' => 'Debit', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'ACH', 'gateway_type_id' => $gatewayTypeBankTransfer],
            ['name' => 'Visa Card', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'MasterCard', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'American Express', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Discover Card', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Diners Card', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'EuroCard', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Nova', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Credit Card Other', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'PayPal', 'gateway_type_id' => $gatewayTypePayPal],
            ['name' => 'Google Wallet'],
            ['name' => 'Check'],
            ['name' => 'Carte Blanche', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'UnionPay', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'JCB', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Laser', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Maestro', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Solo', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Switch', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'iZettle', 'gateway_type_id' => $gatewayTypeCreditCard],
            ['name' => 'Swish', 'gateway_type_id' => $gatewayTypeBankTransfer],
            ['name' => 'Venmo'],
        ];

        foreach ($paymentTypes as $paymentType) {
            $record = PaymentType::where('name', '=', $paymentType['name'])->first();

            if ($record) {
                $record->name = $paymentType['name'];
                $record->gateway_type_id = ! empty($paymentType['gateway_type_id']) ? $paymentType['gateway_type_id'] : null;

                $record->save();
            } else {
                PaymentType::create($paymentType);
            }
        }
    }
}
