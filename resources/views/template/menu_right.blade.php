<style type="text/css">
	.menu-top-right {
		width: 100%;
		padding: 40px 4%;
		box-shadow: 0 3px 4px 0 rgba(212, 212, 212, 0.5);
	}

	.title-at-top-right {
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
      	padding-top: 12px;
	}
</style>

<div class="col-sm-9 menu-right">
	<div class="row">
		<div class="menu-top-right">
			<div class="title-at-top-right pull-left"> 
			@if(Route::current()->getName() == 'home')
    			BERANDA
    		@elseif(Route::current()->getName() == 'route_admin')
    			KELOLA AKUN
        @elseif(Route::current()->getName() == 'route_setting')
          PENGATURAN
        @elseif(Route::current()->getName() == 'show_inventory')
          TAMBAH INVENTORY LIST
    		@else 
    			ERROR
			@endif 
			</div>

			<div class="pull-right">
				<ul class="nav navbar-nav navbar-right">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                    	<span class="glyphicon glyphicon-user" style="color:black"></span>
                    	&nbsp;
                        {{ ucfirst(Auth::user()->name) }} 
                        &nbsp;&nbsp;<span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                    	<li>
                    		<a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <span class="glyphicon glyphicon-envelope"></span>
                                Profile Anda
                            </a>

                            <form id="profile-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
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
		</div> <!-- menu-top-right -->
		@yield('content')

		
	</div>
</div>