<?php

use App\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'name' => $faker->colorName,
                'desc' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1, 1000),
                'quantity' => $faker->randomFloat(2, 1, 1000),
            ]);
        }
    }
}
