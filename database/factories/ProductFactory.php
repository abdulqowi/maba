<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\product;
use Faker\Generator as Faker;

$factory->define(product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'desc' => $this->faker->paragraph,
        'price' => $this->faker->randomNumber(4),
        'image' => $this->faker->image('public/assets/images', 640, 480, null, false),
    ];
});
