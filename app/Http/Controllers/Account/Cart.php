<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart as CartModel;
use App\Category;
use App\Products;
use Auth;
use JSRender;

class Cart extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('get');
    }

    public function get(Request $request) {
      $dat = [];
      
      if (Auth::check()) {
        $arr_cart = CartModel::getCartListId($request->user()->id);
        $dat['cart'] = [
          'items' => $arr_cart,
          'products' => Products::getProductsList(array_column($arr_cart, 'id'),false,true)
        ];
      }

      if ($request->ajax()) {
        if (Auth::check()) {
          return [
            'status' => 'OK',
            'data' => [
              'setCart' => $dat['cart']['items'],
              'setCartProducts' => $dat['cart']['products']
            ]
          ];
        } else {
          $this->validate($request, [
            'items' => 'required|array'
          ]);
          $param = $request->all();
          return [
            'status' => 'OK',
            'data' => [
              'setCartProducts' => Products::getProductsList(array_column($param['items'], 'id'),false,true)
            ]
          ];
        }
      }

      $title = 'Моя корзина';
      $data = ['date' => '', 'items' => Category::getCatalog('')];

      $dat['catalog'] = $data;
      $dat['nonVisibleAside'] = true;

      $ssr = JSRender::render($request->getRequestUri(), $dat);

       //dd($request->path());
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => $title]);
    }

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

    public function editToCart(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'id_list' => 'required|array',
        ]);
        $param = $request->all();
        $new_cart = CartModel::AddToCartFromLocal($request->user()->id, $param['id_list'], true, true);
        
        return [
          'status' => 'OK',
          'data' => [
            'setCart' => $new_cart
          ]
        ];
      }
    }

    public function deleteFromCart(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'id_list' => 'required|array',
        ]);
        $param = $request->all();
        $new_cart = CartModel::deleteFromCart($request->user()->id, $param['id_list']);
        
        return [
          'status' => 'OK',
          'data' => [
            'setCart' => $new_cart
          ]
        ];
      }
    }

    public function clearCart(Request $request) {
      if ($request->ajax()) {
        CartModel::clearCart($request->user()->id);
        return [
          'status' => 'OK'
        ];
      }
    }
}
