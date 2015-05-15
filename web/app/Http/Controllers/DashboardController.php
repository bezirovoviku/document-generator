<?php namespace App\Http\Controllers;

class DashboardController extends Controller {

	public function __construct()
	{
        // TODO change middleware to auth
        // $this->middleware('auth');
		$this->middleware('guest');
	}

	public function index()
	{

		return view('dashboard.index');
	}

}
