<?php

class Comment extends Moloquent {

    protected $collection = 'comments';

    public function venue() {
        return $this->belongsTo('Venue', 'venue_id', '_id');
    }

    public function user() {
    	return $this->belongsTo('User', 'user_id', '_id');
    }

}