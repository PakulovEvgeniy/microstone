<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PartyParams extends Model
{
    protected $table = 'party_params';
    protected $fillable = ['product_id1s','party_id1s', 'param_type_id'];
    public $timestamps = false;

    public static function getProductParams($id_1s) {
    	$prd = PartyParams::where(['product_id1s' => $id_1s])
    		->select('param_type_id', 'value')
    		->groupBy('param_type_id', 'value')
    		->get();
    	return $prd->toArray();
    }

    public static function getMinMaxValueForCategory($id_1s, $param_type_id)
    {
        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
            ->leftJoin('party_params', function ($join) use ($param_type_id)
            {
                $join->on('products.id_1s','=','party_params.product_id1s')
                ->where('party_params.param_type_id','=',$param_type_id);
            })
            ->select(DB::raw('MAX(CAST(value as DECIMAL(15,5))) as max, MIN(CAST(value as DECIMAL(15,5))) as min'))
            ->get();

        return ['min' => $prd[0]->min, 'max' => $prd[0]->max];
    }

    public static function getListParamsForCategory($id_1s, $param_type_id)
    {
        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
            ->leftJoin('party_params', function ($join) use ($param_type_id)
            {
                $join->on('products.id_1s','=','party_params.product_id1s')
                ->where('party_params.param_type_id','=',$param_type_id);
            })
            ->select('value')
            ->groupBy('value')
            ->orderBy('value')
            ->get();
        $res = [];
        foreach ($prd as $val) {
            $res[] = $val->value;
        }
        return $res;
    }
}
