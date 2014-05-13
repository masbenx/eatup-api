<?php


class UserSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Faker\Factory::create();
 
        // User::truncate();
 
        foreach(range(1,5) as $index)
        {
        	$user = Sentry::createUser(array(
		        'email' => $faker->email,
		        'password'  => 'rahasia',
		        'firstname' => $faker->firstName(),
		        'lastname' => $faker->lastName,
		        'activated' => true,
		    ));
        }
	}

}
