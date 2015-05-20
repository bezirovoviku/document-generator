<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\User;
use App\Template;
use App\Request as RequestModel;

class ApiController extends Controller {

	public function __construct(Guard $auth, Filesystem\Factory $storage)
	{
		$this->user = $auth->user();
		$this->storage = $storage;
	}

	/**
	 * @return user selected from DB by X-AUTH header
	 */
	private function getUser($request) {
		$token = $request->header('X-AUTH');
		return User::where(['api_key' => $token])->first();
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
		$this->getUser($request)->templates()->save($template);

		// save to filesystem
		$this->storage->put($template->getStoragePathname(), $request->getContent());

		return [
			'template_id' => $template->id,
			'md5' => md5_file($template->getRealPath()),
		];
	}

	/**
	 * Deletes template by its ID
	 */
	public function deleteTemplate(Request $request, $template_id) {
		// get template from DB
		$template = $this->getUser($request)->templates()->find($template_id);
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
		$user = $this->getUser($request);

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

		$request = new RequestModel([
			'type' => $request->input('type'),
			'data' => json_encode($request->input('data')),
			'callback_url' => $request->input('callback_url'),
		]);

		$template->requests()->save($request);

		// TODO move to cron
		try {
			$request->generate();
		} catch (Exception $e) {
			throw new ApiException('Generating failed.');
		}

		return ['request_id' => $request->id];
	}

	/**
	 * Returns request info by its ID
	 */
	public function requestInfo(Request $request, $request_id) {
		// get request from DB
		$request = $this->getUser($request)->requests()->find($request_id);
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
		$request = $this->getUser($request)->requests()->find($request_id);
		if ($request == NULL) {
			throw new ApiException('Request not found.');
		}

		return response()->download($request->getStoragePathname());
	}

}
