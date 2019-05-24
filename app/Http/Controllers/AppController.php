<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;

class AppController extends Controller
{
     
	public function get(Request $request) {
     	//phpinfo();
     	//return;
     	$data = ['date' => '', 'items' => Category::getCatalog('')];

        $ssr = JSRender::render($request->path(), ['catalog' => $data]);
        //$rend = $this->render($request->path()); 
        //$ssr = phpinfo();
        return view('app', ['ssr' => $ssr]);
    }
}

