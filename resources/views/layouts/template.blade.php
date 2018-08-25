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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"> </script>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/indosat.png')}}" />


  
</head>

<style type="text/css">
  html, body {
    height: 100%!important;
  }

  .full_wrapper {
    min-width: 1000px;
    max-width: 1700px;
    margin : 0 auto;
    height: 100%!important;
  }


  
</style>
<body>
    <div class="full_wrapper">
      @include('template.menu_left') 
      @include('template.menu_right')
      
      
      <div class="clearfix"> </div>
    </div> <!-- Wrapper -->
        
     

    
</body>
</html>
