<div class="wrap_top">
  <!-- left-->
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
    @elseif(Route::current()->getName() == 'accesscard')
      ACCESS CARD
    @elseif(Route::current()->getName() == 'accesscardrequest')
      ACCESS CARD REQUEST
    @elseif(Route::current()->getName() == 'inventory')
      OLD INVENTORY  
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
    @elseif(Route::current()->getName() == 'access_report')
      ACCESS REPORT
    @elseif(Route::current()->getName() == 'inventory_report')
      INVENTORY REPORT
    @elseif(Route::current()->getName() == 'helper.index')
      CONFIGURATION 
    @elseif(Route::current()->getName() == 'new_inventory.index')
      INVENTORY
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