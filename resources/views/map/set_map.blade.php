<html>
<head>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">

var limit = "{{$data['map_location']->inventory_data_qty}}";
var adjust_left = 0;
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

	calculate_pos_x = (pos_x-3)/clientWidth*100-(adjust_left*2.5)+"%";
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
					'{{$data["map_location"]->image_location}}'+
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



	$('#'+adjust_id).fadeIn(800);
	//start_animate(adjust_id);
	adjust_left++;

	$('#map_reset').show();
	$('#map_cancel').hide();
	$("#map_submit").prop('disabled', false);
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
</script>

<style type="text/css">
	body, html {
	    background-color:black;
	    height: 100%;
	    min-width: 1200px;
		max-width: 1700px;
		margin : 0 auto;
	}

	.full_wrapper {
		width: 96%;
		margin : 0 auto;
		/*background-color: blue;*/
	    height: 100%!important;
	}

	.wrapper_top {
		background-color: red;
		width: 100%;
		height: 12%;
		padding-top: 20px
	}

	.wrapper_map {
		/*background-color: yellow;*/
		height: 88%;
	}

	.body_map {
		/*background-color: orange;*/
		height: 100%;
		border: 5px solid gray;
	}
	.bg { 
	    /* The image used */
	    background-image: url("{{URL::to('/')}}{{$data['map_location']->map_images}}");

	    /* Full height */
	    height: 100%; 

	    /* Center and scale the image nicely */
	    background-position: center;
	    background-repeat: no-repeat;
	    background-size: 100% 100%;
	    cursor: cell;

	}
</style>
</head>
<body>

	<div class="full_wrapper" id="full_wrapper">

		<div class="wrapper_top">
			<div class="col-xs-6" style="background-color: yellow">
				<div class="pull-right">
					<div >
						<button id="map_submit" class="btn btn-primary">
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

		<div class="wrapper_map">
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
	$('#map_reset').hide();
	$("#map_submit").prop('disabled', true);

</script>
</body>
</html>