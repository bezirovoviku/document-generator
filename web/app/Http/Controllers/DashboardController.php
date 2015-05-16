<?php namespace App\Http\Controllers;

use Auth;
use App\Template;
use App\Request;

class DashboardController extends Controller {

	public function __construct()
	{
        $this->middleware('auth');
	}

	public function index()
	{
		return view('dashboard.index')
			->with('user', Auth::user())
			->with('requests', Request::all())
			->with('templates', Template::all());
	}

}
