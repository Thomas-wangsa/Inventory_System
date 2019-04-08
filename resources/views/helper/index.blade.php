@extends('layouts.template')

@section('content')
	<style type="text/css">
		.table td, .table th {
   			text-align: center;   
		}
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

	<?php
	$category_value=null; 
	Request::get('search_filter') == null ? $id_edit_category=null : $id_edit_category = Request::get('search_filter');
	?>

	<div style="margin-top: 15px">
		<div id="helper_header">

			<div class="pull-left">  
				<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal_new_config">
					add new configuration
				</button>
			</div>
			<div class="clearfix" style="margin-bottom: 10px"> </div>



			<div class="pull-left">
				<form class="form-inline" action="{{route('helper.index')}}">
	
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
				      	<select class="form-control" name="search_filter" required="">
				      		<option value=""> Select Category </option>

				    		@foreach($data['config'] as $key=>$val)
				                <option value="{{$val}}"
				                @if($val == Request::get('search_filter')) 
				    				selected
				    			@endif
				                >
				                  {{$key}}
				                </option>
			              	@endforeach
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

		<div id="helper_body" style="margin-top:20px">

			<div class="scrollme"> 
				<table class="table table-bordered table-responsive">
			    	<thead>
			    		 <tr>
					      	<th> No </th>
					      	<th> Category </th>
					        <th> Name </th>
					        <th> Detail </th>
					        <th> Created By </th>
					        <th> Updated By </th>
					        <th> Action </th>
			        	</tr>
			 		</thead>
			 		<tbody>
			 			<tr>
			 				@if($data['rows'] == null ) 
			 					<td colspan="10" class="text-center"> Please select the category </td>
			 				@elseif(count($data['rows']) < 1)
			 					<td colspan="10" class="text-center"> Data not found! </td>
			 				@else 
			 					<?php $no=1;?>

			 					@switch(Request::get('search_filter'))
								    @case(1)
								        <?php $val_name="group1_name";$val_detail="group1_detail";$category_value = "kota";?>
								        @break
								    @case(2)
								    	<?php $val_name="group2_name";$val_detail="group2_detail";$category_value = "gedung";?>
								        @break
								    @case(3)
								    	<?php $val_name="group3_name";$val_detail="group3_detail";$category_value = "divisi indosat";?>
								        @break
								    @case(4)
								    	<?php $val_name="group4_name";$val_detail="group4_detail";$category_value = "sub divisi indosat";?>
								        @break
								    @case(5)
								    	<?php $val_name="inventory_name";$val_detail="inventory_detail_name";$category_value = "inventory list";?>
								        @break
								    @case(6)
								    	<?php $val_name="vendor_name";$val_detail="vendor_detail_name";$category_value = "pic list";?>
								        @break
								     @case(7)
								    	<?php $val_name="admin_room";$val_detail="admin_room_detail";$category_value = "admin room list";?>
								        @break
								    @default
								    	@break
								@endswitch

			 					@foreach($data['rows'] as $key=>$val)
			 					<tr>
			 						<td> {{$no}} </td>
			 						<td> 
			 							{{$category_value}}
			 						</td>
			 						<td> 
			 							{{$val->$val_name}}
			 						</td>
			 						<td> 
			 							{{$val->$val_detail}}
			 						</td>
			 						<td> {{$val->created_by_user}}</td>
			 						<td> {{$val->updated_by_user}}</td>
			 						<td>  
			 							<button 
		                    			class="btn btn-warning"
		                    			onclick="edit_config('{{$val->id}}','{{$val->$val_name}}','{{$val->$val_detail}}')"
		                    			>
		                    				Edit {{ucwords($category_value)}} Category
		                    			</button>
			 						</td>
			 					</tr>
			 					<?php $no++;?>
			 					@endforeach
			 				@endif
			 			</tr>
			 		</tbody>
			 	</table>
			</div>
		</div>
	</div>

	
	@include('helper.modal_new_config')
	@include('helper.modal_edit_config')

	<script type="text/javascript">
		function reset_filter() {
			window.location = "{{route('helper.index')}}";
		}

		function edit_config(id,name,detail) {
			$('#edit_config_uuid').val(id);
			$('#edit_main_category').val(name);
			$('#edit_additional_category').val(detail);
			// alert(id);
			$('#modal_edit_config').modal('show');
		}
	</script>
@endsection