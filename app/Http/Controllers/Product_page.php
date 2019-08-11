<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;
use App\Products;

class Product_page extends Controller
{
    public function get(Request $request) {
        //phpinfo();
        //return;
        $id = $request->route('id');
        $title = 'Интернет-магазин "Микростоун"';
        $data = ['date' => '', 'items' => Category::getCatalog('')];
        $prod = [];
        if ($id) {
           $id_1s = Products::getId_1sByChpu($id);
           if ($id_1s) {
               $prod = Products::getProduct($id_1s);
               $title = $prod['name'];
           } 
        }
        $dat = [
			'catalog' => $data,
			'nonVisibleAside' => true
        ];
        if (count($prod)) {
    		$dat['product'] = $prod;
    	}

        $ssr = JSRender::render($request->path(), $dat);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => $title]);
    }
}
