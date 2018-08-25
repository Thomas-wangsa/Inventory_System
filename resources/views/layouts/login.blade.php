<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

	<title>{{ config('app.name', 'Laravel') }}</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    

    <!-- Styles -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


     <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/indosat.png')}}" />

</head>


<style type="text/css">
	body {
		background-image: url("{{URL::asset(DB::table("design")->pluck('logo')->first())}}");
        background-repeat: no-repeat;
        background-size: 100% 100%;
		background-color: LightGray;
	}

	html, body {
        height: 100%!important;

    }
	.wrapper {
		min-width: 1000px;
		max-width: 1700px;
		margin : 0 auto;
		height: 100%!important;
	}

	.mid_wrapper {
		height: 100%!important;
		background-color: white;
	}

	.center {
	    display: block;
	    margin-left: auto;
	    margin-right: auto;
	    width: 65%;
    }

    .footer {
	    position: absolute;
	    left: 0;
	    bottom: 0;
	    width: 100%;
	    color: gray;
	    text-align: center;
	    padding-bottom: 5px
	}
</style>
<body>

	<div class="wrapper">
		<div class="col-sm-3">
		</div>

		<div class="col-sm-6 mid_wrapper" >

			<div style="" >
                <a href="{{ route('login')}}">
				    <img class="img-responsive center"  
				    src="{{ asset('images/logo/indosat.png')}}"
				    style="max-width: 300px"  / >
                </a>
				<div class="text-center"> 
					<h4> Access & Inventory Management </h4> 
				</div>
				@yield('content')
			</div>

			<div class="footer" style="margin-bottom: 10px"> 
				Indosat System &#169; 2018 All Right Reserved 
			</div>

		</div>

		<div class="col-sm-3">
		</div>

		<div class="clearfix"> </div>
	</div>
	

</body>
</html>
