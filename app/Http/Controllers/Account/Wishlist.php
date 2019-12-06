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
          'products' => Products::getProductsList($arr_wish),
          'curGroup' => null,
          'groups' => Wishlist_groups::getAllGroups($request->user()->id)
        ];

        if ($request->ajax()) {
          return [
            'status' => 'OK',
            'data' => [
              'setWishlist' => $arr_wish,
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
        $dat['wishlist'] = [
          'items' => $arr_wish,
          'products' => Products::getProductsList($arr_wish),
          'curGroup' => $id,
          'groups' => [] 
        ];

        if ($request->ajax()) {
          return [
            'status' => 'OK',
            'data' => [
              'setWishlist' => $arr_wish,
              'setWishlistProducts' => $dat['wishlist']['products'],
              'setWishGroup' => $id,
              'setWishGroups' => []
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
	      ]);
    		$param = $request->all();
	    	$row = WishModel::where([
	    		['user_id', '=', $request->user()->id],
	    		['product_id', '=', $param['id']],
	    		['wish_group_id', '=', 0]
	    	])->first();
	    	if (!$row) {
	    		return ['status' => 'OK'];
	    	} else {
	    		$row->delete();
	    		return [
          	'status' => 'OK',
          	'data' => [
          		'delFromItemWish' => $param['id']
          	]
        	];
	    	}
    	}
    }

    public function addToWish(Request $request) {
    	if ($request->ajax()) {
	    	$this->validate($request, [
	        'id' => 'required|integer',
	      ]);
	    	$param = $request->all();
	    	$row = WishModel::where([
	    		['user_id', '=', $request->user()->id],
	    		['product_id', '=', $param['id']],
	    		['wish_group_id', '=', 0]
	    	])->first();

	    	if ($row) {
	    		return ['status' => 'OK'];
	    	} else {
	    		$neww = new WishModel();
	    		$neww->user_id = $request->user()->id;
	    		$neww->product_id = $param['id'];
	    		$neww->save();
	    		return [
          	'status' => 'OK',
          	'data' => [
          		'addToItemWish' => $param['id']
          	]
        	];
	    	}
    	}
    }
}
