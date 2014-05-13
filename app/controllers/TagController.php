<?php


class TagController extends ApiController {

	/**
	 * list for tag user
	 */
	public function myTags(){
		$payload =  Request::header('X-Auth-Token');
	    $userModel = Sentry::getUserProvider()->createModel();
	    $user =  $userModel->where('api_token',$payload)->first();

	    //user_id
	    $user_id = $user->_id;

	    //tags
	    $tags = Tag::where('user_id', '=', new MongoId($user_id))->get();
	    return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $tags->toArray(),
		]);
	}

	public function create(){
		$payload =  Request::header('X-Auth-Token');
	    $userModel = Sentry::getUserProvider()->createModel();
	    $user =  $userModel->where('api_token',$payload)->first();

	    //user_id
	    $user_id = $user->_id;

	    if ( ! Input::has('name')) {
	    	return $this->respondNotFound('input field must be include');
	    }

	    $tag = new Tag;
	    $tag->name = Input::get('name');
	    $tag->user_id = new MongoId($user_id);

	    if ( ! $tag->save()){
	    	return $this->respondInternalError();
	    }

	    return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $tag->toArray(),
		]);
	}

	public function find(){
		$payload =  Request::header('X-Auth-Token');
	    $userModel = Sentry::getUserProvider()->createModel();
	    $user =  $userModel->where('api_token',$payload)->first();

	    //user_id
	    $user_id = $user->_id;

	    if ( ! Input::has('name')){
	    	return $this->respondNotFound('input name must be include');
	    }

	    //tags
	    $name = Input::get('name');
	    $tags = Tag::where('name', 'like', '%'.$name.'%')->where('user_id', '=', new MongoId($user_id))->get();
	    return $this->setStatusCode(200)->respond([
			'status' => 'success',
			'data' => $tags->toArray(),
		]);

	}

}