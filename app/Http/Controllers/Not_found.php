<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;

class Not_found extends Controller
{
    

    public function get(Request $request) {
        //phpinfo();
        //return;
        $data = ['date' => '', 'items' => Category::getCatalog('')];

       $ssr = JSRender::render($request->path(), [
           'catalog' => $data,
           'nonVisibleAside' => false
       ]);
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => 'Интернет-магазин "Микростоун"']);
   }
}
