<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model {

	protected $fillable = ['name', 'filename'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function requests()
	{
		return $this->hasMany('App\Request');
	}

}
