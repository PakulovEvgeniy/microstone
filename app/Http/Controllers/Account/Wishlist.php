<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Wishlist as WishModel;
use App\Products;
use App\Wishlist_groups;
use Illuminate\Validation\ValidationException;
use Auth;
use JSRender;

class Wishlist extends Controller
{
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('auth')->except('wishlist');
    }

    public function wishlist(Request $request) {
      
      $dat = [];

      if (Auth::check()) {
        $arr_wish = WishModel::getWishlistAll($request->user()->id);
        $dat['wishlist'] = [
          'items' => $arr_wish,
          'curItems' => $arr_wish,
          'products' => Products::getProductsList(array_column($arr_wish, 'id')),
          'curGroup' => null,
          'groups' => Wishlist_groups::getAllGroups($request->user()->id)
        ];

        if ($request->ajax()) {
          return [
            'status' => 'OK',
            'data' => [
              'setWishlist' => $arr_wish,
              'setCurWishlist' => $arr_wish,
              'setWishlistProducts' => $dat['wishlist']['products'],
              'setWishGroup' => null,
              'setWishGroups' => $dat['wishlist']['groups']
            ]
          ];
        }
      }
      $title = 'Мои списки';
      $data = ['date' => '', 'items' => Category::getCatalog('')];

      $dat['catalog'] = $data;
      $dat['nonVisibleAside'] = true;

      $ssr = JSRender::render($request->getRequestUri(), $dat);

       //dd($request->path());
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => $title]);
    }



    public function wishlistGroup(Request $request) {
      
      $id = (int)$request->route('id');

      $dat = [];

      

      if (Auth::check()) {
        $arr_wish = WishModel::getWishlistAll($request->user()->id, $id);
        $grp = Wishlist_groups::find($id);
        $arr_wish_all = WishModel::getWishlistAll($request->user()->id);
        $dat['wishlist'] = [
          'items' => $arr_wish_all,
          'curItems' => $arr_wish,
          'products' => Products::getProductsList(array_column($arr_wish, 'id')),
          'curGroup' => $id,
          'groups' => Wishlist_groups::getAllGroups($request->user()->id) 
        ];

        if ($request->ajax()) {
          return [
            'status' => 'OK',
            'data' => [
              'setWishlist' => $arr_wish_all,
              'setCurWishlist' => $arr_wish,
              'setWishlistProducts' => $dat['wishlist']['products'],
              'setWishGroup' => $id,
              'setWishGroups' => $dat['wishlist']['groups']
            ]
          ];
        }
      }
      $title = 'Мои списки';
      $data = ['date' => '', 'items' => Category::getCatalog('')];

      $dat['catalog'] = $data;
      $dat['nonVisibleAside'] = true;
      

      $ssr = JSRender::render($request->getRequestUri(), $dat);

       //dd($request->path());
       //$rend = $this->render($request->path()); 
       //$ssr = phpinfo();
       return view('app', ['ssr' => $ssr, 'title' => $title]);
    }

    public function addWishGroup(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'name' => 'required'
        ]);
        $param = $request->all();
        $row = Wishlist_groups::where([
          ['user_id', '=', $request->user()->id],
          ['name', '=', $param['name']]
        ])->first();
        if ($row) {
          $error = ValidationException::withMessages([
            'name' => ['Такой список уже существует']
          ]);
          throw $error;
        }
        $grp = new Wishlist_groups();
        $grp->user_id = $request->user()->id;
        $grp->name = $param['name'];
        $grp->save();
        return [
          'status' => 'OK',
          'message' => 'Список успешно добавлен!',
          'succesParams' => $grp->id,
          'data' => [
            'setWishGroups' => Wishlist_groups::getAllGroups($request->user()->id)
          ]
        ];
      }
    }

    public function editWishGroup(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'name' => 'required',
          'id' => 'required|integer'
        ]);
        $param = $request->all();
        $row = Wishlist_groups::where([
          ['user_id', '=', $request->user()->id],
          ['name', '=', $param['name']],
          ['id', '<>', $param['id']]
        ])->first();
        if ($row) {
          $error = ValidationException::withMessages([
            'name' => ['Такой список уже существует']
          ]);
          throw $error;
        }
        $grp = Wishlist_groups::where([
          ['id' , '=', $param['id']],
          ['user_id', '=', $request->user()->id]
        ])->first();
        if (!$grp) {
          $error = ValidationException::withMessages([
            'id' => ['Не найден id списка']
          ]);
          throw $error;
        }

        $grp->name = $param['name'];
        $grp->save();
        return [
          'status' => 'OK',
          'message' => 'Список успешно сохранен!',
          'data' => [
            'setWishGroups' => Wishlist_groups::getAllGroups($request->user()->id)
          ]
        ];
      }
    }

    public function archiveWishGroup(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'id' => 'required|integer'
        ]);
        $param = $request->all();
        $grp = Wishlist_groups::where([
          ['id' , '=', $param['id']],
          ['user_id', '=', $request->user()->id]
        ])->first();
        if (!$grp) {
          $error = ValidationException::withMessages([
            'id' => ['Не найден id списка']
          ]);
          throw $error;
        }

        $arch = 0;
        if (isset($param['arch']) && $param['arch']) {
          $arch = 1;
        }

        $grp->archived = $arch;
        $grp->save();
        return [
          'status' => 'OK',
          'message' => $arch ? 'Список успешно добавлен в архив!' : 'Список успешно извлечен из архива',
          'data' => [
            'setWishGroups' => Wishlist_groups::getAllGroups($request->user()->id)
          ]
        ];
      }
    }

    public function delWishGroup(Request $request) {
      if ($request->ajax()) {
        $this->validate($request, [
          'id' => 'required|integer'
        ]);
        $param = $request->all();
        $grp = Wishlist_groups::where([
          ['id' , '=', $param['id']],
          ['user_id', '=', $request->user()->id]
        ])->first();
        if (!$grp) {
          $error = ValidationException::withMessages([
            'id' => ['Не найден id списка']
          ]);
          throw $error;
        }

        WishModel::where([
          ['user_id', '=', $request->user()->id],
	    		['wish_group_id', '=', $param['id']]
        ])->delete();
        $grp->delete();
        
        return [
          'status' => 'OK',
          'message' => 'Список успешно удален!',
          'data' => [
            'setWishGroups' => Wishlist_groups::getAllGroups($request->user()->id)
          ]
        ];
      }
    }

    public function delFromWish(Request $request) {
    	if ($request->ajax()) {
    		$this->validate($request, [
          'id' => 'required|integer',
          'group_id' => 'required|integer'
	      ]);
        
        $param = $request->all();
        $char = "";
        if (isset($param['characteristic'])) {
          $char = $param['characteristic'];
        }
        if (!$char) {
          $char = "";
        }
    		
	    	$row = WishModel::where([
	    		['user_id', '=', $request->user()->id],
	    		['product_id', '=', $param['id']],
          ['characteristic_id', '=', $char],
	    		['wish_group_id', '=', $param['group_id']]
	    	])->first();
	    	if (!$row) {
	    		return ['status' => 'OK'];
	    	} else {
          $row->delete();
          $data = [];
          $pr = [
            'id' => $param['id'],
            'characteristic' => $char
          ];
          if ((int)$param['group_id'] == 0) {
            $data['delFromItemWish'] = $pr;
            $data['delFromCurItemWish'] = $pr;
            $data['delFromWishListProducts'] = $param['id'];
          } else {
            $data['delFromCurItemWish'] = $pr;
            $data['delFromWishListProducts'] = $param['id'];
          }
	    		return [
          	'status' => 'OK',
          	'data' => $data
        	];
	    	}
    	}
    }

    public function delFromWishGroup(Request $request) {
    	if ($request->ajax()) {
    		$this->validate($request, [
          'group_id' => 'required|integer'
        ]);
        $param = $request->all();
        $grp_id = (int)$param['group_id'];
	    	WishModel::where([
	    		['user_id', '=', $request->user()->id],
	    		['wish_group_id', '=', $grp_id]
	    	])->delete();
	    	
        $data = [];
        if ($grp_id == 0) {
          $data['setWishlist'] = [];
          $data['setWishlistProducts'] = [];
        } else {
          $data['setWishlistProducts'] = [];
        }
	    	return [
        	'status' => 'OK',
        	'data' => $data
        ];
    	}
    }

    public function addToWish(Request $request) {
    	if ($request->ajax()) {
	    	$this->validate($request, [
	        'id' => 'required|integer',
	      ]);
        $param = $request->all();
        $char = "";
        if (isset($param['characteristic'])) {
          $char = $param['characteristic'];
        }
        if (!$char) {
          $char = "";
        }
        $pr = [
            'id' => $param['id'],
            'characteristic' => $char
        ];
        $res = WishModel::AddToWishList($request->user()->id, $pr, 0);
	    	
	    	if (!$res) {
	    		return ['status' => 'OK'];
	    	} else {
	    		return [
          	'status' => 'OK',
          	'data' => [
          		'addToItemWish' => $pr
          	]
        	];
	    	}
    	}
    }

    public function addToWishList(Request $request) {
    	if ($request->ajax()) {
	    	$this->validate($request, [
          'product_id' => 'required|array',
          'groups' => 'required|array'
	      ]);
        $param = $request->all();
        
        $flAdd = false;
        $user_id = $request->user()->id;

        foreach ($param['product_id'] as $prod) {
          $pr = [
            'id' => $prod['id'],
            'characteristic' => $prod['characteristic'] ? $prod['characteristic'] : ''
          ];
          foreach ($param['groups'] as $val) {
            $grp_id = (int)$val;
            $res = WishModel::AddToWishList($user_id, $pr, $grp_id);
            if ($res) {
              $flAdd = true;
            }
          }
        }
        

	    	if (!$flAdd) {
	    		return ['status' => 'OK'];
	    	} else {
          $arr_wish_all = WishModel::getWishlistAll($request->user()->id);
	    		return [
            'status' => 'OK',
            'message' => 'Товар успешно добавлен в списки!',
            'data' => [
              'setWishlist' => $arr_wish_all
            ]
        	];
	    	}
    	}
    }
}
