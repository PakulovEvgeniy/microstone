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
            ->select(DB::raw('MAX(CASE WHEN price is null THEN 0 ELSE price END) as max, MIN(CASE WHEN price is null THEN 0 ELSE price END) as min'))
            ->get();

        return ['min' => ceil($prd[0]->min), 'max' => round($prd[0]->max)];
    }

    public static function getProductsByFiltr($id_1s, $val)
    {
        $ar = explode('-', $val);
        if (count($ar)!=2) {
            return false;
        }

        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
            ->leftJoin('price_party','products.id_1s','=','price_party.product_id1s')
            ->select('id_1s')
            ->whereRaw('CASE WHEN price is null THEN 0 ELSE price END >= ?', [$ar[0]])
            ->whereRaw('FLOOR(CASE WHEN price is null THEN 0 ELSE price END) <= ?', [$ar[1]])
            ->groupBy('id_1s')
            ->get();

        $res = [];
        foreach ($prd as $val) {
            $res[] = $val->id_1s;
        }
        return $res;
    }
}
