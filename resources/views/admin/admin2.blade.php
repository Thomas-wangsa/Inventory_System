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
				        		Nama 
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
				        		Handphone 
				        	</option>
				      	</select>
				  	</div>
				  
				  	<button type="submit" class="btn btn-info"> 
				  		Find 
				  	</button>
				  	<button type="reset" 
				  	class="btn btn-danger"
				  	onclick="reset_filter()"> 
				  		Reset 
				  	</button>
			  	</form>
			</div>
			<div class="pull-right">  
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#myModal">
					Add new user
				</button>
			</div>
			<div class="clearfix"> </div>
		</div>

		<div style="margin-top: 10px"  class=""> 
			<table class="table table-bordered table-responsive">
			    <thead>
			      <tr>
			        <th> Name </th>
			        
			        <th>
			        	Level <br/>
			        	Authority
			        </th>
			      </tr>
			    </thead>
			    <tbody>
			    	@if (count($data['users']) == 0 ) 
			    	<td colspan="10" class="text-center"> Kosong </td>
			    	@else 
			    		@foreach($data['users'] as $key=>$val)
			    		<?php 
			    		$count_rowspan = count($data['level_authorization'][$key]);
			    		?>
			    		<tr> 
			    			<td rowspan="{{$count_rowspan}}"> 
			    				{{ ucfirst($val->name) }} 
			    			</td>

			    			<?php foreach($data['level_authorization'][$key] as $key_row=>$val_row) : ?>

			    				<?php if($key_row != 0) : ?>
			    					<tr>
			    				<?php endif; ?>

			    				<td> {{$val_row['divisi_name']}} </td>

			    				<?php if($key_row != $count_rowspan-1) : ?>
			    					</tr>
			    				<?php endif; ?>

			    			<?php endforeach; ?>
			    		</tr>
			    		@endforeach
			    	@endif
			    </tbody>
			</table>
			<div class="pull-right" style="margin-top: -30px!important"> 
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
	</div>

	@include('admin.modal')
	@include('admin.modal_edit')
	@include('admin.modal_role')
	@include('admin.modal_special')

<script type="text/javascript">

	

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