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

  

  .wrapper_right { 
    min-height: 100%;
    height: auto;
    float:right;
    width: 80%;
  }

  .wrap_top {
    padding: 18px 4%;
    width: 100%;
    box-shadow: 0 3px 0 0 rgba(212, 212, 212, 0.5);
    height: 75px;
  }

  
</style>
<body>
    <div class="full_wrapper">
      @include('template.menu_left') 

      
      <div class="wrapper_right">
        <div class="wrap_top">
          <div class="pull-left">
            <div style="margin-top: 15px">
            @if(Route::current()->getName() == 'home')
              DASHBOARD
            @elseif(Route::current()->getName() == 'route_admin')
              ADMIN
            @elseif(Route::current()->getName() == 'route_setting')
              SETTING
            @elseif(Route::current()->getName() == 'akses')
              ACCESS
            @elseif(Route::current()->getName() == 'inventory')
              INVENTORY  
            @elseif(Route::current()->getName() == 'show_inventory')
              SHOW INVENTORY
            @elseif(Route::current()->getName() == 'show_background')
              EDIT BACKGROUND 
            @elseif(Route::current()->getName() == 'route_notify')
              NOTIFICATION
            @elseif(Route::current()->getName() == 'profile')
              PROFILE
            @elseif(Route::current()->getName() == 'password')
              EDIT PASSWORD
            @elseif(Route::current()->getName() == 'map')
              MAP LOCATION
            @elseif(Route::current()->getName() == 'route_setting')
              SETTING
            @elseif(Route::current()->getName() == 'route_report')
              REPORT
            @else 
              ERROR
            @endif
            PAGE 
            </div>
          </div>
          <div class="pull-right">
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" 
                data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                  <span class="glyphicon glyphicon-user" 
                  style="color:black">
                  </span>
                    &nbsp;
                    {{ ucfirst(Auth::user()->name) }} 
                    &nbsp;&nbsp;<span class="caret"></span>
                </a>

                <ul class="dropdown-menu">
                  <li>
                    <a href="{{ route('profile') }}">
                      <span class="glyphicon glyphicon-envelope"></span>
                      Profile Anda
                    </a>

                  </li>
                  <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <span class="glyphicon glyphicon-log-out"></span>
                        Keluar
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <div class="clearfix"> </div>
        </div> <!--wrap top-->
      </div>  <!--wrapper_right-->
      <div class="clearfix"> </div>
    </div> <!-- Wrapper -->
        
     

    
</body>
</html>
