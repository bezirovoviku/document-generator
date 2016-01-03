<?php namespace App\Http\Controllers;

use Gate;
use App\User;
use App\Request;

class AdminController extends Controller {

	public function users()
	{
		$this->authorize('list-users');

		return view('admin.users')
			->with('users', User::orderBy('role', 'ASC')->orderBy('email', 'ASC')->paginate());
	}

	public function requests()
	{
		$this->authorize('list-requests');

		return view('admin.requests')
			->with('requests', Request::newestFirst()->paginate());
	}

}
