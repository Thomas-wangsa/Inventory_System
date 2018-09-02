<?php

namespace App\Http\Controllers;
use App\Http\Models\Map;
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
