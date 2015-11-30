<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLimitsRequest;
use App\Jobs\GenerateRequest;
use App\Request as TemplateRequest;

class RequestController extends Controller {

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
	
	public function show(TemplateRequest $request)
	{
		$this->authorize('show-request', $request);

		return view('request.show')
			->with('request', $request)
			->with('archive_size', file_exists($request->getStoragePathname()) ? $this->human_filesize(filesize($request->getStoragePathname())) : null);
	}
	
	public function download(TemplateRequest $request)
	{
		$this->authorize('show-request', $request);
		
		return response()->download($request->getStoragePathname());
	}

}
