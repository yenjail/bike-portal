<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/all-bike-details', 'ApiController\ClientController@getAllBikes');
Route::get('/bike-details/{id}', 'ApiController\ClientController@getBikeDetails');

Route::get('/new-bikes', 'ApiController\ClientController@getNewBikes');
Route::get('/old-bikes', 'ApiController\ClientController@getOldBikes');

Route::post('/sign-up', 'ApiController\ClientController@saveSeller');

Route::post('/log-in', 'ApiController\ClientController@sellerLogin');
Route::get('/all-brands', 'ApiController\ClientController@getAllBrands');
Route::get('/{brand}', 'ApiController\ClientController@getModel');
Route::get('/{brand}/{model}', 'ApiController\ClientController@getVersion');

Route::post('/add-bike-for-sale/', 'ApiController\ClientController@saveBikeForSale');


