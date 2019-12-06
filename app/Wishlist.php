<?php

namespace App;

use App\Products;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';
    public $timestamps = false;

    public static function getWishlistAll($user_id, $grp = 0)
    {
    	$row = Wishlist::where([['user_id', '=',  $user_id], ['wish_group_id', '=', $grp]])
			->select('product_id')
			->get();
		$prod_arr = [];
		foreach ($row as $val) {
			$prod_arr[] = $val->product_id;
		}
		return $prod_arr;
    }

    public static function AddToWishListFromLocal($user_id, $arr)
	{
		
		$prod_arr = self::getWishlistAll($user_id);
		
		$row = Products::whereIn('id', $arr)
			->whereNotIn('id', $prod_arr)
			->select('id')->get();

		foreach ($row as $val) {
			$w = new Wishlist();
			$w->user_id = $user_id;
			$w->product_id = $val->id;
			$w->save();
		}

		return self::getWishlistAll($user_id, 0);
	}
}

