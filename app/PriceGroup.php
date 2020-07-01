<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceGroup extends Model
{
    protected $table = 'price_group';
    public $timestamps = false;
    protected $fillable = ['id_1s'];
}
