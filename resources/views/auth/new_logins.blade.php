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

	<div class="container" style="background-color: red">
		fafa
			<div class="center" style="background-color: blue">
				<div style="margin-top: 40px"> </div>
				<img class="center" src="{{ asset('images/logo/google.png')}}"  / >
				<div class="text-center"> 
					<h4> Inventory Management </h4> 
				</div>

				<div class="center_form" style="background-color: yellow;"> 
					<form class="form-horizontal" action="/action_page.php">
					    <div class="form-group">
					      <label class="control-label col-sm-2" for="email">
					      	<span class="glyphicon glyphicon-envelope" style="font-size: 20px;"></span>
					      </label>
					      <div class="col-sm-10">
					        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
					      </div>
					    </div>
					    <div class="form-group">
					      <label class="control-label col-sm-2" for="pwd">Password:</label>
					      <div class="col-sm-10">          
					        <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
					      </div>
					    </div>
					    <div class="form-group">        
					      <div class="col-sm-offset-2 col-sm-10">
					        <div class="checkbox">
					          <label><input type="checkbox" name="remember"> Remember me</label>
					        </div>
					      </div>
					    </div>
					    <div class="form-group">        
					      <div class="col-sm-offset-2 col-sm-10">
					        <button type="submit" class="btn btn-default">Submit</button>
					      </div>
					    </div>
					</form>
				</div>

				<div class="footer"> All Right Reserved </div>
			</div>
	</div>

<body>
	

</body>
</html>