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
        $code = 200;

        $filters = $request->all();
        $page = 1;
        if (isset($filters['page'])) {
           $page = $filters['page'];
        }

        $id = $request->route('id');
        $curBrand = [];
        if ($id) {
           $curBrand = Brands::getBrandByChpu($id);
           if (count($curBrand)==0) { 
               $code = 404;
           }
        }

        $title = 'Производители';

        $dat = [
            'catalog' => $data,
            'brands' => $brands,
            'nonVisibleAside' => true,
            'pageManuf' => $page
        ];
        if (count($curBrand)) {
            $dat['curBrand'] = $curBrand;
            $title = $curBrand['full_name'];
        }

        $ssr = JSRender::render($request->path(), $dat);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return response()->view('app', ['ssr' => $ssr, 'title' => $title], $code);
    }
}
