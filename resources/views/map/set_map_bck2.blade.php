<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<style type="text/css">
	body, html {
    background-color:gray;
    height: 100%;
    min-width: 1200px;
	max-width: 1700px;
	margin : 0 auto;
	}

	.full_wrapper {
		width: 96%;
		margin : 0 auto;
		background-color: blue;
	    height: 100%!important;
	}

	.wrapper_top {
		background-color: red;
		width: 100%;
		height: 12%;
	}

	.wrapper_map {
		background-color: yellow;
		height: 88%;
		padding: 5px;
	}


	.bg { 
    /* The image used */
    background-image: url("{{URL::to('/')}}/images/map/6586eede-5431-339b-8894-5ccfc79d66be.jpg	");

    /* Full height */
    height: 100%; 

    /* Center and scale the image nicely */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    border: 5px solid gray;
	}
</style>
</head>
<body>

	<div class="full_wrapper">

		<div class="wrapper_top">
		</div>

		<div class="wrapper_map">
			<div id="pointer_div" onclick="point_it(event)" class="bg">

			</div>
		</div>

	</div>


</body>
</html>