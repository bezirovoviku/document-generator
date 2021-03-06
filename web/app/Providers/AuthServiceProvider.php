<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		'App\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
	 * @return void
	 */
	public function boot(GateContract $gate)
	{
		$this->registerPolicies($gate);

		$gate->define('show-template', function($user, $template) {
			return $template->user->id == $user->id;
		});

		$gate->define('delete-template', function($user, $template) {
			return $template->user->id == $user->id;
		});

		$gate->define('create-request', function($user, $request) {
			return $request->user->id == $user->id;
		});

		$gate->define('show-request', function($user, $request) {
			return $request->user->id == $user->id;
		});

		$gate->define('download-request', function($user, $request) {
			return $request->user->id == $user->id;
		});

		$gate->before(function ($user, $ability) {
			if ($user->isAdmin()) {
				return true;
			}
		});
	}
}
