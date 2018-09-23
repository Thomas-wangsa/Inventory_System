@extends('layouts.map')

@section('content')
<style type="text/css">

	.full_view_wrapper {
		height: 100%
	}
	.wrapper_top {
/*		background-color: red;
*/		width: 100%;
		height: 10%;
		padding-top: 15px;
	}

	.wrapper_map {
		/*background-color: yellow;*/
		height: 90%;
	}

	.body_map {
		/*background-color: orange;*/
		height: 100%;
		border: 5px solid gray;
	}
	.bg { 
	    /* The image used */
	    background-image: url("{{URL::to('/')}}{{$data['data_map']->map_position}}");

	    /* Full height */
	    height: 100%; 

	    /* Center and scale the image nicely */
	    background-position: center;
	    background-repeat: no-repeat;
	    background-size: 100% 100%;
	}

	.full_edit_wrapper {
		height: 100%;
	}
</style>
	
	<div class="full_view_wrapper">
		<div class="wrapper_top">
			<div class="col-xs-6">
				<div class="pull-right">
					<button id="map_back" 
					class="btn btn-success"
					onclick="back()"
					>
						<span class="glyphicon glyphicon-chevron-left">
						</span> Back
					</button>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="pull-left">
					<button id="map_edit" 
					class="btn btn-warning"
					onclick="map_edit()"
					>
						<span class="glyphicon glyphicon-pencil	">
						</span> Edit
					</button>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="wrapper_map">
			<div class="body_map">
				<div id="view_pointer" 
				class="bg">
				</div>
			</div>
		</div>
	</div>





<script type="text/javascript">
	var no_img = 0;
	function back() {
		window.location = "{{route('inventory')}}"+
					"?search=on&search_uuid="+
					"{{$data['data_map']->inventory_data_uuid}}";
	}

	function map_edit() {
		alert(no_img);
	}
	function generate() {

		@if(count($data['data_position']) > 0) 
			@foreach($data['data_position'] as $key=>$val)

				adjust_id = "img_"+no_img;

				calculate_pos_x = "{{$val->x_point}}";
				calculate_pos_y = "{{$val->y_point}}";
				var images = '<img '+
				'id="'+adjust_id+'" '+
				'src='+
					'"'+
						'{{URL::to("/")}}'+
						'{{$data["data_map"]->image_position}}'+
					'"'+
				' width="30px" '+
				'style='+
					'"'+
						'position:relative;'+
						'z-index:2;'+
					
					'"'+
					'/>';
				$('#view_pointer').append(images);
				document.getElementById(adjust_id).style.left = calculate_pos_x;
				document.getElementById(adjust_id).style.top = calculate_pos_y;

				no_img++;

			@endforeach
		@else 
			console.log("{{count($data['data_position'])}}")

		@endif

	}

	

	generate();
</script>
@endsection
