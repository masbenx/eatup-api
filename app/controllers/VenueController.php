<?php

use \Allspark\Transformers\VenueTransformer;

class VenueController extends ApiController {

	/*
	 * @var Allspark\Transformer
	 */

	protected $venuetransformer;

	function __construct(VenueTransformer $venuetransformer){
		$this->venuetransformer = $venuetransformer;
	}

	public function nearby() {
		if(( ! Input::has('lng')) && ( ! Input::has('lat'))){
			return $this->respondNotFound('venue does not exist');
		}

		$lng = Input::get('lng');
		$lat = Input::get('lat');
		$rad = (Input::has('rad')) ? Input::get('rad') : 35 ;
		// $lng = -79.961645; 
		// $lat = 14.686952;
		$radiusOfEarth = 6378.1; //avg radius of earth in km

		$list = Venue::raw(function($collection) use ($radiusOfEarth, $lng, $lat, $rad)
		{
			$collection->ensureIndex(array('loc' => '2d'));

			return $collection->find(array('loc' =>
				array('$within' =>
					array('$centerSphere' =>
						array(
							array(floatval($lng), floatval($lat)), $rad/$radiusOfEarth
						)
					)
				)
			));
		});

		return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $this->venuetransformer->transformCollection($list->toArray()),
			// 'data' => $list->toArray(),
		]);
	}

	public function find(){
		if ( ! Input::has('name')) {
    		return $this->respondNotFound('venue name must be include');
		}

		$name = Input::get('name');
		$list = Venue::where('name', 'like', '%'.$name.'%')->get();

		return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $this->venuetransformer->transformCollection($list->toArray()),
			// 'data' => $list->toArray(),
		]);
	}

	public function advanceSearch(){
		//rating
		$overall = (Input::has('overall')) ? Input::get('overall') : 0;
		$coziness = (Input::has('coziness')) ? Input::get('coziness') : 0;
		$food = (Input::has('food')) ? Input::get('food') : 0;
		$price = (Input::has('price')) ? Input::get('price') : 0;

		//name
		$name = (Input::has('name')) ? Input::get('name') : "";

		//open hour
		$date = strtotime(date("H:i:s"));
		// return date('H:i:s', $date);

		$venue = Venue::where('name', 'like', '%'.$name.'%')
			 ->where('open', '<=', $date)
			 ->where('close', '>=', $date)
			 ->where('overall', '>=', intval($overall))
			 ->where('coziness', '>=', intval($coziness))
			 ->where('food', '>=', intval($food))
			 ->where('price', '>=', intval($price))
			 ->get();

		return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $this->venuetransformer->transformCollection($venue->toArray()),
			//'data' => $venue->toArray(),
		]);
	}

	public function detail() {
		if ( ! Input::has('id')){
			return $this->respondNotFound('venue name must be include');
		}

		$venueId = Input::get('id');
		$venue = Venue::with(array(
			'owner',
			'comments' => function($q){
				$q->with('user');
			}))
			->where('_id', '=', new MongoId($venueId))->first();

		return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $this->venuetransformer->transform($venue->toArray()),
			// 'data' => $venue->toArray(),
		]);
	}

	public function create(){
		$payload =  Request::header('X-Auth-Token');
	    $userModel = Sentry::getUserProvider()->createModel();
	    $user =  $userModel->where('api_token',$payload)->first();

	    //user_id
	    $user_id = $user->_id;

	    if ( ! Input::has('name')) {
	    	return $this->respondNotFound('venue field must be include');
	    }

	    $venue = new Venue;
	    $venue->name = Input::get('name');
	    $venue->user_id = new MongoId($user_id);
	    $venue->address = Input::get('address');
	    $venue->city = Input::get('city');
	    $venue->country = Input::get('country');
	    $venue->open = Input::get('open');
	    $venue->close = Input::get('close');
	    $venue->longitude = Input::get('longitude');
	    $venue->latitude = Input::get('latitude');
	    $venue->loc = [floatval(Input::get('longitude')), floatval(Input::get('latitude'))]; 

	    if ( ! $venue->save()) {
	    	return $this->respondInternalError();
	    }

	    return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $this->venuetransformer->transform($venue->toArray()),
			// 'data' => $venue->toArray(),
		]);
	}

	public function addToTag(){
		$payload =  Request::header('X-Auth-Token');
	    $userModel = Sentry::getUserProvider()->createModel();
	    $user =  $userModel->where('api_token',$payload)->first();

	    //user_id
	    $user_id = $user->_id;

	    if (( ! Input::has('venue')) || ( ! Input::has('tag'))) {
	    	return $this->respondNotFound('venue and tag field must be include');
	    }

	    $venue = Venue::find(Input::get('venue'));
	    // return $venue;
	    $venue->tags()->attach(new MongoId(Input::get('tag')));

	    return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $this->venuetransformer->transform($venue->toArray()),
			// 'data' => $venue->toArray(),
		]);
	}

	public function rate(){
		$payload =  Request::header('X-Auth-Token');
	    $userModel = Sentry::getUserProvider()->createModel();
	    $user =  $userModel->where('api_token',$payload)->first();

	    //user_id
	    $user_id = $user->_id;

	    if ( ! Input::has('venue')){
	    	return $this->respondNotFound('venue field must be include');
	    }

	    $rating = new Rating;
	    $rating->venue_id = Input::get('venue');
	    $rating->user_id = $user_id;
	    $rating->food = Input::get('food');
	    $rating->coziness = Input::get('coziness');
	    $rating->price = Input::get('price');

	    if ( ! $rating->save()){
	    	return $this->respondInternalError();
	    }
	    
	    return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $rating->toArray(),
		]);
	}
}