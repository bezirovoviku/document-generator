<?php namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\User;

class HomeController extends Controller {

	use AuthenticatesAndRegistersUsers;

	public function __construct()
	{
		$this->redirectPath = action('DashboardController@index');
		$this->loginPath = action('HomeController@index');
	}

	public function index()
	{
		return view('home.index');
	}

	public function register()
	{
		return view('home.register');
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

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
			'password_confirmation' => 'required|min:6'
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'email' => $data['email'],
			'password' => $data['password'],
		]);
	}

}
