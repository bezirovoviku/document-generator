<?php namespace App\Http\Controllers;

use App\User;
use App\Request;

class AdminController extends Controller {

	public function users()
	{
		return view('admin.users')
			->with('users', User::orderBy('role', 'ASC')->orderBy('email', 'ASC')->paginate());
	}

	public function requests()
	{
		return view('admin.requests')
			->with('requests', Request::newestFirst()->paginate());
	}

}
