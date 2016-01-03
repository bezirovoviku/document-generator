<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Template extends Model {

	use SoftDeletes;

	protected $fillable = ['name', 'type'];

	const TEMPLATE_DIR = 'templates';

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

	public function delete()
	{
		// delete from filesystem (and quietly ignore errors)
		if (Storage::exists($this->getStoragePathname())) {
			Storage::delete($this->getStoragePathname());
		}

		// delete from DB
		parent::delete();
	}


	// storage path helpers follows

	public function getPath()
	{
		return $this->user->id;
	}

	public function getFilename()
	{
		return $this->id . '.' . $this->type;
	}

	public function getRealPath()
	{
		return join(DIRECTORY_SEPARATOR, [storage_path(), 'app', static::TEMPLATE_DIR, $this->getPath()]);
	}

	public function getRealPathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getRealPath(), $this->getFilename()]);
	}

	public function getPathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getPath(), $this->getFilename()]);
	}

	public function getStoragePathname()
	{
		return join(DIRECTORY_SEPARATOR, [static::TEMPLATE_DIR, $this->getPathname()]);
	}

}
