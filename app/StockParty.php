<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockParty extends Model
{
    protected $table = 'stock_party';
    protected $fillable = ['product_id1s', 'party_id1s'];
    public $timestamps = false;
}
