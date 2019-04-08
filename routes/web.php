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



		Route::resource('helper', 'HelperController');
		Route::resource('new_inventory','NewInventoryController');

		Route::post('/new_inventory/new_inventory_checking_data','NewInventoryController@new_inventory_checking_data')->name('new_inventory_checking_data');
		Route::post('/new_inventory/draft_data','NewInventoryController@set_draft_data')->name('new_inventory_draft_data');
		Route::post('/new_inventory/get_inventory_detail_ajax','NewInventoryController@get_inventory_detail_ajax')->name('get_new_inventory_data_ajax');
		Route::post('/new_inventory/get_inventory_detail_ajax_by_uuid','NewInventoryController@get_inventory_detail_ajax_by_uuid')->name('get_inventory_detail_ajax_by_uuid');
		Route::post('/new_inventory/new_inventory_update_data','NewInventoryController@new_inventory_update_data')->name('new_inventory_update_data');

		Route::post('/new_inventory/new_inventory_sub_data_update_ajax','NewInventoryController@new_inventory_sub_data_update_ajax')->name('new_inventory_sub_data_update_ajax');
		Route::post('/new_inventory/new_inventory_data_approve_ajax','NewInventoryController@new_inventory_data_approve_ajax')->name('new_inventory_data_approve_ajax');

		Route::post('/new_inventory/new_inventory_data_update_ajax','NewInventoryController@new_inventory_data_update_ajax')->name('new_inventory_data_update_ajax');

		Route::post('/new_inventory/save_new_inventory_sub_data','NewInventoryController@save_new_inventory_sub_data')->name('save_new_inventory_sub_data');
		
		Route::post('/new_inventory/delete_new_inventory_sub_data','NewInventoryController@delete_new_inventory_sub_data')->name('delete_new_inventory_sub_data');
		

		Route::post('/new_inventory/new_inventory_add_new_map','NewInventoryController@new_inventory_add_new_map')->name('new_inventory_add_new_map');

		Route::post('/new_inventory/new_inventory_add_new_images','NewInventoryController@new_inventory_add_new_images')->name('new_inventory_add_new_images');

		Route::post('/ajax/get_group_detail','AjaxController@get_group_detail')->name('get_group_detail');


		// MAP
		Route::post('/map/add_map', 'MapController@add_map')->name('add_map');
		Route::post('/map/set_map_location', 'MapController@set_map_location')->name('set_map_location');
		Route::post('/map/approve_map_location', 'MapController@approve_map_location')->name('approve_map_location');
		Route::get('/map/view_map', 'MapController@view_map')->name('view_map');

		Route::get('/map/new_inventory_select_map', 'MapController@new_inventory_select_map')->name('new_inventory_select_map');

	});
	


	Route::group(['middleware' => ['get_credentials']], function() { 
		Route::get('/old_home', 'HomeController@old_index')->name('old_home');
		Route::get('/notify', 'HomeController@notify')->name('route_notify');
		


		// Akses
		//Route::get('/access', 'AksesController@index')->name('akses');

		// Route::post('/akses/new_pic_list', 'AksesController@new_pic_list')->name('new_pic_list');


		Route::post('/pendaftaran_pic', 'AksesController@pendaftaran_pic')->name('post_pendaftaran_pic');
		Route::post('/pendaftaran_akses', 'AksesController@pendaftaran_akses')->name('post_pendaftaran_akses');
		Route::get('/akses_approval', 'AksesController@akses_approval')->name('akses_approval');
		Route::get('/akses_reject', 'AksesController@akses_reject')->name('akses_reject');
		Route::post('/akses_reject', 'AksesController@proses_reject')->name('proses_reject');
		Route::post('/akses_get_info', 'AksesController@akses_get_info')->name('akses_get_info');
		Route::post('/update_access_card', 'AksesController@update_access_card')->name('update_access_card');
		Route::post('/akses/deactivated_access_card', 'AksesController@deactivated_access_card')->name('deactivated_access_card');



		// Access Card
		Route::get('/accesscard', 
			'AccessCardController@index')
		->name('accesscard');

			// insert data pic & admin room category
			Route::post('/accesscard/new_pic_list', 'AccessCardController@new_pic_list')
			->name('new_pic_list');
			
			Route::post('/akses/new_admin_room_list', 
				'AccessCardController@new_admin_room_list')
			->name('new_admin_room_list');
			// insert data pic & admin room category


			// Register New access card
			Route::post('/accesscard/post_new_access_card', 'AccessCardController@post_new_access_card')
			->name('post_new_access_card');
			//

			// Set access card number
			Route::post('/accesscard/post_new_set_access_card_number', 'AccessCardController@post_new_set_access_card_number')
			->name('post_new_set_access_card_number');
			//

			// Set photo schedule
			Route::post('/accesscard/post_custome_set_photo_schedule', 'AccessCardController@post_custome_set_photo_schedule')
			->name('post_custome_set_photo_schedule');
			//

			// Set pick up schedule
			Route::post('/accesscard/post_custome_set_pick_up_schedule', 'AccessCardController@post_custome_set_pick_up_schedule')
			->name('post_custome_set_pick_up_schedule');
			//

			// Set Admin Room
			Route::post('/accesscard/post_new_set_admin_room', 'AccessCardController@post_new_set_admin_room')
			->name('post_new_set_admin_room');
			//


			// Set CHECK ACCESS CARD NUMBER
			Route::post('/accesscard/post_extend_check_access_card_number', 'AccessCardController@post_extend_check_access_card_number')
			->name('post_extend_check_access_card_number');
			//

			// Submit Exteding Access Card
			Route::post('/accesscard/post_extending_access_card_number', 'AccessCardController@post_extending_access_card_number')
			->name('post_extending_access_card_number');
			//


			// Submit Broken Access Card
			Route::post('/accesscard/post_broken_access_card_number', 'AccessCardController@post_broken_access_card_number')
			->name('post_broken_access_card_number');
			//

			// Submit Broken Access Card
			Route::post('/accesscard/post_lost_access_card_number', 'AccessCardController@post_lost_access_card_number')
			->name('post_lost_access_card_number');
			//

			// Submit Broken Access Card
			Route::post('/accesscard/post_leveling_access_card_number', 'AccessCardController@post_leveling_access_card_number')
			->name('post_leveling_access_card_number');
			//
		// Access Card




		// Inventory
		Route::get('/inventory','InventoryController@index')->name('inventory');
		Route::post('/inventory/add-inventory', 'InventoryController@add_inventory')->name('add_inventory');
		Route::post('/inventory/upload-excel', 'InventoryController@upload_excel')->name('upload_excel');
		Route::get('/inventory_approval', 'InventoryController@inventory_approval')->name('inventory_approval');
		Route::get('/inventory_reject', 'InventoryController@inventory_reject')->name('inventory_reject');
		Route::post('/inventory_reject', 'InventoryController@proses_reject')->name('proses_reject_inventory');
		Route::post('/inventory/inventory_insert_data', 'InventoryController@inventory_insert_data')->name('inventory_insert_data');
		Route::post('/inventory/inventory_get_info_by_uuid', 'InventoryController@inventory_get_info_by_uuid')->name('inventory_get_info_by_uuid');
		Route::post('/inventory/inventory_update_data', 'InventoryController@inventory_update_data')->name('inventory_update_data');
		Route::post('/inventory/get_inventory_data_ajax', 'InventoryController@get_inventory_data_ajax')->name('get_inventory_data_ajax');


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

		Route::post('/admin/shorcut_insert_inventory', 'AdminController@shorcut_insert_inventory')->name('shorcut_insert_inventory');

		Route::post('/admin/shorcut_insert_pic', 'AdminController@shorcut_insert_pic')->name('shorcut_insert_pic');

		Route::post('/admin/shorcut_insert_admin_room', 'AdminController@shorcut_insert_admin_room')->name('shorcut_insert_admin_room');



		Route::post('/ajax/get_akses_role', 'AjaxController@get_akses_role')->name('get_akses_role');
		Route::post('/ajax/get_inventory_level', 'AjaxController@get_inventory_level')->name('get_inventory_level');
		Route::post('/ajax/get_inventory_list', 'AjaxController@get_inventory_list')->name('get_inventory_list');
		Route::post('/ajax/get_pic_level', 'AjaxController@get_pic_level')->name('get_pic_level');
		Route::post('/ajax/get_pic_list', 'AjaxController@get_pic_list')->name('get_pic_list');
		Route::post('/ajax/get_admin_room_list', 'AjaxController@get_admin_room_list')->name('get_admin_room_list');

		Route::post('/ajax/get_special_level', 'AjaxController@get_special_level')->name('get_special_level');


		// Setting 
		Route::get('/access_report','SettingController@access_report')->name('access_report');
		Route::get('/inventory_report','SettingController@inventory_report')->name('inventory_report');
		Route::get('/report/download','SettingController@report_download')->name('report_download');
		Route::get('/report/download/inventory','SettingController@inventory_report_download')->name('inventory_report_download');

		// Disabled since crud role/position using full ajax
		// Route::get('/admin/delete_role_notif', 'AdminController@delete_role_notif')->name('delete_role_notif');
		// Route::get('/admin/add_role_notif', 'AdminController@add_role_notif')->name('add_role_notif');

	});
		
		

	



	// Akses Features	
	
		
	


	// Inventory Features
	Route::get('/map_location','InventoryController@map_location')->name('map');
	// Route::post('/inventory/create_new_inventory', 'InventoryController@create_new_inventory')->name('create_new_inventory');
	
	

	


	
	

	

	
	
	

	
	

	// END




	// Route::post('/pencetakan_akses', 'AksesController@pencetakan_akses')->name('post_pencetakan_akses');
	// Route::post('/pencetakan_diterima', 'AksesController@pencetakan_diterima')->name('post_pencetakan_diterima');

	// Route::post('/aktifkan_akses', 'AksesController@aktifkan_akses')->name('post_aktifkan_akses');
	// Route::post('/aktifkan_diterima', 'AksesController@aktifkan_diterima')->name('post_aktifkan_diterima');


	// Route::get('/setting/add-inventory', 'SettingController@show_inventory')->name('show_inventory');
	
	
	// Route::post('/pendaftaran_diterima', 'AksesController@pendaftaran_diterima')->name('post_pendaftaran_diterima');

});