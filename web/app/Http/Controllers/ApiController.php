<?php namespace App\Http\Controllers;

use Validator;
use Config;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\Jobs\GenerateRequest;
use App\User;
use App\Template;
use App\Request as RequestModel;

class ApiController extends Controller {

	public function __construct(Guard $auth)
	{
		$this->user = $auth->user();
	}

	/**
	 * Saves template to DB and filesystem.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return result array
	 */
	public function uploadTemplate(Request $request) {
		// not using $this->validate because of filesize is not normal input
		$validator = Validator::make([
			'name' => $request->input('name'),
			'type' => $request->input('type'),
			'filesize' => $request->header('Content-Length'),
		], [
			'name' => 'required|max:255',
			'type' => 'in:docx,md,html',
			'filesize' => 'required|integer|min:0|max:' . Config::get('app.template_max_size'),
		]);
		if ($validator->fails()) {
			return response(['error' => $validator->errors()], 400);
		}

		// save to DB
		$template = new Template([
			'name' => $request->input('name'),
			'type' => $request->input('type')
		]);
		$this->user->templates()->save($template);
		$template->saveContents($request->getContent());

		return [
			'template_id' => $template->id,
			'md5' => $template->getMD5(),
		];
	}

	/**
	 * Deletes template by its ID.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  string $template_id
	 * @return \Illuminate\Http\Response
	 */
 	public function deleteTemplate(Request $request, Template $template)
 	{
 		$this->authorize('delete-template', $template);
 		$template->delete();
		return response(NULL, 200);
	}

	/**
	 * Creates request to generate.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return result array
	 */
	public function createRequest(Request $request) {
		$user = $this->user;

		if ($user->isOverRequestLimit()) {
			throw new ApiException('Request limit exceeded.');
		}

		$this->validate($request, [
			'template_id' => 'required|exists:templates,id,user_id,'.$user->id,
			'type' => 'required|in:pdf,docx,html,md',
			'data' => 'required',
			'data_type' => 'in:json,xml,csv',
			'callback_url' => 'url',
		]);

		// get template from DB
		$template = $user->templates()->find($request->input('template_id'));
		if ($template == NULL) {
			throw new ApiException('Template not found.');
		}

		$allowed = [
			'md' => ['md', 'html', 'pdf'],
			'html' => ['html', 'pdf'],
			'docx' => ['docx', 'pdf']
		];

		if (!in_array($request->input('type'), $allowed[$template->type]))
			throw new ApiException("Specified template can't be converted to requested type.");

		$requestModel = new RequestModel([
			'type' => $request->input('type'),
			'callback_url' => $request->input('callback_url'),
		]);

		// try to set data for the request
		try {
			$data = $request->input('data');
			$data_type = $request->input('data_type', 'json');
			$requestModel->setData($data, $data_type);
		} catch (\Exception $e) {
			throw new ApiException('Invalid data format.', 0, $e);
		}

		$requestModel->user()->associate($template->user);
		$template->requests()->save($requestModel);

		$requ = new GenerateRequest($requestModel);
		$requ->handle();

		//$this->dispatch(new GenerateRequest($requestModel));


		return ['request_id' => $requestModel->id];
	}

	/**
	 * Returns request info by its ID
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  string $request_id
	 * @return \Illuminate\Http\Request $request
	 */
	public function getRequestInfo(RequestModel $request) {
		$this->authorize('show-request', $request);
		return $request;
	}

	/**
	 * Returns request info by its ID
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  string $request_id
	 * @return \Illuminate\Http\Response
	 */
	public function downloadRequest(RequestModel $request) {
		$this->authorize('download-request', $request);
		return response()->download(env_path($request->getStoragePathname()));
	}

}
