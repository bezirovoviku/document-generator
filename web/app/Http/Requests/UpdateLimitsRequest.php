<?php namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests\Request;

class UpdateLimitsRequest extends Request {

	public function __construct(Guard $auth)
	{
    	$this->user = $auth->user();
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->user->isAdmin();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'request_limit' => 'numeric|min:0'
		];
	}

}
