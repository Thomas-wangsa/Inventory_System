<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Akses_Role;
use App\Http\Models\Inventory_Level;

class AjaxController extends Controller
{
    public function get_akses_role(Request $request) {
    	$role = Akses_Role::all();
    	return json_encode($role);
    }

    public function get_inventory_level(Request $request) {
    	$inventory_level = Inventory_Level::all();
    	return json_encode($inventory_level);
    }
}
