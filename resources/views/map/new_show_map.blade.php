@extends('layouts.map')

@section('content')
<style type="text/css">


	.wrapper_top {
/*		background-color: red;
*/		width: 100%;
		height: 10%;
		padding-top: 20px;
	}

	.wrapper_map {
		/*background-color: yellow;*/
		height: 100%;
	}

	.body_map {
		/*background-color: orange;*/
		height: 100%;
		border: 5px solid gray;
	}
	.bg { 
	    /* The image used */
	    background-image: url("{{URL::to('/')}}{{$data['map_data']->map_images}}");

	    /* Full height */
	    height: 100%; 

	    /* Center and scale the image nicely */
	    background-position: center;
	    background-repeat: no-repeat;
	    background-size: 100% 100%;
	    cursor: cell;
	    z-index: 1;

	}
</style>
	<div id="id_wrapper_map" class="wrapper_map">
		<div class="body_map">
			<div id="pointer_div" 
			onmousemove="get()" 
			class="bg" style="cursor: pointer;">

			</div>
		</div>
	</div>

<script type="text/javascript">
	var adjust_left = 0;

	function generate() {

		@if(count($data['group_map']) > 0) 
			@foreach($data['group_map'] as $key=>$val)
				color = "white";

				<?php 
					if($val->sub_data_status == "good") {
						$colors = "blue";
					} else if($val->sub_data_status == "bad") {
						$colors = "red";
					} else if($val->sub_data_status == "others") {
						$colors = "yellow";
					} else {
						$colors = "white";
					}
				?>

				<?php
					if($val->sub_data_uuid == $data['inventory_sub_data']->sub_data_uuid ) {
						$colors = "green";
					} 
				?>

				color = "{{$colors}}";

				adjust_id = "view_image_"+adjust_left;

				calculate_pos_x = "{{$val->x_point}}";
				calculate_pos_y = "{{$val->y_point}}";
				var images = '<img '+
				'id="'+adjust_id+'" '+
				'class="img-thumbnail" '+ 
				'src='+
					'"'+
						'{{URL::to("/")}}'+
						'{{$val->images}}'+
					'"'+
				' width="30px" '+
				' data-toggle="tooltip" title="<?php echo substr($val->sub_data_uuid, 0,10); ?>" '+
				'style='+
					'"'+
						'position:relative;'+
						'z-index:2;'+
						'background-color:'+color+';'+

					'"'+
					'/>';
				$('#pointer_div').append(images);
				// 

				// calculate_pos_x_modif = ((adjust_left*30))/(clientWidth)*100;
				// alert(calculate_pos_x_modif);

				new_x_coordinate = calculate_x_coordinate(adjust_left,calculate_pos_x);

				document.getElementById(adjust_id).style.left = new_x_coordinate;
				document.getElementById(adjust_id).style.top = calculate_pos_y;

				adjust_left++;

			@endforeach
		@else 
			console.log("{{count($data['group_map'])}}")

		@endif
	}

	function calculate_x_coordinate(adjust_left,calculate_pos_x) {

		if(adjust_left == 0 ) {
			return calculate_pos_x
		} else {
			var clientWidth = document.getElementById('pointer_div').clientWidth;
			calculate_pos_x_modif = (adjust_left*30)/(clientWidth)*100;
			//
			var res = calculate_pos_x.split("%");
			x_ori = res[0];
			//
			//alert(res);
			return x_ori-calculate_pos_x_modif+"%";
		}

	}

	generate();
</script>
@endsection

