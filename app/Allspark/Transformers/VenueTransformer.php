<?php namespace Allspark\Transformers;

class VenueTransformer extends Transformer {

	/**
	 * 
	 */
	private function parseTime($inTime){

	}

	public function transform($venue){
		
		return [
			'id' => $venue['_id'],
			'name' => $venue['name'],
			'user_id' => $venue['user_id'],
			'address' => $venue['address'],
			'city' => $venue['city'],
			'country' => $venue['country'],
			'open' => date('H:i:s', $venue['open']),
			'closed' => date('H:i:s', $venue['close']),
			'image' => asset("/upload/image/".$venue['image']),
			'header' => asset("/upload/image/".$venue['image']),
			'loc' => $venue['loc'],
			'overall' => $venue['overall'],
			'food' => $venue['food'],
			'coziness' => $venue['coziness'],
			'price' => $venue['price'],
			'counter' => $venue['counter'],
		];
		
	}
}