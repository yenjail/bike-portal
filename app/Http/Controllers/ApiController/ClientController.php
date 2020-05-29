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
use App\Question;
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
        $seller = SellingBike::find($id)->seller;
        $images = SellingBike::find($id)->image;
        // $questions=SellingBike::where('id',$id)->with('questions','seller')->get();

        $questions= DB::table('questions')
                ->join('sellers','questions.seller_id', '=', 'sellers.id')
                ->where('questions.selling_bike_id',$id)->get();

        // DB::table('articles')
        //         ->join('categories','articles.id', '=', 'categories.id')
        //         ->join('user', 'articles.user_id', '=', 'user.id')
        //         ->select('articles.id','articles.title','articles.body','user.user_name', 'categories.category_name')
        //         ->get();

        return [$bike,$details,$seller,$images,$questions];
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
        if(is_null($request->color)){
            $selling_bike->color= "#000000";
        }
        else{
            $selling_bike->color= $request->color;
        }
        $selling_bike->bike_status= $request->bike_status;
        $selling_bike->asking_price= $request->asking_price;
        $selling_bike->seller_id= $request->seller_id;
        $selling_bike->additional_details= $request->additional_details;
        $selling_bike->post_date= $request->upload_date;
        
        $selling_bike->additional_details= $request->additional_details;

        $mySave = $selling_bike->save();

        if($mySave){
            if($request->image){
                $extension = explode('/', mime_content_type($request->image))[1];
                $name = time().'.'.$extension;
                // $name=time().'.'.explode('/', explode(':', substr($request->image,0, strpos($request->image, ';'))))[1][1];
                $height = Image::make($request->image)->save(public_path('upload/'.$name));
            }

            $bikeImage = new BikeImage();
            $bikeImage->selling_bike_id = $selling_bike->id;
            $bikeImage->image = "upload/".$name;

            $saveImage = $bikeImage->save();
            if($saveImage){
                return "Bike for sale saved";
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

    public function postQuestions(Request $request){
        
        $question= new Question();
        $question->selling_bike_id= $request->selling_bike_id;
        $question->seller_id= $request->seller_id;
        $question->question= $request->question;
        $question->question_date= $request->question_date;
        $saved= $question->save();
        if ($saved) {
            return ['message'=>'Question posted!!'];
        }
    }

    public function getUserProfile($id){
        $userDetails = Seller::where('id', $id)->first();
        $profileDetails = SellingBike::where('seller_id',$id)->with('bike','image')->get();
        // $questionsAsked = SellingBike::where('seller_id',$id)->with('questions')->get();

        // $shares = DB::table('selling_bikes')
        //         ->join('questions', 'selling_bikes.id', '=', 'questions.selling_bike_id')
        //         ->join('followers', 'followers.user_id', '=', 'users.id')
        //         ->where('followers.follower_id', '=', 3)
        //         ->get();

         $questionsAsked= DB::table('questions')
                    ->join('selling_bikes','questions.selling_bike_id', '=', 'selling_bikes.id')
                    ->join('sellers','selling_bikes.seller_id', '=', 'sellers.id')
                    ->where('selling_bikes.seller_id',$id)->get();

        return [$userDetails, $profileDetails, $questionsAsked];
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

    public function delete($id) {
        $bike = SellingBike::findOrFail($id);
        if($bike){
            $bike->delete(); 
            return "Deleted!!";
        }
        else{
            return response()->json(error);
        }
           
        return response()->json(null); 
        }
}
