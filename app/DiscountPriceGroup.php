<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountPriceGroup extends Model
{
    protected $table = 'discount_price_group';
    protected $fillable = ['id'];
    public $timestamps = false;
    public $incrementing = false;

    public static function getDiscounts($id)
	{
	  if (!$id) {
	  	return [];
	  }
	  $res = [];
	  $prd = DiscountPriceGroup::where(['group' => $id, 'status' => 1])
	        ->get();
	  foreach ($prd as $val) {
	  	$res[] = [
	  		'id' => $val->id,
	  		'qty' => $val->qty,
	  		'discount' => $val->discount
	  	];
	  }
	  return $res;
	}
}


