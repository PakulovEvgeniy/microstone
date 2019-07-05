<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
