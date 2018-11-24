@extends('layouts.template')

@section('content')

<style type="text/css">
	th,td {text-align: center}
	.table>tbody>tr>td,.table>thead>tr>th {vertical-align: middle}
	.scrollme {overflow-y: auto;}
</style>
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

  	@if ($errors->any())
		<div class="alert alert-danger">
		    <ul>
		        @foreach ($errors->all() as $error)
		            <li>{{ $error }}</li>
		        @endforeach
		    </ul>
		</div>
	@endif

	<div style="margin-top: 15px">
		<div>
			
			<div class="pull-left">  
				<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#myModal">
					add new user
				</button>
			</div>
			<div class="clearfix"> </div>

			<div style="margin: 10px"> </div>

			<div class="pull-left">
				<form class="form-inline" action="{{route('route_admin')}}">
	
				    <input type="hidden" name="search" value="on"> </input>
				    <div class="form-group">
					    <div class="input-group">
					    	<span class="input-group-addon">
					    		<i class="glyphicon glyphicon-search">
					    		</i>
					    	</span>
					    	<input type="text" class="form-control" 
					    	name="search_nama" placeholder="Find Name..."
					    	value="{{Request::get('search_nama')}}">
					  	</div>
				  	</div>
					
					<div class="form-group">
				      	<select class="form-control" name="search_filter">
				      		<option value=""> Filter By </option>
				        	@foreach($data['divisi'] as $key=>$val)
				    		<option value="{{$val->id}}" 
				    			@if($val->id == Request::get('search_filter')) 
				    				selected
				    			@endif
				    			> 
				    			{{ucfirst($val->name)}}
				    		</option>
				    		@endforeach
				    		<option value="is_deleted"
				    			@if('is_deleted' == Request::get('search_filter')) 
				    				selected
				    			@endif
				    			> 
				    			Deleted Users 
				    		</option> 
				      	</select>
				  	</div>

				  	<div class="form-group">
				      	<select class="form-control" name="search_order">
				      		<option value=""> Sort By </option>
				        	<option value="name"
				        		@if('name' == Request::get('search_order')) 
				    				selected
				    			@endif
				    			> 
				        		Name 
				        	</option>
				        	<option value="email"
				        		@if('email' == Request::get('search_order')) 
				    				selected
				    			@endif
				        		> 
				        		Email 
				        	</option>
				        	<option value="mobile"
				        		@if('mobile' == Request::get('search_order')) 
				    				selected
				    			@endif
				        		> 
				        		Mobile 
				        	</option>
				      	</select>
				  	</div>
				  
				  	<button type="submit" class="btn btn-info"> 
				  		Filter
				  	</button>
				  	<button type="reset" 
				  	class="btn"
				  	onclick="reset_filter()"> 
				  		Clear Filter 
				  	</button>
			  	</form>
			</div>
			<div class="clearfix"> </div>
			
		</div>

		<div style="margin-top: 10px"  class="scrollme"> 
			<table class="table table-bordered table-responsive">
			    <thead>
			      <tr>
			      	<th> No </th>
			        <th> Name </th>
			        <th> NIK </th>
			        <th> Email </th>
			        <th> Mobile </th>
			        <th> 
			        	Personal <br/>
			        	Identity 
			        </th>
			        <th style="min-width: 120px"> 
			        	Position
			        </th>
			        <th> 
			        	Company
			        </th>
			        <th style="min-width: 120px"> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    	@if (count($data['users']) == 0 ) 
			    	<td colspan="10" class="text-center"> 
			    		No User Found! 
			    	</td>
			    	@else 
			    		@foreach($data['users'] as $key=>$val)
			    		<tr> 
			    			<td> 
			    				{{ ($data['users']->currentpage()-1) 
			    				* $data['users']->perpage() + $key + 1 }}
			    			</td>
			    			<td> {{ ucfirst($val->name) }} </td>
			    			<td> {{ ucfirst($val->nik) }} </td>
			    			<td> {{ $val->email }} </td>
			    			<td> {{ $val->mobile }} </td>
			    			<td>
			    				<img src="{{URL::to('/')}}{{$val->foto}}"/ width="80px" 
			    				onclick="show_foto('<?php 
			    					echo URL::to('/').$val->foto;
			    				?>')"> 
			    			</td>
			    			<td style="padding-top: 10px"> 
			    				<?php 
			    				foreach($data['level_authorization'][$key] as $key_level=>$val_level) :
			    				?>	
			    					@if($val_level->divisi == 1) 
			    						administrator
			    					@else 
			    						{{$val_level->nama_jabatan}}
			    					@endif
			    					<br/><br/>
			    				<?php 
			    				endforeach;
			    				?>
			    			</td>
			    			<td> 
			    				{{$val->company}}
			    			</td>
			    			<td>
			    				<div class="text-center" > 
			    				@if(Request::get('search_filter') == 'is_deleted')
			    					<button class="btn btn-primary"
			    					onclick="aktifkan_user('{{$val->id}}')">
			    						set active
			    					</button>

			    				@else
			    				
			    					<span class="glyphicon glyphicon-pencil"
			    					style="color:black;cursor:pointer" 
			    					title="Edit User"
			    					onclick='get_data_user("{{$val->uuid}}")'>
			    						
			    					</span> &nbsp;

			    					<span onclick='delete_akun("{{ $val->uuid }}")' >
			    						<span class="glyphicon glyphicon-trash"
			    						style="color:red;cursor:pointer" 
			    						title="Delete User">
			    						</span>
			    					</span> &nbsp;
			    					
			    					<span class="glyphicon glyphicon-plus" 
			    					style="color: green;cursor:pointer" 
			    					title="Edit Position User" 
			    					onclick='get_role_user("{{$val->uuid}}","{{$val->name}}")'>
			    						
			    					</span> &nbsp;

			    					<span class="glyphicon glyphicon-cog" 
			    					style="color: blue;cursor:pointer" 
			    					title="Edit Feature User"
			    					onclick='get_features_user("{{$val->uuid}}","{{$val->name}}")'>
			    						
			    					</span> &nbsp;
			    				@endif
			    				</div>
			    			</td>
			    		</tr>
			    		@endforeach
			    	@endif
			    </tbody>
			</table>
			
			
		</div> <!--scrollme-->
		<div class="clearfix"> </div>
		<div class="pull-right" style="margin-top: -15px!important"> 
			{{ $data['users']->appends(
				[
				'search' => Request::get('search'),
				'search_nama' => Request::get('search_nama'),
				'search_filter' => Request::get('search_filter'),
				'search_order' => Request::get('search_order')
				])

			->links() }}
		</div>
		<div class="clearfix"> </div>
	</div>

	@include('admin.modal')
	@include('admin.modal_image')
	@include('admin.modal_edit')
	@include('admin.modal_role')
	@include('admin.modal_special')

