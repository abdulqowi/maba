<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products');
    }
}
