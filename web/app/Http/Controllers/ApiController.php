<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem;
use Illuminate\Http\Request;

use App\User;
use App\Template;

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
		]);

		// save to DB
		$template = new Template([
			'name' => $request->input('name')
		]);
		$this->getUser($request)->templates()->save($template);

		// save to filesystem
		$this->storage->put($template->getStoragePathname(), $request->getContent());

		// return response
		return [
			'template_id' => $template->id,
			'md5' => md5_file($template->getRealPath()),
		];
	}

	public function deleteTemplate() {}
	public function createRequest() {}
	public function requestInfo() {}

}
