<?php

use App\Product;
use App\Category;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        $categories = Category::all();

        foreach ($products as $product) {
            $category = $categories->random();
            $category->products()->attach($product);
        }
    }
}
