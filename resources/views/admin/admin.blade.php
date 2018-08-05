@extends('layouts.template')

@section('content')
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
	<div style="padding: 0 30px;margin-top: 40px">
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
					Tambah Akun
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
			        <th> Identitas Diri </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    	@if (count($data['users']) == 0 ) 
			    	<td colspan="6" class="text-center"> Kosong </td>
			    	@else 
			    		@foreach($data['users'] as $key=>$val)
			    		<tr> 
			    			<td> 
			    				{{ ($data['users']->currentpage()-1) 
			    				* $data['users']->perpage() + $key + 1 }}
			    			</td>
			    			<td> {{ ucfirst($val->name) }} </td>
			    			<td> {{ $val->email }} </td>
			    			<td> {{ $val->mobile }} </td>
			    			<td>
			    				<a href="{{$val->foto}}" target="_blank" >
			    					<img src="{{$val->foto}}"/ width="80px"> 
			    				</a>
			    			</td>
			    			<td>
			    				<div class="text-center" > 
			    					<span class="glyphicon glyphicon-pencil"
			    					style="color:black;cursor:pointer" 
			    					title="Edit Data"
			    					onclick='get_data_user("{{$val->uuid}}")'>
			    						
			    					</span> &nbsp;

			    					<span onclick='delete_akun("{{ $val->uuid }}")' >
			    						<span class="glyphicon glyphicon-trash"
			    						style="color:red;cursor:pointer" 
			    						title="Hapus Data">
			    						</span>
			    					</span> &nbsp;
			    					
			    					<span class="glyphicon glyphicon-plus" 
			    					style="color: green;cursor:pointer" 
			    					title="Tambah Role" 
			    					onclick='get_role_user("{{$val->uuid}}")'>
			    						
			    					</span> &nbsp;

			    					<span class="glyphicon glyphicon-cog" 
			    					style="color: blue;cursor:pointer" 
			    					title="Tambah Feature"
			    					data-toggle="modal" data-target="#modal_special">
			    						
			    					</span> &nbsp;

			    				</div>
			    			</td>
			    		</tr>
			    		@endforeach
			    	@endif
			    </tbody>
			</table>
			<div class="pull-right" style="margin-top: -30px!important"> 
			{{ $data['users']->links() }}
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>

	@include('admin.modal');
	@include('admin.modal_edit');
	@include('admin.modal_role');
	@include('admin.modal_special');

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

    

    

	
</script>
@endsection