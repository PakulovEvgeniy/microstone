<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsDescriptions extends Model
{
    protected $table = 'products_descriptions';
    protected $fillable = ['products_id'];

    public function products()
    {
        return $this->belongsTo('App\Products');
    }
}
