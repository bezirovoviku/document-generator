<?php

use App\User;
use App\Template;
use App\Request;

class RequestControllerTest extends TestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create([
			'request_limit' => 0,
		]);
		$this->template = factory(Template::class)->make();
		$this->user->templates()->save($this->template);
		$this->request = factory(Request::class)->make();
		$this->request->user()->associate($this->template->user);
		$this->template->requests()->save($this->request);

		// login and go to template show URL
		$this->actingAs($this->user);
	}

	/**
	* Test request detail page display
	*/
	public function testShow()
	{
		$this->visit('/user/request/' . $this->request->id . '/show')
			->see($this->template->name)
			->see($this->request->id)
			->see('Request data')
			->see('Template')
			->see('Created')
			->see('Status');
	}

	/**
	* Download request
	*/
	public function testDownload()
	{
		// save template file to storage
		$file = $this->getTemplateFile();
		$this->template->saveFile($file);

		// create a request
		$this->visit('/user/template/' . $this->template->id . '/show')
			->type('{"name": "Hildegard Testimen"}', 'data')
			->press('Submit a request')
			->see('Request generated');

		// get newly created request
		$new_request = $this->template->requests()->newestFirst()->firstOrFail();

		// download request
		$response = $this->call('GET', '/user/request/' . $new_request->id . '/download');
		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class, $response);
	}


}
