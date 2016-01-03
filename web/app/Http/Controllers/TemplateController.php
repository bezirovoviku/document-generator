<?php namespace App\Http\Controllers;

use Log;
use File;
use Validator;
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

		// common validation rules
		$validator = Validator::make($request->all(), [
			'data_type' => 'required|in:json,xml,csv',
			'input_type' => 'required|in:direct,file',
			'callback_url' => 'url',
			'data' => 'required_if:input_type,direct',
			'data_file' => 'sometimes|required_if:input_type,file|max:1024', // max 1MB
		], [
			'json' => 'Request data is not a valid JSON.',
			'xml' => 'Request data is not a valid XML.',
		]);
		// direct input validation rules
		if ($request->input('input_type') == 'direct') {
			$validator->sometimes('data', 'json', function($input) { return $input->data_type == 'json'; });
			$validator->sometimes('data', 'xml', function($input) { return $input->data_type == 'xml'; });
		}
		// file validation rules
		if ($request->input('input_type') == 'file') {
			$validator->sometimes('data_file', 'mimes:json,txt', function($input) { return $input->data_type == 'json'; });
			$validator->sometimes('data_file', 'mimes:csv,txt', function($input) { return $input->data_type == 'csv'; });
			$validator->sometimes('data_file', 'mimes:xml,txt', function($input) { return $input->data_type == 'xml'; });
		}
		// validate
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$requestModel = new RequestModel([
			'type' => 'docx',
			'callback_url' => $request->input('callback_url'),
		]);

		// load request payload into $data (directly from form or from a file)
		if ($request->input('input_type') == 'direct') {
			$data = $request->input('data');
		} else {
			$data = File::get($request->file('data_file')->getRealPath());
		}

		// try to set data for the request
		try {
			$requestModel->setData($data, $request->input('data_type'));
		} catch (\Exception $e) {
			Log::error($e);
			return redirect()->back()->withDanger($e->getMessage())->withInput();
		}

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
		$template = new Template($request->only('name'), 'docx');
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
