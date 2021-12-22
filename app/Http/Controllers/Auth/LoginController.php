<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'));
    }

    /**
     * Check the a validate for an incoming login request.
     *
     * @param  Request  $request
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ], [
            'g-recaptcha-response.required' => trans('form.captcha_is_required'),
        ]);
    }

    /**
     * Check the Login with User Credentials with User is Approved or Disabled.
     *
     * @param  Request  $request
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if ($this->guard()->validate($this->credentials($request))) {

            $user = $this->guard()->getLastAttempted();
            if ($user->status == "approved" && $this->attemptLogin($request)) {

                return $this->sendLoginResponse($request);
            } else if ($user->status == "disabled") {
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['email' =>  trans('form.loginform.your_account_was_disabled')]);
            } else if ($user->status == "pending") {
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['email' =>  trans('form.loginform.your_account_is_pending')]);
            }
        }
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $remember_me = $request->has('remember') ? true : false;
        if ($this->guard()->attempt(
            $this->credentials($request),
            $remember_me
        )) {
            $user = auth()->user();
            Auth::login($user, $remember_me);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Handle logout facility.
     *
     * @param  Request  $request
     */
    public function logout(Request $request)
    {
        \Auth::logout();
        return redirect('/login');
    }
}
