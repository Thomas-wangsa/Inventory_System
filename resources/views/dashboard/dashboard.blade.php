@extends('layouts.template')

@section('content')
<style type="text/css">
    .Rectangle-5 {
  width: 207px;
  height: 196px;
  border-radius: 10px;
  background-color: #ffffff;
  border: solid 0.8px #979797;
}

.id-card {
  width: 56px;
  height: 84px;
  object-fit: contain;
}

</style>
    
    <div style="padding: 25px 30px">
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
        <h2> Hi {{ ucfirst($data['credentials']->name) }} </h2>
        <h4> Apa yang anda lakukan hari ini </h4>

        <div> 
            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <a href="{{route('akses')}}">
                            <img  class="center" src="{{ asset('images/logo/id-card.png')}}"  
                            style="width:30%;margin-top: 35px" / >
                        </a>
                        <div style="margin-top: 10px"> </div>
                        <div class="text-center"> Akses </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <a href="{{route('inventory')}}">
                            <img  class="center" src="{{ asset('images/logo/checklist.png')}}"  style="width:30%;margin-top: 35px" / >
                        </a>
                        <div style="margin-top: 10px"> </div>
                        <div class="text-center"> Inventory </div>
                    </div>
                </div>
            </div>

            @if($data['credentials']->divisi == 1)
            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <a href="{{route('route_admin')}}">
                            <img  class="center" src="{{ asset('images/logo/user.png')}}"  style="width:30%;margin-top: 45px" / >
                        </a>
                        <div style="margin-top: 20px"> </div>
                        <div class="text-center"> Kelola Akun </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <a href="{{route('route_setting')}}">
                            <img  class="center" src="{{ asset('images/logo/settings.png')}}"  style="width:30%;margin-top: 45px" / >
                        </a>
                        <div style="margin-top: 20px"> </div>
                        <div class="text-center"> Report </div>
                    </div>
                </div>
            </div>

        </div> 
    </div>
@endsection
