<div class="col-sm-3 menu-left" style="">
                
  <a href="{{route('home')}}">
    <img class="center" src="{{ asset('images/logo/indosat.png')}}"  / >
<!--     <img class="center" src="{{ asset('images/logo/google.png')}}"  / > -->
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
                <span class="glyphicon glyphicon-home"
                @if(Route::current()->getName() == 'home')
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
                <a class="icon-text" href="{{route('home')}}"
                @if(Route::current()->getName() == 'home')
                  style="color:blue"
                @else
                  style="color:black"
                @endif
                >
                  Beranda
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>
          
          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('route_notify')}}">
                <span class="glyphicon glyphicon-bell"
                @if(Route::current()->getName() == 'route_notify')
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
                <a class="icon-text" href="{{route('route_notify')}}"
                @if(Route::current()->getName() == 'route_notify')
                  style="color:blue"
                @else
                  style="color:black"
                @endif
                >
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

          
          @if($data['credentials']->divisi == 2 || $data['credentials']->divisi == 4)
          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('akses')}}">
                <span class="glyphicon glyphicon-list-alt"
                @if(Route::current()->getName() == 'akses')
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
                <a class="icon-text" href="{{route('akses')}}"
                @if(Route::current()->getName() == 'akses')
                  style="color:blue"
                @else
                  style="color:black"
                @endif
                >
                 Akses
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>
          @endif


          @if($data['credentials']->divisi == 3 || $data['credentials']->divisi == 4)
          <div class="icon-left">
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

          @if($data['credentials']->divisi == 4)
          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('route_admin')}}">
                <span class="glyphicon glyphicon-user"
                @if(Route::current()->getName() == 'route_admin')
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
                <a class="icon-text" href="{{route('route_admin')}}"
                @if(Route::current()->getName() == 'route_admin')
                  style="color:blue"
                @else
                  style="color:black"
                @endif
                >
                  Kelola Akun
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>
          @endif



          @if($data['credentials']->divisi == 4 || in_array('1',$data['setting']))
          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('route_setting')}}">
                <span class="glyphicon glyphicon-cog"
                @if(Route::current()->getName() == 'show_background')
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
                <a class="icon-text" href="{{route('route_setting')}}"
                @if(Route::current()->getName() == 'show_background')
                  style="color:blue"
                @else
                  style="color:black"
                @endif
                >
                  Setting
                </a>
              </div>
            </div>     
          </div>
          <div class="clearfix"> </div>
          @endif


          @if($data['credentials']->divisi == 4 || in_array('1',$data['setting']))
          <div class="icon-left">
            <div class="col-sm-2 icon-image">
              <a href="{{route('route_report')}}">
                <span class="glyphicon glyphicon-file"
                @if(Route::current()->getName() == 'route_report')
                  style="color:blue"
                @else
                  style="color:black"
                @endif>
                </span>
              </a>
            </div>
            <div class="col-sm-10">
              <div class="row">
                <a class="icon-text" href="{{route('route_report')}}"
                @if(Route::current()->getName() == 'route_report')
                  style="color:blue"
                @else
                  style="color:black"
                @endif
                >
                  Report
                </a>
              </div>
            </div>     
          </div>
          @endif

          <div class="clearfix" style="margin-bottom: 20px"> </div>

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
                  <span class="glyphicon glyphicon-envelope"
                  @if(Route::current()->getName() == 'profile')
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
                  <a class="icon-text" href="{{route('profile')}}"
                  @if(Route::current()->getName() == 'profile')
                    style="color:blue"
                  @else
                    style="color:black"
                  @endif
                  >
                    Profile
                  </a>
                </div>
              </div>     
            </div>
            <div class="clearfix"> </div>
            <div class="icon-left">
              <div class="col-sm-2 icon-image">
                <a href="{{route('password')}}">
                  <span class="glyphicon glyphicon-wrench"
                  @if(Route::current()->getName() == 'password')
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
                  <a class="icon-text" href="{{route('password')}}"
                  @if(Route::current()->getName() == 'password')
                    style="color:blue"
                  @else
                    style="color:black"
                  @endif
                  >
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

