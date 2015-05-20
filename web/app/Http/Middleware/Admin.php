<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Admin {

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
		if ($this->auth->check() && $this->auth->user()->isAdmin()) {
			return $next($request);
		} else {
			if ($request->ajax()) {
				return response('Unauthorized.', 401);
			} else {
				abort(401, 'You are not admin.');
			}
		}
	}

}
