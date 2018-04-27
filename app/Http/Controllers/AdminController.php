<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Divisi;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
    	$data 	= Users::GetJabatan()->get();
    	$user 	= Auth::user();
    	$divisi = Divisi::all();
    	return view('admin/admin',compact('data','user','divisi'));
    }
}
