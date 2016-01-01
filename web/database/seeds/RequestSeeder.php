<?php

use Illuminate\Database\Seeder;
use App\Request;
use App\Template;

class RequestSeeder extends Seeder {

	public function run()
	{
		$templates = iterator_to_array(Template::all());

		$faker = Faker\Factory::create();

		for ($i = 0; $i < 100; $i++) {
			$request = new Request([
				'type' => $faker->randomElement(['pdf', 'docx']),
				'callback_url' => $faker->url,
				'created_at' => ($created_at = $faker->dateTimeThisYear),
				'updated_at' => $created_at,
				'generated_at' => ($generated_at = $faker->optional(0.8)->dateTimeBetween($created_at, $created_at->modify('+3 day'))),
				'failed_at' => ($generated_at ? NULL : $faker->optional(0.5)->dateTimeBetween($created_at, $created_at->modify('+3 day'))),
			]);
			$template = $faker->randomElement($templates);
			$request->user()->associate($template->user);
			$template->requests()->save($request);
		}
	}

}
