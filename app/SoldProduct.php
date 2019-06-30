<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldProduct extends Model
{
    protected $table = 'sold_product';
    protected $fillable = ['product_id1s'];
    public $timestamps = false;
}
