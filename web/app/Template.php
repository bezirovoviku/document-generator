<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpFoundation\File\File;
use Storage;

class Template extends Model {

	use SoftDeletes;

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

	public function getMD5()
	{
		return md5_file(env_path($this->getStoragePathname()));
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

	/**
	* Moves given file to filesystem
	*/
	public function saveFile(File $file)
	{
		$path = env_path($this->getStoragePathname());
		return $file->move(dirname($path), basename($path));
	}

	/**
	* Save template data to filesystem
	*/
	public function saveContents($contents)
	{
		return Storage::put($this->getStoragePathname(), $contents);
	}


	// storage path helpers follows

	public static function getTemplateDir()
	{
		return 'templates';
	}

	public function getPath()
	{
		return join(DIRECTORY_SEPARATOR, [static::getTemplateDir(), $this->user->id]);
	}

	public function getFilename()
	{
		return $this->id . '.docx';
	}

	public function getStoragePathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getPath(), $this->getFilename()]);
	}

}
