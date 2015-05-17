<?php namespace App\Http\Controllers;

use Auth;
use App\Template;
use App\Request;

class DashboardController extends Controller {

	public function __construct()
	{
        // TODO middleware?
	}

	public function download(Request $r)
	{
		return 'TODO';
	}

	public function cancel(Request $r)
	{
		return 'TODO';
	}

}
