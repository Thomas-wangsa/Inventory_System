@extends('layouts.map')

@section('content')
<style type="text/css">


	.wrapper_top {
/*		background-color: red;
*/		width: 100%;
		height: 7%;
		padding-top: 5px;
	}

	.wrapper_map {
		/*background-color: yellow;*/
		height: 92%;
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
</style>


	<div class="wrapper_top">
		<div>
			<button id="map_submit" 
			class="btn btn-success btn-block"
			onclick="back()"
			>
				<span class="glyphicon glyphicon-chevron-left"></span> Back
			</button>
		</div>
	</div>

	<div class="wrapper_map">
		<div class="body_map">
			<div id="pointer_div" 
			class="bg">
				


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
				$('#pointer_div').append(images);
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
