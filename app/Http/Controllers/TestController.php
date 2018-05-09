<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;

use App\Notifications\Akses_Notifications;




class TestController extends Controller
{
    public function index() {
    	$data = array(
    		"user" => "thomas"
    	);

    	$user = Users::find(2);

    	$user->notify(new Akses_Notifications());

    	
    }
}
