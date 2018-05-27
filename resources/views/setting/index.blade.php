@extends('layouts.template')

@section('content')
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
<div class="col-sm-12" style="margin-top: 30px">


    <a href="{{route('show_background')}}">
        <button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-th-list">
          	Edit Background
          </span> 
        </button>
    </a>

<!-- 
    <a href="">
        <button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-th-list">
            Sharing Inventory Akses
          </span> 
        </button>
    </a> -->

</div>
@endsection