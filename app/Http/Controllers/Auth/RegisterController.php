<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Rules\Captcha;
use App\Wishlist;
use App\Products;
use App\Cart;
use JSRender;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'captcha' => ['required', new Captcha]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => '',
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function registered(Request $request, $user) {
        if ($request->ajax()) {

            $par = $request->all();
            $new_list = [];
            $prod = [];
            $new_cart = [];
            if (isset($par['wishlist']) && is_array($par['wishlist'])) {
                $new_list = Wishlist::AddToWishListFromLocal($user->id, $par['wishlist']);
                $prod = Products::getProductsList(array_column($new_list, 'id'));
            }

            if (isset($par['cart']) && is_array($par['cart'])) {
                $new_cart = Cart::AddToCartFromLocal($user->id, $par['cart'], false);
                //$prod = Products::getProductsList($new_list);
            }

            return [
                'status' => 'OK',
                'email' => $user->email,
                'isVerify' => $user->hasVerifiedEmail(),
                'redirectTo' => $this->redirectPath(),
                'csrf' => csrf_token(),
                'message' => 'Регистрация прошла успешно!',
                'data' => [
                    'setWishlist' => $new_list,
                    'setWishlistProducts' => $prod,
                    'setCart' => $new_cart
                ]
            ];   
        }
    }

    public function showRegistrationForm(Request $request) {
        $ssr = JSRender::render($request->path(), ['nonVisibleMain' => true]);
        //$rend = $this->render($request->path());
        //$ssr = phpinfo(); 
        return view('app', ['ssr' => $ssr, 'title' => 'Зарегистрироваться']);
    }
}
