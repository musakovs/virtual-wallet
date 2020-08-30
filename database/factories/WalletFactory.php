<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\Wallet::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'amount' => 1000 //do not change - used for tests, todo implement tests another way
    ];
});
