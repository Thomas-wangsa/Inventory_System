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
			      	<select class="form-control" name="search_category">
			      		<option value=""> Category  </option>
			 			@foreach($data['category'] as $val)
			 			<option value="{{$val->id}}"
			 				@if($val->id == Request::get('search_category')) 
			    				selected
			    			@endif
			    			> 
			 				{{$val->inventory_name}}
			 			</option>
			 			@endforeach
			      	</select>
			  	</div>

				<div class="form-group">
			      	<select class="form-control" name="search_filter">
			      		<option value=""> Status  </option>
			 			@foreach($data['status_inventory'] as $val)
			 			<option value="{{$val->id}}"
			 				@if($val->id == Request::get('search_filter')) 
			    				selected
			    			@endif
			    			> 
			 				{{$val->name}}
			 			</option>
			 			@endforeach
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
			    @if(count($data['new_inventory_data']) > 0)
			    	@foreach($data['new_inventory_data'] as $key=>$val)
			    	<tr> 
			    		<td> 
			    			{{ ($data['new_inventory_data']->currentpage()-1) 
			    				* $data['new_inventory_data']->perpage() + $key + 1 }}  
			    		</td>
			    		<td> {{$val->inventory_name}} </td>
			    		<td> {{$val->group1_name}} </td>
			    		<td> {{$val->group2_name}} </td>
			    		<td> {{$val->group3_name}} </td>
			    		<td> {{$val->group4_name ? $val->group4_name : "undefined"}}  </td>
			    		<td> {{$val->inventory_list_name}} </td>
			    		<td style="color:{{$val->status_inventory_color}}"> {{$val->status_inventory_name}} </td>
			    		<td>
			    			<div class="btn-group-vertical"> 
			    				<button 
                    			class="btn btn-info"
                    			onclick="info('{{$val->uuid}}')" 
                    			>
                    				Info Inventory
                    			</button>
			    			@switch($val->status)
				    			@case("1")

	                    			<button 
	                    			class="btn btn-basic"
	                    			onclick="set_sub_data_inventory('{{$val->uuid}}')" 
	                    			>
	                    				Set Sub Data
	                    			</button>
	                    			
	                    			<button 
	                    			class="btn btn-warning"
	                    			onclick='edit("{{$val->uuid}}")' 
	                    			>
	                    				Edit Inventory
	                    			</button>

	                    			<button 
	                    			class="btn btn-primary"
	                    			onclick="approve('{{$val->uuid}}')" 
	                    			>
	                    				Submit Inventory
	                    			</button>

				    				@break
				    			@case("2")
	                    			@if($data['conditional_head'][$key])
	                    			<button 
	                    			class="btn btn-primary"
	                    			onclick="approve('{{$val->uuid}}')" 
	                    			>
	                    				Approve Inventory
	                    			</button>
	                    			<button 
	                    			class="btn btn-danger"
	                    			onclick="reject_head('{{$val->uuid}}','{{$val->inventory_name}}')" 
	                    			>
	                    				Reject Inventory
	                    			</button>
	                    			@endif
				    				@break
				    			@case("3")
				    				<button 
	                    			class="btn btn-warning"
	                    			onclick='update_inventory("{{$val->uuid}}")' 
	                    			>
	                    				Update Inventory
	                    			</button>
				    				@break
				    			@case("4")
				    				@break
				    			@default
				    				-
				    				@break
				    		@endswitch 
				    		</div>
			    		</td>
			    	</tr> 
			    	@endforeach
			    @else
			    	<tr> <td colspan="10"> NO DATA FOUND! </td></tr> 
			    @endif
			</table>
		</div>
		<div class="pull-right" style="margin-top: -20px!important"> 
			{{ $data['new_inventory_data']->appends(
				[
				'search' => Request::get('search'),
				'search_nama' => Request::get('search_nama'),
				'search_filter' => Request::get('search_filter'),
				'search_category' => Request::get('search_category')
				])->links() }}
	
		</div>
		<div class="clearfix"> </div>


	</div>
  
	@include('new_inventory.modal_new')
	@include('new_inventory.modal_info')
	@include('new_inventory.modal_edit')
	@include('new_inventory.modal_reject')

	<script type="text/javascript">
		function reset_filter() {
    		window.location = "{{route('new_inventory.index')}}";
    	}

    	function set_sub_data_inventory(uuid) {
			var url = "{{URL::to('/')}}"+'/new_inventory/create?uuid=';
			window.location = url+uuid;
		}
		

		function approve(uuid) {
			if (confirm('Submit inventory ?')) {
				var data = {
					"uuid":uuid,
				}
				$.ajaxSetup({
			      headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			      }
			    });


			    $.ajax({
			      type : "POST",
			      url: " {{ route('new_inventory_data_approve_ajax') }}",
			      contentType: "application/json",
			      data : JSON.stringify(data),
			      success: function(result) {
			        response = JSON.parse(result);
			        if(response.status == true) {
			        	var url = "{{URL::to('/')}}"+'/new_inventory?search=on&search_uuid=';
						window.location = url+response.data.uuid;
			        } else {
			          alert(response.message);
			        }

			      },
			      error: function( jqXhr, textStatus, errorThrown ){
			        console.log( errorThrown );
			      }
			    });
			}
		}
	</script>

@endsection
