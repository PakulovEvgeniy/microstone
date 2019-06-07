<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Orders;
use App\Groups;
use App\Products as mod_Products;

class Products extends Controller
{
    public function index(Request $request) {
    	$param = $request->route('id');
    	$stat = 'ER';
    	$data = [];
    	if ($param == 'category') {
    		$stat = 'OK';
    		$data = Category::getCatalog('');
		}
		if ($param == 'orders') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$stat = 'OK';
				$data = Orders::getOrdersByChpu($qu['chpu']);
			}
		}
		if ($param == 'products_cat') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$stat = 'OK';
				$data = mod_Products::getProductsByChpu($qu['chpu']);
			}
		}
		if ($param == 'groups') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$stat = 'OK';
				$data = Groups::getGroupsByChpu($qu['chpu']);
			}
    	}
    	return ['status' => $stat, 'data' => $data];
    }
}
