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

    .icon_block a {
        text-decoration: none;
        color:gray;
    }

    .icon_block span {
        font-size: 80px;
    }
</style>
    <div class="menu_wrap_body">
        <div class="menu_block">
            <div class="sub_menu_block">
                <div class="icon_block">
                    <a href="{{route('akses')}}">
                        <img src="{{ asset('images/logo/id-card.png')}}" / >
                        <h4> Access Page </h4> 
                    </a>
                </div>                
            </div>
        </div>

        <div class="menu_block">
            <div class="sub_menu_block">
                <div class="icon_block">
                    <a href="{{route('inventory')}}">
                        <img src="{{ asset('images/logo/checklist.png')}}" / >
                        <h4> Inventory Page </h4> 
                    </a>
                </div>                
            </div>
        </div>

        <div class="menu_block">
            <div class="sub_menu_block">
                <div class="icon_block">
                    <a href="{{route('route_admin')}}">
                        <img src="{{ asset('images/logo/user.png')}}" / >
                        <h4> Admin Page </h4> 
                    </a>
                </div>                
            </div>
        </div>

        <div class="menu_block">
            <div class="sub_menu_block">
                <div class="icon_block">
                    <a href="{{route('route_setting')}}">
                        <img src="{{ asset('images/logo/settings.png')}}" / >
                        <h4> Setting Page </h4> 
                    </a>
                </div>                
            </div>
        </div>

        <div class="menu_block">
            <div class="sub_menu_block">
                <div class="icon_block">
                    <a href="{{route('route_report')}}">
                        <span class="glyphicon glyphicon-file">
                        </span> &nbsp;
                        <h4> Report Page </h4> 
                    </a>
                </div>                
            </div>
        </div>

        <div class="clearfix"> </div>
    </div>

    

@endsection
