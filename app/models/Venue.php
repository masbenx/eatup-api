<?php

class Venue extends Moloquent {

    protected $collection = 'venues';

    public function comments(){
    	return $this->hasMany('Comment', 'venue_id', '_id');
    }

    public function owner(){
    	return $this->belongsTo('User', 'user_id', '_id');
    }

    public function tags(){
    	// return $this->belongsToMany('Tag', 'venue_tags', 'venue_id', 'tag_id');
    	return $this->belongsToMany('Tag', null, 'venue_id', 'tag_id');
    }

    public function ratings(){
    	return $this->hasMany('Rating', 'venue_id', '_id');
    }
}