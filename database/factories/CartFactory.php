<?php

use App\{Cart, Item};
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'items' => function () use ($faker) {
            return factory(Item::class, $faker->numberBetween(3, 12))->make();
        }
    ];
});
