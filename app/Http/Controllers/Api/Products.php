<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Orders;
use App\Groups;
use App\Products as mod_Products;
use App\Filters;
use App\FiltersDef;
use App\Brands;
use App\Wishlist;
use Auth;


class Products extends Controller
{
    public function index(Request $request) {
    	$param = $request->route('id');
    	$stat = 'ER';
    	$data = [];
    	if ($param == 'category') {
    		$stat = 'OK';
    		$data = [
    			'setCatalog' => Category::getCatalog('')
    		];
		}
		if ($param == 'orders') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$stat = 'OK';
				$data = [
					'setOrders' => Orders::getOrdersByChpu($qu['chpu'])
				];
			}
		}
		if ($param == 'curbrand') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$stat = 'OK';
				$data = [
				 'setCurBrand' =>	Brands::getBrandByChpu($qu['chpu'])
				];
			}
		}
		//if ($param == 'products_cat') {
		//	$qu = $request->all();
		//	if (isset($qu['chpu'])) {
		//		$stat = 'OK';
		//		$data = mod_Products::getProductsByChpu($qu['chpu']);
		//	}
		//}
		if ($param == 'productpage') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$id_1s = mod_Products::getProductByChpu($qu['chpu']);
				if ($id_1s) {
					$stat = 'OK';
					$data = [
						'setProductsOfCategoryPage' => mod_Products::getProductsCategoryPage($id_1s, $qu)
					];
				}
			}
		}
		
		if ($param == 'groups') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$stat = 'OK';
				$data = [
					'setGroups' => Groups::getGroupsByChpu($qu['chpu'])
				];
			}
		}
		if ($param == 'popular') {
			//$qu = $request->all();
			//if (isset($qu['chpu'])) {
			$stat = 'OK';
			$data = [
			  'setPopularProducts' =>	[
				'category' => Category::getPopularCategory(),
				'product' => mod_Products::getPopularProduct()
			  ]
			];
			//}
		}
		if ($param == 'brands') {
			
			$stat = 'OK';
			$data = [
				'setBrands' => Brands::getBrands()
			];
		}
		if ($param == 'product') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$id_1s = mod_Products::getId_1sByChpu($qu['chpu']);
				if ($id_1s) {
					$stat = 'OK';
					$data = [
						'setProduct' => mod_Products::getProduct($id_1s)
					];
				}
			}
		}

		if ($param == 'wishlist') {
			$qu = $request->all();
			if (isset($qu['pr']) && is_array($qu['pr'])) {
				$stat = 'OK';
				$data = [
					'setWishlistProducts' => mod_Products::getProductsList($qu['pr']),
					'setWishGroup' => null
				];
			}
		}

		if ($param == 'compare') {
			$qu = $request->all();
			if (isset($qu['pr']) && is_array($qu['pr'])) {
				$stat = 'OK';
				$data = [
					'setCompareProducts' => mod_Products::getProductsList($qu['pr'])
				];
			}
		}

		
		if ($param == 'banners') {
			$stat = 'OK';
    		$data = [
    			'setBanners' => Category::getBanners()
    		];
		}

		if ($param == 'filters') {
			$qu = $request->all();
			if (isset($qu['chpu'])) {
				$stat = 'OK';
				$data = [
					'setFilters' => [
						'filters' => Filters::getFiltersByChpu($qu['chpu']),
						'filtersDef' => FiltersDef::getFiltersDefByChpu($qu['chpu'])
					]
				];
			}
		}
    	return ['status' => $stat, 'data' => $data];
    }
}
