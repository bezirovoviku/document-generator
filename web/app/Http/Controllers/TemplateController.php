<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLimitsRequest;
use App\Jobs\GenerateRequest;
use App\Template;
use App\Request as RequestModel;

class TemplateController extends Controller {

	public function __construct(Guard $auth)
	{
		$this->user = $auth->user();
	}

	public function show(Template $template)
	{
		$this->authorize('show-template', $template);

		return view('template.show')
			->with('template', $template)
			->with('requests', $template->requests()->newestFirst()->paginate());
	}

	public function createRequest(Request $request, Template $template) {
		$this->authorize('create-request', $template);

		if ($this->user->isOverRequestLimit()) {
			return redirect()->back()->withDanger('Request limit exceeded.');
		}

		$this->validate($request, [
			'data' => 'required|json',
		], [
			'json' => 'Request data is not a valid JSON.'
		]);

		$requestModel = new RequestModel([
			'data' => $request->input('data'),
		]);
		$requestModel->user()->associate($template->user);
		$template->requests()->save($requestModel);

		$this->dispatch(new GenerateRequest($requestModel));

		return redirect()->back()->withSuccess('Request generated.');
	}

	public function uploadTemplate(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			'template' => 'required|max:2048',
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
		$this->authorize('delete-template', $template);
		$template->delete();
		return redirect()->back()->withSuccess('Template deleted.');
	}

}
