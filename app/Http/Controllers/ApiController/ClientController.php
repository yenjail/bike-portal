<?php

namespace App\Http\Controllers\ApiController;

use App\Bike;
use App\Http\Controllers\Controller;
use App\SellingBike;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function getAllBikes(){
        $bikes = SellingBike::with('bike','image')->get();
        return $bikes;
    }

    public function getBikeDetails($id){
        $bike = SellingBike::find($id);
        $details = SellingBike::find($id)->bike;
        $images = SellingBike::find($id)->bikeImages;

        return [$bike,$details,$images];
    }

    public function getNewBikes(){
        $bikes = SellingBike::where('bike_status','unused')->with('bike','image')->get();
        return $bikes;
    }

    public function getOldBikes(){
        $bikes = SellingBike::where('bike_status','used')->with('bike','image')->get();
        return $bikes;
    }
}
