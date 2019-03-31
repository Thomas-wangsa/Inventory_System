<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Models\Akses_Data;
use App\Http\Models\Users_Role;
use App\Http\Models\Users;
use App\Http\Models\AccessCardRequest;
use App\Http\Models\AccessCardRegisterStatus;
use App\Http\Models\Status_Akses;

use Illuminate\Support\Facades\Auth;
use App\Notifications\Access_Notification;
use App\Notifications\Akses_Notifications;

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

		if(env("ENV_STATUS", "development") == "production") {
			$this->notify_mail();
		}
				
		return $this->response;
	}


	private function notify_mail() {
		$param = array();
		// category checker
		if($this->category == 1) {
            
			// Request Type Checker
            if(
				$this->data->request_type == 1 ||
				$this->data->request_type == 2 ||
				$this->data->request_type == 3 ||
				$this->data->request_type == 4 ||
				$this->data->request_type == 5
			) { 
				// Global Variable            	
            	$request_name = AccessCardRequest::find($this->data->request_type)->request_name;
            	$register_name = AccessCardRegisterStatus::find($this->data->register_type)->register_name;

            	$status_akses  = Status_Akses::find($this->data->status_akses);
            	// Global Variable

            	$param['cc_email'] = array();
            	//initiate
            	array_push($param['cc_email'],Users::find(Auth::user()->id)->email);
            	
            	$param['subject'] = "[no-reply] [Access Card] ".
            						$request_name." : ".$status_akses->name.
            						" a/n ".$this->data->name;
            	$param['description'] = "Here is auto notification feature from our application, with the information of access card :";
            	$param['status_name'] 	= $status_akses->name;
            	$param['status_color']	= $status_akses->color;
            	$param['note']			= null;

            	if($this->data->status_akses == 1) {
            		$param['note']  = "Note : link ini tidak berlaku untuk ".
            						$this->data->name;
            		// worker
            		if(!in_array($this->data->email,$param['cc_email'])) {				
            			array_push($param['cc_email'],$this->data->email);
            		}

            		$next_user = Users_Role::join('pic_role',
                        'pic_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi','=',2)
                        ->where('pic_list_id',$this->data->pic_list_id)
                        ->where('pic_level_id',2)
                        ->pluck('pic_role.user_id');

                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
            	} else if($this->data->status_akses == 2) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',1)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
					
				} else if($this->data->status_akses == 3) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',2)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
					
				} else if($this->data->status_akses == 4) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 5) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',4)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 6) {
					$next_user = Users_Role::join('admin_room_role',
                        'admin_room_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi','=',5)
                        ->where('admin_room_role.admin_room_list_id',$this->data->admin_room_list_id)
                        ->pluck('admin_room_role.user_id');

					$list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					$card_printing_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');

                    $list_email_cp = Users::whereIn('id',$card_printing_user)
                    			->pluck('users.email');

                    foreach($list_email_cp as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}
					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 7) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',5)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}
					

					$card_printing_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');
                    $list_email_cp = Users::whereIn('id',$card_printing_user)
                    			->pluck('users.email');

                    foreach($list_email_cp as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}
					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 8) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
					
				} else if($this->data->status_akses == 9) {
					$requester_user = Users::find($this->data->created_by)->email;
					// requester
					if(!in_array($requester_user,$param['cc_email'])) {
						array_push($param['cc_email'],$requester_user);
					}
					

					$sponsor_user = Users_Role::join('pic_role',
                        'pic_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi','=',2)
                        ->where('pic_list_id',$this->data->pic_list_id)
                        ->where('pic_level_id',2)
                        ->pluck('pic_role.user_id');
                    $list_email = Users::whereIn('id',$sponsor_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}
					


					$approval_verifications = Users_Role::where('divisi',3)
                    ->where('jabatan',2)
                    ->pluck('user_id');

                    $list_email_cp = Users::whereIn('id',$approval_verifications)
                    			->pluck('users.email');

                    foreach($list_email_cp as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}
					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 10) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',1)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 11) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',2)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 12) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',2)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 14) {
					$card_printing_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$card_printing_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 15) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',5)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else if($this->data->status_akses == 16) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');
                    $list_email = Users::whereIn('id',$next_user)
                    			->pluck('users.email');

                    foreach($list_email as $key=>$val) {
						if(!in_array($val,$param['cc_email'])) {
							array_push($param['cc_email'],$val);
						}
					}

					array_shift($param['cc_email']);
                    $this->send_notify_email($param);
				} else {
            		$this->response['error'] 	= true;
					$this->response['message'] 	= "out of scope email access card status";
            	}

            	

			} else {
				$this->response['error'] 	= true;
				$this->response['message'] 	= "out of scope email access card request type";
			}
			// Request Type Checker
		} else {
			$this->response['error'] 	= true;
			$this->response['message'] 	= "out of scope email notification category";
		}
        // category checker

	}



	private function send_notify_email($param) {
		$user  = Users::find(Auth::user()->id);
		$access_card_no = $this->data->no_access_card == null ? '-' : $this->data->no_access_card;
		$data = array(
				"from"				=> "notification@indosatooredoo.com",
				"replyTo"			=> "notification@indosatooredoo.com",
                "subject"           => $param['subject'],
                "cc_email"          => $param['cc_email'],
                "description"       => $param['description'],
                "access_card_name"  => $this->data->name,
                "access_card_no"    => $access_card_no,
                "status_akses"      => $param['status_name'],
                "status_color"      => $param['status_color'],
                "uuid"              => $this->data->uuid,
                "note"              => $param['note'],
                     
            );

		
		$user->notify(new Akses_Notifications($data));	
		
	}
 
	private function notify_apps() {
		$data_user = array();
		// category checker
		if($this->category == 1) {
			$requester_user = $this->data->created_by;
			$current_user 	= $this->data->updated_by;
			

			// request type checker
			if(
				$this->data->request_type == 1 ||
				$this->data->request_type == 2 ||
				$this->data->request_type == 3 ||
				$this->data->request_type == 4 ||
				$this->data->request_type == 5
			) {
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
					
					// if(!in_array($requester_user,$data_user)) {
					// 	array_push($data_user,$requester_user);
					// }

					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					$this->send_notify($data_user);
				} else if($this->data->status_akses == 6) {
					$next_user = Users_Role::join('admin_room_role',
                        'admin_room_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi','=',5)
                        ->where('admin_room_role.admin_room_list_id',$this->data->admin_room_list_id)
                        ->pluck('admin_room_role.user_id');

					array_push($data_user,$current_user);
					
					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}

					$card_printing_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');

                    foreach($card_printing_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}

					$this->send_notify($data_user);
				} else if($this->data->status_akses == 7) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',5)
                    ->pluck('user_id');

					array_push($data_user,$current_user);
					
					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}

					$card_printing_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');

                    foreach($card_printing_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}

					$this->send_notify($data_user);
				} else if($this->data->status_akses == 8) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');

					array_push($data_user,$current_user);
					
					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}

					$this->send_notify($data_user);
				} else if($this->data->status_akses == 9) {
					// current
					array_push($data_user,$current_user);
					// requester
					if(!in_array($requester_user,$data_user)) {
						array_push($data_user,$requester_user);
					}
					

					$sponsor_user = Users_Role::join('pic_role',
                        'pic_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi','=',2)
                        ->where('pic_list_id',$this->data->pic_list_id)
                        ->where('pic_level_id',2)
                        ->pluck('pic_role.user_id');

					foreach($sponsor_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}


					$approval_verifications = Users_Role::where('divisi',3)
                    ->where('jabatan',2)
                    ->pluck('user_id');

                    foreach($approval_verifications as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}

					$this->send_notify($data_user);
				} else if($this->data->status_akses == 10) {
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
				} else if($this->data->status_akses == 11) {
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
				} else if($this->data->status_akses == 12) {
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
				} else if($this->data->status_akses == 14) {
					array_push($data_user,$current_user);

					$card_printing_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');

                    foreach($card_printing_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					
					$this->send_notify($data_user);
				} else if($this->data->status_akses == 15) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',5)
                    ->pluck('user_id');

					array_push($data_user,$current_user);
					
					foreach($next_user as $key=>$val) {
						if(!in_array($val,$data_user)) {
							array_push($data_user,$val);
						}
					}
					
					$this->send_notify($data_user);
				} else if($this->data->status_akses == 16) {
					$next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');

					array_push($data_user,$current_user);
					
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
				// status type 
			} else {
				$this->response['error'] 	= true;
				$this->response['message'] 	= "out of category access card scope";
			}
			// request type checker
		} else if($this->category == 2) {
			// status inventory
			$current_user 	= Auth::user()->id;
			
			if($this->data->status == 1) {
				$next_user = Users_Role::join('new_inventory_role','new_inventory_role.id','=','users_role.jabatan')
					->where('users_role.divisi',6)
					->where('new_inventory_role.inventory_level_id','=',2)
					->where('new_inventory_role.inventory_list_id','=',$this->data['inventory_list_id'])
                    ->pluck('users_role.user_id');
                array_push($data_user,$current_user);
					
				foreach($next_user as $key=>$val) {
					if(!in_array($val,$data_user)) {
						array_push($data_user,$val);
					}
				}
				$this->send_notify($data_user);
			}
			
		} else {
			$this->response['error'] 	= true;
			$this->response['message'] 	= "out of category apps scope";
		}
		// category checker
	}


	private function send_notify($user_array) {
		$full_data = array();
		// dd($this->data);
		for($i=0;$i<count($user_array);$i++) {

			if($this->category == 1) {
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
			} else if($this->category == 2) {
				$data = array(
				'user_id'		=> $user_array[$i],
				'category'		=> $this->category,
				'notify_type'	=> $this->data->status_data,
				'notify_status' => $this->data->status,
				'data_id'		=> $this->data->id,
				'data_uuid'		=> $this->data->uuid,
				'created_at'	=> date('Y-m-d H:i:s'),
				'updated_at'	=> date('Y-m-d H:i:s')
				);
			}
			
			array_push($full_data,$data);
		}
		Model_Notification::insert($full_data);
	}
}
