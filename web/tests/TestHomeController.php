<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestHomeController extends TestCase
{
	/**
	 * Test basic home page display
	 */
	public function testIndex()
	{
		// test visibility of key components
		$this->visit('/')
			->see('Docs');
			->see('Login');
			->see('Register');
			->see('How does it work');
	}

	/**
	 * Test basic home page display
	 */
	public function testDocs()
	{
		// test visibility of key components
		$this->visit('/')
			->see('Docs');
			->see('Login');
			->see('Register');
			->see('How does it work');
	}
}
