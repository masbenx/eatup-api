<?php


class User extends Moloquent {

	protected $collection = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'persist_code');

    public function tags()
    {
        return hasMany('Tag', 'user_id', '_id');
    }

    public function ratings()
    {
        return hasMany('Rating', 'user_id', '_id');
    }

}