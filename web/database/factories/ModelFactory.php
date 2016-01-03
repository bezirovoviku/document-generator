<?php namespace App;

use Faker\Generator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Generator $faker) {
	return [
		'email' => $faker->unique()->email,
		'password' => 'pass',
		'request_limit' => $faker->numberBetween(0, 300),
	];
});


$factory->define(Template::class, function (Generator $faker) {
	return [
		'name' => $faker->sentence(3),
	];
});


$factory->define(Request::class, function (Generator $faker) {
	return [
		'type' => $faker->randomElement(['pdf', 'docx']),
		'callback_url' => $faker->url,
		'created_at' => ($created_at = $faker->dateTimeThisYear),
		'updated_at' => $created_at,
		'generated_at' => ($generated_at = $faker->optional(0.8)->dateTimeBetween($created_at, $created_at->modify('+3 day'))),
		'failed_at' => ($generated_at ? NULL : $faker->optional(0.5)->dateTimeBetween($created_at, $created_at->modify('+3 day'))),
	];
});
