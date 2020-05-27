<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\slim;
use Illuminate\Support\Facades\DB;
use App\Bike;
use App\SellingBike;
use Session;
use App\BikeImage;
use Redirect;
use Image;
use File;
use Carbon\Carbon;

class SellingBikesController extends Controller
{
    public function index(){
        $bikes = SellingBike::with('bike','image','seller')->get();
    	return view('admin.sellingbikes.index',compact('bikes'));
    }

    public function showForm(){
    $brand = Bike::latest()->distinct('brand')->get(['brand']);
    $years = array_reverse(range(1900, strftime("%Y", time())));
      return view("admin.sellingbikes.form",compact('brand','years'));
    }

    public function edit(Request $request,$id){


        $bikes = SellingBike::findOrFail($id);
        $brand = Bike::latest()->distinct('brand')->get(['brand']);

        $proImage = BikeImage::where('selling_bike_id',$id)->get();
        $years = array_reverse(range(1900, strftime("%Y", time())));

        return view('admin.sellingbikes.editForm', compact('bikes','proImage','brand','years'));
      }

    public function fetch(Request $request){

    	$select = $request->get('select');
	     $value = $request->get('value');
	     $dependent = $request->get('dependent');

	     $data = DB::table('bikes')
	       ->where($select, $value)
	       ->groupBy($dependent)
	       ->get();
	     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
	     foreach($data as $row)
	     {
	      $output .= '<option value="'.$row->$dependent.'">'.$row->$dependent.'</option>';
	     }
	     echo $output;
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

    public function store(Request $request){

      if ($request->version == "") {
      	$bike_id=DB::table('bikes')->where('brand','=',$request->brand)
      								->where('model','=',$request->model)->get();
      }
      else{
      	$bike_id=DB::table('bikes')->where('brand','=',$request->brand)->where('model','=',$request->model)
      								->where('version','=',$request->version)->get();
      }



      $selling_bike =new SellingBike();
      $selling_bike->bike_id= $bike_id[0]->id;

      $selling_bike->make_year= $request->make_year;
      $selling_bike->kms_run= $request->kms_run;
      $selling_bike->engine_cc= $request->engine_cc;
      $selling_bike->color= $request->color;
      $selling_bike->seller_id= null;
      $selling_bike->bike_status= $request->bike_status;
      $selling_bike->asking_price= $request->asking_price;
      $selling_bike->seller_name= $request->seller_name;
      $selling_bike->phone= $request->phone;
      $selling_bike->additional_details= $request->additional_details;
      $selling_bike->post_date= Carbon::now();
      $mySave = $selling_bike->save();

      $pId = $selling_bike->id;

      if ($mySave) {
        $this->saveImageToProduct($selling_bike->id);
          Session::flash('flash_message', 'Bike for sale successfully saved');
        return redirect()->route('sellingbike.index');
      }


      else {
        Session::flash('flash_message', 'Bike for sale could not be added!');

        return redirect()->route('sellingbike.index');
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

    public function update(Request $request , $id){
    	if ($request->version == "") {
            $bike_id=DB::table('bikes')->where('brand','=',$request->brand)
                                        ->where('model','=',$request->model)->get();
        }
        else{
            $bike_id=DB::table('bikes')->where('brand','=',$request->brand)->where('model','=',$request->model)
                                        ->where('version','=',$request->version)->get();
        }

        try{
            $selling_bike =SellingBike::findOrFail($id);
            $selling_bike->bike_id= $bike_id[0]->id;

            $selling_bike->make_year= $request->make_year;
            $selling_bike->kms_run= $request->kms_run;
            $selling_bike->engine_cc= $request->engine_cc;
            $selling_bike->color= $request->color;
            $selling_bike->seller_id= null;
            $selling_bike->bike_status= $request->bike_status;
            $selling_bike->asking_price= $request->asking_price;
            $selling_bike->seller_name= $request->seller_name;
            $selling_bike->phone= $request->phone;
            $selling_bike->additional_details= $request->additional_details;
            $selling_bike->post_date= Carbon::now();
            $mySave = $selling_bike->update();

            $bike = $selling_bike->id;

            if ($mySave) {
                $saved = "false";
                $saved = $this->saveImageToProduct($bike);

                if ($saved) {
                    Session::flash('flash_message', 'Bike for sale details successfully updated!');

                    return redirect()->route('sellingbike.index');
                  }
                  else {
                    Session::flash('flash_message', 'Bike for sale details could not be updated!');

                    return redirect()->route('sellingbike.index');
                  }
                }
        }

      catch(Exception $e){
        Session::flash('flash_message', 'Bike details could not be updated!');

            return redirect()->route('sellingbike.index');
      }
    }

    public function updateImage(Request $request){

        $bikeimg= DB::table('bike_images')->where('selling_bike_id', $request->pId)->get();

        $allimg = DB::table('bike_images')->where('selling_bike_id', '!=' ,$request->pId)->get();

        $random=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 5);

        foreach(request()->file as $f)

          $imageName = $f->getClientOriginalName();
          $filesize = $f->getSize();

          $width = Image::make($f)->width();
          $height = Image::make($f)->height();
          $reqHeight = (500/$width) * $height;

              if($filesize >= 1024)
              {
                  $filesize = number_format($filesize / 1024, 2);
                  // dd($filesize);
              }

          $count=0;
          $c= 0;

          foreach($bikeimg as $img){

            if ($img->image == $imageName) {
              // dd($img->product_id, $request->pId, $img->image_location, $imageName);
              $count=$count+1;
            }
          }

          if ($count==0) {
            foreach($allimg as $i){
              if ($i->image == $imageName) {

                $c = $c +1;
              }
            }

              if($c == 0){
                if(($width > 500) || ((int)$filesize > 50)){
                  $image_resize = Image::make($f->getRealPath());
                  $image_resize->resize(500, $reqHeight);
                  $image_resize->save(public_path('upload/' .$imageName));
                }
                else{
                  $f->move(public_path('upload'), $imageName);
                }

                $Image = new BikeImage();
                $Image->bike_id = $request->pId;
                $Image->image = 'upload/' .$imageName;



                $saveImage = $Image->save();
                return response()->json(['uploaded' => '/upload/'.$imageName]);

              }
              # code...
              return response()->json(['uploaded' => '/upload/'.$imageName]);
            }

            else{
              $newName = $random."_".$imageName;
              if(($width > 500) || ((int)$filesize > 50)){
                  $image_resize = Image::make($f->getRealPath());
                  $image_resize->resize(500, $reqHeight);
                  $image_resize->save(public_path('upload/' .$newName));
                }
                else{
                  $f->move(public_path('upload'), $newName);
                }
              $Image = new BikeImage();
              $Image->bike_id = $request->pId;
              $Image->image = 'upload/' .$imageName;

              $saveImage = $Image->save();

              return response()->json(['uploaded' => '/upload/'.$imageName]);

            }
         }

      public function removeImage(Request $request){
        $image_path = "upload/".$request->image;
        if(File::exists($image_path)) {

          File::delete($image_path);
        }

        $delete= DB::table('bike_images')->where('selling_bike_id',$request->id)
                                            ->where('image',$request->image)->delete();
        if ($delete) {
          $success="true";
          return $success;
        }
      }

    public function delete($id){
      $deleted = SellingBike::find($id);
      $done = $deleted->delete();

      if ($done) {
        Session::flash('flash_message', 'Bike on sale details successfully deleted!');

        return redirect()->route('sellingbike.index');
      }
      else {
        Session::flash('flash_message', 'Bike on sale details could not be deleted!');

        return redirect()->route('sellingbike.index');
      }

    }
}
