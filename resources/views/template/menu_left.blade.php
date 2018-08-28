<style type="text/css">
    .wrapper_left {
        width: 20%;
        box-shadow: 3px 2px 4px 0 rgba(212, 212, 212, 0.5);
        min-height: 100%!important;
        height: auto;
        float: left;
    }
    .wrap_center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 60%;
    }
	.menu_icon_left {
		margin: 0 8%;
	}

	.sub_menu_icon_left {
		margin-top: 20px;
		padding-left: 15px;
	}

	.sub_menu_options_icon_left {
		font-size: 110%;
		margin-bottom: 17px;
		margin-left: 15px;
	}

	.sub_menu_options_icon_left a {
		text-decoration: none;
		color :black;
	}

    .left_menu_active {
        font-weight: bold;
        color:#337ab7!important;
    }
</style>

<div class="wrapper_left">
    
    <div class="wrap_center">
      <a href="{{route('home')}}">
        <img class="img-responsive" 
        style="max-height: 150px" 
        src="{{ asset('images/logo/indosat.png')}}"  / >
      </a>
      <div class="text-center"> 
        Access & Inventory <br/> Management
      </div>
    </div>


    <div class="menu_icon_left" 
    style="border-bottom : 2px solid gray;margin-top: 30px">
    	<strong> Menu : </strong>
    	<div class="sub_menu_icon_left">
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('home')}}"> 
	    			<span class="glyphicon glyphicon-home">
	    			</span> &nbsp;
	    			Dashboard
    			</a>
    		</div>
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('route_notify')}}"> 
	    			<span class="glyphicon glyphicon-bell">
	    			</span> &nbsp;
	    			Notification 
                    @if($count_user_notify > 0)
                    <span class="badge" style="background-color: blue">
                        {{$count_user_notify}}
                    </span>
                    @endif
    			</a>
    		</div>
            @if(in_array(1,$user_divisi)
                ||
                in_array(2,$user_divisi)
                ||
                in_array(3,$user_divisi)
                )
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('akses')}}"> 
	    			<span class="glyphicon glyphicon-list-alt">
	    			</span> &nbsp;
	    			Access
    			</a>
    		</div>
            @endif
            @if(in_array(1,$user_divisi))
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('inventory')}}"> 
	    			<span class="glyphicon glyphicon-th-large">
	    			</span> &nbsp;
	    			Inventory
    			</a>
    		</div>
            @endif
            @if(in_array(1,$user_divisi))
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('route_admin')}}"> 
	    			<span class="glyphicon glyphicon-user">
	    			</span> &nbsp;
	    			Admin
    			</a>
    		</div>
            @endif
            @if(in_array(1,$user_divisi))
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('route_setting')}}"> 
	    			<span class="glyphicon glyphicon-cog">
	    			</span> &nbsp;
	    			Setting
    			</a>
    		</div>
            @endif
            @if(in_array(1,$user_divisi)
                || 
                in_array(2,$user_divisi)
                )
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('route_report')}}"> 
	    			<span class="glyphicon glyphicon-file">
	    			</span> &nbsp;
	    			Report
    			</a>
    		</div>
            @endif
    	</div>
    </div> <!--menu_icon_left-->
    <div class="menu_icon_left" 
    style="margin-top: 15px">
    	<strong> Profile : </strong>
    	<div class="sub_menu_icon_left">
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('profile')}}"> 
	    			<span class="glyphicon glyphicon-envelope">
	    			</span> &nbsp;
	    			Profile
    			</a>
    		</div>
    		<div class="sub_menu_options_icon_left">
    			<a href="{{route('password')}}"> 
	    			<span class="glyphicon glyphicon-wrench">
	    			</span> &nbsp;
	    			Edit Password
    			</a>
    		</div>
    	</div>
    </div> <!--menu_icon_left-->
</div> <!--wrapper_left-->

