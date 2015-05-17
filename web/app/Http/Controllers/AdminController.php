<?php namespace App\Http\Controllers;

class AdminController extends Controller {

	public function __construct()
	{
        // TODO middleware for admin
        // $this->middleware('auth');
		$this->middleware('guest');
	}

	public function index()
	{

		return view('admin.index');
	}

}
