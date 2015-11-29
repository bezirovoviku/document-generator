<?php namespace App\Http\Controllers;

use Validator;
use Config;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\Jobs\GenerateRequest;
use App\User;
use App\Template;
use App\Request as RequestModel;
use League\Csv\Reader;
use Nathanmac\Utilities\Parser\Facades\Parser;

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
		// not using $this->validate because of filesize is not normal input
		$validator = Validator::make([
			'name' => $request->input('name'),
			'filesize' => $request->header('Content-Length'),
		], [
			'name' => 'required|max:255',
			'filesize' => 'required|integer|min:0|max:' . Config::get('app.template_max_size'),
		]);
		if ($validator->fails()) {
			return response(['error' => $validator->errors()], 400);
		}

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
	 * Parses request data
	 * @param string $type data type, csv/json/xml expected.
	 * @param string|object $data data content
	 * @return array|null result data or null when parsing failed
	 */
	private function parseData($type, $data) {
		//json in json
		if (is_object($data) || is_array($data)) {
			return $type == 'json' ? $data : null;
		}
		
		//text data, possibly json too
		if (is_string($data)) {
			//@TODO: Better way to tree this?
			switch($type) {
				case 'xml':
					$xml = Parser::xml($data);
					if (!$xml)
						return null;
					
					$data = array();
					foreach($xml as $key => $value) {
						if (is_array($value))
							$data = array_merge($data, $value);
						else
							$data[] = $value;
					}
					
					return $data;
				case 'json':
					return Parser::json($data);
				case 'csv':
					$reader = Reader::createFromString($data);
					$reader->setDelimiter(';');
					
					//Load header, which is required
					$header = $reader->fetchOne();
					if (!$header || count($header) == 0) {
						return null;
					}
					
					//Parse each row
					$data = array();
					foreach($reader as $index => $row) {
						if ($index == 0)
							continue;
						
						$columns = array();
						foreach($row as $column => $value) {
							if (!isset($header[$column]))
								return null;
								
							$columns[$header[$column]] = $value;
						}
						$data[] = $columns;
					}
					
					return $data;
				default:
					return null;
			}
		}
		
		return null;
	}

	/**
	 * Creates request to generate
	 */
	public function createRequest(Request $request) {
		$user = $this->user;

		if ($user->isOverRequestLimit()) {
			throw new ApiException('Request limit exceeded.');
		}

		$this->validate($request, [
			'template_id' => 'required|exists:templates,id,user_id,'.$user->id,
			'type' => 'required|in:pdf,docx',
			'data' => 'required',
			'data_type' => 'in:json,xml,csv',
			'callback_url' => 'url',
		]);

		// get template from DB
		$template = $user->templates()->find($request->input('template_id'));
		if ($template == NULL) {
			throw new ApiException('Template not found.');
		}

		//Validate and parse input data
		$data_type = $request->input('data_type', 'json');
		$data = $request->input('data');
		
		//json in json
		$data = $this->parseData($data_type, $data);
		
		//No data or parsing failed
		if (is_null($data) || count($data) == 0) {
			throw new ApiException('Data are empty or in invalid format.');
		}
		
		$requestModel = new RequestModel([
			'type' => $request->input('type'),
			'data' => json_encode($data),
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
