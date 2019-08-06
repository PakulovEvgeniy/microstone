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
		$filt = [];
     	if ($id) {
     		$cat = Category::getCategoryByChpu($id);
     		if ($cat) {
				  $title = 'Подобрать ' . $cat['name'] . ' по параметрам';
				  $count = Category::getCountChild($cat['id_1s']);
     			  if ($count == 0) {
					$filt = Filters::getFilters($cat['id_1s']);
				  }
     		}
     	}

     	$dat = [
			'catalog' => $data,
			'nonVisibleAside' => true
		];
		
		if (count($filt)) {
    		$dat['filterItems'] = $filt;
    	}

    	$ssr = JSRender::render($request->path(), $dat);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => $title]);    	
    }
}
