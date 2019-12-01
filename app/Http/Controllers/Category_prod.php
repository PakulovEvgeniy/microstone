<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Orders;
use App\Groups;
use App\Products;
use App\Filters;
use App\FiltersDef;
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
		$filt = [];
		$filtDef = [];
		$itPage = [];
		$code = 200;
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
					$itPage = Products::getProductsCategoryPage($cat['id_1s'], $filters);
					$fltItems = Filters::getFilters($cat['id_1s']);
					$fltItemsDef = FiltersDef::getFiltersDef($cat['id_1s']);

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
					if (isset($filters['f']) && is_array($filters['f'])) {
						$fl = $filters['f'];
						foreach ($fl as $key => $val) {
							$k = array_search($key, array_column($fltItems, 'id_1s'));
							
							if ($k !== false) {
								$pr = $fltItems[$k];
								$ar = explode('-', $val);
								if ($pr['filter_type'] == 'Число') {
									if (count($ar) == 2) {
										$categoryFilters['f[' . $key . ']'] = $val;
										$fltItems[$k]['grp_data']['minValue'] = $ar[0];
										$fltItems[$k]['grp_data']['maxValue'] = $ar[1];
									}
								} else {
									$categoryFilters['f[' . $key . ']'] = $val;
									$fltItems[$k]['grp_data']['fChecked'] = $ar;
								}
							}

						}
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
					$filtDef = $fltItemsDef;
     			} 
     		} else {
				 $title = "Каталог товаров";
				 $code = 404;	
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
		if (count($itPage)) {
    		$dat['productsOfCategoryPage'] = $itPage;
    	}
    	if (count($filt)) {
    		$dat['filterItems'] = $filt;
    	}
    	if (count($filtDef)) {
    		$dat['filterItemsDef'] = $filtDef;
    	}
        $ssr = JSRender::render($request->path(), $dat);
        //dd($request->getRequestUri());
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return response()->view('app', ['ssr' => $ssr, 'title' => $title], $code);
    }
}
