<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use JSRender;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function resend(Request $request)
    {
        $us = $request->user();
        if ($us->hasVerifiedEmail() && !$request->code) {
            if ($request->ajax()) {
                return [
                    'redirect' => $this->redirectPath()
                ];
            }
            else {
                return redirect($this->redirectPath());
            }
        }

        if ($request->code == 1 && $request->email) {
            if ($request->email != $us->email) {
               $this->validate($request, ['email' => ['required', 'string', 'email', 'max:255', 'unique:users']]);
            }
            $cod = rand(0,9) . rand(0, 9) . rand(0,9) . rand(0,9) . rand(0, 9) . rand(0,9);
            $request->session()->put('cod', $cod);
            $request->session()->put('email_ext', $request->email);
            $us->sendEmailVerificationNotificationCode($cod, $request->email);
            return [
              'status' => 'OK',
              'message' => 'Письмо успешно отправлено!'
            ];
        } elseif ($request->code == 2) {
            if (!$request->cod || $request->cod != $request->session()->get('cod')) {
              $error = ValidationException::withMessages([
                'code' => ['Неверный код подтверждения']
              ]);
              throw $error;
            }
            if (!$request->email || $request->email != $request->session()->get('email_ext')) {
              $error = ValidationException::withMessages([
                'email' => ['Неверный email']
              ]);
              throw $error;
            }
            if ($us->email != $request->email) {
                $us->email = $request->email;
                $us->save();   
            }
            $us->markEmailAsVerified();
            return [
              'status' => 'OK',
              'email' => $request->email,
              'isVerify' => $us->hasVerifiedEmail(),
              'message' => 'Email успешно подтвержден!'
            ];
        } else {
            $us->sendEmailVerificationNotification();
            return back()->with('resent', true);
        }
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
           if($request->user()->hasVerifiedEmail()) {
            return [
                'redirect' => $this->redirectPath()
            ];
           } else {
                return [
                    'message' => 'Письмо успешно отправлено!'
                ];
           } 
        } 

        if($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        } else {
            $ssr = JSRender::render($request->path(), ['nonVisibleMain' => true]);
            //$rend = $this->render($request->path());
            //$ssr = phpinfo(); 
            return view('app', ['ssr' => $ssr, 'title' => 'Подтверждение email']);
        }
        
    }
}
