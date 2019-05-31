<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
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
     	if ($id) {
     		$cat = Category::getCategoryByChpu($id);
     		if ($cat) {
     			$title = $cat['name'];
     			$count = Category::getCountChild($cat['id_1s']);
     			if ($count == 0) {
     				$nonVis = true;
                    $categoryFilters = $request->all();
     			} 
     		} else {
     			$title = "Каталог товаров";	
     		}
     	} else {
     		$title = "Каталог товаров";
     	}

        $ssr = JSRender::render($request->path(), ['catalog' => $data,
        	'nonVisibleAside' => $nonVis, 'categoryFilters' => $categoryFilters
    	]);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => $title]);
    }
}
