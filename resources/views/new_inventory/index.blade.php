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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $(".datepicker_class" ).datepicker({
      dateFormat: 'yy-mm-dd' ,
      showButtonPanel: true
    });
  });
</script>
	<div style="margin-top: 40px">
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
	

		<div class="pull-left">
		 	<form class="form-inline" action="{{route('new_inventory.index')}}">
			    
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
			      	</select>
			  	</div>
			  
			  	<button type="submit" class="btn btn-info"> Filter </button>
			  	<button type="reset" 
			  	class="btn"
			  	onclick="reset_filter()"> 
			  		Clear Filter 
			  	</button>
		  	</form>
		</div>

		<div class="pull-right">
			<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal_new">
				Add New Inventory
			</button>
		</div>
		<div class="clearfix"> </div>


		<div style="margin-top: 10px" class="scrollme"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			      	<th> 
			      		Name
			      	</th>
			      	<th> 
			      		Group1
			      	</th>
			      	<th> 
			      		Group2
			      	</th>
			      	<th> 
			      		Group3
			      	</th>
			      	<th> 
			      		Group4
			      	</th>
			      	<th>
			      		Category
			      	</th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    </tbody>
			    <?php $no = 0;?>
			    @if(count($data['new_inventory_data']) > 1)
			    	@foreach($data['new_inventory_data'] as $key=>$val)
			    	<tr> 
			    		<td> {{$no+1}}</td>
			    		<td> {{$val->inventory_name}} </td>
			    		<td> {{$val->group1_name}} </td>
			    		<td> {{$val->group2_name}} </td>
			    		<td> {{$val->group3_name}} </td>
			    		<td> {{$val->group4_name ? $val->group4_name : "undefined"}}  </td>
			    		<td> {{$val->inventory_name}} </td>
			    		<td style="color:{{$val->status_inventory_color}}"> {{$val->status_inventory_name}} </td>
			    		<td> ACtion </td>
			    	</tr> 
			    	<?php $no++;?>
			    	@endforeach
			    @else
			    	<tr> <td colspan="10"> NO DATA FOUND! </td></tr> 
			    @endif
			</table>
		</div>


	</div>
  
	@include('new_inventory.modal_new')



@endsection
