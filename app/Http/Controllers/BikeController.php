<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\slim;
use Illuminate\Support\Facades\DB;
use App\Bike;
use Session;
use App\BikeImage;
use Redirect;
use Image;
use File;

class BikeController extends Controller
{
	public function index(){
    	$bikes = Bike::latest()->get();
    	return view('admin.bikes.index', compact('bikes'));
    }

    public function showForm(){


      return view("admin.bikes.form");
    }


     public function store(Request $request){
      // dd($request);
      $request->validate([
        'brand' => 'required',
        'model' => 'required|max:100',
        'version' => 'required|unique:bikes',
        'current_mp' => 'required',
        'features' => 'required',
      ]);


      $bike =new Bike();
      $bike->brand= $request->brand;
      $bike->model= $request->model;
      $bike->version= $request->version;
      $bike->current_mp= $request->current_mp;
      $bike->features= $request->features;
      $mySave = $bike->save();


      if ($mySave) {
          Session::flash('flash_message', 'Bike data successfully saved');
        return redirect()->route('bike.index');
      }


      else {
        Session::flash('flash_message', 'Bike could not be added!');

        return redirect()->route('bike.index');
      }
    }



    public function edit(Request $request,$id){


      $bikes = Bike::findOrFail($id);


      return view('admin.bikes.editForm', compact('bikes'));
    }

    public function update(Request $request , $id){
    	$request->validate([
        'brand' => 'required',
        'model' => 'required|max:100',
        'current_mp' => 'required',
        'features' => 'required',
      	]);

      try{
        $update = Bike::findOrFail($id);

        $update->brand= $request->brand;
        $update->model= $request->model;
        $update->version= $request->version;

        $update->current_mp= $request->current_mp;
        $update->features= $request->features;
        $myUpdate = $update->update();

        $bike = $update->id;

      if ($myUpdate) {

            Session::flash('flash_message', 'Bike details successfully updated!');

            return redirect()->route('bike.index');
          }
          else {
            Session::flash('flash_message', 'Bike details could not be updated!');

            return redirect()->route('bike.index');
          }


      }
      catch(Exception $e){
        Session::flash('flash_message', 'Bike details could not be updated!');

            return redirect()->route('bike.index');
      }
    }


     public function delete($id){
      $deleted = Bike::find($id);
      $done = $deleted->delete();

      if ($done) {
        Session::flash('flash_message', 'Bike details successfully deleted!');

        return redirect()->route('bike.index');
      }
      else {
        Session::flash('flash_message', 'Bike details could not be deleted!');

        return redirect()->route('bike.index');
      }

    }

}
