<?php

use \Allspark\Transformers\FacilityTransformer;

class FacilityController extends ApiController {

	/*
	 * @var Allspark\Transformer
	 */

	protected $facilitytransformer;

	function __construct(FacilityTransformer $facilitytransformer){
		$this->facilitytransformer = $facilitytransformer;
	}


	public function listAll()
	{
		$facility = Facility::all();

		return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $this->facilitytransformer->transformCollection($facility->toArray()),
		]);
	}

	public function show($id) {
		$facility = Facility::find($id);
		// return $facility->toArray();
		if ( ! $facility){
			return $this->respondNotFound('facility does not exist');
		}else {
			return $this->setStatusCode(200)->respond([
				'status' => 'success',
				'data' => $this->facilitytransformer->transform($facility)
			]);
		}
	}

	public function add(){
		$facility = new Facility;
		$facility->name = 'toilet';
		$facility->bool = 1;
		$facility->save();
	}

	

}