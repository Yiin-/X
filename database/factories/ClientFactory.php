<?php

use Faker\Generator as Faker;

/**
 * Client
 */
$factory->define(App\Domain\Model\Documents\Client\Client::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\it_IT\Company($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Company($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));

    return [
        'name' => $faker->company,
        'registration_number' => $faker->numberBetween(100000, 500000),
        'vat_number' => $faker->randomElement(['IE9700053D', 'IE6336982T', 'IE6336982T', 'IE6346967G']),
        'website' => $faker->domainName,
        'phone' => $faker->phoneNumber,
        'description' => $faker->text,
        'address1' => $faker->streetName,
        'address2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postal_code' => $faker->postcode,
        'notes' => $faker->text,
        'industry_id' => $faker->randomElement(App\Domain\Model\Documents\Passive\Industry::all()->pluck('id')->toArray()),
        'country_id' => $faker->randomElement(App\Domain\Model\Documents\Passive\Country::all()->pluck('id')->toArray()),
        'language_id' => 1,
        'company_size_id' => $faker->randomElement(App\Domain\Model\Documents\Passive\CompanySize::all()->pluck('id')->toArray()),
        'payment_terms' => $faker->randomElement([7, 14, 15, 30, 60, 90, 0]),
        'currency_code' => $faker->randomElement(['EUR', 'USD', 'GBP']),

        'contacts' => factory(App\Domain\Model\Documents\Client\ClientContact::class, $faker->numberBetween(1, 3))->make()
    ];
});

/**
 * Client Contact
 */
$factory->define(App\Domain\Model\Documents\Client\ClientContact::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'job_position' => $faker->jobTitle
    ];
});

/**
 * Vendor
 */
$factory->define(App\Domain\Model\Documents\Vendor\Vendor::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\it_IT\Company($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Company($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));

    return [
        'company_name' => $faker->company,
        'registration_number' => $faker->numberBetween(100000, 500000),
        'vat_number' => $faker->randomElement(['IE9700053D', 'IE6336982T', 'IE6336982T', 'IE6346967G']),
        'website' => $faker->domainName,
        'phone' => $faker->phoneNumber,
        'description' => $faker->text,
        'address1' => $faker->streetName,
        'address2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'country_id' => $faker->randomElement(App\Domain\Model\Documents\Passive\Country::all()->pluck('id')->toArray()),
        'postal_code' => $faker->postcode,
        'notes' => $faker->text,
        'currency_code' => $faker->randomElement(['EUR', 'USD', 'GBP']),

        'contacts' => factory(App\Domain\Model\Documents\Vendor\VendorContact::class, $faker->numberBetween(1, 3))->make()
    ];
});

/**
 * Vendor Contact
 */
$factory->define(App\Domain\Model\Documents\Vendor\VendorContact::class, function (Faker $faker) {
    return factory(App\Domain\Model\Documents\Client\ClientContact::class)->make()->toArray();
});

/**
 * Product
 */
$factory->define(App\Domain\Model\Documents\Product\Product::class, function (Faker $faker) {
    return [
        'name' => title_case($faker->randomElement([
            'beef', 'teddies', 'hair brush', 'sharpie', 'canvas', 'carrots', 'spring', 'street lights',
            'tire swing', 'towel', 'television', 'flag', 'shirt', 'bag', 'hair tie', 'piano', 'cup',
            'milk', 'candy wrapper', 'toe ring', 'window', 'lamp', 'sponge', 'soy sauce packet',
            'face wash', 'doll', 'chalk', 'tooth picks', 'paint brush', 'lip gloss', 'video games',
            'shawl', 'drill press', 'rug', 'radio', 'key chain', 'deodorant', 'washing machine', 'watch',
            'slipper', 'glass', 'mirror', 'tomato', 'CD', 'door', 'sketch pad', 'zipper', 'eye liner',
            'ipod', 'headphones', 'perfume', 'spoon', 'bowl', 'mouse pad', 'ring', 'button', 'balloon',
            'floor', 'rusty nail', 'chocolate', 'clock', 'bed', 'thermometer', 'book', 'checkbook', 'car',
            'bracelet', 'remote', 'tv', 'picture frame', 'bread', 'helmet', 'rubber band', 'keyboard',
            'toothpaste', 'rubber duck', 'clay pot', 'grid paper', 'seat belt', 'tree', 'wallet', 'soap',
            'vase', 'sailboat', 'fridge', 'sofa', 'cookie jar', 'nail file', 'bottle', 'bananas', 'twezzers',
            'USB drive', 'puddle', 'needle', 'purse', 'credit card', 'cat', 'house', 'sticky note', 'chapter book',
            'coasters', 'socks', 'thermostat', 'cork', 'money', 'leg warmers', 'bookmark', 'paper', 'sidewalk',
            'apple', 'tissue box', 'packing peanuts', 'pool stick', 'cell phone', 'water bottle', 'computer',
            'knife', 'table', 'keys', 'photo album', 'pillow', 'flowers', 'hanger', 'magnet', 'eraser', 'twister',
            'shovel', 'fake flowers', 'cinder block', 'lace', 'pencil', 'shoe lace', 'outlet', 'boom box', 'sandal',
            'glow stick', 'couch', 'food', 'lotion', 'nail clippers', 'brocolli', 'shoes', 'truck', 'screw',
            'bottle cap', 'stockings', 'ice cube tray', 'lamp shade', 'white out', 'clothes', 'mp3 player', 'fork',
            'model car', 'candle', 'shampoo', 'clamp', 'sun glasses', 'speakers', 'pen', 'drawer', 'bow', 'chair',
            'phone', 'monitor', 'plate', 'buckel', 'plastic fork', 'greeting card', 'air freshener', 'glasses', 'wagon',
            'soda can', 'pants', 'thread', 'stop sign', 'mop', 'sand paper', 'playing card', 'conditioner', 'camera',
            'newspaper', 'blouse', 'desk', 'controller', 'charger', 'scotch tape', 'toilet', 'toothbrush', 'box', 'blanket'
        ])),
        'price' => $faker->randomFloat(2, 0.5, 60),
        'currency_code' => 'EUR',
        'identification_number' => $faker->randomElement([$faker->ean13, $faker->ean8, $faker->isbn13, $faker->isbn10]),
        'qty' => $faker->numberBetween(0, 200),
        'description' => $faker->text
    ];
});

/**
 * Invoice
 */
$factory->define(App\Domain\Model\Documents\Invoice\Invoice::class, function (Faker $faker) {
    $clients = App\Domain\Model\Documents\Client\Client::all()->pluck('uuid')->toArray();
    return [
        'client_uuid' => count($clients) ? $faker->randomElement($clients) : null
    ];
});