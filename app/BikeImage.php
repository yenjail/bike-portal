<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BikeImage extends Model
{
    protected $table = 'bike_images';
    protected $fillable = ['selling_bike_id','image'];

    public function selling_bike(){
        return $this->belongsTo('App\SellingBike');
    }

}
