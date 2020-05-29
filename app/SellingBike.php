<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellingBike extends Model
{
	protected $table = "selling_bikes";

    protected $fillable = ['bike_id','make_year','kms_run',	'engine_cc','color',	'asking_price',	'seller_id','seller_name',	'phone',	'additional_details',	'post_date','bike_status'];

    public function bike(){
        return $this->belongsTo('App\Bike');
    }

    public function seller(){
        return $this->belongsTo('App\Seller');
    }

    public function image(){
    	return $this->hasOne('App\BikeImage');
    }

    public function bikeImages(){
    	return $this->hasMany('App\BikeImage');
    }

    public function questions(){
        return $this->hasMany('App\Question');
    }
}
