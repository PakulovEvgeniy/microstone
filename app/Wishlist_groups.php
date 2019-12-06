<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Wishlist;
use App\Products;

class Wishlist_groups extends Model
{
    protected $table = 'wishlist_groups';
    public $timestamps = false;

    public static function getAllGroups($user_id)
    {
    	$row = Wishlist_groups::where('user_id', '=',  $user_id)
			->get();

		$res = [];
		foreach ($row as $val) {
			$arr_wish = Wishlist::getWishlistAll($user_id, $val->id);
			$prod = Products::getProductsList($arr_wish);
			$total_price = 0;
			foreach ($prod as $v) {
				$total_price+=$v['price_with_discount'];
			}
			$res[] = [
			  'id' => $val->id,
			  'name' => $val->name,
			  'count' => count($arr_wish),
			  'total_price' => $total_price,
			  'archived' => $val->archived
			];
		}
		return $res;
    }
}
