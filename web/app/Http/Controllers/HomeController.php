<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;

class HomeController extends Controller {

    public function __construct(Guard $auth, Registrar $registrar, PasswordBroker $passwords)
    {
        $this->middleware('guest');
    }

	public function index()
	{

		return view('home.index');
	}

}