<script type="text/javascript">

	function show_foto(location) {
		$('#dinamic_image').attr("src",location);
		$('#modal_image').modal('show');
	}	

    function delete_akun(uuid) {
    	$(document).ready(function(){
	        if (confirm('Apakah anda yakin ingin menghapus Akun ini ?')) {

	        	var data = {
	        		"uuid":uuid
	        	};

			    $.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
	    		$.ajax({
	    			type : "POST",
	    			url: " {{ route('admin_delete_user') }}",
	    			contentType: "application/json",
	    			data : JSON.stringify(data),
	    			success: function(result) {
	    				response = JSON.parse(result);
	    				if(response.status == true) {
	    					window.location = "{{route('delete_user_notif')}}";
	    				}
	    			},
	    			error: function( jqXhr, textStatus, errorThrown ){
       					console.log( errorThrown );
    				}
	    		});
			} 
		});
    };


    function aktifkan_user(id) {
    	$(document).ready(function(){
	        if (confirm('Apakah anda yakin ingin restore Akun ini ?')) {

	        	var data = {
	        		"id":id
	        	};

			    $.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
	    		$.ajax({
	    			type : "POST",
	    			url: " {{ route('admin_aktifkan_user') }}",
	    			contentType: "application/json",
	    			data : JSON.stringify(data),
	    			success: function(result) {
	    				response = JSON.parse(result);
	    				if(response.status == true) {
	    					window.location = "{{route('aktif_user_notif')}}";
	    				}
	    			},
	    			error: function( jqXhr, textStatus, errorThrown ){
       					console.log( errorThrown );
    				}
	    		});
			} 
		});
    };


    function reset_filter() {
    	window.location = "{{route('route_admin')}}";
    }
    

    

	
</script>
@endsection