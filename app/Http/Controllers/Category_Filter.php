<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;

class Category_Filter extends Controller
{
    public function get(Request $request) {
     	$items = Category::getCatalog('');
     	$data = ['date' => '', 'items' => $items];
     	$id = $request->route('idF');
     	$title = 'Подобрать по параметрам';
     	if ($id) {
     		$cat = Category::getCategoryByChpu($id);
     		if ($cat) {
     			$title = 'Подобрать ' . $cat['name'] . ' по параметрам';
     		}
     	}

     	$dat = [
			'catalog' => $data,
			'nonVisibleAside' => true
    	];

    	$ssr = JSRender::render($request->path(), $dat);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => $title]);    	
    }
}
