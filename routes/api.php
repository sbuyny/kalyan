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

Route::resource('kalyannayas', 'KalyannayaController');

Route::resource('kalyans', 'KalyanController');

Route::get('kalyans/kalyannaya/{id}', array('as' => 'kalyannaya', 'uses' => 'KalyanController@kalyannaya'));

Route::get('bookings', array('as' => 'index', 'uses' => 'BookingController@index'));

Route::get('bookings/kalyannaya/{id}', array('as' => 'kalyannaya', 'uses' => 'BookingController@kalyannaya'));

Route::post('bookings', array('as' => 'store', 'uses' => 'BookingController@store'));

Route::get('bookings/{id}', array('as' => 'show', 'uses' => 'BookingController@show'));

Route::get('users', array('as' => 'users', 'uses' => 'BookingController@users'));

Route::post('search', array('as' => 'search', 'uses' => 'BookingController@search'));