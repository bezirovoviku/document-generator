<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Request extends Model {

	protected $fillable = ['type', 'data', 'callback_url'];
	protected $dates = ['created_at', 'updated_at', 'generated_at'];

	public function user()
	{
		return $this->template->user();
	}

	public function template()
	{
		return $this->belongsTo('App\Template');
	}

    public function scopeLastMonth($query)
    {
        return $query->where('requests.created_at', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'));
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
