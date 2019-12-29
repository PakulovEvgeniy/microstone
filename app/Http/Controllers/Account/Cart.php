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
        CartModel::AddToCartFromLocal($request->user()->id, $param['id_list']);
	    	
	    	return [
          'status' => 'OK',
          'message' => 'Товар(ы) успешно добавлены в корзину', 
         	'data' => [
        		'addToItemCart' => $param['id_list']
         	]
        ];
    	}
    }
}
