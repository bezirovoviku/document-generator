<?php namespace App\Http\Middleware;

use Exception;
use Closure;
use App\User;
use App\Exceptions\ApiException;

class VerifyApiKey {

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
		$user = User::where(['api_key' => $token])->exists();
		if ($user) {
			return $next($request);
		} else {
			throw new ApiException('Invalid API key.', 401);
		}

	}

}
