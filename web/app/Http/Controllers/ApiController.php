<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\Jobs\GenerateRequest;
use App\User;
use App\Template;
use App\Request as RequestModel;

class ApiController extends Controller {

	public function __construct(Filesystem $storage, Request $request)
	{
		$this->storage = $storage;

		// selected user from DB by X-AUTH header
		$token = $request->header('X-AUTH');
		$this->user = User::where(['api_key' => $token])->first();
	}

	/**
	 * Saves template to DB and filesystem
	 */
	public function uploadTemplate(Request $request) {
		$this->validate($request, [
			'name' => 'required|max:255',
			// TODO maximum filesize check
		]);

		// save to DB
		$template = new Template([
			'name' => $request->input('name')
		]);
		$this->user->templates()->save($template);

		// save to filesystem
		$this->storage->put($template->getStoragePathname(), $request->getContent());

		return [
			'template_id' => $template->id,
			'md5' => md5_file($template->getRealPathname()),
		];
	}

	/**
	 * Deletes template by its ID
	 */
	public function deleteTemplate(Request $request, $template_id) {
		// get template from DB
		$template = $this->user->templates()->find($template_id);
		if ($template == NULL) {
			throw new ApiException('Template not found.');
		}

		$template->delete();
		return response(NULL, 200);
	}

	/**
	 * Creates request to generate
	 */
	public function createRequest(Request $request) {
		$user = $this->user;

		if ($user->request_limit) {
			$requestsUsed = $user->requests()->lastMonth()->count();
			if ($requestsUsed >= $user->request_limit) {
				throw new ApiException('Request limit exceeded.');
			}
		}

		$this->validate($request, [
			'template_id' => 'required|exists:templates,id,user_id,'.$user->id,
			'type' => 'required|in:pdf,docx',
			'data' => 'required',
			'callback_url' => 'url',
		]);

		// get template from DB
		$template = $user->templates()->find($request->input('template_id'));
		if ($template == NULL) {
			throw new ApiException('Template not found.');
		}

		$requestModel = new RequestModel([
			'type' => $request->input('type'),
			'data' => json_encode($request->input('data')),
			'callback_url' => $request->input('callback_url'),
		]);
		$requestModel->user()->associate($template->user);
		$template->requests()->save($requestModel);

		$this->dispatch(new GenerateRequest($requestModel));

		return ['request_id' => $requestModel->id];
	}

	/**
	 * Returns request info by its ID
	 */
	public function requestInfo(Request $request, $request_id) {
		// get request from DB
		$request = $this->user->requests()->find($request_id);
		if ($request == NULL) {
			throw new ApiException('Request not found.');
		}

		return $request;
	}

	/**
	 * Returns request info by its ID
	 */
	public function downloadRequest(Request $request, $request_id) {
		// get request from DB
		$request = $this->user->requests()->find($request_id);
		if ($request == NULL) {
			throw new ApiException('Request not found.');
		}

		return response()->download($request->getStoragePathname());
	}

}
