@extends('layouts.template')

@section('content')
<style type="text/css">
	th,td {text-align: center}
	.table>tbody>tr>td,.table>thead>tr>th {vertical-align: middle}
	.scrollme {overflow-y: auto;}
	.conditional{
/*		display: none
*/	}
</style>
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
			@if(in_array(1,$user_divisi) || in_array(4,$user_setting))
			<div class="pull-left" style="margin-right: 5px">
				<div class="btn btn-primary" data-toggle="modal" data-target="#modal_new_inventory">
					Add Inventory Category 
				</div>
			</div>
			@endif
			@if(in_array(1,$user_divisi) || in_array(2,$user_setting))
			<div class="pull-left" style="margin-right: 5px">
				<div class="btn btn-primary">
					Upload Excel
				</div>
			</div>
			@endif
			@if(in_array(1,$user_divisi) || in_array(7,$user_setting))
			<div class="pull-left">
				<div class="btn btn-primary">
					Add Floor Location
				</div>
			</div>
			@endif
			<div class="clearfix" style="margin-bottom: 10px"> </div>
			
			<div class="pull-left">
			 	<form class="form-inline" action="{{route('akses')}}">
				    
				    <input type="hidden" name="search" value="on"> </input>
				    <div class="input-group">
				    	<span class="input-group-addon">
				    		<i class="glyphicon glyphicon-search">
				    		</i>
				    	</span>
				    	<input type="text" class="form-control" 
				    	name="search_nama" placeholder="Find Name..."
				    	value="{{Request::get('search_nama')}}">
				  	</div>
					
					<div class="form-group">
				      	<select class="form-control" name="search_filter">
				      		<option value=""> Filter  </option>
				 
				      	</select>
				  	</div>

				  	<div class="form-group">
				      	<select class="form-control" name="search_order">
				      		<option value=""> Sort  </option>
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
				      	</select>
				  	</div>
				  
				  	<button type="submit" class="btn btn-info"> Find </button>
				  	<button type="reset" 
				  	class="btn btn-danger"
				  	onclick="reset_filter()"> 
				  		Reset 
				  	</button>
			  	</form>
			</div>
			<div class="pull-right">


				<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal_all">
					Add New Access Card
				</button>
			</div>
			<div class="clearfix"> </div>

		</div>
		<div style="margin-top: 10px" class="scrollme"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			      	<th> Name </th>
			        <th > Email </th>
			        <th > NIK </th>
			        <th > No Access Card </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
				    
			    </tbody>
			</table>
		</div> <!--scrolme-->
		
	</div>
  	@include('inventory.modal_add')
	


<script type="text/javascript">
	

	
</script>






@endsection

