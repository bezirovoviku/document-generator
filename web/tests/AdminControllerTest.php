<?php

use App\User;
use App\Template;
use App\Request;

class AdminControllerTest extends TestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
		$this->user->setRole(User::ROLE_ADMIN);

		$this->template = factory(Template::class)->make();
		$this->user->templates()->save($this->template);

		$this->request = factory(Request::class)->make();
		$this->request->user()->associate($this->template->user);
		$this->template->requests()->save($this->request);

		$this->actingAs($this->user);
	}

	/**
	* Test all user list
	*/
	public function testUsers()
	{
		$this->visit('/admin/users')
			->see($this->user->id)
			->see($this->user->email)
			->see('admin');
	}

	/**
	* Test all request list
	*/
	public function testRequests()
	{
		$this->visit('/admin/requests')
			->see($this->request->id)
			->see($this->user->email)
			->see($this->template->name)
			->see('details');
	}

}
