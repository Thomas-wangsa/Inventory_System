@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
		<div>
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
			        <th> Email </th>
			        <th> No Handphone </th>
			        <th> Posisi </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    	@if (count($data) == 0 ) 
			    	<td colspan="6" class="text-center"> Kosong </td>
			    	@else 
			    		@foreach($data as $key=>$val)
			    		<tr> 
			    			<td> {{ $key+1 }} </td>
			    			<td> {{ $val->name }} </td>
			    			<td> {{ $val->email }} </td>
			    			<td> {{ $val->mobile }} </td>
			    			<td> {{ $val->jabatan }} </td>
			    			<td> 

			    			</td>
			    		</tr>
			    		@endforeach
			    	@endif
			    </tbody>
			</table>
		</div>
	</div>

	@include('admin.modal');

@endsection