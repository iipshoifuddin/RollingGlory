<?php

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
//     return view('welcome');
// });
Route::get('/','ControllerGift@index');

//First Page
Route::get('/gifts','ControllerGift@index');
Route::get('/gifts/{id}','ControllerGift@detailGift');
Route::post('/gifts','ControllerGift@storeGift');

//Second Page
Route::post('/gifts/{id}/rating','DetailGiftsController@addRating');
Route::post('/gifts/{id}/redeem','DetailGiftsController@addRedeem');
Route::patch('/gifts/{id}','DetailGiftsController@updateRedeem');
Route::delete('/gifts/{id}','DetailGiftsController@deleteRedeem');
