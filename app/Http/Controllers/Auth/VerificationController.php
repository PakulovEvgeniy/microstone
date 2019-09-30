<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
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
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->ajax()) {
                return [
                    'redirect' => $this->redirectPath()
                ];
            }
            else {
                return redirect($this->redirectPath());
            }
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
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
