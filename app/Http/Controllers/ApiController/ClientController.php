<?php

namespace App\Http\Controllers\ApiController;

use App\Bike;
use App\Classes\slim;
use App\Http\Controllers\Controller;
use App\SellingBike;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\BikeImage;
use App\Seller;
use Redirect;
use Image;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class ClientController extends Controller
{
    public function getAllBikes(){
        $bikes = SellingBike::with('bike','image','seller')->get();
        return $bikes;
    }

    public function getBikeDetails($id){
        $bike = SellingBike::find($id);
        $details = SellingBike::find($id)->bike;
        $images = SellingBike::find($id)->bikeImages;
        return [$bike,$details,$images];
    }

    public function getNewBikes(){
        $bikes = SellingBike::where('bike_status','unused')->with('bike','image','seller')->get();
        return $bikes;
    }

    public function getOldBikes(){
        $bikes = SellingBike::where('bike_status','used')->with('bike','image','seller')->get();
        return $bikes;
    }

    public function saveBikeForSale(Request $request){

        $selling_bike =new SellingBike();
        $selling_bike->bike_id= $request->bike_id;
        $selling_bike->make_year= $request->make_year;
        $selling_bike->kms_run= $request->kms_run;
        $selling_bike->engine_cc= $request->engine_cc;
        $selling_bike->color= $request->color;
        $selling_bike->bike_status= $request->bike_status;
        $selling_bike->asking_price= $request->asking_price;
        $selling_bike->seller_id= $request->seller_id;
        $selling_bike->additional_details= $request->additional_details;
        $selling_bike->post_date= $request->upload_date;
        $mySave = $selling_bike->save();

        $random=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 4);
        if($mySave){
            foreach(request()->image as $f){
                $imageName = $f->getClientOriginalName();
                $filesize = $f->getSize();

                $height = Image::make($f)->height();
                $width = Image::make($f)->width();
                $reqHeight = (500/$width) * $height;

                if($filesize >= 1024)
                {
                $filesize = number_format($filesize / 1024, 2);
                }

                $image_path = "upload/".$imageName;

                if(File::exists($image_path)){
                    $newName = $random."_".$imageName;
                    if(($width > 500) || ((int)$filesize > 50)){
                        $image_resize = Image::make($f->getRealPath());
                        $image_resize->resize(500, $reqHeight);
                        $image_resize->save(public_path('upload/' .$newName));
                    }
                    else{
                        $f->move(public_path('upload'), $newName);
                    }

                    $bikeImage = new BikeImage();
                    $bikeImage->selling_bike_id = $request->bike_id;
                    $bikeImage->image = "upload/".$newName;

                    $saveImage = $bikeImage->save();

                    return response()->json(['uploaded' => '/upload/'.$newName]);
                }

                else{

                    if(($width > 500) || ((int)$filesize > 50)){
                        $image_resize = Image::make($f->getRealPath());
                        $image_resize->resize(500, $reqHeight);
                        $image_resize->save(public_path('upload/' .$imageName));
                    }
                    else{
                        $f->move(public_path('upload'), $imageName);
                    }
                        $newName = $random."_".$imageName;
                        $bikeImage = new BikeImage();
                        $bikeImage->selling_bike_id = $request->bike_id;
                        $bikeImage->image = "upload/".$newName;

                        $saveImage = $bikeImage->save();

                        return response()->json(['uploaded' => '/upload/'.$imageName]);
                }
            }
        }
        else {
            return ['message'=>'Bike for sale adding failed!'];
        }
    }

      public function saveSeller(Request $request){
        $validator = Validator::make($request->all(), [
             'name' => 'required',
            'contact' => 'required',
            'email' => 'required|email|unique:sellers',
            'location' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
          return response()->json(['errors'=>$validator->errors()]);

        }
        else{
          $seller=new Seller();
          $seller->name = $request->name;
          $seller->email= $request->email;
          $seller->number = $request->contact;
          $seller->location= $request->location;
          $seller->password = Hash::make($request->password);

          $saved= $seller->save();

          if ($saved) {
            return $seller;
          }
          else{
            return "Seller could not be registered";
          }
        }
      }

      public function sellerLogin(Request $request){
        $validator = Validator::make($request->all(), [
        'email' => 'required',
        'password' => 'required',
       ]);

       if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
       }
      else{
        $email = $request->email;
        $data = $request->all();
        $users = Seller::where('email', $data['email'])->first();
        if ($users) {
            if (Hash::check($data['password'], $users->password)) {
                $bikes = SellingBike::where('seller_id',$users->id)->with('bike','image','seller')->get();
                return $users;
            } else {
                return ['message'=>'Password is incorrect'];
            }
        } else {
            return ['message'=>'User does not exist'];
        }
      }
    }

    public function getUserProfile($id){
        $userDetails = Seller::where('id', $id)->first();
        $profileDetails = SellingBike::where('seller_id',$id)->with('bike','image','seller')->get();
        return [$userDetails, $profileDetails];
    }

    public function getAllBrands(){
        $brands = Bike::latest()->pluck('brand');
        return $brands;
    }

    public function getModel($brand){
        $models= Bike::where('brand',$brand)->pluck('model');
        return $models;
    }

    public function getVersion($brand,$model){
        $version= Bike::where('brand',$brand)->where('model',$model)->get();
        return $version;
    }
}
