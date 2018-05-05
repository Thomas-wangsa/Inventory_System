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
      html, body {
        height: 100%!important;
        min-height: 100%;
        margin: 0px auto;
        max-width: 1400px;
        min-width: 700px!important;
        background-color: red;
      }

      .wrapper {
        width: 100%;
        height: 100%;
      }

      .menu-left {
        height: 100%;
        min-height: 100%;
        background-color: yellow;
        box-shadow: 3px 2px 4px 0 rgba(212, 212, 212, 0.5);
        padding: 30px 2%;
      }

      .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 70%;
      }

      .dashboard {
        font-family: Roboto;
        font-size: 16px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: left;
        color: #4a4a4a;
        color: var(--greyish-brown);
        margin-bottom: 20px
      }

      .icon-left {
        margin-bottom: 40px;
      }

      .icon-image {

      }

      .inner-image {
        vertical-align: baseline;
        height:20px;
      }

      .icon-text {
        font-family: Roboto;
        font-size: 18px;
        font-weight: normal;
        font-style: normal;
        font-stretch: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: left;
        color: #4a4a4a;
        color: var(--greyish-brown);

      }
      
    </style>
</head>
<body>
    <div class="wrapper">
      
      <div class="col-sm-3 menu-left" style="">
                
        <a href="{{route('home')}}">
          <img class="center" src="{{ asset('images/logo/google.png')}}"  / >
        </a>
        
        <div class="text-center" style="margin-bottom: 30px">  
          Inventory Management 
        </div>  
        
        <div class="col-sm-12" style="background-color: blue">
          <div class="row">
            
            <h4 class="dashboard">
              Dashboard
            </h4>

            <div class="col-sm-12">
              <div class="row">
                
                <div class="icon-left">
                  <div class="col-sm-2 icon-image">
                    <a href="{{route('home')}}">
                      <img class="inner-image" 
                      src="{{ asset('images/logo/home-anticon.png')}}" />
                    </a>
                  </div>
                  <div class="col-sm-10">
                    <div class="row">
                      <a class="icon-text" href="{{route('home')}}">
                        Beranda
                      </a>
                    </div>
                  </div>     
                </div>

                <div class="clearfix"> </div>
                
                <div class="icon-left">
                  <div class="col-sm-2 icon-image">
                    <a href="{{route('home')}}">
                      <img class="inner-image" 
                      src="{{ asset('images/logo/home-anticon.png')}}" />
                    </a>
                  </div>
                  <div class="col-sm-10">
                    <div class="row">
                      <a class="icon-text" href="{{route('home')}}">
                        Pemberitahuan
                      </a>
                    </div>
                  </div>     
                </div>

              </div>
            </div>

          </div>
        </div>

      </div> <!-- col left -->

      <div class="col-sm-9" style="background-color: green">
        bbb
      </div>
    </div> <!-- Wrapper -->
        
     

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
