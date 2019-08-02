<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiltersDiap extends Model
{
    protected $table = 'filters_diap';
    public $timestamps = false;

    public function filters()
    {
        return $this->belongsTo('App\Filters');
    }

    public static function getDiaps($id) {
    	$rows = FiltersDiap::where('filters_id','=',$id)->get();
    	$res = [];
    	foreach ($rows as $val) {
    		$res[] = [
    			'value1' => $val->value1,
    			'descr1' => $val->descr1,
    			'value2' => $val->value2,
    			'descr2' => $val->descr2
    		];
    	}
    	return $res;
    }
}
