<?php namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Jsonable;

class ApiException extends Exception implements Jsonable
{
	public function toJson($options = 0)
	{
		$res = ['error' => $this->getMessage()];
		if ($this->getCode()) {
			$res['error_id'] = $this->getCode();
		}
		return json_encode($res, $options);
	}
}
