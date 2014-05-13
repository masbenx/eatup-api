<?php



class VenueSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$faker = Faker\Factory::create();

		Venue::truncate();
		Rating::truncate();

		foreach (range(1,10) as $index) {
			$food = rand(1,5);
			$coziness = rand(1,5);
			$price = rand(1,5);
			$overall = intval(($food+$coziness+$price)/3);

			$lat = $faker->latitude;
			$lng = $faker->longitude;
			$data = Venue::create([
				'name' => $faker->unique()->sentence(2),
				'user_id' => new MongoId('536f829eb109c0a95c000000'), 
				'address' => $faker->address,
				'city' => $faker->city,
				'country' => $faker->country,
				'header' => 'noheader.jpg',
				'image' => 'noimage.jpg',
				'open' => strtotime($faker->time($format = 'H:i:s')),
				'close' => strtotime($faker->time($format = 'H:i:s')),
				'longitude' => $lng,
				'latitude' => $lat,
				'loc' => [floatval($lng), floatval($lat)],
				'overall' => $overall,
				'food' => $food,
				'price' => $price,
				'coziness' => $coziness,
				'counter' => 1
				]);

			
			Rating::create([
				'user_id' => new MongoId('536f829eb109c0a95c000000'),
				'venue_id' => new MongoId($data->id),
				'overall' => $overall,
				'food' => $food,
				'price' => $price,
				'coziness' => $coziness
				]);
				
		}
	}

}
