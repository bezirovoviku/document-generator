<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model {

	protected $fillable = ['type', 'data', 'callback_url'];
	protected $dates = ['created_at', 'updated_at', 'generated_at'];

	public function user()
	{
		return $this->template->user();
	}

	public function template()
	{
		return $this->belongsTo('App\Template');
	}

	public function getStatusAttribute()
	{
		if ($this->generated_at) {
			return 'done';
		} else {
			return 'in_progress';
		}
	}

}
