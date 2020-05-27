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
        if($request->saleBike){
            $item = $request->saleBike;

            $selling_bike =new SellingBike();
            $selling_bike->bike_id= $item->bike_id;
            $selling_bike->make_year= $item->make_year;
            $selling_bike->kms_run= $item->kms_run;
            $selling_bike->engine_cc= $item->engine_cc;
            $selling_bike->color= $item->color;
            $selling_bike->bike_status= $item->bike_status;
            $selling_bike->asking_price= $item->asking_price;
            $selling_bike->seller_id= $item->seller_id;
            $selling_bike->additional_details= $item->additional_details;
            $selling_bike->post_date= Carbon::now();
            $mySave = $selling_bike->save();

            $pId = $selling_bike->id;

            if ($mySave) {
                $this->saveImageToProduct($selling_bike->id);
                return ['message'=>'Bike for sale added!'];
            }
            else {
                return ['message'=>'Bike for sale adding failed!'];
            }

        }
        else{
            return ['message' => 'No selling bike details'];
        }
    }

    public function saveImageToProduct($bike){
        $images = Slim::getImages();

        foreach ($images as $image) {

          $files = array();
          // save output data if set
            if (isset($image['output']['data'])) {

                // Save the file
                $name = $image['output']['name'];

                // We'll use the output crop data
                $data = $image['output']['data'];

                $height = $image['output']['height'];
                $width = $image['output']['width'];
                $filesize = $image['input']['size'];

                 //dd($image);

                // If you want to store the file in another directory pass the directory name as the third parameter.
                // dd($file);
                $imageName = $this->sanitizeFileName($name);
                $imageName = uniqid() . '_' . $imageName;

                $reqHeight = (800/$width) * $height;

                if($filesize >= 1024)
                {
                    $filesize = number_format($filesize / 1024, 2);
                }

                if(($width > 800) || ((int)$filesize > 50)){
                  $file = Slim::saveFile($data, $name, public_path('upload/'));

                    //$image_resize = Image::make($data->getRealPath());
                    $image_resize = Image::make(public_path('upload/').$file);

                    // dd($image_resize);

                    $image_resize->resize(800, $reqHeight);

                    $image_resize->save(public_path('upload/' .$file));
                    //return "true";
                }
                else{
                    $file = Slim::saveFile($data, $name, public_path('upload/'));
                    //return "true";
                }
                $Image = new BikeImage();
                $Image->selling_bike_id = $bike;
                $Image->image = 'upload/'.$file;
                $savedimg=$Image->save();
                //array_push($files, $output);
                if($savedimg){
                    return true;
                }
            }

        }
    }

      public static function sanitizeFileName($str) {
          // Basic clean up
          $str = preg_replace('([^\w\s\d\-_~,;\[\]\(\).])', '', $str);
          // Remove any runs of periods
          $str = preg_replace('([\.]{2,})', '', $str);
          return $str;
      }

      public function saveImage(Request $request){
        $random=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 4);

        foreach(request()->file as $f)
          $imageName = $f->getClientOriginalName();


          $filesize = $f->getSize();
          // dd($filesize);
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
              $bikeImage->selling_bike_id = $request->pId;
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
            $bikeImage = new BikeImage();
              $bikeImage->selling_bike_id = $request->pId;
              $bikeImage->image = "upload/".$newName;

              $saveImage = $bikeImage->save();

            return response()->json(['uploaded' => '/upload/'.$imageName]);
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
                return $bikes;
            } else {
                return ['message'=>'Password is incorrect'];
            }
        } else {
            return ['message'=>'User does not exist'];
        }
      }


    }
}
