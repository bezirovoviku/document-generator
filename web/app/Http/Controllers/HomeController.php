<?php namespace App\Http\Controllers;

class HomeController extends Controller {

	public function __construct()
	{
		$this->middleware('guest');
	}

	public function index()
	{

		return view('home.index');
	}

}
