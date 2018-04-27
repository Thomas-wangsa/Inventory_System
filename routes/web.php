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


// Route::get('/test', function () {
// 	//return redirect('login');
//     return view('auth.old_login');
// });

Route::group(['middleware' => ['auth']], function() { 
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/akses', 'AksesController@index')->name('akses');
	Route::get('/admin','AdminController@index')->name('route_admin');

	Route::post('/pendaftaran_akses', 'AksesController@pendaftaran_akses')->name('post_pendaftaran_akses');
	Route::post('/pendaftaran_diterima', 'AksesController@pendaftaran_diterima')->name('post_pendaftaran_diterima');

	Route::post('/pencetakan_akses', 'AksesController@pencetakan_akses')->name('post_pencetakan_akses');
	Route::post('/pencetakan_diterima', 'AksesController@pencetakan_diterima')->name('post_pencetakan_diterima');

	Route::post('/aktifkan_akses', 'AksesController@aktifkan_akses')->name('post_aktifkan_akses');
	Route::post('/aktifkan_diterima', 'AksesController@aktifkan_diterima')->name('post_aktifkan_diterima');
});