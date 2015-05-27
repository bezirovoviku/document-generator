<?php namespace App;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	const ROLE_USER = 'user';
	const ROLE_ADMIN = 'admin';

	protected $fillable = ['email', 'password', 'request_limit', 'role'];
	protected $hidden = ['password', 'remember_token'];

	public function templates()
	{
		return $this->hasMany('App\Template');
	}

	public function requests()
	{
		return $this->hasMany('App\Request');
	}

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

	public function getUsageHistory()
	{
		$res = [];
		// one year (12 months) ago
		for ($i = 11; $i >= 0; $i--) {
			// key is x-axis label, value is y-value
			$res[date('M Y', strtotime("-$i months"))] = $this->requests()->monthsBefore($i)->count();
		}
		return $res;
	}

	public function regenerateApiKey()
	{
		$this->attributes['api_key'] = md5($this->id . microtime());
	}

	public function setRole($role)
	{
		assert($role == static::ROLE_ADMIN || $role == static::ROLE_USER);
		$this->role = $role;
	}

	public function isAdmin()
	{
		return $this->role == static::ROLE_ADMIN;
	}
}
