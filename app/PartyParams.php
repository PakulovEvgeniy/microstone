<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Filters;
use DB;

class PartyParams extends Model
{
    protected $table = 'party_params';
    protected $fillable = ['product_id1s','party_id1s', 'param_type_id'];
    public $timestamps = false;

    public static function getProductParams($id_1s) {
    	$prd = PartyParams::where(['product_id1s' => $id_1s])
            ->leftJoin('param_type','party_params.param_type_id','=','param_type.id')
    		->select('party_params.param_type_id as param_type_id', 'value', 'param_type.name as name')
    		->groupBy('party_params.param_type_id', 'value', 'param_type.name')
    		->get();
    	return $prd->toArray();
    }

    public static function getArrayOfParams($id_1s, $par_t) {
    	$prd = PartyParams::where(['product_id1s' => $id_1s, 'param_type_id' => $par_t])
    		->select('value')
    		->groupBy('value')
            ->get();
        $res = [];
        foreach ($prd as $val) {
            $res[] = $val->value;
        }
    	return $res;
    }

    public static function getManufacturesByProduct($id_1s) {
        $type_pr = ParamTypes::where('name', 'Производитель')->first();
        $type_pr = $type_pr->id;

    	$prd = PartyParams::where(['product_id1s' => $id_1s, 'param_type_id' => $type_pr])
            ->select('value', 'value_id', 'full_name', 'logo')
            ->leftJoin('brands','party_params.value_id','=','brands.id')
    		->groupBy('value', 'value_id', 'full_name', 'logo')
            ->get();
        $res = [];
        foreach ($prd as $val) {
            $res[] = [
              'id' => $val->value_id,
              'name' => $val->value,
              'full_name' => $val->full_name,
              'logo' => $val->logo
            ];
        }
    	return $res;
    }

    public static function getMinMaxValueForCategory($id_1s, $param_type_id)
    {
        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
            ->leftJoin('party_params', function ($join) use ($param_type_id)
            {
                $join->on('products.id_1s','=','party_params.product_id1s')
                ->where('party_params.param_type_id','=',$param_type_id);
            })
            ->select(DB::raw('MAX(CAST(CASE WHEN value is null THEN 0 ELSE value END as DECIMAL(15,5))) as max, MIN(CAST(CASE WHEN value is null THEN 0 ELSE value END as DECIMAL(15,5))) as min'))
            ->get();

        return ['min' => ceil($prd[0]->min), 'max' => round($prd[0]->max)];
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
        $flNeopr = false;
        foreach ($prd as $val) {
            if (!$val->value) {
                $flNeopr = true;
                continue;
            }
            $res[] = $val->value;
        }
        if ($flNeopr) {
            $res[] = 'Неопределено';
        }
        return $res;
    }

    public static function getProductsByFiltr($id_1s, $key, $val)
    {
        

        $ar = explode('-', $val);
        $fl = Filters::where('id_1s', $key)->first();
        if (!$fl) {
            return false;
        }
        if (($fl->filter_type=='Число' && count($ar)!=2) || !$fl->param_type_id)  {
            return false;
        }
        
        $prd = Products::where(['products.status' => 1, 'parent_id' => $id_1s])
            ->leftJoin('party_params','products.id_1s','=','party_params.product_id1s')
            ->select('product_id1s');
        if ($fl->filter_type == 'Строка') {
            $fVal = PartyParams::getListParamsForCategory($id_1s, $fl->param_type_id);
            $val = [];
            foreach ($ar as $v) {
                try {
                    $val[] = $fVal[$v];
                } catch (\Exception $e) {}
            }
            $prd = $prd->whereIn('value', $val);
        } else {
            $prd = $prd
            ->whereRaw('not value is null')
            ->whereRaw('CAST(value as DECIMAL(15,5)) >= ?', [$ar[0]])
            ->whereRaw('FLOOR(CAST(value as DECIMAL(15,5))) <= ?', [$ar[1]]);
        }
            
        $prd = $prd->groupBy('product_id1s')
            ->get();

        $res = [];
        foreach ($prd as $val) {
            $res[] = $val->product_id1s;
        }
        return $res;
    }
}
