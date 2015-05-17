<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLimitsRequest;
use App\Template;

class DashboardController extends Controller {

	public function __construct(Guard $auth, Filesystem\Factory $storage)
	{
        $this->middleware('auth');

    	$this->user = $auth->user();
    	$this->storage = $storage;
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

	public function updateLimits(UpdateLimitsRequest $request)
	{
		$this->user->update($request->all());
		return redirect()->back()->withSuccess('Changes were saved.');
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
		$request->file('template')->move($template->getPath(), $template->getFilename());

		return redirect()->back()->withSuccess('Template was uploaded.');
	}

}
