<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Products;

class Cart extends Model
{
    protected $table = 'cart';
    public $timestamps = false;

    public static function getCartListId($user_id)
    {
      $row = self::where([['user_id', '=',  $user_id]])
			  ->select('product_id')
        ->get();
      $prod_arr = [];
      foreach ($row as $val) {
        $prod_arr[] = $val->product_id;
      }
      return $prod_arr;
    }

  public static function AddToCartFromLocal($user_id, $arr)
	{
		
		$prod_arr = self::getCartListId($user_id);
		
		$row = Products::whereIn('id', $arr)
			->whereNotIn('id', $prod_arr)
			->select('id')->get();

		foreach ($row as $val) {
			$w = new Cart();
			$w->user_id = $user_id;
			$w->product_id = $val->id;
			$w->save();
		}

		return self::getCartListId($user_id);
	}
}
