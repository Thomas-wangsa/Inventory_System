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
	    background-image: url("{{URL::to('/')}}");

	    /* Full height */
	    height: 100%; 

	    /* Center and scale the image nicely */
	    background-position: center;
	    background-repeat: no-repeat;
	    background-size: 100% 100%;
	    cursor: cell;

	}
</style>


	<div class="wrapper_top">
		<div>
			<button id="map_submit" 
			class="btn btn-default btn-block"
			>
				Back
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
@endsection
