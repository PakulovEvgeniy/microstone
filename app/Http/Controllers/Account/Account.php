<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\UserPersonal;
use App\UserContragents;
use JSRender;
use Carbon\Carbon;

class Account extends Controller
{
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function post(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'phone' => 'nullable|digits:10',
          'pol' => 'nullable|in:n,m,f',
          'bithday' => 'nullable|date'
        ]);
        $params = $request->all();
        //return $params;
        $user = $request->user();
        $us = UserPersonal::where('user_id', $user->id)->first();
        if (!$us) {
          $us = new UserPersonal;
        }
        $us->user_id = $user->id;
        $us->phone = $params['phone'];
        $us->name = $params['name'];
        $us->family = $params['family'];
        $us->otchestvo = $params['otchestvo'];
        $us->nickname = $params['nickname'];
        $us->pol = $params['pol'];
        $us->bithday = $params['bithday'] ? Carbon::parse($params['bithday']) : null;
        $us->save();
        $pers = UserPersonal::getPersonalData($user);
        unset($pers['email']);
        unset($pers['sendConfirm']);
        return [
          'status' => 'OK',
          'data' => $pers,
          'message' => 'Персональные данные успешно сохранены'
        ];
      }
    }

    public function get(Request $request) {
        //phpinfo();
        //return;
      $title = 'Профиль';
      $id = $request->route('id');
      $userPersonal = [];
      $contragents = [];
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
         } elseif ($id == 'contragents') {
           $title = 'Контрагенты';
           $contragents = UserContragents::getContragents($request->user());
           if ($request->ajax()) {
             return [
               'status' => 'OK',
               'data' => $contragents
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
      if (count($contragents)) {
        $dat['userContragents'] = [
          'date' => '',
          'items' => $contragents
        ];
      }

       $ssr = JSRender::render($request->path(), $dat);
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => $title]);
   }
}
