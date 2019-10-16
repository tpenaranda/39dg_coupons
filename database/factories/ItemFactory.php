<?php

use App\Item;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->numberBetween(1),
        'description' => $faker->sentence,
        'price' => $faker->randomFloat($decimals = 2, $min = 1, $max = 100),
    ];
});
