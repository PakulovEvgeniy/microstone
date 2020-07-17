<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Wishlist;
use App\Products;
use App\PriceParty;

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
			
			$total_price = 0;
			foreach ($arr_wish as $v) {
				$prd = Products::find($v['id']);
				if (!$prd) {
					continue;
				}
				if ($prd->have_charact) {
					$p = PriceParty::getPriceForProductCharact($prd->id_1s);
					$k = array_search($v['characteristic'], array_column($p, 'id'));
					if ($k) {
						$total_price+=$p[$k]['price'];
					}
				} else {
					$p = PriceParty::getMinMaxPriceForProduct($prd->id_1s);
					$total_price+=$p['min'];
				}
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
