<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Request extends Model {

	const ITEMS_PER_PAGE = 10;

	protected $fillable = ['type', 'data', 'callback_url'];
	protected $dates = ['created_at', 'updated_at', 'generated_at'];

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

}
