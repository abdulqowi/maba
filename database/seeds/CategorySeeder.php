<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Elektronik'],
            ['name' => 'Fashion'],
            ['name' => 'Makanan dan Minuman'],
            ['name' => 'Kesehatan'],
            ['name' => 'Olahraga'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
