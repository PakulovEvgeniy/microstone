<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFiles extends Model
{
    protected $table = 'product_files';
    protected $fillable = ['product_id'];
    public $timestamps = false;
}
