@extends('layouts.template')

@section('content')
<div class="col-sm-12" style="margin-top: 30px">
	<form method="POST" action="{{ route('post_setting_add_inventory') }}">
	  {{ csrf_field() }}
	  <input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
	  <div class="form-group">
	    <label for="staff_nama"> Nama Inventory :</label>
	    <input type="text" class="form-control" id="nama" name="inventory" value="" required="">
	  </div>
	  
	  <button type="submit" class="btn btn-block btn-warning">Tambah Inventory </button>
	</form>
</div>
@endsection