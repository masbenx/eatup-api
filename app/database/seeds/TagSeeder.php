<?php

class TagSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Faker\Factory::create();

		Tag::truncate();

		foreach (range(1,4) as $index) {
			$lat = $faker->latitude;
			$lng = $faker->longitude;
			Tag::create([
				'name' => $faker->sentence(2),
				'user_id' => new MongoId('536f829eb109c0a95c000000'),
				]);
		}
	}

}