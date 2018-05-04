<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style type="text/css">
    	body {
    	background-image: url("{{URL::asset(DB::table("design")->pluck('logo')->first())}}");
		}
		
		html, body {
        height: 100%!important;
        margin: 0px!important;
    	}

    	.center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .footer {
	    position: fixed;
	    left: 0;
	    bottom: 0;
	    width: 100%;
	    color: gray;
	    text-align: center;
	    padding-bottom: 5px
		}


		.center_form {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 65%;
        }

    </style>


</head>

	<div class="container" style="">
			<div class="center" style="">
				<div style="margin-top: 40px"> </div>
                <a href="{{ route('login')}}">
				    <img class="center" src="{{ asset('images/logo/google.png')}}"  / >
                </a>
				<div class="text-center"> 
					<h4> Inventory Management </h4> 
				</div>

				@yield('content')
				
				<div class="footer" style="margin-bottom: 20px"> XXX Party &#169; 2018 All Right Reserved </div>
			</div>
	</div>

<body>
	

</body>
</html>