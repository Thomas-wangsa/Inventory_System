<?php

namespace App\Http\Controllers;
use App\Http\Models\Map;
use App\Http\Models\Map_Location;
use App\Http\Models\Inventory_Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Faker\Factory as Faker;


class MapController extends Controller
{	
	protected $faker;

	public function __construct(){
        $this->faker    = Faker::create();
        //$this->env      = env("ENV_STATUS", "development");
    }

    public function index(Request $request) {

    } 

    public function set_map_location(Request $request) {
        $request->validate([
            'image_location' => 'required|image|mimes:jpeg,png,jpg|max:550',
            'map_location_uuid' => 'required|max:50',
            'map_id' => 'required|max:50',
        ]);

        $inventory_data = Inventory_Data::where('uuid',$request->map_location_uuid)->first();

        if(count($inventory_data) < 1) {
            $request->session()->flash('alert-danger', 'inventory data ID is not found!');
            return redirect("/inventory"); 
        } 


        $check_map = Map_Location::where('inventory_data_id',$inventory_data->id)
                ->get();
        if(count($check_map) < 1) {
            $map_location = new Map_Location;
            $uuid = $this->faker->uuid;

            if ($request->hasFile('image_location')) {
                $image = $request->file('image_location');
                $file_name = $uuid.".".$image->getClientOriginalExtension();
                $path = "/images/imagelocation/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $map_location->image_location = $path.$file_name;
            } else {
                $request->session()->flash('alert-danger', 'image_location is not found!');
                return redirect("/inventory");
            }


            $map_location->map_id = $request->map_id;
            $map_location->inventory_data_id =$inventory_data->id;
            $map_location->uuid = time().$uuid;
            $map_location->created_by = Auth::user()->id;
            $map_location->updated_by = Auth::user()->id;

            $bool = $map_location->save();

            if(!$bool) {
                $request->session()->flash('alert-danger', 'insert map location is failed!');
                return redirect("/inventory");
            }
        } else {

        }

        dd($request);
        $data = array();
        return view('inventory/map',compact('data'));
    }
    public function add_map(Request $request) {
    	$request->validate([
            'map_images' => 'required|image|mimes:jpeg,png,jpg|max:550',
            'inventory_list_id' => 'required|max:50',
            'map_name' => 'required|max:50',
        ]);

    	$map = new Map;
        if ($request->hasFile('map_images')) {
            $image = $request->file('map_images');
            $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
            $path = "/images/map/";
            $destinationPath = public_path($path);
            $image->move($destinationPath, $file_name);
            $map->map_images = $path.$file_name;
        }

        $map->inventory_list_id = $request->inventory_list_id;
        $map->map_name 			= $request->map_name;
        $map->map_notes 		= $request->map_notes;
        $map->created_by 		= Auth::user()->id;
        $map->updated_by 		= Auth::user()->id;
        $check = $map->save();

        if($check) {
        	$request->session()->flash('alert-success', 'new map has been registerred');
        } else {
        	$request->session()->flash('alert-danger', 'failed to register new map');
        }

        return redirect("/inventory");
    }
}
