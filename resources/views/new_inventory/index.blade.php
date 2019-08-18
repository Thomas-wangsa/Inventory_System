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
		

		@if(in_array(1,$user_divisi) || in_array(2,$user_setting) || count($data['conditional_head']) > 0 )
		<div class="pull-left" style="margin-bottom: 10px">
			<div class="btn btn-primary"
			data-toggle="modal" data-target="#modal_upload_new">
				Upload Excel
			</div>

			@if(
			Request::get('search_category') ||
			Request::get('search_kota') ||
			Request::get('search_gedung') ||
			Request::get('search_divisi') ||
			Request::get('search_sub_divisi')

			) 
			<div class="btn btn-success" onclick="download_selected_report()">
				Download Data
			</div>
			@endif 
		</div>
		<div class="clearfix"> </div>
		@endif

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
			      	<select class="form-control" name="search_category" id="search_category">
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

			  	<br/> <br/>


			  	<div class="form-group">
			      	<select class="form-control" name="search_kota">
			      		<option value=""> Select Kota  </option>
			 			@foreach($data['group1'] as $val)
			 			<option value="{{$val->id}}"
			 				@if($val->id == Request::get('search_kota')) 
			    				selected
			    			@endif
			    			> 
			 				{{$val->group1_name}}
			 			</option>
			 			@endforeach
			      	</select>
			  	</div>


			  	<div class="form-group">
			      	<select class="form-control" name="search_gedung">
			      		<option value=""> Select Gedung  </option>
			 			@foreach($data['group2'] as $val)
			 			<option value="{{$val->id}}"
			 				@if($val->id == Request::get('search_gedung')) 
			    				selected
			    			@endif
			    			> 
			 				{{$val->group2_name}}
			 			</option>
			 			@endforeach
			      	</select>
			  	</div>


			  	<div class="form-group">
			      	<select class="form-control" name="search_divisi">
			      		<option value=""> Select Divisi  </option>
			 			@foreach($data['group3'] as $val)
			 			<option value="{{$val->id}}"
			 				@if($val->id == Request::get('search_divisi')) 
			    				selected
			    			@endif
			    			> 
			 				{{$val->group3_name}}
			 			</option>
			 			@endforeach
			      	</select>
			  	</div>


			  	<div class="form-group">
			      	<select class="form-control" name="search_sub_divisi">
			      		<option value=""> Select Sub Divisi  </option>
			 			@foreach($data['group4'] as $val)
			 			<option value="{{$val->id}}"
			 				@if($val->id == Request::get('search_sub_divisi')) 
			    				selected
			    			@endif
			    			> 
			 				{{$val->group4_name}}
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
			<br/> <br/> <br/>

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
			      		Kota
			      	</th>
			      	<th> 
			      		Gedung
			      	</th>
			      	<th> 
			      		Divisi Indosat
			      	</th>
			      	<th> 
			      		Sub Divisi Indosat
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
	                    			id="id_approve_{{$val->uuid}}" 
	                    			>
	                    				Submit Inventory
	                    			</button>

				    				@break
				    			@case("2")
	                    			@if($data['conditional_head'][$key])
	                    			<button 
	                    			class="btn btn-primary"
	                    			onclick="approve('{{$val->uuid}}')" 
	                    			id="id_approve_{{$val->uuid}}"
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
	                    			id="id_update_{{$val->uuid}}"
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
				'search_category' => Request::get('search_category'),
				'search_kota' => Request::get('search_kota'),
				'search_gedung' => Request::get('search_gedung'),
				'search_divisi' => Request::get('search_divisi'),
				'search_sub_divisi' => Request::get('search_sub_divisi')
				])->links() }}
	
		</div>
		<div class="clearfix"> </div>


	</div>
  
	@include('new_inventory.modal_new')
	@include('new_inventory.modal_info')
	@include('new_inventory.modal_edit')
	@include('new_inventory.modal_reject')
	@include('new_inventory.modal_upload_new')

	<script type="text/javascript">


		function download_selected_report() {
			url = "{{route('download_data_inventory')}}"+"?category=";

			@if(Request::get('search_category'))
			url = url+"{{Request::get('search_category')}}";
			@endif

			url = url+"&search_kota=";

			@if(Request::get('search_kota'))
			url = url+"{{Request::get('search_kota')}}";
			@endif

			url = url+"&search_gedung=";

			@if(Request::get('search_gedung'))
			url = url+"{{Request::get('search_gedung')}}";
			@endif

			url = url+"&search_divisi=";

			@if(Request::get('search_divisi'))
			url = url+"{{Request::get('search_divisi')}}";
			@endif

			url = url+"&search_sub_divisi=";

			@if(Request::get('search_sub_divisi'))
			url = url+"{{Request::get('search_sub_divisi')}}";
			@endif


			window.location = url;
		}

		function reset_filter() {
    		window.location = "{{route('new_inventory.index')}}";
    	}

    	function set_sub_data_inventory(uuid) {
			var url = "{{URL::to('/')}}"+'/new_inventory/create?uuid=';
			window.location = url+uuid;
		}
		

		function approve(uuid) {
			if (confirm('Submit inventory ?')) {
				$("#id_approve_"+uuid).html("Please wait..");
				$("#id_approve_"+uuid).prop('disabled', true);
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


		function update_inventory(uuid) {
			if (confirm('Update inventory ?')) {
				$("#id_update_"+uuid).html("Please wait..");
				$("#id_update_"+uuid).prop('disabled', true);
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
			      url: " {{ route('new_inventory_data_update_ajax') }}",
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
