<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Wishlist;
use App\Products;
use App\Cart;
use JSRender;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/account/personal';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8']
        ]);
    }

    public function showLoginForm(Request $request) {
        $ssr = JSRender::render($request->path(), ['nonVisibleMain' => true]);
        //$rend = $this->render($request->path());
        //$ssr = phpinfo(); 
        return view('app', ['ssr' => $ssr, 'title' => 'Войти в личный кабинет']);
    }

    public function authenticated(Request $request, $user)
    {
        if ($request->ajax()) {

            $par = $request->all();
            $new_list = [];
            $prod = [];
            $new_cart = [];
            if (isset($par['wishlist']) && is_array($par['wishlist'])) {
                $new_list = Wishlist::AddToWishListFromLocal($user->id, $par['wishlist']);
                $prod = Products::getProductsList($new_list);
            }

            if (isset($par['cart']) && is_array($par['cart'])) {
                $new_cart = Cart::AddToCartFromLocal($user->id, $par['cart']);
                //$prod = Products::getProductsList($new_list);
            }

            return [
                'status' => 'OK',
                'email' => $user->email,
                'isVerify' => $user->hasVerifiedEmail(),
                'redirectTo' => $this->redirectPath(),
                'csrf' => csrf_token(),
                'message' => 'Вы успешно авторизовались',
                'data' => [
                    'setWishlist' => $new_list,
                    'setWishlistProducts' => $prod,
                    'setCart' => $new_cart
                ]
            ];   
        }
    }

    public function loggedOut(Request $request)
    {
        if ($request->ajax()) {
            return [
                'status' => 'OK',
                'csrf' => csrf_token(),
                'redirectTo' => '/'
            ];   
        }
    }
}
