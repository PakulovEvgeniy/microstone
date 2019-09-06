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

        $filters = $request->all();
        $page = 1;
        if (isset($filters['page'])) {
           $page = $filters['page'];
        }

        $id = $request->route('id');
        $curBrand = [];
        if ($id) {
           $curBrand = Brands::getBrandByChpu($id); 
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
        return view('app', ['ssr' => $ssr, 'title' => $title]);
    }
}
