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


	// Akses Features
	
	Route::get('/akses', 'AksesController@index')->name('akses');
	
	Route::post('/pendaftaran_pic', 'AksesController@pendaftaran_pic')->name('post_pendaftaran_pic');

	
	Route::post('/pendaftaran_akses', 'AksesController@pendaftaran_akses')->name('post_pendaftaran_akses');




	Route::get('/inventory','InventoryController@index')->name('inventory');


	// Profile Features
	Route::get('/profile', 'HomeController@profile')->name('profile');
	Route::post('/ganti_foto', 'HomeController@ganti_foto')->name('ganti_foto');
	Route::post('/ganti_profile', 'HomeController@ganti_profile')->name('ganti_profile');



	Route::get('/notify', 'HomeController@notify')->name('route_notify');
	

	// Password Features
	Route::get('/password', 'HomeController@password')->name('password');
	Route::post('/password', 'HomeController@post_password')->name('post_password');
	
	
	Route::get('/admin','AdminController@index')->name('route_admin');
	Route::get('/setting','SettingController@index')->name('route_setting');
	

	
	// Route::post('/pencetakan_akses', 'AksesController@pencetakan_akses')->name('post_pencetakan_akses');
	// Route::post('/pencetakan_diterima', 'AksesController@pencetakan_diterima')->name('post_pencetakan_diterima');

	// Route::post('/aktifkan_akses', 'AksesController@aktifkan_akses')->name('post_aktifkan_akses');
	// Route::post('/aktifkan_diterima', 'AksesController@aktifkan_diterima')->name('post_aktifkan_diterima');


	// Route::get('/setting/add-inventory', 'SettingController@show_inventory')->name('show_inventory');
	// Route::post('/setting/add-inventory', 'SettingController@add_inventory')->name('post_setting_add_inventory');
	// Route::get('/setting/show-background', 'SettingController@show_background')->name('show_background');
	// Route::post('/setting/update-background', 'SettingController@update_background')->name('update_background');

	// Route::post('/ajax/get_akses_role', 'AjaxController@get_akses_role')->name('get_akses_role');
	// Route::post('/ajax/get_inventory_level', 'AjaxController@get_inventory_level')->name('get_inventory_level');

	// Route::post('/admin/create_new_users', 'AdminController@create_new_users')->name('create_new_users');
	// Route::post('/admin/delete_user', 'AdminController@delete_user')->name('admin_delete_user');
	// Route::get('/admin/delete_user_notif', 'AdminController@delete_user_notif')->name('delete_user_notif');

	// Route::post('/inventory/create_new_inventory', 'InventoryController@create_new_inventory')->name('create_new_inventory');
	// Route::post('/inventory/approve_by_head', 'InventoryController@approve_by_head')->name('post_approve_by_head');
	// Route::post('/inventory/approve_by_admin', 'InventoryController@approve_by_admin')->name('post_approve_by_admin');

	// Route::get('/akses_approval', 'AksesController@akses_approval')->name('akses_approval');
	// Route::get('/akses_reject', 'AksesController@akses_reject')->name('akses_reject');
	// Route::post('/akses_reject', 'AksesController@proses_reject')->name('proses_reject');

	// Route::get('/inventory_approval', 'InventoryController@inventory_approval')->name('inventory_approval');
	// Route::get('/inventory_reject', 'InventoryController@inventory_reject')->name('inventory_reject');
	// Route::post('/inventory_reject', 'InventoryController@proses_reject')->name('proses_reject_inventory');


	// Route::post('/pendaftaran_diterima', 'AksesController@pendaftaran_diterima')->name('post_pendaftaran_diterima');

});