<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


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
	protected $redirectTo = '/games';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}


	public function logout(Request $request)
	{
		$this->middleware('removeAllGameSessions');

		$this->guard()->logout();

		$request->session()->invalidate();

		return redirect('/');
	}

	/**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\User  $user
	 * @return void
	 */
	protected function authenticated(Request $request, $user)
	{
		if ($user->session_id) {
			Session::getHandler()->destroy($user->session_id);
		}

		$user->session_id = Session::getId();
		$user->save();
	}
}
