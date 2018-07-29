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
			  	<div class="form-group hide">
				    <input type="file" class="form-control" name="background" required>
				</div>
			  	<button type="submit" class="btn btn-primary hide">
			  		Export csv
			  	</button>
			  	<button type="button" class="btn btn-danger btn-md">
				Sharing Access
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
			<div class="pull-right hide">  
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
			    	<tr>
			    		<td> 1 </td>
			    		<td> CCTV Samkok </td>
			    		<td> cctv </td>
			    		<td> 9 </td>
			    		<td> Staff Inventory Agus </td>
			    		<td style="color:blue"> Pending Head </td>
			    		<td> 
			    			<span class="glyphicon glyphicon-check" style="color:green" ></span>
			    			<span class="glyphicon glyphicon-remove" style="color:red" ></span>
			    		</td> 
			    	</tr>

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