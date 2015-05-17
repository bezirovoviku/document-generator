<?php namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests\Request;
use App\Template;

class DeleteTemplateRequest extends Request {

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
		return $this->route('template')->user->id == $this->user->id;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [];
	}

}
