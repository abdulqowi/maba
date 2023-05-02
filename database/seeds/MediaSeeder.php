<?php

use App\Media;
use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();

        foreach ($products as $product) {
            Media::create([
                'product_id' => $product->id,
                'url' => Str::random(12),
            ]);
        }
    }
}
