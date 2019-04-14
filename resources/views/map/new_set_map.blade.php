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
		height: 90%;
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
						<a href="{{URL::to('/')}}/new_inventory/create?uuid={{$data['inventory_data']->uuid}}">
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
				class="bg" style="cursor: pointer;">

				</div>
			</div>
		</div>

		<div id="define_wrapper" class="wrapper_map" style="background-color: gray">
			
		</div> 

<script type="text/javascript">
	$(document).ready(function(){
  		$('[data-toggle="tooltip"]').tooltip();   
	});

	$('#map_reset').hide();
	$("#id_wrapper_map").hide();
	$("#map_submit").prop('disabled', true);

	var limit = <?php echo count($data['group_map']) + 1 ?>;
	var adjust_left = 0;

	var images_position = {
	    "data_x":null,
	    "data_y":null
	};

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
						'{{$data["map_images_data"]->images}}'+
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
				document.getElementById(adjust_id).style.left = calculate_pos_x;
				document.getElementById(adjust_id).style.top = calculate_pos_y;

				adjust_left++;

			@endforeach
		@else 
			console.log("{{count($data['group_map'])}}")

		@endif

	}


	function point_it(event){
		if(adjust_left >= limit) {
			alert("Images already set!");
			return false;
		}

		adjust_id = "img_"+adjust_left;
		var clientWidth = document.getElementById('pointer_div').clientWidth;
		var clientHeight = document.getElementById('pointer_div').clientHeight;
		pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("pointer_div").offsetLeft;
		pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("pointer_div").offsetTop;
		calculate_pos_x = ((pos_x-15)-(adjust_left*30))/(clientWidth)*100+"%";
		calculate_pos_y = (pos_y-15)/clientHeight*100+"%" ;


		<?php 
			if($data['inventory_sub_data']->sub_data_status == "good") {
				$colors = "blue";
			} else if($data['inventory_sub_data']->sub_data_status == "bad") {
				$colors = "red";
			} else if($data['inventory_sub_data']->sub_data_status == "others") {
				$colors = "yellow";
			} else {
				$colors = "white";
			}
		?>

		color = "{{$colors}}";

		var new_images = '<img '+
				'id="'+adjust_id+'" '+
				'class="img-thumbnail" '+ 
				'src='+
					'"'+
						'{{URL::to("/")}}'+
						'{{$data["map_images_data"]->images}}'+
					'"'+
				' width="30px" '+
				' data-toggle="tooltip" title="<?php echo substr($data['inventory_sub_data']->sub_data_uuid, 0,10); ?>" '+
				'style='+
					'"'+
						'position:relative;'+
						'z-index:2;'+
						'background-color:'+color+';'+
					
					'"'+
					'/>';


		$('#pointer_div').append(new_images);
		$('#'+adjust_id).hide();
		document.getElementById(adjust_id).style.left = calculate_pos_x;
		document.getElementById(adjust_id).style.top = calculate_pos_y;

		images_position['data_x'] = calculate_pos_x;
		images_position['data_y'] = calculate_pos_y; 

		$('#'+adjust_id).fadeIn(800);
		//start_animate(adjust_id);
		adjust_left++;

		$('#map_reset').show();
		$('#map_cancel').hide();

		if(adjust_left == limit) {
			$("#map_submit").prop('disabled', false);
		}
		
	}

	function execute() {
		$('#define_wrapper').hide();
		$("#id_wrapper_map").show();
	}
	

	setTimeout(execute(),3000); 

	function clear_map() {

		if (confirm('are you sure to clear and reset the map ?')) {
			$('#pointer_div')
			    .find('#img_'+"{{count($data['group_map'])}}")
			    .remove()
			    .end();
			adjust_left = "{{count($data['group_map'])}}";
			$('#map_reset').hide();
			$('#map_cancel').show();
			$("#map_submit").prop('disabled', true);

			images_position = {
		    	"data_x":null,
		    	"data_y":null
			};
		}
	}

	function start_animate(id) {
		$("#"+id).fadeIn(1000, function () {
	        $("#"+id).fadeOut(300, function () {
	            start_animate(id);
	        });
	    });
	}

	function get() {
		// $('#aaa').html(event.offsetX);
	}


	function submit_map() {
		if (confirm('are you sure to set this map ?')) {
			console.log(images_position); 
			var data = {
	        "data_x":images_position['data_x'],
	        "data_y":images_position['data_y'],
	        "map_id":"{{$data['map_data']->id}}",
	        "map_images_id":"{{$data['map_images_data']->id}}",
	        "sub_data_uuid":"{{$data['inventory_sub_data']->sub_data_uuid}}"
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
						var url = "{{URL::to('/')}}"+'/new_inventory/create?uuid=';
						window.location = url+"{{$data['inventory_data']->uuid}}";
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

	


	generate();
	//$('#id_wrapper_map').show();

</script>
@endsection
