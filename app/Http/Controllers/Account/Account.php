<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\UserPersonal;
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
      $title = 'Профиль';
      $id = $request->route('id');
      $userPersonal = [];
      if ($id) {
         if ($id == 'personal') {
           $title = 'Личные данные';
           $userPersonal = UserPersonal::getPersonalData($request->user());
           if ($request->ajax()) {
             return [
               'status' => 'OK',
               'data' => $userPersonal
             ];
           }
         }
      }


      $data = ['date' => '', 'items' => Category::getCatalog('')];

      $dat = [
        'catalog' => $data,
        'nonVisibleAside' => true
      ];
      if (count($userPersonal)) {
        $dat['userPersonal'] = [
          'date' => '',
          'data' => $userPersonal
        ];
      }

       $ssr = JSRender::render($request->path(), $dat);
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => $title]);
   }
}
