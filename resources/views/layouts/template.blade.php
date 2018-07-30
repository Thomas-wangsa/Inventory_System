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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


    <style type="text/css">
      html, body {
        height: 100%!important;
        min-height: 100%;
        margin: 0px auto;
        max-width: 1600px;
        min-width: 700px!important;
      }

      .wrapper {
        width: 100%;
        height: 100%;
      }

      .menu-left {
        height: 100%;
        min-height: 100%;
        box-shadow: 3px 2px 4px 0 rgba(212, 212, 212, 0.5);
        padding: 30px 2%;
      }

      .menu-right {
        height: 100%;
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
        text-decoration : none!important;

      }
      
    </style>
</head>
<body>
    <div class="wrapper">
      
      @include('template.menu_left')
      @include('template.menu_right')

      
    </div> <!-- Wrapper -->
        
     

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
