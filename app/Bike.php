<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    protected $table = "bikes";

    protected $fillable = ['model','brand','version','current_mp', 'features'];

    protected $searchable = [
        'model',
        'brand',
        'version'
    ];

    public function selling_bikes(){
        return $this->hasMany('App\SellingBike');
    }

}
