<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use JSRender;

class Account extends Controller
{
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request) {
        //phpinfo();
        //return;
        $data = ['date' => '', 'items' => Category::getCatalog('')];

       $ssr = JSRender::render($request->path(), [
           'catalog' => $data,
           'nonVisibleAside' => true
       ]);
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => 'Профиль']);
   }
}
