<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\UserPersonal;
use App\UserContragents;
use JSRender;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Validator;

class Account extends Controller
{
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'oldPassword' => ['required', 'string', 'min:8'],
          'newPassword' => ['required', 'string', 'min:8']
        ]);
        $us = $request->user();
        if (!Hash::check($request->oldPassword, $us->password)) {
           $error = ValidationException::withMessages([
              'oldPassword' => ['Старый пароль введен неверно!']
           ]);
           throw $error;
        }

        $us->password = Hash::make($request->newPassword);
        $us->save();

        return [
          'status' => 'OK',
          'redirectTo' => '/account/personal',
          'message' => 'Пароль успешно изменен!'
        ];
      }
    }

    public function searchContragent(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'type' => 'in:u,p',
          'fullname' => 'nullable|string|min:5'
        ]);
        $params = $request->all();
        if ($params['type'] == 'u') {
          $this->validate($request, [
            'inn' => 'nullable|digits:10'
          ]);
        } else {
          $this->validate($request, [
            'inn' => 'nullable|digits:12'
          ]);
        }
        if (!$params['fullname'] && !$params['inn']) {
          $error = ValidationException::withMessages([
              'inn_fullname' => ['Неверные параметры!']
          ]);
          throw $error;
        }
        $qu = '';
        if ($params['inn']) {
          $qu = $params['inn'];
        }
        if ($params['fullname']) {
          $qu = $qu ? ($qu . ' ' . $params['fullname']) : $params['fullname'];
        }
        $params = array(
          'query' => $qu,
          'type' => $params['type'] == 'u' ? 'LEGAL' : 'INDIVIDUAL'
        );
        $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/party';
        $result = file_get_contents($url, false, stream_context_create(array(
          'http' => array(
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n" .
                         "Accept: application/json\r\n" . 
                         "Authorization: Token 750d3bd09b2119cb598f59132a5ea57f30526fc5",
            'content' => json_encode($params)
          )
        )));
        $res = json_decode($result);
        if ($res) {
          $res = $res->suggestions;
        }
        return [
          'status' => 'OK',
          'data' => [
            'setFoundContragents' => $res
          ]
        ];
      }
     }

    public function addContragent(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'type' => 'in:u,p,f'
        ]);
        $params = $request->all();
        if ($params['type'] == 'f') {
          $this->validate($request, [
            'name' => 'required',
            'family' => 'required'
          ]);
          $user = $request->user();
          $otch = '';
          if (isset($params['otchestvo']) && $params['otchestvo']) {
            $otch = $params['otchestvo'];
          }
          $fullname = $params['family'] . '#-#' . $params['name'] . ($otch ? ('#-#' . $otch) : '');
         
          $validator = Validator::make(
            array('fullname' => $fullname),
            array('fullname' => 'unique:user_contragents,name,NULL,id,type,f,user_id,'.$user->id)
          );
          if ($validator->fails())
          {
            $error = ValidationException::withMessages([
              'fullname' => ['Такой контрагент уже существует']
            ]);
            throw $error;
          }

          $contr = new UserContragents;
          $contr->user_id = $user->id;
          $contr->type = 'f';
          $contr->name = $fullname;
          $contr->save();
        }
        return [
          'status' => 'OK',
          'data' => [
            'setUserContragents' => UserContragents::getContragents($user)
          ],
          'message' => 'Контрагент успешно добавлен!',
          'redirectTo' => '/account/contragents'
        ];
      }
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
          'data' => [
            'setUserPersonalObj' => $pers,
          ],
          'message' => 'Персональные данные успешно сохранены'
        ];
      }
    }

    public function get(Request $request) {
        //phpinfo();
        //return;
      $title = 'Профиль';
      $id = $request->route('id');
      $act = $request->route('act');
      $userPersonal = [];
      $contragents = [];
      if ($id) {
         if ($id == 'personal') {
           $title = 'Личные данные';
           $userPersonal = UserPersonal::getPersonalData($request->user());
           if ($request->ajax()) {
             return [
               'status' => 'OK',
               'data' => [
                  'setVerify' => $request->user()->hasVerifiedEmail(),
                  'setUserPersonal' => $userPersonal
                ]
             ];
           }
         } elseif ($id == 'contragents') {
           $title = 'Контрагенты';
           $contragents = UserContragents::getContragents($request->user());
           if ($request->ajax()) {
            return [
              'status' => 'OK',
              'data' => [
                'setUserContragents' => $contragents
              ] 
            ];
          }

          if ($act && $act=='add') {
            $userPersonal = UserPersonal::getPersonalData($request->user());
            $title = 'Добавление контрагента';
          }
           
         } elseif ($id == 'changepsw') {
            $title = "Сменить пароль";
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
