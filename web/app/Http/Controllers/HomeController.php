<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;

class HomeController extends Controller {

	use AuthenticatesAndRegistersUsers;

	var $redirectPath = '/dashboard';
	var $loginPath = '/';

	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->middleware('guest', ['except' => ['logout']]);

		$this->auth = $auth;
		$this->registrar = $registrar;
	}

	public function index()
	{
		return view('home.index');
	}

	public function loginOrRegister(Request $request)
	{
		if ($request->has('register')) {
			return $this->postRegister($request)->withSuccess('Welcome to your new account.');
		} else {
			return $this->postLogin($request);
		}
	}

	public function logout()
	{
		return $this->getLogout();
	}

}
