<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\UserPersonal;
use App\UserContragents;
use App\UserAddresses;
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

    public function editAddress(Request $request) {
      if ($request->ajax()) {
        $user = $request->user();
        $this->validate($request, [
          'id' => 'integer|exists:user_addresses,id,user_id,' . $user->id,
          'region' => 'required',
          'city' => 'required',
          'street' => 'required',
          'house' => 'required',
          'postind' => 'required|digits:6',
        ]);
        $params = $request->all();
        $dep='';
        if (isset($params['dep']) && $params['dep']) {
          $dep = $params['dep'];
        }
        $row = UserAddresses::where([
          'user_id' => $user->id,
          'region' => $params['region'],
          'city' => $params['city'],
          'street' => $params['street'],
          'house' => $params['house'],
          'dep' => $params['dep'],
          'postind' => $params['postind'],
          ['id', '<>', $params['id']]
        ])->first();
        if ($row) {
          $error = ValidationException::withMessages([
            'address' => ['Такой адрес уже существует']
          ]);
          throw $error;
        }

        $row = UserAddresses::where([
          'user_id' => $user->id,
          'id' => $params['id']
        ])->first();

        $row->region = $params['region'];
        $row->city = $params['city'];
        $row->street = $params['street'];
        $row->house = $params['house'];
        $row->dep = $dep;
        $row->postind = $params['postind'];
        $row->save();

        return [
          'status' => 'OK',
          'redirectTo' => '/account/addresses',
          'message' => 'Адрес успешно сохранен!'
        ];
      }
    }

    public function addAddress(Request $request) {
      if ($request->ajax()) {
        $user = $request->user();
        $this->validate($request, [
          'region' => 'required',
          'city' => 'required',
          'street' => 'required',
          'house' => 'required',
          'postind' => 'required|digits:6'
        ]);
        $params = $request->all();
        $dep='';
        if (isset($params['dep']) && $params['dep']) {
          $dep = $params['dep'];
        }
        $row = UserAddresses::where([
          'user_id' => $user->id,
          'region' => $params['region'],
          'city' => $params['city'],
          'street' => $params['street'],
          'house' => $params['house'],
          'dep' => $params['dep'],
          'postind' => $params['postind'],
        ])->first();
        if ($row) {
          $error = ValidationException::withMessages([
            'address' => ['Такой адрес уже существует']
          ]);
          throw $error;
        }

        $row = new UserAddresses;
        $row->user_id = $user->id;
        $row->region = $params['region'];
        $row->city = $params['city'];
        $row->street = $params['street'];
        $row->house = $params['house'];
        $row->dep = $dep;
        $row->postind = $params['postind'];
        $row->save();

        return [
          'status' => 'OK',
          'redirectTo' => '/account/addresses',
          'message' => 'Адрес успешно добавлен!'
        ];
      }
    }

    public function deleteAddress(Request $request) {
      if ($request->ajax()) {
        $user = $request->user();
        $this->validate($request, [
          'id' => 'integer|exists:user_addresses,id,user_id,' . $user->id
        ]);
        $params = $request->all();
        UserAddresses::destroy($params['id']);
        return [
          'status' => 'OK',
          'data' => [
            'setUserAddresses' => UserAddresses::getAddresses($user)
          ],
          'message' => 'Адрес успешно удален!'
        ];
      }
    }

    public function deleteContragent(Request $request) {
      if ($request->ajax()) {
        $user = $request->user();
        $this->validate($request, [
          'id' => 'integer|exists:user_contragents,id,user_id,' . $user->id
        ]);
        $params = $request->all();
        UserContragents::destroy($params['id']);
        return [
          'status' => 'OK',
          'data' => [
            'setUserContragents' => UserContragents::getContragents($user)
          ],
          'message' => 'Контрагент успешно удален!'
        ];
      }
    }

    public function editContragent(Request $request) {
      if ($request->ajax()) {
        $user = $request->user();

        $this->validate($request, [
          'type' => 'in:u,p,f',
          'id' => 'integer|exists:user_contragents,id,user_id,' . $user->id
        ]);

        $params = $request->all();
        $contr = UserContragents::where([
          'user_id' => $user->id,
          'id' => $params['id']
        ])->first();
        /*if (!$curContr) {
          $error = ValidationException::withMessages([
            'id' => ['Контрагент не найден']
          ]);
          throw $error;
        }*/

        if ($params['type'] == 'f') {
          $this->validate($request, [
            'name' => 'required',
            'family' => 'required'
          ]);

          $otch = '';
          if (isset($params['otchestvo']) && $params['otchestvo']) {
            $otch = $params['otchestvo'];
          }
          $fullname = $params['family'] . '#-#' . $params['name'] . ($otch ? ('#-#' . $otch) : '');
         
          $validator = Validator::make(
            array('fullname' => $fullname),
            array('fullname' => 'unique:user_contragents,name,'. $params['id'] .',id,type,f,user_id,'.$user->id)
          );
          if ($validator->fails())
          {
            $error = ValidationException::withMessages([
              'fullname' => ['Такой контрагент уже существует']
            ]);
            throw $error;
          }

          $contr->type = 'f';
          $contr->name = $fullname;
          $contr->save();
        } elseif ($params['type'] == 'u') {

          $this->validate($request, [
            'inn' => 'required|digits:10',
            'kpp' => 'required|digits:9',
            'uraddress' => 'required',
            'bik' => 'required|digits:9',
            'bankname' => 'required',
            'bankcity' => 'required',
            'korr_sch' => 'required|digits:20',
            'rasch_sch' => 'required|digits:20',
            'name' => 'required'
          ]);

          $cont = UserContragents::where([
            ['user_id', '=', $user->id],
            ['type', '=', 'u'],
            ['name', '=', $params['name']],
            ['kpp', '=', $params['kpp']],
            ['id', '<>', $params['id']]
          ])->first();

          if ($cont) {
            $error = ValidationException::withMessages([
              'fullname' => ['Такой контрагент уже существует!']
            ]);
            throw $error;
          }


          $contr->type = 'u';
          $contr->name = $params['name'];
          $contr->inn = $params['inn'];
          $contr->kpp = $params['kpp'];
          $contr->uraddress = $params['uraddress'];
          $contr->bik = $params['bik'];
          $contr->bankname = $params['bankname'];
          $contr->bankcity = $params['bankcity'];
          $contr->korr_sch = $params['korr_sch'];
          $contr->rasch_sch = $params['rasch_sch'];
          $contr->save();

        } else {
          
          $this->validate($request, [
            'name' => 'required|unique:user_contragents,name,'. $params['id'] .',id,type,p,user_id,'.$user->id,
            'inn' => 'required|digits:12',
            'uraddress' => 'required',
            'bik' => 'required|digits:9',
            'bankname' => 'required',
            'bankcity' => 'required',
            'korr_sch' => 'required|digits:20',
            'rasch_sch' => 'required|digits:20'
          ]);

          $contr->type = 'p';
          $contr->name = $params['name'];
          $contr->inn = $params['inn'];
          $contr->uraddress = $params['uraddress'];
          $contr->bik = $params['bik'];
          $contr->bankname = $params['bankname'];
          $contr->bankcity = $params['bankcity'];
          $contr->korr_sch = $params['korr_sch'];
          $contr->rasch_sch = $params['rasch_sch'];
          $contr->save();
        }
        return [
          'status' => 'OK',
          'data' => [
            'setUserContragents' => UserContragents::getContragents($user)
          ],
          'message' => 'Контрагент успешно сохранен!',
          'redirectTo' => '/account/contragents'
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
        } elseif ($params['type'] == 'u') {
          $user = $request->user();
          $this->validate($request, [
            'name' => 'required',
            'inn' => 'required|digits:10',
            'kpp' => 'required|digits:9',
            'uraddress' => 'required',
            'bik' => 'required|digits:9',
            'bankname' => 'required',
            'bankcity' => 'required',
            'korr_sch' => 'required|digits:20',
            'rasch_sch' => 'required|digits:20'
          ]);

          $cont = UserContragents::where([
            'user_id' => $user->id,
            'type' => 'u',
            'name' => $params['name'],
            'kpp' => $params['kpp']
          ])->first();

          if ($cont) {
            $error = ValidationException::withMessages([
              'fullname' => ['Такой контрагент уже существует!']
            ]);
            throw $error;
          }

          $contr = new UserContragents;
          $contr->user_id = $user->id;
          $contr->type = 'u';
          $contr->name = $params['name'];
          $contr->inn = $params['inn'];
          $contr->kpp = $params['kpp'];
          $contr->uraddress = $params['uraddress'];
          $contr->bik = $params['bik'];
          $contr->bankname = $params['bankname'];
          $contr->bankcity = $params['bankcity'];
          $contr->korr_sch = $params['korr_sch'];
          $contr->rasch_sch = $params['rasch_sch'];
          $contr->save();

        } else {
          $user = $request->user();
          $this->validate($request, [
            'name' => 'required|unique:user_contragents,name,NULL,id,type,p,user_id,'.$user->id,
            'inn' => 'required|digits:12',
            'uraddress' => 'required',
            'bik' => 'required|digits:9',
            'bankname' => 'required',
            'bankcity' => 'required',
            'korr_sch' => 'required|digits:20',
            'rasch_sch' => 'required|digits:20'
          ]);

          $contr = new UserContragents;
          $contr->user_id = $user->id;
          $contr->type = 'p';
          $contr->name = $params['name'];
          $contr->inn = $params['inn'];
          $contr->uraddress = $params['uraddress'];
          $contr->bik = $params['bik'];
          $contr->bankname = $params['bankname'];
          $contr->bankcity = $params['bankcity'];
          $contr->korr_sch = $params['korr_sch'];
          $contr->rasch_sch = $params['rasch_sch'];
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
      $contragent = [];
      $addresses = [];
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
            if ($act && $act=='edit') {
              $userPersonal = UserPersonal::getPersonalData($request->user());
              $title = 'Редактирование контрагента';
            }
           
         } elseif ($id == 'changepsw') {
            $title = "Сменить пароль";
         } elseif ($id == 'addresses') {
            $title = 'Адреса доставки';
            $addresses = UserAddresses::getAddresses($request->user());
            if ($request->ajax()) {
              return [
                'status' => 'OK',
                'data' => [
                  'setUserAddresses' => $addresses
                ] 
              ];
            }
            if ($act && $act=='add') {
              $title = 'Добавление адреса';
            }
            if ($act && $act=='edit') {
              $title = 'Редактирование адреса';
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
      if (count($addresses)) {
        $dat['userAddresses'] = $addresses;
      }

       $ssr = JSRender::render($request->getRequestUri(), $dat);

       //dd($request->path());
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => $title]);
   }
}
