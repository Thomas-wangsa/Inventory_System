@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
		<div >
			<div class="pull-left"> asa </div>
			<div class="pull-right">  
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#myModal">
					Tambah Barang
				</button>
			</div>
			<div class="clearfix"> </div>

		</div>
		<div style="margin-top: 10px"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			        <th> Nama Barang </th>
			        <th> Kategori </th>
			        <th> Jumlah </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    	

			    </tbody>
			</table>
		</div>
	</div>

	@include('inventory.modal');
@endsection