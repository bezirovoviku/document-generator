<?php namespace App\Http\Controllers;

use App\Request as RequestModel;

class RequestController extends Controller {

	/**
	* Shows request details.
	*
	* @param  \Illuminate\Http\Request $request
	* @return \Illuminate\Http\Response
	*/
	public function show(RequestModel $request)
	{
		$this->authorize('show-request', $request);

		return view('request.show')
			->with('request', $request)
			->with('archive_size', $request->getHumanFilesize());
	}

	/**
	* Downloads request.
	*
	* @param  \Illuminate\Http\Request $request
	* @return \Illuminate\Http\Response
	*/
	public function download(RequestModel $request)
	{
		$this->authorize('download-request', $request);
		return response()->download(env_path($request->getStoragePathname()));
	}

}
