<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiltersDefParams extends Model
{
    protected $table = 'filters_def_params';
    public $timestamps = false;

    public function filters_def()
    {
        return $this->belongsTo('App\FiltersDef');
    }

     public static function getParamsForFiltersDef($id) {
     	$rows = FiltersDefParams::where('filters_def_id','=',$id)->get();
     	$res = [];
     	foreach ($rows as $val) {
     		$res[] = [
                'filters_id_1s' => $val->filters_id_1s,
                'value' => $val->value
            ];
     	}
     	return $res;
     }
}
