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

		<div>
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
				        	@foreach($data['divisi'] as $key=>$val)
				    		<option value="{{$val->id}}"> {{ucfirst($val->name)}}</option>
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
			    	@if (count($data['akses']) == 0 ) 
			    	<td colspan="7" class="text-center"> Kosong </td>
			    	@else 
			    		@foreach($data['akses'] as $key=>$val)
			    		<tr> 
			    			<td>  
			    				{{ ($data['akses']->currentpage()-1) 
			    				* $data['akses']->perpage() + $key + 1 }}
			    			</td>
			    			<td> {{$val->name}} </td>
			    			<td> {{$val->divisi}} </td>
			    			<td> {{$val->email}} </td>
			    			<td> Updated By : {{ $val->username}} </td>
			    			<td style="color: {{$val->color}}"> {{$val->status_name}} </td>
			    				
			    			<td style="width: 250px"> 
			    				@switch($val->status_akses)
			    					@case(1)
			    						<div class="col-sm-6">
			    							<div class="row text-center">
				    						<form method="POST" action="{{route('post_pendaftaran_diterima')}}">
				    							{{ csrf_field() }}
				    							<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
				    							<input type="hidden" name="data_id" value="{{$val->id}}" >
				    							<button type="submit" class="btn btn-danger"> Tolak Daftar  </button>
				    						</form>
			    							</div>
			    						</div>

			    						<div class="col-sm-6">
			    							<div class="row text-center">
			    							<form method="POST" action="{{route('post_pendaftaran_diterima')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-success"> Setuju Daftar  </button>
			    							</form>
			    							</div>
			    						</div>

			    						<div class="clearfix"> </div>
			    						@break
			    					@case(2)
			    						<form method="POST" action="{{route('post_pencetakan_akses')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Cetak Kartu </button>
			    						</form>
			    						@break
			    					@case(3)
			    						<form method="POST" action="{{route('post_pencetakan_diterima')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Setuju Cetak </button>
			    						</form>
			    						@break
			    					@case(4)
			    						<form method="POST" action="{{route('post_aktifkan_akses')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Aktifkan Kartu </button>
			    						</form>
			    						@break
			    					@case(5)
			    						<form method="POST" action="{{route('post_aktifkan_diterima')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
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
			<div class="pull-right" style="margin-top: -30px!important"> 
			{{ $data['akses']->links() }}
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
  
	@include('akses.modal');
	
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
