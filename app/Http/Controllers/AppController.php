<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Pakula\Jsrender;

class AppController extends Controller
{
     public function get(Request $request) {
        $ssr = JSRender::render($request->path(), []);
        //$rend = $this->render($request->path());
        //$ssr = phpinfo(); 
        return view('app', ['ssr' => $ssr]);
    }
}

