<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	use DatabaseMigrations;

	protected $baseUrl = 'http://localhost';

	public function setUp()
	{
		parent::setUp();
		$this->runDatabaseMigrations();
	}

	/**
	 * Copy testing template and return UploadedFile instance with it
	 *
	 * @return \Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	public function getTemplateFile()
	{
		$origPath = env_path('template.docx');
		$path = env_path(str_random(10));
		copy($origPath, $path); // Storage::copy works bad

		$orig_name = 'template.docx';
		$mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		$size = 14575;
		$error = null;
		$test = true;
		return new UploadedFile($path, $orig_name, $mime_type, $size, $error, $test);
	}

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';
		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		return $app;
	}

}
