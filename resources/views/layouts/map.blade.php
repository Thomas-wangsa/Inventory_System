<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

	<title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/indosat.png')}}" />


<script type="text/javascript">


</script>

<style type="text/css">
	body, html {
/*	    background-color:black;
*/	    height: 100%;
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

</style>
</head>
<body>

	<div class="full_wrapper" id="full_wrapper">
		@yield('content') 
	</div>


</body>
</html>