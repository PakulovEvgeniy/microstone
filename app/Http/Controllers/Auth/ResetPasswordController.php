<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Session;
use App\Wishlist;
use App\Products;
use App\Cart;
use JSRender;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    protected function sendResetResponse(Request $request, $response)
    {
        Session::forget('reset_email');
        


        if ($request->ajax()) {
            $par = $request->all();
            $new_list = [];
            $prod = [];
            $new_cart = [];
            $red = '';
            $user = $request->user();

            $dialog = false;
            if (isset($par['dialog']) &&  $par['dialog']) {
                $dialog = true;
            }

            if (isset($par['wishlist']) && is_array($par['wishlist'])) {
                $new_list = Wishlist::AddToWishListFromLocal($user->id, $par['wishlist']);
                $prod = Products::getProductsList(array_column($new_list, 'id'));
            }

            if (isset($par['cart']) && is_array($par['cart'])) {
                $new_cart = Cart::AddToCartFromLocal($user->id, $par['cart'], false, false, $dialog);
                //$prod = Products::getProductsList($new_list);
            }
            if (isset($par['redirect']) && $par['redirect']) {
               $red = $par['redirect'];
            }

            $res = [
                'email' => $request->user()->email,
                'status' => 'OK',
                'isVerify' => $request->user()->hasVerifiedEmail(),
                'csrf' => csrf_token(),
                'message' => trans($response),
                 'data' => [
                    'setWishlist' => $new_list,
                    'setWishlistProducts' => $prod,
                    'setCart' => $new_cart
                ]
            ];

            if (!$dialog) {
               $res['redirectTo'] = $red ? $red : $this->redirectPath();
            }
            

            return $res;
        };
        
        return redirect($this->redirectPath())
                            ->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        
        if ($request->ajax()) {
            return [
                'status' => 'ER',
                'error' => trans($response)
            ];
        };
        
        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        if ($request->ajax()) {
            $email = Session::get('reset_email');
            $data = ['token' => $token, 'email' => $email];
            return [
                'status' => 'OK',
                'data' => [
                    'setResetEmail' => $data
                ]
            ];
        } else {
            $email = Session::get('reset_email');
            $data = ['token' => $token, 'email' => $email];
            $ssr = JSRender::render($request->path(), [
                'nonVisibleMain' => true, 
                'resetEmail' => $data
            ]);
            return view('app', ['ssr' => $ssr, 'title' => 'Восстановление пароля']);
        }
    }
}
