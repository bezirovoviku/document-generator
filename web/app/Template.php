<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Template extends Model {

	use SoftDeletes;

	protected $fillable = ['name', 'type'];

	const TEMPLATE_DIR = 'templates';

	/**
	 * @return user associated with the template
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	/**
	 * @return requests associated with the template
	 */
	public function requests()
	{
		return $this->hasMany('App\Request');
	}

	/**
	 * @return usage count
	 */
	public function getUsageCount()
	{
		return $this->requests()->count();
	}

	/**
	 * Deletes template from storage.
	 */
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

	/**
	 * @return path
	 */
	public function getPath()
	{
		return $this->user->id;
	}

	/**
	 * @return filename
	 */
	public function getFilename()
	{
		return $this->id . '.' . $this->type;
	}

	/**
	 * @return real path
	 */
	public function getRealPath()
	{
		return join(DIRECTORY_SEPARATOR, [storage_path(), 'app', static::TEMPLATE_DIR, $this->getPath()]);
	}

	/**
	 * @return real path name
	 */
	public function getRealPathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getRealPath(), $this->getFilename()]);
	}

	/**
	 * @return path name
	 */
	public function getPathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getPath(), $this->getFilename()]);
	}

	/**
	 * @return storage path name 
	 */
	public function getStoragePathname()
	{
		return join(DIRECTORY_SEPARATOR, [static::TEMPLATE_DIR, $this->getPathname()]);
	}

}
