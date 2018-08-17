<style type="text/css">
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

  .body_menu {
    margin: 30px;
  }

</style>

<div class="wrapper_right">
    @include('template.menu_right_top')

    <div class="body_menu">
      @yield('content')
    </div>

</div>  <!--wrapper_right-->