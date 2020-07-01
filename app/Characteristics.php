<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use JSRender;

class Characteristics extends Model
{
    protected $table = 'characteristics';
    protected $fillable = ['id'];
    public $incrementing = false;
    public $timestamps = false;

    public static function getCharacteristics($id_1s)
	{
	  if (!$id_1s) {
	  	return [];
	  }
	  $res = [];
	  $prd = Characteristics::where(['product_id1s' => $id_1s])
	        ->get();
	  foreach ($prd as $val) {
	  	$res[] = [
	  		'id' => $val->id,
	  		'name' => $val->name,
	  		'image' => $val['image'] ? JSRender::resizeImage('catalog/'.$val['image'],30,30) : ''
	  	];
	  }
	  return $res;
	}
}
