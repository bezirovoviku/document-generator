<?php

use App\User;

class DashboardControllerTest extends TestCase
{
	/**
	* Test basic home page display
	*/
	public function testIndex()
	{
		// test visibility of key components
		$user = factory(User::class)->create();
		$this->actingAs($user)
			->visit('/user/dashboard')
			->see('API key')
			->see('Usage history')
			->see('Templates')
			->see('Requests')
			->dontSee('Admin tools');
	}

	/**
	* Test API key is regenerated
	*/
	public function testRegenerateApiKey()
	{
		$user = factory(User::class)->create();
		$old_api_key = $user->api_key;

		$this->actingAs($user)
			->visit('/user/dashboard')
			->see('API key')
			->press('Regenerate')
			->see('API key regenerated')
			->dontSeeInDatabase('users', ['api_key' => $old_api_key]);
	}

	/**
	* Test user limits are updated
	*/
	public function testUpdateLimits()
	{
		// TODO test update limits for other users (not implemented yet)

		$user = factory(User::class)->create();
		$user->setRole(User::ROLE_ADMIN);

		$this->actingAs($user)
			->visit('/user/dashboard')
			->see('Admin tools')
			->type('100', 'request_limit')
			->press('Apply')
			->see('New limits saved')
			->seeInDatabase('users', [
				'email' => $user->email,
				'request_limit' => '100'
			]);
	}

}
