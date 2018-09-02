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
				<div class="btn btn-primary" 
				data-toggle="modal" data-target="#modal_new_inventory">
					Add Inventory Category 
				</div>
			</div>
			@endif
			@if(in_array(1,$user_divisi) || in_array(2,$user_setting))
			<div class="pull-left" style="margin-right: 5px">
				<div class="btn btn-primary"
				data-toggle="modal" data-target="#modal_upload">
					Upload Excel
				</div>
			</div>
			@endif
			@if(in_array(1,$user_divisi) || in_array(7,$user_setting))
			<div class="pull-left">
				<div class="btn btn-primary"
				data-toggle="modal" data-target="#modal_new_floor">
					Add Floor Location
				</div>
			</div>
			@endif
			<div class="clearfix" style="margin-bottom: 10px"> </div>
			
			<div class="pull-left">
			 	<form class="form-inline" action="{{route('inventory')}}">
				    
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
				 			@foreach($data['search_status'] as $val)
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
				@if(in_array(1,$user_divisi) || $data['insert_inventory_data'])
				<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal_new">
					Add New Inventory
				</button>
				@endif
			</div>
			<div class="clearfix"> </div>

		</div>
		<div style="margin-top: 10px" class="scrollme"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			      	<th> 
			      		Inventory
			      		Category 
			      	</th>
			        <th> Lokasi Site </th>
			        <th> Qty </th>
			        <th> Created By </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
				    @if(count($data['inventory_data']) < 1)
				    <tr>
				    	<td colspan="10" class="text-center"> 
	                        No Data Found 
	                    </td>
				    </tr>
				    @else
				    	@foreach($data['inventory_data'] as $key=>$val)
				    	<tr>
				    		<td>
				    			{{ ($data['inventory_data']->currentpage()-1) 
			    				* $data['inventory_data']->perpage() + $key + 1 }} 
				    		</td>
				    		<td>
				    			{{$val->inventory_list_name}}
				    		</td>
				    		<td>
				    			{{$val->lokasi_site}}
				    		</td>
				    		<td>
				    			{{$val->inventory_data_qty}}
				    		</td>
				    		<td>
				    			{{$val->users_created_by}}
				    		</td>
				    		<td style="color:{{$val->status_inventory_color}}">
				    			{{$val->status_inventory_name}}
				    		</td>
				    		<td>
				    			@switch($val->inventory_data_status)
				    			@case("1")
				    				<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->uuid}}')" 
		                    			>
		                    				Info Inventory
		                    			</button>
		                    			<button
		                    			class="btn btn-success"
		                    			onclick="set_location('{{$val->uuid}}')"
		                    			>
		                    				Set Location
		                    			</button>
		                    			@if(in_array(1,$user_divisi)
		                    			|| $data['conditional_head'][$key]
		                    			)
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(2,'{{$val->uuid}}')" 
		                    			>
		                    				Approve Inventory
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick='edit("{{$val->uuid}}")' 
		                    			>
		                    				Edit Inventory
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove('{{$val->status_inventory}}','{{$val->uuid}}')" 
		                    			>
		                    				Reject Inventory
		                    			</button>
		                    			@endif
		                    		</div>
				    				@break
				    			@case("2")
				    				<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->uuid}}')" 
		                    			>
		                    				Info Inventory
		                    			</button>
		                    			<button
		                    			class="btn btn-success"
		                    			onclick="set_location('{{$val->uuid}}')"
		                    			>
		                    				Set Location
		                    			</button>
		                    			@if(in_array(1,$user_divisi))
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(3,'{{$val->uuid}}')" 
		                    			>
		                    				Approve Inventory
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick="edit('{{$val->uuid}}')" 
		                    			>
		                    				Edit Inventory
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove('{{$val->status_inventory}}','{{$val->uuid}}')" 
		                    			>
		                    				Reject Inventory
		                    			</button>
		                    			@endif
		                    		</div>
				    				@break
				    			@case("3")
				    				<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
	                    			</div>
				    				@break
				    			@case("4")
				    			@case("5")
				    				<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
	                    			</div>
	                    			<br/> <br/>
	                    			{{$val->comment}}
				    				@break
				    			@default
				    				-
				    				@break

				    			@endswitch
				    		</td>
				    	</tr>
				    	@endforeach
				    @endif
			    </tbody>
			</table>
		</div> <!--scrolme-->
		<div class="pull-right" style="margin-top: -20px!important"> 
			{{ $data['inventory_data']->appends(
				[
				'search' => Request::get('search'),
				'search_nama' => Request::get('search_nama'),
				'search_filter' => Request::get('search_filter'),
				'search_order' => Request::get('search_order')
				])->links() }}
	
		</div>
		<div class="clearfix"> </div>
	</div>
  	@include('inventory.modal_add')
  	@include('inventory.modal_upload')
  	@include('inventory.modal_floor')
  	@include('inventory.modal_new')
  	@include('inventory.modal_new_location')
  	@include('inventory.modal_edit')
	


<script type="text/javascript">
	

	function approve(next_status,uuid) {
		if (confirm('Approve this request ?')) { 
			var url = window.location.protocol+"//"+window.location.host+'/inventory_approval?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+next_status;
		}
	}

	function remove(status,uuid) {
		if (confirm('Reject this request ?')) {
			var url = window.location.protocol+"//"+window.location.host+'/inventory_reject?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+status;
		}
	}

	function info(uuid) {
		alert('on progress');
	}




	function reset_filter() {
    	window.location = "{{route('inventory')}}";
    }
	
</script>






@endsection

