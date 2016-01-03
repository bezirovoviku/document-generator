<?php namespace App;

use DB;
use Storage;
use Illuminate\Database\Eloquent\Model;
use Docx\Generator;
use Docx\Converter\OPDF;
use League\Csv\Reader;
use Nathanmac\Utilities\Parser\Facades\Parser;

class Request extends Model {

	const TMP_PATH = '/tmp';

	const STATUS_DONE = 'done';
	const STATUS_FAILED = 'failed';
	const STATUS_IN_PROGRESS = 'in_progress';

	protected $fillable = ['type', 'callback_url'];
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
		$driver = DB::connection()->getDriverName();
		if ($driver == 'mysql') {
			return $query->where('requests.created_at', '>', DB::raw('date_sub(curdate(), interval 1 month)'));
		} elseif ($driver == 'sqlite') {
			return $query->where('requests.created_at', '>', DB::raw('datetime("now", "-1 month")'));
		} else {
			throw new Exception('Unexpected DB driver.');
		}
	}

	public function scopeNewestFirst($query)
	{
		return $query->orderBy('updated_at', 'DESC');
	}

	public function scopeMonthsBefore($query, $months)
	{
		assert(is_int($months) && $months >= 0);
		$driver = DB::connection()->getDriverName();
		if ($driver == 'mysql') {
			return $query->where('requests.created_at', DB::raw('between'), DB::raw(
				'date_sub(curdate(), interval ' . ($months + 1) . ' month) and ' .
				'date_sub(curdate(), interval ' . $months . ' month)')
			);
		} elseif ($driver == 'sqlite') {
			return $query->where('requests.created_at', DB::raw('between'),
				DB::raw('datetime("now", "-' . ($months + 1) . ' month") and datetime("now", "-' . $months . ' month")')
			);
		} else {
			throw new Exception('Unexpected DB driver.');
		}
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
			throw new \Exception('Unknown request status value.');
		}
	}

	public function getDataAttribute() {
		return json_decode($this->attributes['data'], true);
	}

	public function setDataAttribute($data) {
		$this->attributes['data'] = (is_object($data) || is_array($data)) ? json_encode($data) : $data;
	}

	/**
	 * Set request data
	 * @param string|object $data data content
	 * @param string $type data type, csv/json/xml expected.
	 * @return array|null result data or null when parsing failed
	 */
	public function setData($data, $type = 'json') {
		switch($type) {
			case 'json':
				if (is_object($data) || is_array($data)) {
					$this->data = $data;
				} elseif (is_string($data)) {
					$this->data = Parser::json($data);
				} else {
					throw new \Exception('Unknown JSON data format.');
				}
				break;

			case 'xml':
				$xml = Parser::xml($data);
				if (!$xml) {
					throw new \Exception('Cannot parse XML.');
				}

				$data = [];
				foreach($xml as $key => $value) {
					if (is_array($value))
						$data = array_merge($data, $value);
					else
						$data[] = $value;
				}
				$this->data = $data;
				break;

			case 'csv':
				$reader = Reader::createFromString($data);
				$reader->setDelimiter(';');

				// load header, which is required
				$header = $reader->fetchOne();
				if (!$header || count($header) == 0) {
					throw new \Exception('Missing header in CSV file.');
				}

				// parse each row
				$data = [];
				foreach($reader as $index => $row) {
					if ($index == 0) continue; // skip header
					$columns = [];
					foreach($row as $column => $value) { // parse each column in each row
						if (!isset($header[$column])) {
							throw new \Exception('Unknown column in CSV file.');
						}
						$columns[$header[$column]] = $value;
					}
					$data[] = $columns;
				}

				$this->data = $data;
				break;

			default:
				throw new \Exception('Unknown data format.');
		}
	}

	/**
	* Generate archive from template and data.
	*/
	public function generate()
	{
		$generator = new Generator();
		$converter = null;
		if ($this->type == 'pdf')
			$converter = new OPDF();

		$generator->addFilters();
		$generator->setTmp(static::TMP_PATH);
		$generator->setTemplate(env_path($this->template->getStoragePathname()));
		$generator->generateArchive($this->data, env_path($this->getStoragePathname()), $converter);
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

	public function getHumanFilesize()
	{
		if (!Storage::exists($this->getStoragePathname()))
			return null;

		$bytes = Storage::size($this->getStoragePathname());
		$decimals = 2;

 		$size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
 		$factor = floor((strlen($bytes) - 1) / 3);
 		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}

	// storage path helpers

	public static function getArchiveDir()
	{
		return 'archives';
	}

	public function getPath()
	{
		return join(DIRECTORY_SEPARATOR, [static::getArchiveDir(), $this->user->id]);
	}

	public function getFilename()
	{
		return $this->id . '.zip';
	}

	public function getStoragePathname()
	{
		return join(DIRECTORY_SEPARATOR, [$this->getPath(), $this->getFilename()]);
	}

}
