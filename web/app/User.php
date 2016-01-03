<?php namespace App;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

	use Authenticatable, Authorizable, CanResetPassword;

	const ROLE_USER = 'user';
	const ROLE_ADMIN = 'admin';

	protected $fillable = ['email', 'password', 'request_limit', 'role'];
	protected $hidden = ['password', 'remember_token'];

	/**
	 * @return templates associated with the user
	 */
	public function templates()
	{
		return $this->hasMany('App\Template');
	}

	/**
	 * @return password 
	 */
	public function getAuthPassword() {
		return $this->attributes['password'];
	}

	/**
	 * @return requests associated with the user
	 */
	public function requests()
	{
		return $this->hasMany('App\Request');
	}

	/**
	 * Hashes the password.
	 *
	 * @param $value
	 */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
	 * Returns one year usage history.
	 *
	 * @return array result
	 */
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

	/**
	 * Decides if user exceeds the request limit.
	 *
	 * @return true if user is over request limit, otherwise false
	 */
    public function isOverRequestLimit()
    {
		return $this->request_limit > 0 && $this->requests()->lastMonth()->count() >= $this->request_limit;
    }

    /**
	 * Regenerates a new api key.
	 */
	public function regenerateApiKey()
	{
		$this->attributes['api_key'] = md5($this->id . microtime());
	}

	/**
	 * @param $role role to set
	 */
	public function setRole($role)
	{
		assert($role == static::ROLE_ADMIN || $role == static::ROLE_USER);
		$this->role = $role;
	}

	/**
	 * @return true if role is admin, otherwise false
	 */
	public function isAdmin()
	{
		return $this->role == static::ROLE_ADMIN;
	}
}
