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
	Route::get('/setting','SettingController@index')->name('route_setting');

	Route::post('/pendaftaran_akses', 'AksesController@pendaftaran_akses')->name('post_pendaftaran_akses');
	Route::post('/pendaftaran_diterima', 'AksesController@pendaftaran_diterima')->name('post_pendaftaran_diterima');

	Route::post('/pencetakan_akses', 'AksesController@pencetakan_akses')->name('post_pencetakan_akses');
	Route::post('/pencetakan_diterima', 'AksesController@pencetakan_diterima')->name('post_pencetakan_diterima');

	Route::post('/aktifkan_akses', 'AksesController@aktifkan_akses')->name('post_aktifkan_akses');
	Route::post('/aktifkan_diterima', 'AksesController@aktifkan_diterima')->name('post_aktifkan_diterima');

	Route::post('/setting/add-inventory', 'SettingController@add_inventory')->name('post_setting_add_inventory');


	Route::post('/ajax/get_akses_role', 'AjaxController@get_akses_role')->name('get_akses_role');
	Route::post('/ajax/get_inventory_level', 'AjaxController@get_inventory_level')->name('get_inventory_level');

	Route::post('/admin/create_new_users', 'AdminController@create_new_users')->name('create_new_users');

});