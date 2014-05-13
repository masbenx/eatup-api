<?php

Route::get('trial', function(){
    $time = date("H:i:s");
    echo strtotime("17:00:00");
    echo "<br>";
    echo strtotime("18:00:00");
});

Route::get('create', function()
{
    $user = Sentry::createUser(array(
        'email'     => 'masbenx@eatup.com',
        'password'  => 'rahasia',
        'activated' => true,
    ));

    return 'User Created';
});

Route::post('api/v1/login',function()
{
    try
    {
        $user = Sentry::authenticate(Input::all(), false);

        $token = hash('sha256',Str::random(10),false);

        $user->api_token = $token;

        $user->save();

        return Response::json(array('token' => $token, 'user' => $user->toArray()));
    }
    catch(Exception $e)
    {
        App::abort(404,$e->getMessage());
    }
});

Route::filter('auth.token', function($route, $request)
{
    //$payload = $request->post('X-Auth-Token');
    $payload = Input::get('X-Auth-Token');

    $userModel = Sentry::getUserProvider()->createModel();

    $user =  $userModel->where('api_token',$payload)->first();

    if(!$user) {

        $response = Response::json([
            'error' => true,
            'message' => 'Not authenticated',
            'code' => 401],
            401
        );

        $response->header('Content-Type', 'application/json');
    return $response;
    }

});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

/*
| API Routes
*/
Route::group(array('prefix' => 'api/v1'), function() {
    Route::any('venue/nearby', array('as'=>'venue.nearby', 'uses'=>'VenueController@nearby'));
    Route::any('venue/detail', array('as'=>'venue.detail', 'uses'=>'VenueController@detail'));
});

Route::group(array('prefix' => 'api/v1', 'before' => 'auth.token'), function() {
	Route::any('/', function() {
        return Response::json([
            'status' => 'success',
            'data' => 'protected resource',
        ]);
      });
	Route::any('facility', array('as'=>'facility', 'uses'=>'FacilityController@listAll'));
	Route::any('facility/add', array('as'=>'facility.add', 'uses'=>'FacilityController@add'));
	Route::any('facility/{id}', array('as'=>'facility.show', 'uses'=>'FacilityController@show'));	

    //route main page
    Route::any('venue/find', array('as'=>'venue.find', 'uses'=>'VenueController@find'));
    Route::any('tag/my', array('as'=>'tag.my', 'uses'=>'TagController@myTags'));

    //route find restaurant


    //route tags
    Route::any('tag/create', array('as'=>'tag.create', 'uses'=>'TagController@create'));
    Route::any('tag/find', array('as'=>'tag.find', 'uses'=>'TagController@find'));

    //route detail venue
    Route::any('venue/create', array('as'=>'venue.create', 'uses'=>'VenueController@create'));
    Route::any('venue/tagging', array('as'=>'venue.tagging', 'uses'=>'VenueController@addToTag'));
    Route::any('venue/advance', array('as'=>'venue.advance', 'uses'=>'VenueController@advanceSearch'));

});

