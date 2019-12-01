<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Filters;
use JSRender;

class Category_Filter extends Controller
{
    public function get(Request $request) {
     	$items = Category::getCatalog('');
     	$data = ['date' => '', 'items' => $items];
     	$id = $request->route('idF');
		$title = 'Подобрать по параметрам';
		$categoryFilters = [];
		$filt = [];
		$code = 200;
     	if ($id) {
     		$cat = Category::getCategoryByChpu($id);
     		if ($cat) {
				  $title = 'Подобрать ' . $cat['name'] . ' по параметрам';
				  $count = Category::getCountChild($cat['id_1s']);
     			  if ($count == 0) {
					$filt = Filters::getFilters($cat['id_1s']);
					$filters = $request->all();
					if (isset($filters['order'])) {
						$categoryFilters['order'] = $filters['order'];
					} 
					if (isset($filters['group'])) {
						$categoryFilters['group'] =  $filters['group'];
					} 
					if (isset($filters['stock'])) {
						$categoryFilters['stock'] = $filters['stock'];
					}
					if (isset($filters['mode'])) {
						$categoryFilters['mode'] = $filters['mode'];
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
							$k = array_search($key, array_column($filt, 'id_1s'));
							
							if ($k !== false) {
								$pr = $filt[$k];
								$ar = explode('-', $val);
								if ($pr['filter_type'] == 'Число') {
									if (count($ar) == 2) {
										$categoryFilters['f[' . $key . ']'] = $val;
										$filt[$k]['grp_data']['minValue'] = $ar[0];
										$filt[$k]['grp_data']['maxValue'] = $ar[1];
									}
								} else {
									$categoryFilters['f[' . $key . ']'] = $val;
									$filt[$k]['grp_data']['fChecked'] = $ar;
								}
							}

						}
					}
				  }
     		} else {
				$code = 404;
			}
     	}

     	$dat = [
			'catalog' => $data,
			'nonVisibleAside' => true
		];
		
		if (count($filt)) {
    		$dat['filterItems'] = $filt;
    	}
    	if (count($categoryFilters)) {
    		$dat['categoryFilters'] = $categoryFilters;
    	}

    	$ssr = JSRender::render($request->path(), $dat);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return response()->view('app', ['ssr' => $ssr, 'title' => $title], $code);    	
    }
}
