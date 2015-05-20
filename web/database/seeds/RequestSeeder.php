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
				'data' => NULL, // TODO
				'callback_url' => $faker->url,
				'created_at' => ($created_at = $faker->dateTimeThisYear),
				'updated_at' => $created_at,
				'generated_at' => $faker->optional(0.8)->dateTimeBetween($created_at, $created_at->modify('+3 day')),
			]);
			$faker->randomElement($templates)->requests()->save($request);
		}
	}

}
