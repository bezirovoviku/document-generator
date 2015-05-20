<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;

class HomeController extends Controller {

	use AuthenticatesAndRegistersUsers;

	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->redirectPath = action('DashboardController@index');
		$this->loginPath = action('HomeController@index');

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
