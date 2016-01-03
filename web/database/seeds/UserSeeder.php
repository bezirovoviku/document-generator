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

		factory(User::class, 10)->create();
	}

}
