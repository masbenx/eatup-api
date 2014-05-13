<?php

class Tag extends Moloquent {

    protected $collection = 'tags';

    public function user(){
    	return $this->belongsTo('User', 'user_id', '_id');
    }

    public function venue_tags(){
    	return $this->belongsToMany('Venue', 'venue_tags');
    }

}