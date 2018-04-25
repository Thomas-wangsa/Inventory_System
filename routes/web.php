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

Route::get('/', function () {
	return redirect('login');
    //return view('welcome');
});

Auth::routes();


Route::get('/test', function () {
	//return redirect('login');
    return view('auth.old_login');
});

Route::group(['middleware' => ['auth']], function() { 
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/akses', 'AksesController@index')->name('akses');
});