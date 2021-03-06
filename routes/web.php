<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 

Route::get('/', function () {
    return view('welcome'); 
});

*/

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
  ]);

  Route::get('/', 'HomeController@index')->name('index');
  Route::get('/home', 'HomeController@home')->name('home');
  Route::get('/{vue_capture}','HomeController@index')->where('vue_capture', '[\/\w\.-]*');