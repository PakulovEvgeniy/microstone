<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldProduct extends Model
{
    protected $table = 'sold_product';
    protected $fillable = ['product_id1s'];
    public $timestamps = false;

    public static function getSoldProduct($id_1s)
    {
        $prd = SoldProduct::where(['product_id1s' => $id_1s])
            ->get();
        if ($prd) {
        	return $prd[0]->sold;
        }
        return 0;
    }
}
