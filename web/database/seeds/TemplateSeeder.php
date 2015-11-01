<?php

use Illuminate\Database\Seeder;
use App\Template;
use App\User;

class TemplateSeeder extends Seeder {

	public function run()
	{
	$users = iterator_to_array(User::all());

	$faker = Faker\Factory::create();

	for ($i = 0; $i < 20; $i++) { 
		$template = new Template([
			'name' => $faker->sentence(3),
		]);
		$faker->randomElement($users)->templates()->save($template);
	}
	}

}
