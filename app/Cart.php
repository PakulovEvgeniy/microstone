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
			  ->select('product_id', 'characteristic_id', 'qty')
        ->get();
      $prod_arr = [];
      foreach ($row as $val) {
        $prod_arr[] = [
        	'id' => $val->product_id,
        	'characteristic' => $val->characteristic_id,
        	'qty' => $val->qty
        ];
      }
      return $prod_arr;
    }

  public static function AddToCartFromLocal($user_id, $arr, $need_add, $updt = false, $need_clear = false)
	{
		
		//$prod_arr = self::getCartListId($user_id);
		if ($need_clear) {
			Cart::where(['user_id' => $user_id])->delete();
		}
		
		foreach ($arr as $val) {
			/*$res = array_filter($prod_arr, function($k) use($val) {
    			return ($k['id'] == $val['id']) && ($k['characteristic'] == $val['characteristic']);
			});
			if (count($res)) {
				continue;
			}*/
			$prd = Products::where(['id' => $val['id']])->first();
			if (!$prd) {
				continue;
			}
			$charact = '';
			if ($val['characteristic']) {
				$charact = $val['characteristic'];
				$cr = Characteristics::where(['id' => $charact, 'product_id1s' => $prd->id_1s])->first();
				if (!$cr) {
					continue;
				}
			}
			$w = Cart::where(['characteristic_id' => $charact, 'product_id' => $prd->id, 'user_id' => $user_id])->first();
			if ($w) {
				if (!$need_add) {
					continue;
				}
				if ($updt) {
					$w->qty = $val['qty'] ? $val['qty'] : 1;
				} else {
					$w->qty += $val['qty'] ? $val['qty'] : 1;
				}
				
			} else {
				$w = new Cart();
				$w->user_id = $user_id;
				$w->product_id = $val['id'];
				$w->characteristic_id  = $val['characteristic'] ? $val['characteristic'] : '';
				$w->qty = $val['qty'] ? $val['qty'] : 1;
			}
			
			$w->save();
		}

		return self::getCartListId($user_id);
	}

	public static function deleteFromCart($user_id, $arr)
	{
		
		foreach ($arr as $val) {
			
			$charact = '';
			if ($val['characteristic']) {
				$charact = $val['characteristic'];
			}
			Cart::where(['characteristic_id' => $charact, 'product_id' => $val['id'], 'user_id' => $user_id])->delete(); 
		}

		return self::getCartListId($user_id);
	}

	public static function clearCart($user_id)
	{
		
		Cart::where(['user_id' => $user_id])->delete();

		return true;
	}
}
