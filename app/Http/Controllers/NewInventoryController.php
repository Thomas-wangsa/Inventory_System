<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users_Role;
use App\Http\Models\Status_Inventory;

use Illuminate\Support\Facades\Auth;

 
class NewInventoryController extends Controller
{   

    protected $admin = 1;
    protected $new_inventory_divisi_id = 7;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $user_divisi = \Request::get('user_divisi');
        $restrict_divisi_inventory = 7;
        $allow = false;
        if(
            in_array($this->admin,$user_divisi)
            ||
            in_array($this->new_inventory_divisi_id,$user_divisi)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to inventory page');
            return redirect('home');
        }


        
        $list_new_inventory_role = Users_Role::GetInventoryRoleById(Auth::user()->id)->get();
        $data = [
            'list_new_inventory_role'   => $list_new_inventory_role,
            'status_inventory'          => Status_Inventory::all()
        ];
        //dd($data);
        return view('new_inventory/index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
