<?php

use Illuminate\Database\Seeder;
use App\Template;
use App\User;

class TemplateSeeder extends Seeder {

	public function run()
	{
		$template = factory(Template::class, 20)->make()->each(function($template) {
			User::orderByRaw('RAND()')->firstOrFail()->templates()->save($template);
		});
	}

}
