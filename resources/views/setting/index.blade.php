@extends('layouts.template')

@section('content')
<div class="col-sm-12" style="margin-top: 30px">
    <a href="{{route('show_inventory')}}">
        <button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-th-list">
          	Tambah List Inventory
          </span> 
        </button>
    </a>

    <a href="">
        <button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-th-list">
          	Upload CSV
          </span> 
        </button>
    </a>

    <a href="">
        <button type="button" class="btn btn-default">
          <span class="glyphicon glyphicon-th-list">
          	Edit Background
          </span> 
        </button>
    </a>

</div>
@endsection