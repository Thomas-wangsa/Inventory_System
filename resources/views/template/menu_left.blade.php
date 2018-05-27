<div class="col-sm-3 menu-left" style="">
                
  <a href="{{route('home')}}">
    <img class="center" src="{{ asset('images/logo/indosat.png')}}"  / >
  </a>
  
  <div class="text-center" style="margin-bottom: 30px">  
    Inventory Management 
  </div>  
  
  <div class="col-sm-12" >
    <div class="row">
      
      <h4 class="dashboard">
        Dashboard
      </h4>

      <div class="col-sm-12" style="border-bottom : 2px solid gray">
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
              <a href="{{route('route_notify')}}">
                <span class="glyphicon glyphicon-bell" style="color:black">
                </span>
              </a>
            </div>
            <div class="col-sm-10">
              <div class="row">
                <a class="icon-text" href="{{route('route_notify')}}">
                  Pemberitahuan
                    @if(isset($data['notify']) && $data['notify'] > 0 )
                      <span class="badge" style="background-color: red;color:white"> 
                        {{$data['notify']}}
                    </span>
                  </a>
                    @endif
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>

          
          @if($data['credentials']->divisi != 4)
          <div class="icon-left
          @if(Route::current()->getName() == 'inventory')
            hide
          @endif
          ">
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
          @endif


          @if(Route::current()->getName() == 'akses')
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
                    Pencetakan Akses
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
                    Pengaktifan Akses
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
                    Aktifkan Akses
                  </a>
                </div>
              </div>     
            </div>
            <div class="clearfix"> </div>
          @endif



          @if($data['credentials']->divisi != 4)
          <div class="icon-left
          @if(Route::current()->getName() == 'akses')
            hide
          @endif

          ">
            <div class="col-sm-2 icon-image">
              <a href="{{route('inventory')}}">
                <span class="glyphicon glyphicon-th-large "
                @if(Route::current()->getName() == 'inventory')
                  style="color:blue"
                @else
                  style="color:black"
                @endif
                >
                </span>
              </a>
            </div>
            <div class="col-sm-10">
              <div class="row">
                <a class="icon-text" href="{{route('inventory')}}"
                @if(Route::current()->getName() == 'inventory')
                  style="color:blue"
                @endif


                >
                  Inventory
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>
          @endif

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

          @if($data['credentials']->divisi != 4)
          <div class="icon-left
          @if(Route::current()->getName() == 'akses' || Route::current()->getName() == 'inventory')
            hide
          @endif
          ">
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
          <div class="clearfix" style="margin-bottom: 20px"> </div>
          @endif
        </div>
        
      </div>

    </div>
  </div>

  <div class="clearfix"> </div>
  
  <div class="col-sm-12" style="margin-top: 20px">
    <div class="row">
      <h4 class="dashboard"> Profile </h4>

        <div class="col-sm-12">
          <div class="row">    
            <div class="icon-left">
              <div class="col-sm-2 icon-image">
                <a href="{{route('profile')}}">
                  <span class="glyphicon glyphicon-envelope" style="color:black">
                  </span>
                </a>
              </div>
              <div class="col-sm-10">
                <div class="row">
                  <a class="icon-text" href="{{route('profile')}}">
                    Profile
                  </a>
                </div>
              </div>     
            </div>
            <div class="clearfix"> </div>
            <div class="icon-left">
              <div class="col-sm-2 icon-image">
                <a href="{{route('password')}}">
                  <span class="glyphicon glyphicon-wrench" style="color:black">
                  </span>
                </a>
              </div>
              <div class="col-sm-10">
                <div class="row">
                  <a class="icon-text" href="{{route('password')}}">
                    Ganti Password
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

