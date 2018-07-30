@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">


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
			@if($data['credentials']->divisi == 4 || in_array('2',$data['setting']))
			<form class="form-inline" style="margin-bottom: 10px">
				{{ csrf_field() }}
			  	<div class="form-group ">
				    <input type="file" class="form-control" name="background" required>
				</div>
			  	<button type="submit" class="btn btn-primary">
			  		Export csv
			  	</button>
			</form>
			@endif

			@if($data['credentials']->divisi == 4 || in_array('3',$data['setting']))
			<button type="button" class="btn btn-warning btn-md" data-toggle="modal" data-target="#myModal1">
			Add New Inventory
			</button>
			@endif

			@if($data['credentials']->divisi == 4 || in_array('4',$data['setting']))
		  	<button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#myModal2">
			Sharing Access
			</button>
			@endif
			
			

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
				        	@foreach($data['status_inventory'] as $key=>$val)
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
			<div class="pull-right
			<?php
				if($data['credentials']->id_jabatan != 1) {
					echo "hide";
				}
			?>
			">  
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
			    	@if(count($data['data']) < 1)
	                    <tr>
	                        <td colspan="10" class="text-center"> No Data Found </td>
	                    </tr>
	                @else 
	                    <?php $no = 1; ?>
	                    @foreach($data['data'] as $key=>$val)
	                    <tr>
	                        <td> {{$no}}</td>
	                        <td> {{$val->inventory_sub_list_name}}</td>
	                        <td> {{$val->inventory_name}}</td>
	                        <td> {{$val->count}}</td>
	                        <td> Updated By {{$val->username}}</td>
	                        <td style="color:{{$val->inventory_color}}"> {{$val->status_name}}</td>
	                        <td>
                        	@if(
                        		($data['credentials']->id_jabatan == 2 
                        		&& $val->status_inventory == 1) 
                        	||  
                        		($data['credentials']->divisi == 4 
                        		&& ($val->status_inventory == 1 
                        			||
                        			$val->status_inventory == 2
                        			)
                        		)
                        	)
                        		<span class="glyphicon glyphicon-check"
                        		onclick="approve('{{$val->uuid}}')"
                        		style="color:green">	
                        		</span>
                        		<span class="glyphicon glyphicon-edit" style="color:black">
                        		</span>
                        		<span class="glyphicon glyphicon-remove"
                        		onclick="remove('{{$val->uuid}}')" 
                        		style="color:red">
                        		</span>
                        	@endif

	                        </td>

	                    </tr>
	                    <?php $no++;?>
	                    @endforeach
	                @endif

			    </tbody>
			</table>
			<div class="pull-right" style="margin-top: -30px!important"> 
			{{ $data['data']->links() }}
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
<script type="text/javascript">
	function approve(uuid) {
		var url = window.location.protocol+"//"+window.location.host+'/inventory_approval?uuid=';
		window.location = url+uuid;
	}


	function remove(uuid) {
		var url = window.location.protocol+"//"+window.location.host+'/inventory_reject?uuid=';
		window.location = url+uuid;
	}
</script>
	@include('inventory.modal');
	@include('inventory.modal_add');
	@include('inventory.modal_sharing');
@endsection