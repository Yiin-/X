<?php

use Faker\Generator as Faker;

$factory->define(App\Domain\Model\Client\Client::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'registration_number' => $faker->numberBetween(100000, 500000),
        'vat_number' => $faker->vat(false),
        'website' => $faker->domainName,
        'email' => $faker->email,
        'description' => $faker->text,
        'address1' => $faker->streetName,
        'address2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postal_code' => $faker->postcode,
        'notes' => $faker->text
    ];
});
