<?php namespace Allspark\Transformers;

class FacilityTransformer extends Transformer {

	public function transform($facility){
		
		return [
			'id' => $facility['_id'],
			'name' => $facility['name'],
			'active' => (boolean) $facility['bool']
		];
		
	}
}