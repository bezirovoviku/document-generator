<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder {

	public function run()
	{
		$user = new User([
			'email' => 'admin@admin.cz',
			'password' => 'pass',
		]);
		$user->setRole(User::ROLE_ADMIN);
		$user->save();

		$faker = Faker\Factory::create();
		for ($i = 0; $i < 10; $i++) { 
			User::create([
				'email' => $faker->unique()->email,
				'password' => 'pass',
				'request_limit' => $faker->numberBetween(0, 300),
			]);
		}
	}

}
