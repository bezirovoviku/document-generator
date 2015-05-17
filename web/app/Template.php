<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model {

	use SoftDeletes;

	protected $fillable = ['name'];

	const TEMPLATE_DIR = 'app/templates';

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

	// storage path helpers follows

	public function getPath()
	{
		return $this->user->id;
	}

	public function getFilename()
	{
		return $this->id . '.docx';
	}

	public function getRealPath()
	{
		return join(DIRECTORY_SEPARATOR, [storage_path(), static::TEMPLATE_DIR, $this->getPath()]);
	}

	public function getPathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getPath(), $this->getFilename()]);
	}

}
