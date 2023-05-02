<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    // relasi ke category product
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products');
    }

    public function getCategoryNameAttribute()
    {
        return $this->category;
    }
}
