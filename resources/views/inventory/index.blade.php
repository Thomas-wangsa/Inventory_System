@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
		@if ($errors->any())
	    <div class="alert alert-danger">
	    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
		@endif


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
		<div >
				
			<form class="form-inline">
				{{ csrf_field() }}
			  	<div class="form-group">
				    <input type="file" class="form-control" name="background" required>
				</div>
			  	<button type="submit" class="btn btn-primary">
			  		Upload csv
			  	</button>
			  	<button type="button" class="btn btn-success btn-md">
				Tambah Inventory List
				</button>
			</form>
				


			



			<div class="clearfix" style="margin-bottom: 10px"> </div>
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
							<td style="color: {{$val->color}}"> {{$val->status_name}}</td>
							<td>
								@if($data['credentials']->divisi == 1)
									@switch($val->status_inventory)
										@case(1) 
										@case(2)
				    						@include('inventory.decision')
				    						@break 
				    					@case(3) 
				    						<div class="text-center">
				    							Inventory Aktif 
				    						</div>
				    						@break
				    					@case(4) 
				    					@case(5) 
				    						<div class="text-center">
				    							{{$val->comment}} 
				    						</div>
				    						@break
		    						@endswitch
		    					@elseif($data['credentials']->id_jabatan == 1)
		    						@switch($val->status_inventory)
				    					@case(3) 
				    						<div class="text-center">
				    							Inventory Aktif 
				    						</div>
				    						@break
				    					@case(4) 
				    					@case(5) 
				    						<div class="text-center">
				    							{{$val->comment}} 
				    						</div>
				    						@break
		    						@endswitch
		    					@elseif($data['credentials']->id_jabatan == 2)
		    						@switch($val->status_inventory)
		    							@case(1)
		    								@include('inventory.decision')
		    								@break
				    					@case(3) 
				    						<div class="text-center">
				    							Inventory Aktif 
				    						</div>
				    						@break
				    					@case(4) 
				    					@case(5) 
				    						<div class="text-center">
				    							{{$val->comment}} 
				    						</div>
				    						@break
		    						@endswitch
	    						@endif
							</td>
						</tr>
						@endforeach 
					

			    	@endif

			    </tbody>
			</table>
			<div class="pull-right" style="margin-top: -30px!important"> 
			{{ $data['inventory_data']->links() }}
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>

	@include('inventory.modal');
@endsection