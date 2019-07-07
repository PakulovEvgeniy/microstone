<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Products;
use DB;

class PriceParty extends Model
{
    protected $table = 'price_party';
    protected $fillable = ['product_id1s', 'party_id1s', 'min_qty' , 'party_type'];
    public $timestamps = false;

    public static function getMinMaxPriceForCategory($id_1s)
    {
        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
            ->leftJoin('price_party','products.id_1s','=','price_party.product_id1s')
            ->select(DB::raw('MAX(price) as max, MIN(price) as min'))
            ->get();

        return ['min' => $prd[0]->min, 'max' => $prd[0]->max];
    }
}
