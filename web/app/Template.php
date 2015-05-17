<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model {

	protected $fillable = ['name'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function requests()
	{
		return $this->hasMany('App\Request');
	}

	public function getUsageCount()
	{
		return $this->requests()->count();
	}

	public function getPath()
	{
		return join(DIRECTORY_SEPARATOR, [storage_path(), 'app', $this->user->id]);
	}

	public function getFilename()
	{
		return $this->id . '.docx';
	}

}
