<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JSRender;
use App\Setting;

class Not_found extends Controller
{
    

    public function get(Request $request) {
        //phpinfo();
        //return;
        $data = ['date' => '', 'items' => Category::getCatalog('')];

        $row = Setting::where('setting_id', '=', 'company_name')->first();
        $title = $row->name;

       $ssr = JSRender::render($request->path(), [
           'catalog' => $data,
           'nonVisibleAside' => false
       ]);
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => $title]);
   }
}
