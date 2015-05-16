<?php namespace App;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	protected $fillable = ['email', 'password', 'request_limit'];
	protected $hidden = ['password', 'remember_token'];

	public function templates()
	{
		return $this->hasMany('App\Template');
	}

	public function requests()
	{
		return $this->hasManyThrough('App\Request', 'App\Template');
	}

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

	public function regenerateApiKey()
	{
		$this->attributes['api_key'] = md5($this->id . microtime());
	}

	public function isAdmin()
	{
		return true;
	}
}
