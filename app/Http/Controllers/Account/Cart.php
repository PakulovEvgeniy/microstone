<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart as CartModel;

class Cart extends Controller
{
    public function addToCart(Request $request) {
    	if ($request->ajax()) {
	    	$this->validate($request, [
	        'id_list' => 'required|array',
	      ]);
        $param = $request->all();
        $new_cart = CartModel::AddToCartFromLocal($request->user()->id, $param['id_list'], true);
	    	
	    	return [
          'status' => 'OK',
          'message' => 'Товар(ы) успешно добавлены в корзину', 
         	'data' => [
        		'setCart' => $new_cart
         	]
        ];
    	}
    }
}
