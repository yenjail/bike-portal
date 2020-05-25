<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('admin.dashboard');
// });

Auth::routes();

Route::get('/admin', 'HomeController@index')->name('home');

Route::prefix('/admin')->group(function() {
	Route::group(['prefix' => '/bikes',  'middleware' => 'auth'], function() {
    Route::get('/','BikeController@index')->name('bike.index');
    Route::get('/addBike','BikeController@showForm')->name('bike.form');
    Route::post('/store','BikeController@store')->name('bike.store');

    Route::get('/editBike/{id}', 'BikeController@edit')->name('bike.edit');
     Route::post('/updateBike/{id}', 'BikeController@update')->name('bike.update');
     Route::get('/delete/{id}','BikeController@delete')->name('bike.delete');

    Route::post('/updateImage','BikeController@updateImage')->name('bike.updateImage');

    Route::post('/removeImage','BikeController@removeImage')->name('bike.removeImage');
  });

	Route::group(['prefix' => '/bikes-on-sale',  'middleware' => 'auth'], function() {

	    Route::get('/','SellingBikesController@index')->name('sellingbike.index');

	    Route::get('/add-bike-for-sale','SellingBikesController@showForm')->name('sellingbike.form');

	    Route::post('/fetch','SellingBikesController@fetch')->name('sellingbike.fetch');

	    Route::post('/store','SellingBikesController@store')->name('sellingbike.store');

	    Route::get('/edit-bike-for-sale/{id}', 'SellingBikesController@edit')->name('sellingbike.edit');

	     Route::post('/update-bike-for-sale/{id}', 'SellingBikesController@update')->name('sellingbike.update');

	    Route::post('/updateImage','SellingBikesController@updateImage')->name('sellingbike.updateImage');

	    Route::post('/removeImage','SellingBikesController@removeImage')->name('sellingbike.removeImage');

	     Route::get('/delete/{id}','SellingBikesController@delete')->name('sellingbike.delete');
      });

      Route::group(['prefix' => '/seller',  'middleware' => 'auth'], function() {
        Route::get('/','SellerController@index')->name('seller.index');
        Route::get('/addSeller','SellerController@showForm')->name('seller.form');
        Route::post('/store','SellerController@store')->name('seller.store');

        Route::get('/editBike/{id}', 'SellerController@edit')->name('seller.edit');
         Route::post('/updateBike/{id}', 'SellerController@update')->name('seller.update');
         Route::get('/delete/{id}','SellerController@delete')->name('seller.delete');

      });

});


