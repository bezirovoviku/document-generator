<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Docx\Generator;
use DB;

class Request extends Model {

	protected $fillable = ['type', 'data', 'callback_url'];
	protected $dates = ['created_at', 'updated_at', 'generated_at'];
	protected $appends = ['status'];
	protected $visible = ['id', 'template_id', 'status'];

	public function user()
	{
		return $this->template->user();
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
			return 'done';
		} else {
			return 'in_progress';
		}
	}

	public function generate()
	{
		$generator = new Generator();
		$generator->setTmp('/tmp');
		$generator->setTemplate($request->template->getRealPathname());
		$generator->generateArchive(json_decode($request->data, true), storage_path() . '/app/archives/' . md5($request->id) . '.zip');
		$this->generated_at = $request->freshTimestamp();
	}

}
