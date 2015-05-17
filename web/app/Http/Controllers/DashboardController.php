<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use App\Template;
use App\Request;

class DashboardController extends Controller {

	public function __construct(Guard $auth)
	{
        $this->middleware('auth');

    	$this->user = $auth->user();
	}

	public function index()
	{
    	$this->user->load('requests', 'templates');

		return view('dashboard.index')
			->with('user', $this->user)
			->with('requests', $this->user->requests)
			->with('templates', $this->user->templates);
	}

	public function regenerateApiKey()
	{
		$this->user->regenerateApiKey();
		$this->user->save();
		return redirect()->back()->withSuccess('API key regenerated.');
	}

}
