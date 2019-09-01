<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Brands;
use JSRender;

class Manufacturer extends Controller
{
    public function get(Request $request) {
        //phpinfo();
        //return;
        $data = ['date' => '', 'items' => Category::getCatalog('')];
        $brands = ['date' => '', 'items' => Brands::getBrands()];
        
        $ssr = JSRender::render($request->path(), [
            'catalog' => $data,
            'brands' => $brands
        ]);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => 'Производители']);
    }
}
