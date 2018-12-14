<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Models\Akses_Data;
use App\Http\Models\Users_Role;
use App\Http\Models\Notification AS Model_Notification;


class Notification extends Controller {


	protected $response = array(
		'error'  => true,
		'message'=> '' 
	);

	protected $array_category = array(1,2);
    protected $category;
    protected $data;

	public function set_notify($category,$data) {

		$this->category = $category;
		$this->data     = $data;

		// restrict category
		if(!in_array($this->category, $this->array_category)) {
			$this->response['message'] = "out of category scope";
			return $this->response;
		}


		$this->response['error'] = false;
		$this->notify_apps();

		
		return $this->response;

	}
 
	private function notify_apps() {
		$data_user = array();
		// category checker
		if($this->category == 1) {
			$requester_user = $this->data->created_by;
			$current_user 	= $this->data->updated_by;
			

			// request type checker
			if($this->data->request_type == 1) {
				// status type 
				if($this->data->status_akses == 1) {
					$next_user = Users_Role::join('pic_role',
                        'pic_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi','=',2)
                        ->where('pic_list_id',$this->data->pic_list_id)
                        ->where('pic_level_id',2)
                        ->pluck('pic_role.user_id');

					array_push($data_user,$current_user);
					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					$this->send_notify($data_user);
				} else if($this->data->status_akses == 2) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',1)
                    ->pluck('user_id');

					array_push($data_user,$current_user);

					if(!in_array($requester_user,$data_user)) {
						array_push($data_user,$requester_user);
					}

					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					$this->send_notify($data_user);
				} else if($this->data->status_akses == 3) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',2)
                    ->pluck('user_id');

					array_push($data_user,$current_user);
					
					if(!in_array($requester_user,$data_user)) {
						array_push($data_user,$requester_user);
					}

					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					$this->send_notify($data_user);
				} else if($this->data->status_akses == 4) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');

					array_push($data_user,$current_user);
					
					if(!in_array($requester_user,$data_user)) {
						array_push($data_user,$requester_user);
					}

					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					$this->send_notify($data_user);
				} else if($this->data->status_akses == 5) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',4)
                    ->pluck('user_id');

					array_push($data_user,$current_user);
					
					if(!in_array($requester_user,$data_user)) {
						array_push($data_user,$requester_user);
					}

					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					$this->send_notify($data_user);
				} else {
					$this->response['error'] 	= true;
					$this->response['message'] 	= "out of status access card scope";
				}

			} else {
				$this->response['error'] 	= true;
				$this->response['message'] 	= "out of category access card scope";
			}
			// request type checker




		} else {
			$this->response['error'] 	= true;
			$this->response['message'] 	= "out of category apps scope";
		}
		// category checker
	}


	private function send_notify($user_array) {
		$full_data = array();
		for($i=0;$i<count($user_array);$i++) {
			$data = array(
				'user_id'		=> $user_array[$i],
				'category'		=> $this->category,
				'notify_type'	=> $this->data->request_type,
				'notify_status' => $this->data->status_akses,
				'data_id'		=> $this->data->id,
				'data_uuid'		=> $this->data->uuid,
				'created_at'	=> date('Y-m-d H:i:s'),
				'updated_at'	=> date('Y-m-d H:i:s')
			);
			array_push($full_data,$data);
		}
		Model_Notification::insert($full_data);
	}
}
