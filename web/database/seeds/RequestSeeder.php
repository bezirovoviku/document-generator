<?php

use Illuminate\Database\Seeder;
use App\Request;
use App\Template;

class RequestSeeder extends Seeder {

	public function run()
	{
		$template = factory(Request::class, 100)->make()->each(function($request) {
			$template = Template::orderByRaw('RAND()')->firstOrFail();
			$request->user()->associate($template->user);
			$template->requests()->save($request);
		});
	}

}
