<?php

namespace App;

use App\Products;

use Illuminate\Database\Eloquent\Model;
use App\Characteristics;

class Wishlist extends Model
{
    protected $table = 'wishlist';
    public $timestamps = false;

    public static function getWishlistAll($user_id, $grp = 0)
    {
    	$row = Wishlist::where([['user_id', '=',  $user_id], ['wish_group_id', '=', $grp]])
			->select('product_id', 'characteristic_id')
			->get();
		$prod_arr = [];
		foreach ($row as $val) {
			$prod_arr[] = [
				'id' => $val->product_id,
				'characteristic' => $val->characteristic_id
			];
		}
		return $prod_arr;
    }

    public static function AddToWishListFromLocal($user_id, $arr)
	{
		
		$prod_arr = self::getWishlistAll($user_id);
		
		foreach ($arr as $val) {
			$res = array_filter($prod_arr, function($k) use($val) {
    			return ($k['id'] == $val['id']) && ($k['characteristic'] == $val['characteristic']);
			});
			if (count($res)) {
				continue;
			}
			$prd = Products::where(['id' => $val['id']])->first();
			if (!$prd) {
				continue;
			}
			if ($val['characteristic']) {
				$cr = Characteristics::where(['id' => $val['characteristic'], 'product_id1s' => $prd->id_1s])->first();
				if (!$cr) {
					continue;
				}
			}
			$w = new Wishlist();
			$w->user_id = $user_id;
			$w->product_id = $val['id'];
			$w->characteristic_id  = $val['characteristic'] ? $val['characteristic'] : '';
			$w->save();
		}

		return self::getWishlistAll($user_id, 0);
	}

	public static function AddToWishList($user_id, $prod, $group_id = 0)
	{
		$row = self::where([
			['user_id', '=', $user_id],
			['product_id', '=', $prod['id']],
			['characteristic_id', '=', $prod['characteristic']],
			['wish_group_id', '=', $group_id]
		])->first();
		if (!$row) {
			$neww = new Wishlist();
			$neww->user_id = $user_id;
			$neww->product_id = $prod['id'];
			$neww->characteristic_id = $prod['characteristic'];
			$neww->wish_group_id = $group_id;
			$neww->save();
			return $neww->id;
		}
		return false;
	}
}

