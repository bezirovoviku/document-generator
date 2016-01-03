<?php namespace App\Http\Controllers;

use App\Request as RequestModel;

class RequestController extends Controller {

	public function show(RequestModel $request)
	{
		$this->authorize('show-request', $request);

		return view('request.show')
			->with('request', $request)
			->with('archive_size', $request->getHumanFilesize());
	}

	public function download(RequestModel $request)
	{
		$this->authorize('download-request', $request);
		return response()->download(env_path($request->getStoragePathname()));
	}

}
