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
					Tambah Pengguna Akses
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
			    			@if($data['credentials']->divisi == 1)
			    				@switch($val->status_akses)
									@case(1) 
									@case(3)
									@case(5)
										@include('akses.decision')
										@break
									@case(2)
										<form method="POST" 
										action="{{route('post_pencetakan_akses')}}">
											{{ csrf_field() }}
											<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
											<input type="hidden" name="data_id" value="{{$val->id}}" >
											<div class="text-center">
												<button type="submit" class="btn btn-warning"> Cetak Kartu </button>
											</div>
										</form>
										@break
									@case(4)
										<form method="POST" 
										action="{{route('post_aktifkan_akses')}}">
											{{ csrf_field() }}
											<input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
											<input type="hidden" name="data_id" value="{{$val->id}}" >
											<div class="text-center">
												<button type="submit" class="btn btn-warning"> Aktifkan Kartu </button>
											</div>
										</form>
										@break
									@case(6)
										<div class="text-center"> 
										 	Kartu Aktif
										</div>
										@break
									@default
										<div class="text-center"> 
											{{$val->comment}}
										</div>
									@break
							@endswitch
		    				@elseif($data['credentials']->id_jabatan == 1)
		    					@switch($val->status_akses)
		    						@case(7)
		    						@case(8)
		    						@case(9)
		    							<div class="text-center"> 
										{{$val->comment}}
										</div>
										@break
									@default
										<div class="text-center">
		    							{{$val->status_name}}
		    							</div>
		    							@break;
		    					@endswitch
		    				@elseif($data['credentials']->id_jabatan == 2)
		    					@switch($val->status_akses)
		    						@case(7)
		    						@case(8)
		    						@case(9)
		    							<div class="text-center"> 
										{{$val->comment}}
										</div>
										@break
		    						@case(1)
		    							@include('akses.decision')
		    							@break
		    						@default
		    							<div class="text-center">
		    							{{$val->status_name}}
		    							</div>
		    							@break
		    					@endswitch
		    				@elseif($data['credentials']->id_jabatan == 3)
		    					@switch($val->status_akses)
		    						@case(7)
		    						@case(8)
		    						@case(9)
		    							<div class="text-center"> 
										{{$val->comment}}
										</div>
										@break
		    						@case(2)
		    							<form method="POST" 
		    							action="{{route('post_pencetakan_akses')}}">
											{{ csrf_field() }}
											<input type="hidden" name="updated_by" 
											value="{{$data['credentials']->id}}">
											<input type="hidden" name="data_id" 
											value="{{$val->id}}" >
											<div class="text-center">
												<button type="submit" class="btn btn-warning"> 
													Cetak Kartu 
												</button>
											</div>
										</form>
		    							@break
		    						@default
		    							<div class="text-center">
		    							{{$val->status_name}}
		    							</div>
		    							@break
		    					@endswitch
		    				@elseif($data['credentials']->id_jabatan == 4)
		    					@switch($val->status_akses)
		    						@case(7)
		    						@case(8)
		    						@case(9)
		    							<div class="text-center"> 
										{{$val->comment}}
										</div>
										@break
		    						@case(3)
		    							@include('akses.decision')
		    							@break
		    						@default
		    							<div class="text-center">
		    							{{$val->status_name}}
		    							</div>
		    							@break
		    					@endswitch
		    				@elseif($data['credentials']->id_jabatan == 5)
		    					@switch($val->status_akses)
		    						@case(7)
		    						@case(8)
		    						@case(9)
		    							<div class="text-center"> 
										{{$val->comment}}
										</div>
										@break
		    						@case(4)
		    							<form method="POST" 
		    							action="{{route('post_aktifkan_akses')}}">
											{{ csrf_field() }}
											<input type="hidden" name="updated_by" 
											value="{{$data['credentials']->id}}">
											<input type="hidden" name="data_id" 
											value="{{$val->id}}" >
											<div class="text-center">
												<button type="submit" class="btn btn-warning"> 
													Aktifkan Kartu 
												</button>
											</div>
										</form>
		    							@break
		    						@default
		    							<div class="text-center">
		    							{{$val->status_name}}
		    							</div>
		    							@break
		    					@endswitch
		    				@elseif($data['credentials']->id_jabatan == 6)
		    					@switch($val->status_akses)
		    						@case(7)
		    						@case(8)
		    						@case(9)
		    							<div class="text-center"> 
										{{$val->comment}}
										</div>
										@break
		    						@case(5)
		    							@include('akses.decision')
		    							@break
		    						@default
		    							<div class="text-center">
		    							{{$val->status_name}}
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
			{{ $data['akses']->links() }}
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
  
	@include('akses.modal');


@endsection

