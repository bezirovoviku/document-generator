<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Docx\Generator;
use DB;

class Request extends Model {

	const TMP_PATH = '/tmp';
	const ARCHIVE_DIR = 'app/archives';

	const STATUS_DONE = 'done';
	const STATUS_FAILED = 'failed';
	const STATUS_IN_PROGRESS = 'in_progress';

	protected $fillable = ['type', 'data', 'callback_url'];
	protected $dates = ['created_at', 'updated_at', 'generated_at'];
	protected $appends = ['status'];
	protected $visible = ['id', 'template_id', 'status'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function template()
	{
		return $this->belongsTo('App\Template')->withTrashed();
	}

	public function scopeLastMonth($query)
	{
		return $query->where('requests.created_at', '>', DB::raw('date_sub(curdate(), interval 1 month)'));
	}

	public function scopeNewestFirst($query)
	{
		return $query->orderBy('updated_at', 'DESC');
	}

	public function scopeMonthsBefore($query, $months)
	{
		assert(is_int($months) && $months >= 0);
		return $query->where('requests.created_at', DB::raw('between'), DB::raw(
			'date_sub(curdate(), interval ' . ($months + 1) . ' month) and ' .
			'date_sub(curdate(), interval ' . $months . ' month)')
		);
	}

	public function getStatusAttribute()
	{
		if ($this->generated_at) {
			return static::STATUS_DONE;
		} elseif ($this->failed_at) {
			return static::STATUS_FAILED;
		} else {
			return static::STATUS_IN_PROGRESS;
		}
	}

	public function setStatusAttribute($status)
	{
		if ($status == static::STATUS_DONE) {
			$this->generated_at = $this->freshTimestamp();
		} elseif ($status == static::STATUS_FAILED) {
			$this->failed_at = $this->freshTimestamp();
		} else {
			throw new Exception('Unknown request status value.');
		}
	}

	/**
	* Generate archive from template and data.
	*/
	public function generate()
	{
		$generator = new Generator();
		$generator->addFilters();
		$generator->setTmp(static::TMP_PATH);
		$generator->setTemplate($this->template->getRealPathname());
		$generator->generateArchive(json_decode($this->data, true), $this->getStoragePathname());
	}

	/**
	* Ping callback url if set.
	*/
	public function ping()
	{
		if ($this->callback_url) {
			$ch = curl_init($this->callback_url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 2);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
			curl_exec($ch);
			curl_close($ch);
		}
	}

	// storage path helpers

	public function getPath()
	{
		return $this->user->id;
	}

	public function getFilename()
	{
		return $this->id . '.zip';
	}

	public function getPathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getPath(), $this->getFilename()]);
	}

	public function getStoragePathname()
	{
		return join(DIRECTORY_SEPARATOR, [storage_path(), static::ARCHIVE_DIR, $this->getPathname()]);
	}

}
