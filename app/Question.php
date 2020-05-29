<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = "questions";

    protected $fillable = ['selling_bike_id','question','seller_id','question_date'];
    
    public function bike_for_sale(){
        return $this->belongsTo('App\SellingBike');
    }
}
