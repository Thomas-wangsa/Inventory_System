<html>
<head>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
function point_it(event){
	pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("pointer_div").offsetLeft;
	pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("pointer_div").offsetTop;
	document.getElementById("cross").style.left = (pos_x-1) ;
	document.getElementById("cross").style.top = (pos_y-15) ;
	document.getElementById("cross").style.visibility = "visible" ;
	document.pointform.form_x.value = pos_x;
	document.pointform.form_y.value = pos_y;
}
</script>

<style type="text/css">
	body, html {
    height: 100%;
}

.bg { 
    /* The image used */
    background-image: url("https://www.katrinaleechambers.com/wp-content/uploads/2018/02/download.jpg");

    /* Full height */
    height: 100%; 

    /* Center and scale the image nicely */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
</head>
<body>



	<div class="container">
		<div style="margin: 10px">
		<form name="pointform" method="post">



		<div id="pointer_div" onclick="point_it(event)" class="bg">
		<img src="https://www.gizmotimes.com/wp-content/uploads/2015/08/Printer-300x205.png" width="40px" id="cross" style="position:relative;visibility:hidden;z-index:2;">
		</div>

		You pointed on x = <input type="text" name="form_x" size="4" /> - y = <input type="text" name="form_y" size="4" />
		</form> 
		</div>
	</div>

</body>
</html>