<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder {

	public function run()
	{
		User::create([
			'email' => 'admin@admin.cz',
			'password' => 'heslo',
		]);

        $faker = Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) { 
        	User::create([
				'email' => $faker->unique()->email,
				'password' => 'heslo',
				'request_limit' => $faker->numberBetween(0, 300),
			]);
        }
	}

}
