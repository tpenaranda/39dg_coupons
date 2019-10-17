<?php

use App\Coupon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Coupon::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomNumber(2),
        'name' => $faker->bothify('??????##'),
        'rules' => [
            'min_total' => $faker->randomNumber(2),
            'min_items' => $faker->randomNumber(2),
        ],
        'type' => $faker->randomElement([Coupon::TYPE_FIXED, Coupon::TYPE_PERCENT, Coupon::TYPE_MIXED, Coupon::TYPE_REJECTED]),
    ];
});
