<?php namespace App\Http\Middleware;

use Exception;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\User;
use App\Exceptions\ApiException;

class VerifyApiKey {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$token = $request->header('X-AUTH');
		$user = User::where(['api_key' => $token])->first();
		if ($user) {
			$this->auth->login($user);
			return $next($request);
		} else {
			throw new ApiException('Invalid API key.', 401);
		}

	}

}
