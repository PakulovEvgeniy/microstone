<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Products;
use JSRender;
use App\Setting;

class HomeController extends Controller
{
    public function get(Request $request) {
        //phpinfo();
        //return;
        $data = ['date' => '', 'items' => Category::getCatalog('')];
        $ban = ['date' => '', 'items' => Category::getBanners()];
        $row = Setting::where('setting_id', '=', 'company_name')->first();
        $title = $row->name;

        $ssr = JSRender::render($request->path(), [
            'catalog' => $data,
            'banners' => $ban,
            'nonVisibleAside' => false,
            'popularProducts' => [
                'category' => Category::getPopularCategory(),
                'product' => Products:: getPopularProduct()
            ]
        ]);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => $title]);
    }
}
