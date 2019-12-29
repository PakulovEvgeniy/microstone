<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;

class Compare extends Controller
{
  public function get(Request $request) {
  $title = 'Сравнение товаров';
    $data = ['date' => '', 'items' => Category::getCatalog('')];
    
    $dat = [
      'catalog' => $data,
      'nonVisibleAside' => true
    ];

    $ssr = JSRender::render($request->path(), $dat);
    //$rend = $this->render($request->path()); 
    //$ssr = phpinfo();
    return response()->view('app', ['ssr' => $ssr, 'title' => $title]);
  }
}
