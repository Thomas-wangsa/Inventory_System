@extends('layouts.map')

@section('content')

<style type="text/css">
	
	#full_wrapper_edit_map {
		height: 100%;
	}

	.wrapper_top {
/*		background-color: red;
*/		width: 100%;
		height: 10%;
		padding-top: 20px;
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
	    cursor: cell;

	}
</style>
	<div id="full_wrapper_edit_map">
		<div class="wrapper_top">
			<div class="col-xs-6">
				<div class="pull-right">
					<div >
						<button id="map_submit" 
						class="btn btn-primary"
						onclick="submit_map()"
						>
							Submit Map
						</button>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="pull-left">
					<div id="map_cancel">
						<a href="{{route('inventory')}}">
							<button class="btn btn-danger">
								Cancel
							</button>
						</a>
					</div>
					<div id="map_reset">
						<button class="btn btn-warning" onclick="clear_map()">
							Clear Map
						</button>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>

		<div id="id_wrapper_map" class="wrapper_map">
			<div class="body_map">
				<div id="pointer_div" 
				onclick="point_it(event)"
				onmousemove="get()" 
				class="bg">
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	//$('#full_wrapper_edit_map').hide();
	$('#map_reset').hide();
	$("#map_submit").prop('disabled', true);

	var limit = "{{$data['data_map']->inventory_data_qty}}";
	var adjust_left = 0;


	var full_data = [];

	function point_it(event){
		if(adjust_left >= limit) {
			alert("Quantity is limited");
			return false;
		}

		adjust_id = "img_"+adjust_left;
		var clientWidth = document.getElementById('pointer_div').clientWidth;
		var clientHeight = document.getElementById('pointer_div').clientHeight;
		
		
		pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("pointer_div").offsetLeft;
		pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("pointer_div").offsetTop;


		//calculate_pos_x = (pos_x-3)/clientWidth*100-(adjust_left*2.5)+"%";
		// perhitungan adjustleft *30 for cheat the patern float in css
		calculate_pos_x = ((pos_x-15)-(adjust_left*30))/(clientWidth)*100+"%";
		calculate_pos_y = (pos_y-15)/clientHeight*100+"%" ;

		// document.getElementById("cross").style.left = calculate_pos_x;
		// document.getElementById("cross").style.top = calculate_pos_y;
		// document.getElementById("cross").style.visibility = "visible" ;

		// var new_images = '<img src="{{URL::to("/")}}/images/imagelocation/0529ba75-4c69-3a19-9a03-2eda95c8c7a5.jpeg" width="40px" style="position:relative;z-index:1;">';

		var new_images = '<img '+
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


		$('#pointer_div').append(new_images);
		$('#'+adjust_id).hide();
		document.getElementById(adjust_id).style.left = calculate_pos_x;
		document.getElementById(adjust_id).style.top = calculate_pos_y;


		var data = {
	    "data_x":calculate_pos_x,
	    "data_y":calculate_pos_y
		};


		console.log(data);
		full_data.push(data);

		$('#'+adjust_id).fadeIn(800);
		//start_animate(adjust_id);
		adjust_left++;

		$('#map_reset').show();
		$('#map_cancel').hide();

		if(adjust_left == limit) {
			$("#map_submit").prop('disabled', false);
		}
		
	}

 

	function clear_map() {

		if (confirm('are you sure to clear and reset the map ?')) {
			$('#pointer_div')
			    .find('img')
			    .remove()
			    .end();
			adjust_left = 0;
			$('#map_reset').hide();
			$('#map_cancel').show();
			$("#map_submit").prop('disabled', true);
		}
	}


	function get() {
		// $('#aaa').html(event.offsetX);
	}


	function submit_map() {
		console.log(full_data);
		if (confirm('are you sure to set this map ?')) { 
			var data = {
	        "full_data":full_data,
	        "map_location_uuid":"{{$data['data_map']->map_location_uuid}}"
	        };

	        $.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});

			$.ajax({
				type : "POST",
				url: " {{ route('approve_map_location') }}",
				contentType: "application/json",
				data : JSON.stringify(data),
				success: function(result) {
					response = JSON.parse(result);
					//console.log(response);
					if(response.status == true) { 
						alert("set location success!");
						var url = "{{URL::to('/')}}"+'/inventory?search=on&search_uuid=';
						window.location = url+"{{$data['data_map']->inventory_data_uuid}}";
					} else {
						alert(response.message);
					}
					
				},
				error: function( jqXhr, textStatus, errorThrown ){
					console.log( errorThrown );
				}
			});

		}		
	}


</script>
@endsection
