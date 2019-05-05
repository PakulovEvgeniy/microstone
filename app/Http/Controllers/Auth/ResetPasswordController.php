<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Session;
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
    protected $redirectTo = '/account';

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
            return [
                'email' => $request->user()->email,
                'status' => 'success',
                'redirectTo' => $this->redirectPath(),
                'csrf' => csrf_token(),
                'message' => trans($response)
            ];
        };
        
        return redirect($this->redirectPath())
                            ->with('status', trans($response));
    }

    public function showResetForm(Request $request, $token = null)
    {
        if ($request->ajax()) {
            $errors = Session::get('errors');
            if ($errors && $errors->has('email')) {
                return ['error' => $errors->first('email')];
            } else {
                return ['success' => true];
            }
        } else {
            $email = Session::get('reset_email');
            $data = ['token' => $token, 'email' => $email];
            $ssr = JSRender::render($request->path(), [
                'nonVisibleMain' => true, 
                'resetEmail' => $data
            ]);
            return view('app', ['ssr' => $ssr]);
        }
    }
}
