@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
		<div >
			<div class="pull-left"> 
				<form class="form-inline" action="{{route('route_admin')}}">
				    
				    <div class="input-group">
				    	<span class="input-group-addon">
				    		<i class="glyphicon glyphicon-search">
				    		</i>
				    	</span>
				    	<input type="text" class="form-control" 
				    	name="search_email" placeholder="Cari Nama...">
				  	</div>
					
					<div class="form-group">
				      	<select class="form-control" name="search_filter">
				      		<option value=""> Filter Berdasarkan </option>
				        	@foreach($data['inventory'] as $key=>$val)
				    		<option value="{{$val->id}}"> {{ucfirst($val->inventory_name)}}</option>
				    		@endforeach 
				      	</select>
				  	</div>

				  	<div class="form-group">
				      	<select class="form-control" name="search_order">
				      		<option value=""> Sort Berdasarkan </option>
				        	<option value="1"> Nama </option>
				        	<option value="2"> Email </option>
				        	<option value="3"> Handphone </option>
				      	</select>
				  	</div>
				  
				  	<button type="submit" class="btn btn-info"> Cari </button>
			  	</form>
			</div>
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
			        <th> Updated By </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    	@if(count($data['inventory_data']) == 0) 
					<td colspan="7" class="text-center"> Kosong </td>			    	
					@else
						@foreach($data['inventory_data'] as $key=>$val)
						<tr> 
							<td> {{$key+1}}</td>
							<td> {{$val->inventory_sub_list_name}}</td>
							<td> {{$val->inventory_name}}</td>
							<td> {{$val->count}}</td>
							<td> {{$val->username}}</td>
							<td> {{$val->status_name}}</td>
							<td>
								@switch($val->status_inventory)
									@case(1) 
			    						<div class="col-sm-6">
			    							<div class="row text-center">
				    						<form action="{{route('inventory_reject')}}">
				    							{{ csrf_field() }}
				    							<input type="hidden" name="uuid" value="{{$val->uuid}}" >
				    							<button type="submit" class="btn btn-danger">
				    								@if($val->status_inventory == 1)
				    									Tolak Nambah
				    								@endif
				    							</button>
				    						</form>
			    							</div>
			    						</div>

			    						<div class="col-sm-6">
			    							<div class="row text-center">
			    							<form action="{{route('inventory_approval')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="uuid" value="{{$val->uuid}}" >
			    							<button type="submit" class="btn btn-success"> 
			    								@if($val->status_akses == 1)
			    									Setuju Nambah
			    								@endif  
			    							</button>
			    							</form>
			    							</div>
			    						</div>

			    						<div class="clearfix"> </div>
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

	@include('inventory.modal');
@endsection