<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * Test basic home page display
	 */
	public function testIndex()
	{
		// test visibility of key components
		$this->visit('/')
			->see('Documentation')
			->see('Login')
			->see('Register')
			->see('How does it work')
			->dontSee('Go to your dashboard');
	}

	/**
	 * Test user registration
	 */
	public function testRegister()
	{
		$this->visit('/')
			->see('Register')
			->type('some@user.com', 'email')
			->type('such_password_very_secure', 'password')
			->type('such_password_very_secure', 'password_confirmation')
			->press('Register')
			->seePageIs('/user/dashboard')
			->see('Welcome to your new account')
			->seeInDatabase('users', ['email' => 'some@user.com']);
	}

	/**
	 * Test user login and logout
	 */
	public function testLoginLogout()
	{
		$password = 'such_password_very_secure';
		$user = factory(App\User::class)->create([
			'password' => $password,
		]);

		$this->visit('/')
			->see('Login')
			->type($user->email, 'email')
			->type($password, 'password')
			->press('Login')
			->seePageIs('/user/dashboard')
			->see('Logged in');

		$this->visit('/')
			->see('Go to your dashboard')
			->click('logout')
			->see('Login')
			->dontSee('Go to your dashboard');
	}

	/**
	 * Test documentation page
	 */
	public function testDocs()
	{
		// test visibility of key components
		$this->visit('/docs')
			->see('Documentation')
			->see('Contents') // TOC is presented
			// main headings
			->see('API')
			->see('Templates')
			->see('Examples');
	}
}
