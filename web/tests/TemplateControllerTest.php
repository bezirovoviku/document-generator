<?php

use App\User;
use App\Template;
use App\Request;
use App\Jobs\GenerateRequest;

class TemplateControllerTest extends TestCase
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

		// save template file to storage
		$file = $this->getTemplateFile();
		$this->template->saveFile($file);

		// login and go to template show URL
		$this->actingAs($this->user)->visit('/user/template/' . $this->template->id . '/show');
	}

	public function tearDown()
	{
		$this->template->delete();
	}

	/**
	* Test template detail page display
	*/
	public function testShow()
	{
		$this->see($this->template->name)
			->see('Requests')
			->see($this->request->id);
	}

	/**
	* Test manual request creation
	*/
	public function testCreateRequest()
	{
		$this->see('Test it');

		// JSON
		$this->type('{"name": "Hildegard Testimen"}', 'data')
			->press('Submit a request')
			->see('Request generated')
			->expectsJobs(GenerateRequest::class);

		// CSV
		$this->select('csv', 'data_type')
			->type("name\nHildegard Testimen", 'data')
			->press('Submit a request')
			->see('Request generated');

		// XML
		$this->select('xml', 'data_type')
			->type('<root><document><name>Hildegard Testimen</name></document></root>', 'data')
			->press('Submit a request')
			->see('Request generated');
	}

	/**
	* Test request with URL callback
	*/
	public function testCreateRequestCallback()
	{
		$this->type('{"name": "Hildegard Testimen"}', 'data')
			->type('http://httpbin.org/status/200', 'callback_url')
			->press('Submit a request')
			->see('Request generated');
	}

	/**
	* Test request with file input
	*/
	public function testCreateFileRequest()
	{
		foreach (['json', 'csv', 'xml'] as $type) {
			$this->select('file', 'input_type')
				->select($type, 'data_type')
				->attach(env_path('data.' . $type), 'data_file')
				->press('Submit a request')
				->see('Request generated');
		}
	}

	/**
	* Upload template
	*/
	public function testUploadTemplate()
	{
		$name = str_random(50);
		$this->visit('/user/dashboard')
			->type($name, 'name')
			->attach(env_path('template.docx'), 'template')
			->press('Upload template')
			->see('Template uploaded')
			->seeInDatabase('templates', ['name' => $name]);
	}

	/**
	* Delete template
	*/
	public function testDeleteTemplate()
	{
		$this->visit('/user/dashboard')
			->see($this->template->id)
			->press('delete')
			->see('Template deleted')
			->dontSeeInDatabase('templates', [
				'id' => $this->template->id,
				'deleted_at' => null
			]);
	}


}
