@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
		<div >
			<div class="pull-left"> asa </div>
			<div class="pull-right">  
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#myModal">
					Tambah Pengguna
				</button>
			</div>
			<div class="clearfix"> </div>

		</div>
		<div style="margin-top: 10px"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			        <th> Nama Lengkap </th>
			        <th> Divisi </th>
			        <th> Email </th>
			        <th> Keterangan </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    	@if (count($data) == 0 ) 
			    	<td colspan="7" class="text-center"> Kosong </td>
			    	@else 
			    		@foreach($data as $key=>$val)
			    		<tr> 
			    			<td> {{$key+1}} </td>
			    			<td> {{$val->name}} </td>
			    			<td> {{$val->divisi}} </td>
			    			<td> {{$val->email}} </td>
			    			<td> Updated By : {{ $val->username}} </td>
			    			<td style="color: {{$val->color}}"> {{$val->status_name}} </td>
			    				
			    			<td> 
			    				@switch($val->status_akses)
			    					@case(1)
			    						<form method="POST" action="{{route('post_pendaftaran_diterima')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$user->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Setuju Daftar  </button>
			    						</form>
			    						@break
			    					@case(2)
			    						<form method="POST" action="{{route('post_pencetakan_akses')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$user->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Cetak Kartu </button>
			    						</form>
			    						@break
			    					@case(3)
			    						<form method="POST" action="{{route('post_pencetakan_diterima')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$user->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Setuju Cetak </button>
			    						</form>
			    						@break
			    					@case(4)
			    						<form method="POST" action="{{route('post_aktifkan_akses')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$user->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Aktifkan Kartu </button>
			    						</form>
			    						@break
			    					@case(5)
			    						<form method="POST" action="{{route('post_aktifkan_diterima')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$user->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Setuju Aktifkan </button>
			    						</form>
			    						@break
			    					@case(6)
			    						<button class="btn btn-warning">  Kartu Aktif </button>
			    						@break
			    					@default
			    						{{$val->status_name}}
			    						@break
			    				@endswitch
			    			</td>
			    		</tr>
			    		@endforeach
			      	@endif 

			    </tbody>
			</table>
		</div>
	</div>
  
	@include('akses.modal');
	
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- 
<script type="text/javascript">
	$(document).ready(function(){
		$('#pendaftaran_diterima').click(function(){
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
    		$.ajax({
    			type : "POST",
    			url: "{{$url->to('/pendaftaran_diterima')}}",
    			contentType: "application/json",
    			data : "aaa"

    		});
		});
	});
</script> -->