<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Orders;
use App\Groups;
use App\Products;
use App\Filters;
use JSRender;

class Category_prod extends Controller
{
    public function get(Request $request) {
     	//phpinfo();
     	//return;
     	$items = Category::getCatalog('');
     	$data = ['date' => '', 'items' => $items];
     	$id = $request->route('id');
        $categoryFilters = [];
		$nonVis = false;
		$topf = [];
		$prods = [];
		$filt = [];
     	if ($id) {
     		$cat = Category::getCategoryByChpu($id);
     		if ($cat) {
     			$title = $cat['name'];
     			$count = Category::getCountChild($cat['id_1s']);
     			if ($count == 0) {
     				$nonVis = true;
					$categoryFilters = [];
					$filters = $request->all();
					$ordItems = Orders::getOrders($cat['id_1s']);
					$grpItems = Groups::getGroups($cat['id_1s']);
					$prods = Products::getProductsCategory($cat['id_1s']);
					$fltItems = Filters::getFilters($cat['id_1s']);

					if (isset($filters['order'])) {
						$key = array_search($filters['order'], array_column($ordItems, 'id'));
						$categoryFilters['order'] = ($key === false) ? $ordItems[0]['id'] : $filters['order'];
					} 
					if (isset($filters['group'])) {
						$key = array_search($filters['group'], array_column($grpItems, 'id'));
						$categoryFilters['group'] = ($key === false) ? $grpItems[0]['id'] : $filters['group'];
					} 
					if (isset($filters['stock'])) {
						$key = array_search($filters['stock'], [1,2,3]);
						$categoryFilters['stock'] = ($key === false) ? 1 : $filters['stock'];
					}
					if (isset($filters['mode'])) {
						$categoryFilters['mode'] = ($filters['mode'] == 'tile') ? 'tile' : 'simple';
					}
					if (isset($filters['page'])) {
						$categoryFilters['page'] = $filters['page'];
					}
					if (isset($filters['q'])) {
						$categoryFilters['q'] = $filters['q'];
					}
					

					$topf = [
						'order' => [
							'name' => 'order',
							'date' => '',
							'caption' => 'Сортировать:',
							'items' => $ordItems
						],
						'group' => [
							'name' => 'group',
							'date' => '',
							'caption' => 'Группировать:',
							'items' => $grpItems
						],
						'stock' => [
							'name' => 'stock',
							'caption' => 'Наличие:',
							'items' => [
								[
									'id' => 1,
									'name' => 'В наличии и под заказ'
								],
								[
									'id' => 2,
									'name' => 'В наличии'
								],
								[
									'id' => 3,
									'name' => 'Под заказ'
								]
							]
						]
					];

					$filt = $fltItems;
     			} 
     		} else {
     			$title = "Каталог товаров";	
     		}
     	} else {
     		$title = "Каталог товаров";
     	}
     	$dat = [
			'catalog' => $data,
			'nonVisibleAside' => $nonVis
    	];
    	if (count($categoryFilters)) {
    		$dat['categoryFilters'] = $categoryFilters;
    	}
    	if (count($topf)) {
    		$dat['topFilters'] = $topf;
    	}
    	if (count($prods)) {
    		$dat['productsOfCategory'] = $prods;
    	}
    	if (count($filt)) {
    		$dat['filterItems'] = $filt;
    	}
        $ssr = JSRender::render($request->path(), $dat);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => $title]);
    }
}
