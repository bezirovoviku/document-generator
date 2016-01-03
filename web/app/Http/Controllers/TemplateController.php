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

	/**
    * Creates a new template controller instance.
    *
    * @param Illuminate\Contracts\Auth\Guard $auth
    * @return void
    */
	public function __construct(Guard $auth)
	{
		$this->user = $auth->user();
	}

	/**
    * Shows template details.
    *
    * @param \Illuminate\Contracts\Auth\Guard $auth
    * @return \Illuminate\Http\Response
    */
	public function show(Template $template)
	{
		$this->authorize('show-template', $template);

		return view('template.show')
			->with('template', $template)
			->with('requests', $template->requests()->newestFirst()->paginate());
	}

	/**
    * Creates request.
    *
    * @param  \App\Template $template
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
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

	/**
	* Uploads template.
	*
	* @param  \Illuminate\Http\Request $request
	* @return \Illuminate\Http\Response
	*/
	public function upload(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			'template' => 'required|max:2048',
		]);

		$template = new Template($request->only('name'), 'docx');
		$this->user->templates()->save($template);
		$template->saveFile($request->file('template'));

		return redirect()->back()->withSuccess('Template uploaded.');
	}

	/**
	* Deletes template.
	*
	* @param  \Illuminate\Http\Request $request
	* @param  \App\Template $template
	* @return \Illuminate\Http\Response
	*/
	public function delete(Request $request, Template $template)
	{
		$this->authorize('delete-template', $template);
		$template->delete();
		return redirect()->back()->withSuccess('Template deleted.');
	}

}
