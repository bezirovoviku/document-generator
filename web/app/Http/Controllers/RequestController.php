<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLimitsRequest;
use App\Jobs\GenerateRequest;
use App\Request as TemplateRequest;

class RequestController extends Controller {

	/**
    * Creates a new request controller instance.
    *
    * @param Illuminate\Contracts\Auth\Guard $auth
    * @return void
    */
	public function __construct(Guard $auth)
	{
		$this->user = $auth->user();
	}

	//@TODO: This should be somewhere inside lavarel. Failed to find it. At least move this somewhere more appropriate
	protected function human_filesize($bytes, $decimals = 2) {
		$size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}
	
	/**
    * Shows request details.
    *
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
	public function show(TemplateRequest $request)
	{
		$this->authorize('show-request', $request);

		return view('request.show')
			->with('request', $request)
			->with('archive_size', file_exists($request->getStoragePathname()) ? $this->human_filesize(filesize($request->getStoragePathname())) : null);
	}
	
	/**
    * Downloads request.
    *
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
	public function download(TemplateRequest $request)
	{
		$this->authorize('show-request', $request);
		
		return response()->download($request->getStoragePathname());
	}

}
