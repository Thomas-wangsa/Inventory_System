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

Route::get('/logout',function(){
	Auth::guard()->logout();
    return redirect('/login');
});

Route::group(['middleware' => ['auth']], function() {

	Route::group(['middleware' => ['get_credentials']], function() { 
		Route::get('/home', 'HomeController@index')->name('home');
		// Profile Features
		Route::get('/profile', 'HomeController@profile')->name('profile');
		Route::post('/ganti_foto', 'HomeController@ganti_foto')->name('ganti_foto');
		Route::post('/ganti_profile', 'HomeController@ganti_profile')->name('ganti_profile');
		// Password Features
		Route::get('/password', 'HomeController@password')->name('password');
		Route::post('/password', 'HomeController@post_password')->name('post_password');

		Route::get('/setting','SettingController@index')->name('route_setting');
		Route::get('/setting/show-background', 'SettingController@show_background')->name('show_background');
		Route::post('/setting/update-background', 'SettingController@update_background')->name('update_background');

	});
	


	Route::group(['middleware' => ['get_credentials']], function() { 
		Route::get('/old_home', 'HomeController@old_index')->name('old_home');
		Route::get('/notify', 'HomeController@notify')->name('route_notify');
		


		// Akses
		Route::get('/access', 'AksesController@index')->name('akses');

		Route::post('/akses/new_pic_list', 'AksesController@new_pic_list')->name('new_pic_list');


		Route::post('/pendaftaran_pic', 'AksesController@pendaftaran_pic')->name('post_pendaftaran_pic');
		Route::post('/pendaftaran_akses', 'AksesController@pendaftaran_akses')->name('post_pendaftaran_akses');
		Route::get('/akses_approval', 'AksesController@akses_approval')->name('akses_approval');
		Route::get('/akses_reject', 'AksesController@akses_reject')->name('akses_reject');
		Route::post('/akses_reject', 'AksesController@proses_reject')->name('proses_reject');
		Route::post('/akses_get_info', 'AksesController@akses_get_info')->name('akses_get_info');
		Route::post('/update_access_card', 'AksesController@update_access_card')->name('update_access_card');



		// Inventory
		Route::get('/inventory','InventoryController@index')->name('inventory');
		Route::post('/inventory/add-inventory', 'InventoryController@add_inventory')->name('add_inventory');
		Route::post('/inventory/upload-excel', 'InventoryController@upload_excel')->name('upload_excel');
		Route::get('/inventory_approval', 'InventoryController@inventory_approval')->name('inventory_approval');
		Route::get('/inventory_reject', 'InventoryController@inventory_reject')->name('inventory_reject');
		// Admin
		Route::get('/admin','AdminController@index')->name('route_admin');
		Route::post('/admin/create_new_users', 'AdminController@create_new_users')->name('create_new_users');

		Route::post('/admin/edit_user', 'AdminController@edit_user')->name('edit_user');
		Route::post('/ajax/get_data_user', 'AjaxController@get_data_user')->name('get_data_user');
		Route::post('/ajax/get_role_user', 'AjaxController@get_role_user')->name('get_role_user');


		Route::post('/admin/delete_user', 'AdminController@delete_user')->name('admin_delete_user');
		Route::post('/admin/aktifkan_user', 'AdminController@aktifkan_user')->name('admin_aktifkan_user');

		
		Route::get('/admin/delete_user_notif', 'AdminController@delete_user_notif')->name('delete_user_notif');
		Route::get('/admin/aktif_user_notif', 'AdminController@aktif_user_notif')->name('aktif_user_notif');
		


		Route::post('/admin/delete_role_user', 'AdminController@delete_role_user')->name('delete_role_user');
		Route::post('/admin/delete_role_special_user', 'AdminController@delete_role_special_user')->name('delete_role_special_user');

		Route::post('/admin/restore_role_user', 'AdminController@restore_role_user')->name('restore_role_user');
		Route::post('/admin/restore_role_special_user', 'AdminController@restore_role_special_user')->name('restore_role_special_user');
		Route::post('/admin/add_role_user', 'AdminController@add_role_user')->name('add_role_user');
		Route::post('/admin/add_role_special_user', 'AdminController@add_role_special_user')->name('add_role_special_user');

		
		Route::post('/ajax/get_akses_role', 'AjaxController@get_akses_role')->name('get_akses_role');
		Route::post('/ajax/get_inventory_level', 'AjaxController@get_inventory_level')->name('get_inventory_level');
		Route::post('/ajax/get_pic_level', 'AjaxController@get_pic_level')->name('get_pic_level');
		Route::post('/ajax/get_special_level', 'AjaxController@get_special_level')->name('get_special_level');


		// Setting 
		Route::get('/access_report','SettingController@access_report')->name('access_report');
		Route::get('/inventory_report','SettingController@inventory_report')->name('inventory_report');
		Route::get('/report/download','SettingController@report_download')->name('report_download');

		// Disabled since crud role/position using full ajax
		// Route::get('/admin/delete_role_notif', 'AdminController@delete_role_notif')->name('delete_role_notif');
		// Route::get('/admin/add_role_notif', 'AdminController@add_role_notif')->name('add_role_notif');

	});
		
		

	



	// Akses Features	
	
		
	


	// Inventory Features
	Route::get('/map_location','InventoryController@map_location')->name('map');
	Route::post('/inventory/create_new_inventory', 'InventoryController@create_new_inventory')->name('create_new_inventory');
	
	Route::post('/inventory_reject', 'InventoryController@proses_reject')->name('proses_reject_inventory');

	


	
	

	

	
	
	

	
	

	// END




	// Route::post('/pencetakan_akses', 'AksesController@pencetakan_akses')->name('post_pencetakan_akses');
	// Route::post('/pencetakan_diterima', 'AksesController@pencetakan_diterima')->name('post_pencetakan_diterima');

	// Route::post('/aktifkan_akses', 'AksesController@aktifkan_akses')->name('post_aktifkan_akses');
	// Route::post('/aktifkan_diterima', 'AksesController@aktifkan_diterima')->name('post_aktifkan_diterima');


	// Route::get('/setting/add-inventory', 'SettingController@show_inventory')->name('show_inventory');
	
	
	// Route::post('/pendaftaran_diterima', 'AksesController@pendaftaran_diterima')->name('post_pendaftaran_diterima');

});