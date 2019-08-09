<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;

class HomeController extends Controller
{
    public function get(Request $request) {
        //phpinfo();
        //return;
        $data = ['date' => '', 'items' => Category::getCatalog('')];
        $ban = ['date' => '', 'items' => Category::getBanners()];

        $ssr = JSRender::render($request->path(), [
            'catalog' => $data,
            'banners' => $ban
        ]);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr, 'title' => 'Интернет-магазин "Микростоун"']);
    }
}
