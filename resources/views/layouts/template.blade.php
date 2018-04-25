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
          background-color: #ffffff;
          height:100%;
          margin:0; padding:0
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .Rectangle {
          height: 700px;
          box-shadow: 3px 2px 4px 0 rgba(212, 212, 212, 0.5);
        }

        .indosat-logo {
          width: 114.7px;
          object-fit: contain;
        }

        .Dashboard {
          width: 68px;
          height: 19px;
          font-family: Roboto;
          font-size: 14px;
          font-weight: bold;
          font-style: normal;
          font-stretch: normal;
          line-height: normal;
          letter-spacing: normal;
          text-align: left;
          color: #4a4a4a;
          color: var(--greyish-brown);
        }

        .home---anticon {
          width: 20px;
          height: 21px;
          object-fit: contain;
          font-family: anticon;
          font-size: 20px;
          font-weight: normal;
          font-style: normal;
          font-stretch: normal;
          line-height: normal;
          letter-spacing: normal;
          text-align: left;
          color: #4a4a4a;
          color: var(--greyish-brown);
        }

        .Beranda {
  width: 60px;
  height: 21px;
  font-family: Roboto;
  font-size: 16px;
  font-weight: normal;
  font-style: normal;
  font-stretch: normal;
  line-height: normal;
  letter-spacing: normal;
  text-align: left;
  color: #4a4a4a;
  color: var(--greyish-brown);
}   

.Rectangle-2 {
  height: 110px;
  box-shadow: 0 3px 4px 0 rgba(212, 212, 212, 0.5);
}

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-3 Rectangle" >
                <div style="margin-top: 30px"> </div>
                <a href="/">
                  <img class="center" src="{{ asset('images/logo/google.png')}}"  / >
                </a>
                <div class="text-center"> Inventory Management </div>
                <div style="margin-top: 20px"> </div>
                <div class="Dashboard" style="margin-bottom: 10px"> 
                    Dashboard 
                </div>

                <div style="margin-bottom: 10px"> 
                    <img class="home---anticon" src="{{ asset('images/logo/home-anticon.png')}}"  / > 
                    <span class="Beranda" style="margin-left: 10px"> Beranda </span>
                </div>

                <div style="margin-bottom: 10px"> 
                    <img class="home---anticon" src="{{ asset('images/logo/home-anticon.png')}}"  / > 
                    <span class="Beranda" style="margin-left: 10px"> Beranda </span>
                </div>

                <div style="margin-bottom: 10px"> 
                    <img class="home---anticon" src="{{ asset('images/logo/home-anticon.png')}}"  / > 
                    <span class="Beranda" style="margin-left: 10px"> Beranda </span>
                </div>

                <div style="margin-bottom: 10px"> 
                    <img class="home---anticon" src="{{ asset('images/logo/home-anticon.png')}}"  / > 
                    <span class="Beranda" style="margin-left: 10px"> Beranda </span>
                </div>
            </div>

            <div class="col-md-9" style="">
                <div class="row">
                    <div class="Rectangle-2"> 
                        <div style="padding: 20px 30px;">
                           <!--  <h3 style="padding-top: 20px"> 
                              <span class="pull-left"> 
                                Beranda 
                              </span>
                            </h3>  -->
                            <ul class="nav navbar-nav">
                              <h3> Beranda </h3>
                            </ul>
                            
                            <ul class="nav navbar-nav navbar-right" style="padding-top: -20px">
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                              </li>
                            </ul>
                            
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>


        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
