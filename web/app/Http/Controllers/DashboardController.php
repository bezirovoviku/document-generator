<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLimitsRequest;
use App\Template;

class DashboardController extends Controller {

	public function __construct(Guard $auth)
	{
		$this->user = $auth->user();
	}

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

	public function regenerateApiKey()
	{
		$this->user->regenerateApiKey();
		$this->user->save();
		return redirect()->back()->withSuccess('API key regenerated.');
	}

	public function updateLimits(Request $request)
	{
		$this->authorize('update-limits');
		$this->validate($request, [
			'request_limit' => 'required|numeric|min:0'
		]);

		$this->user->update($request->all());
		return redirect()->back()->withSuccess('New limits saved.');
	}

}
