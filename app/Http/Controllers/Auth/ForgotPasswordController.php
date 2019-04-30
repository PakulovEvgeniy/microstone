<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Rules\Captcha;
use Session;
use JSRender;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function validateEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email']
        ]);
    }

    public function showLinkRequestForm(Request $request) {
        if ($request->ajax()) {
            $errors = Session::get('errors');
            if ($errors && $errors->has('email')) {
                return ['error' => $errors->first('email')];
            } else {
                return ['success' => true];
            }
        }  else {
            $ssr = JSRender::render($request->path(), ['nonVisibleMain' => true]);
        //$rend = $this->render($request->path());
        //$ssr = phpinfo(); 
            return view('app', ['ssr' => $ssr]);
        }
    }
}
