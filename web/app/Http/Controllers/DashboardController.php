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

	public function uploadTemplate(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			'template' => 'required|mimes:docx|max:2048',
		]);

		// save to DB
		$template = new Template($request->only('name'));
		$this->user->templates()->save($template);

		// save to filesystem
		$request->file('template')->move($template->getRealPath(), $template->getFilename());

		return redirect()->back()->withSuccess('Template uploaded.');
	}

	public function deleteTemplate(Request $request, Template $template)
	{
		$template = $this->user->templates()->find($template->id);
		if ($template == NULL) {
			abort(404, 'Template not found.');
		}
		$template->delete();
		return redirect()->back()->withSuccess('Template deleted.');
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
