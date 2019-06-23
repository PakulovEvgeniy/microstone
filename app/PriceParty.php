<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceParty extends Model
{
    protected $table = 'price_party';
    protected $fillable = ['product_id1s', 'party_id1s', 'min_qty' , 'party_type'];
    public $timestamps = false;
}
