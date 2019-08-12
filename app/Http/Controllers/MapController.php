<?php

namespace App\Http\Controllers;
use App\Http\Models\Map;
use App\Http\Models\Map_Location;
use App\Http\Models\Map_Detail;
use App\Http\Models\Inventory_Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Models\New_Inventory_Data;
use App\Http\Models\New_Inventory_Sub_Data;
use App\Http\Models\New_Map;
use App\Http\Models\New_Map_Images;

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

    public function view_map(Request $request) {
        $request->validate([
            'uuid' => 'required|max:100',
            'map_location_uuid' => 'required|max:100',
        ]);


        $data_map = Map_Location::join('inventory_data',
            'inventory_data.id','=','map_location.inventory_data_id')
            ->join('map',
            'map.id','=','map_location.map_id')
            ->where('inventory_data.uuid',$request->uuid)
            ->where('map_location.map_location_uuid',$request->map_location_uuid)
            ->select(
                'inventory_data.uuid AS inventory_data_uuid',
                'inventory_data.qty AS inventory_data_qty',
                'map_location.map_location_uuid',
                'map.map_images AS map_position',
                'map_location.image_location AS image_position'
            )
            ->first();

        if(count($data_map) < 1) {
            $request->session()->flash('alert-danger', 'map is not found!');
            return redirect("/inventory?search=on&search_uuid=".$request->uuid);
        }

        $data_position = Map_Detail::where('map_location_uuid',$data_map->map_location_uuid)
            ->where('status_map_detail',1)
            ->get();

        $data['data_map']       = $data_map;
        $data['data_position']  = $data_position;
        // dd($data_position);

        //dd($data);
        return view('map/view_map',compact('data'));
    }
    public function set_map_location(Request $request) {

        // return view('map/set_map');
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


        
        $uuid = $this->faker->uuid;
        if ($request->hasFile('image_location')) {
            $image = $request->file('image_location');
            $file_name = $uuid.".".$image->getClientOriginalExtension();
            $path = "/images/imagelocation/";
            $destinationPath = public_path($path);
            $image->move($destinationPath, $file_name);
            $new_image_location = $path.$file_name;
            
        } else {
            $request->session()->flash('alert-danger', 'image_location is not found!');
            return redirect("/inventory");
        }

        $check_map = Map_Location::where('inventory_data_id',$inventory_data->id)
                ->first();

        //dd($check_map);
        $data = array();
        if(count($check_map) < 1) {
            $map_location = new Map_Location;
            

            $map_location->inventory_data_id =$inventory_data->id;
            $map_location->map_location_uuid = time().$uuid;
            $map_location->created_by = Auth::user()->id;
            
            $map_location->map_id = $request->map_id;
            $map_location->updated_by = Auth::user()->id;
            $map_location->image_location = $new_image_location;
            $bool = $map_location->save();

            if(!$bool) {
                $request->session()->flash('alert-danger', 'insert map location is failed!');
                return redirect("/inventory");
            }
            // echo "NEW";
            $map_location_data = $map_location;
        } else {
            $check_map->map_id = $request->map_id;
            $check_map->image_location = $new_image_location;
            $check_map->updated_by = Auth::user()->id;
            $bool = $check_map->save();

            if(!$bool) {
                $request->session()->flash('alert-danger', 'updated map location is failed!');
                return redirect("/inventory");
            }
            // echo "UPDATEs";
            $map_location_data = $check_map;
        }

        $data['map_location'] = Map_Location::join('map',
                    'map.id','=','map_location.map_id')
                    ->join('inventory_data',
                    'inventory_data.id','=','map_location.inventory_data_id')
                    ->leftjoin('map_detail',
                    'map_detail.map_location_uuid','=','map_location.map_location_uuid')
                    ->where('map_location.inventory_data_id',$map_location_data->inventory_data_id)
                    ->where('map_location.map_id',$map_location_data->map_id)
                    ->select(
                        'inventory_data.uuid AS inventory_data_uuid',
                        'map.map_images AS map_images',
                        'map_location.image_location AS image_location',
                        'map_location.map_location_uuid AS map_location_uuid',
                        'inventory_data.qty AS inventory_data_qty',
                        'map_detail.map_location_uuid AS map_detail_uuid'
                    )
                    ->first();


        if($data['map_location']->map_detail_uuid != null) {
            Map_Detail::where('map_location_uuid', $data['map_location']->map_detail_uuid)
                ->where('status_map_detail', 1)
                ->update(['status_map_detail' => 0]);
        }
        return view('map/set_map',compact('data'));
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

    public function edit_map_location(Request $request) {
        $response = array();
        $response['status'] = false;


        $inventory_sub_data = New_Inventory_Sub_Data::where('sub_data_uuid','=',$request->sub_data_uuid)
                            ->first();

        if($inventory_sub_data == null) {
            $response['message'] = "inventory sub data is not found!";
            return json_encode($response);
        }

        $inventory_sub_data->x_point = null;
        $inventory_sub_data->y_point = null;
        $inventory_sub_data->map_id = null;
        $inventory_sub_data->map_images_id   = null;

        if($inventory_sub_data->save()) {
            $response['status'] = true;
        } else {
            $response['message'] = "Update Map Failed!";
        }

    
        return json_encode($response);
    }
 
    public function approve_map_location(Request $request) {
        $response = array();
        $response['status'] = false;

        if($request->data_x == null || $request->data_y == null)  {
            $response['message'] = "coordinate map is not found!";
            return json_encode($response);
        } 

        $inventory_sub_data = New_Inventory_Sub_Data::where('sub_data_uuid','=',$request->sub_data_uuid)
                            ->first();

        if($inventory_sub_data == null) {
            $response['message'] = "inventory sub data is not found!";
            return json_encode($response);
        }

        $inventory_sub_data->x_point = $request->data_x;
        $inventory_sub_data->y_point = $request->data_y;
        $inventory_sub_data->map_id = $request->map_id;
        $inventory_sub_data->map_images_id   = $request->map_images_id;

        if($inventory_sub_data->save()) {
            $response['status'] = true;
        } else {
            $response['message'] = "Set Map Failed!";
        }

    
        return json_encode($response);
    }


    public function new_inventory_select_map(Request $request) {

        $request->validate([
            'inventory_data_uuid'   => 'required|max:200',
            'sub_data_uuid'         => 'required|max:200',
            'map_uuid'              => 'required|max:200',
            'images_uuid'           => 'required|max:200',
        ]);


        // Validation Data
        $inventory_data = New_Inventory_Data::where('uuid','=',$request->inventory_data_uuid)
                            ->first();

        if($inventory_data == null) {
            $request->session()->flash('alert-danger', 'inventory data is not found!');
            return redirect('/new_inventory/create?uuid='.$request->inventory_data_uuid);
        }


        $inventory_sub_data = New_Inventory_Sub_Data::where('sub_data_uuid','=',$request->sub_data_uuid)
                            ->where('new_inventory_data_id','=',$inventory_data->id)
                            ->first();

        if($inventory_sub_data == null) {
            $request->session()->flash('alert-danger', 'inventory sub data is not found!');
            return redirect('/new_inventory/create?uuid='.$request->inventory_data_uuid);
        }

        $map_data = New_Map::where('uuid','=',$request->map_uuid)
                    ->where('new_inventory_data_id','=',$inventory_data->id)
                    ->first();

         if($map_data == null) {
            $request->session()->flash('alert-danger', 'map data is not found!');
            return redirect('/new_inventory/create?uuid='.$request->inventory_data_uuid);
        }


        $map_images_data = New_Map_Images::where('uuid','=',$request->images_uuid)
                    ->where('new_inventory_data_id','=',$inventory_data->id)
                    ->first();

         if($map_images_data == null) {
            $request->session()->flash('alert-danger', 'map images is not found!');
            return redirect('/new_inventory/create?uuid='.$request->inventory_data_uuid);
        }


        // $inventory_sub_data->map_id = $map_data->id;
        // $inventory_sub_data->map_images_id = $map_images_data->id;
        // $inventory_sub_data->save();

        $group_map = New_Inventory_Sub_Data::where('new_inventory_data_id','=',$inventory_data->id)
                    ->where('map_id','=',$map_data->id)
                    ->where('map_images_id','=',$map_images_data->id)
                    ->whereNotNull('x_point')
                    ->whereNotNull('y_point')
                    ->get();

        //dd($group_map);
        $data = [
            'inventory_data'        => $inventory_data,
            'inventory_sub_data'    => $inventory_sub_data,
            'map_data'              => $map_data,
            'map_images_data'       => $map_images_data,
            'group_map'             => $group_map
        ];

        return view('map/new_set_map',compact('data'));
    }


    function new_inventory_show_map(Request $request) {
        $request->validate([
            'sub_data_uuid'         => 'required|max:200',
        ]);

        $inventory_sub_data = New_Inventory_Sub_Data::where('sub_data_uuid','=',$request->sub_data_uuid)
                            ->first();

        if($inventory_sub_data == null) {
            $request->session()->flash('alert-danger', 'inventory sub data is not found!');
            return redirect()->back();
        }



        $map_data = New_Map::find($inventory_sub_data->map_id);

         if($map_data == null) {
            $request->session()->flash('alert-danger', 'map data is not found!');
            return redirect()->back();
        }


        $map_images_data = New_Map_Images::find($inventory_sub_data->map_images_id);

         if($map_images_data == null) {
            $request->session()->flash('alert-danger', 'map images is not found!');
            return redirect()->back();
        }




        $group_map = New_Inventory_Sub_Data::join('new_map_images','new_map_images.id','=','new_inventory_sub_data.map_images_id')
                    ->where('new_inventory_sub_data.new_inventory_data_id','=',$inventory_sub_data->new_inventory_data_id)
                    ->where('new_inventory_sub_data.map_id','=',$inventory_sub_data->map_id)
                    //->where('map_images_id','=',$inventory_sub_data->map_images_id)
                    ->whereNotNull('new_inventory_sub_data.x_point')
                    ->whereNotNull('new_inventory_sub_data.y_point')
                    ->select('new_inventory_sub_data.*','new_map_images.images as images')
                    ->get();

        $data = [
            'inventory_sub_data'    => $inventory_sub_data,
            'map_data'              => $map_data,
            'map_images_data'       => $map_images_data,
            'group_map'             => $group_map
        ];
        

        //dd($data);
        return view('map/new_show_map',compact('data'));
    }
}
