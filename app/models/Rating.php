<?php

class Rating extends Moloquent {

    protected $collection = 'venue_ratings';

    public function user() {
        return $this->belongsTo('User', 'user_id', '_id');
    }

    public function venue() {
        return $this->belongsTo('Venue', 'venue_id', '_id');
    }
}