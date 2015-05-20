<?php namespace App\Http\Controllers;

use App\User;
use App\Request;

class ApiController extends Controller {

	public function __construct(Guard $auth, Filesystem\Factory $storage)
	{
		$this->user = $auth->user();
		$this->storage = $storage;
	}

	public function uploadTemplate() {}
	public function deleteTemplate() {}
	public function createRequest() {}
	public function requestInfo() {}

}
