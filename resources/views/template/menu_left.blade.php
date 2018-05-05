<div class="col-sm-3 menu-left" style="">
                
  <a href="{{route('home')}}">
    <img class="center" src="{{ asset('images/logo/google.png')}}"  / >
  </a>
  
  <div class="text-center" style="margin-bottom: 30px">  
    Inventory Management 
  </div>  
  
  <div class="col-sm-12">
    <div class="row">
      
      <h4 class="dashboard">
        Dashboard
      </h4>

      <div class="col-sm-12">
        <div class="row">
          
          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('home')}}">
                <span class="glyphicon glyphicon-home" style="color:black">
                </span>
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
                <span class="glyphicon glyphicon-bell" style="color:black">
                </span>
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
          <div class="clearfix"> </div>

          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('akses')}}">
                <span class="glyphicon glyphicon-list-alt" style="color:black">
                </span>
              </a>
            </div>
            <div class="col-sm-10">
              <div class="row">
                <a class="icon-text" href="{{route('akses')}}">
                  Pendaftaran Akses
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>

          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('inventory')}}">
                <span class="glyphicon glyphicon-th-large " style="color:black">
                </span>
              </a>
            </div>
            <div class="col-sm-10">
              <div class="row">
                <a class="icon-text" href="{{route('inventory')}}">
                  Inventory
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>

          @if($data['credentials']->divisi == 1)
          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('route_admin')}}">
                <span class="glyphicon glyphicon-user " style="color:black">
                </span>
              </a>
            </div>
            <div class="col-sm-10">
              <div class="row">
                <a class="icon-text" href="{{route('route_admin')}}">
                  Kelola Akun
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>
          @endif

          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('route_setting')}}">
                <span class="glyphicon glyphicon-cog " style="color:black">
                </span>
              </a>
            </div>
            <div class="col-sm-10">
              <div class="row">
                <a class="icon-text" href="{{route('route_setting')}}">
                  Setting
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>


        </div>
      </div>

    </div>
  </div>

</div> <!-- col left -->