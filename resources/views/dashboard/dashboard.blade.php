@extends('layouts.template')

@section('content')
<style type="text/css">
    .menu_wrap_body {
        width: 100%;
    }

    .menu_block {
        float: left;
        width: 25%;
        /*background-color: blue;*/
    }

    .sub_menu_block {
        /*background-color: green;*/
        margin: 20px;
        height: 170px;
        border-radius: 10px;
        border: solid 0.8px #979797;
    }

    .icon_block {
        margin : 20px;
        text-align: center;
        /*background-color: tomato;*/
    }

    .menu_block a {
        text-decoration: none;
        color:gray;
    }

    .icon_block span {
        font-size: 80px;
    }
</style>

    <div class="flash-message center">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
          @if(Session::has('alert-' . $msg))

          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} 
            <a href="#" class="close" data-dismiss="alert" aria-label="close">
                &times;
            </a>
         </p>
          @endif
        @endforeach
    </div> <!-- end .flash-message -->

    <div class="menu_wrap_body">


        @if(in_array(1,$user_divisi)
            ||
            in_array(2,$user_divisi)
            ||
            in_array(3,$user_divisi)
            ||
            in_array(5,$user_divisi)
            )
        <div class="menu_block">
            <a href="{{route('accesscard')}}">
                <div class="sub_menu_block">
                    <div class="icon_block">
                        <img src="{{ asset('images/logo/id-card.png')}}" / >
                        <h4> Access Card </h4> 
                    </div>                
                </div>
            </a>
        </div>
        @endif

        @if(in_array(1,$user_divisi) || in_array(6,$user_divisi))
        <div class="menu_block">
            <a href="{{route('new_inventory.index')}}">
                <div class="sub_menu_block">
                    <div class="icon_block">
                        <img src="{{ asset('images/logo/checklist.png')}}" / >
                        <h4> Inventory Page </h4> 
                    </div>                
                </div>
            </a>
        </div>
        @endif



        @if(in_array(1,$user_divisi))
        <div class="menu_block">
            <a href="{{route('route_admin')}}">
                <div class="sub_menu_block">
                    <div class="icon_block">
                        <img src="{{ asset('images/logo/user.png')}}" / >
                        <h4> Admin Page </h4> 
                    </div>                
                </div>
            </a>
        </div>
        @endif
        @if(in_array(1,$user_divisi) || in_array(9,$user_setting))
        <div class="menu_block">
            <a href="{{route('helper.index')}}">
                <div class="sub_menu_block">
                    <div class="icon_block">
                        <img src="{{ asset('images/logo/settings.png')}}" / >
                        <h4> Config Page </h4> 
                    </div>                
                </div>
            </a>
        </div>
        @endif
        @if(in_array(1,$user_divisi) || in_array(1,$user_setting))
        <div class="menu_block">
            <a href="{{route('route_setting')}}">
                <div class="sub_menu_block">
                    <div class="icon_block">
                        <img src="{{ asset('images/logo/settings.png')}}" / >
                        <h4> Setting Page </h4> 
                    </div>                
                </div>
            </a>
        </div>
        @endif
        @if(in_array(1,$user_divisi)
            ||
            in_array(2,$user_divisi)
            ||
            in_array(5,$user_setting)
            )
        <div class="menu_block">
            <a href="{{route('access_report')}}">
                <div class="sub_menu_block">
                    <div class="icon_block">
                        <span class="glyphicon glyphicon-file">
                        </span> &nbsp;
                        <h4> Access Report Page </h4> 
                    </div>                
                </div>
            </a>
        </div>
        @endif

        @if(in_array(1,$user_divisi)
            ||
            in_array(4,$user_divisi)
            )
        <div class="menu_block">
            <a href="{{route('inventory_report')}}">
                <div class="sub_menu_block">
                    <div class="icon_block">
                        <span class="glyphicon glyphicon-file">
                        </span> &nbsp;
                        <h4> Inventory Report Page </h4> 
                    </div>                
                </div>
            </a>
        </div>
        @endif

        <div class="clearfix"> </div>   
    </div>

    

@endsection
