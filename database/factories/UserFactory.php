<?php

use Faker\Generator as Faker;

$factory->define(\App\Domain\Model\Authentication\Account\Account::class, function (Faker $faker) {
    $company = $faker->company;

    return [
        'uuid' => $faker->uuid,
        'name' => $company,
        'site_address' => str_slug($company)
    ];
});

$factory->define(\App\Domain\Model\Authentication\Company\Company::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->company,
        'email' => $faker->safeEmail
    ];
});

$factory->define(\App\Domain\Model\Authentication\User\User::class, function (Faker $faker) {
    static $password;

    return [
        'uuid' => $faker->uuid,
        'username' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret')
    ];
});