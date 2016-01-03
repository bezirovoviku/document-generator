<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLimitsRequest;
use App\Template;

class DashboardController extends Controller {

	/**
    * Creates a new request controller instance.
    *
    * @param Illuminate\Contracts\Auth\Guard $auth
    * @return void
    */
	public function __construct(Guard $auth)
	{
		$this->user = $auth->user();
	}

	/**
    * Displays the dashboard view.
    *
    * @return \Illuminate\Http\Response
    */
	public function index()
	{
		$this->user->load('requests', 'templates');

		$view = view('dashboard.index')
			->with('user', $this->user)
			->with('templates', $this->user->templates)
			->with('requests', $this->user->requests()->newestFirst()->paginate())
			->with('usageHistory', $this->user->getUsageHistory());

		if ($this->user->request_limit) {
			$requestsUsed = $this->user->requests()->lastMonth()->count();
			$view->with('requestsUsed', $requestsUsed);
			$view->with('requestsPercentage', round(100 * $requestsUsed / $this->user->request_limit));
		}

		return $view;
	}

	/**
    * Regenerates and saves api key.
    *
    * @return \Illuminate\Http\Response
    */
	public function regenerateApiKey()
	{
		$this->user->regenerateApiKey();
		$this->user->save();
		return redirect()->back()->withSuccess('API key regenerated.');
	}

	/**
    * Updates limits of generating.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
	public function updateLimits(Request $request)
	{
		// TODO update limits for other users

		$this->authorize('update-limits');
		$this->validate($request, [
			'request_limit' => 'required|numeric|min:0'
		]);

		$this->user->update($request->all());
		return redirect()->back()->withSuccess('New limits saved.');
	}

}
