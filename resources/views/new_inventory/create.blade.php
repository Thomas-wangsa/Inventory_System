@extends('layouts.template')

@section('content')
<style type="text/css">
	th,td {text-align: center}
	.table>tbody>tr>td,.table>thead>tr>th {vertical-align: middle}
	.main_section {margin-top: 40px}
	.main_section_body{margin-top: 10px}
	.interactive_action{margin-top: 15px;margin-bottom: 10px}
</style>

	<div class="main_section">	

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


		<div class="main_section_information">
			<strong> Grouping : </strong> {{$data['new_inventory_data']->grouping_detail}} 
			<br/>
			<strong> Inventory Name : </strong> {{$data['new_inventory_data']->inventory_name}} 
			<br/>
			<strong> Main Quantity : </strong> {{$data['new_inventory_data']->qty}} 
			<br/>
		</div>

		<div class="interactive_action_map" style="margin-top: 7px">
		 	<div class="pull-left" style="margin-right: 10px">
				<div class="btn btn-primary"
				data-toggle="modal" data-target="#modal_create_new_map">
					Register Map 
				</div>
			</div>

			@if(count($data['map_data']) > 0)
			<div class="pull-left" style="margin-right: 10px">
				<div class="btn btn-info"
				data-toggle="modal" data-target="#modal_show_new_map">
					Show Map List
				</div>
			</div>
			@endif

			<div class="pull-left" style="margin-right: 10px">
				<div class="btn btn-success"
				data-toggle="modal" data-target="#modal_create_new_images">
					Register Images 
				</div>
			</div>

			@if(count($data['images_data']) > 0)
			<div class="pull-left" style="margin-right: 10px">
				<div class="btn btn-info"
				data-toggle="modal" data-target="#modal_show_new_map_images">
					Show Images List
				</div>
			</div>
			@endif

			<div class="clearfix"> </div>
		</div>


		<div class="interactive_action">
			<div class="pull-left" style="margin-right: 10px">
				<button id="map_back" 
				class="btn btn-warning"
				onclick="back()"
				>
					<span class="glyphicon glyphicon-chevron-left">
					</span> Back
				</button>
			</div>
			

			<div class="pull-right">
				<button id="map_back" 
				class="btn btn-primary"
				data-toggle="modal" 
				data-target="#modal_create_sub_inventory"
				>
					Add New Sub Data
				</button>
			</div>
			<div class="clearfix"> </div>
		</div>
		<div class="main_section_body">
			<table class="table table-bordered table-striped">
				<thead>
					<tr> 
						<th> No </th>
						<th> Status </th>
						<th> System ID </th>
						<th> Additional Note </th>
						<th> Set Map Location </th>
						<th> Action </th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 0;?>
					@foreach($data['new_inventory_sub_data'] as $key=>$val)
					<tr>						
						<td> {{$no+1}} </td>
						
						<td>
							<div class="form-group">
								<select class="form-control" id='sub_data_status{{$key}}'>
									<option value="good"
									<?php if($val->sub_data_status == "good") {echo "selected";} ?>
									> 
										good 
									</option>
									<option value="bad"
									<?php if($val->sub_data_status == "bad") {echo "selected";} ?>
									> 
										bad 
									</option>
									<option value="others"
									<?php if($val->sub_data_status == "others") {echo "selected";} ?>
									> 
										others 
									</option>
								</select>
							</div> 
						</td>

						<td>
							<?php echo substr($val->sub_data_uuid, 0,10); ?>
						</td>

						<td> 
							<div class="form-group">
								<input type="text" class="form-control" 
								id='sub_data_comment{{$key}}' value="{{$val->comment}}" / >
							</div> 
						</td>

						<td> 
							@if(count($data['map_data']) < 1 && count($data['images_data']) < 1)
								- 
							@elseif(count($data['map_data']) > 0 && count($data['images_data']) < 1)
								set images too!
							@elseif(count($data['map_data']) < 1 && count($data['images_data']) > 0)
								set map too!
							@elseif( count($data['map_data']) > 0 && count($data['images_data']) > 0 )
								@if($val['x_point'] != null && $val['y_point'] != null)
									<button type="submit" class="btn btn-info btn-block"
									onclick='show_map_location("{{$val->sub_data_uuid}}")'>
								  		Check Map
								  	</button>
								  	<button type="submit" class="btn btn-warning btn-block" 
								  	onclick='edit_map_location("{{$val->sub_data_uuid}}")'>
								  		Edit Map
								  	</button>
								@else 
								<form action="{{route('new_inventory_select_map')}}">
								  	<input type="hidden" name="sub_data_uuid" value="{{$val->sub_data_uuid}}">
								  	<input type="hidden" name="inventory_data_uuid" value="{{$data['new_inventory_data']->uuid}}">
								 	<div class="form-group">
									  	<label for="sel1">Select map :</label>
									    <select class="form-control" id="sel1" name="map_uuid">
									    	@foreach($data['map_data'] as $key_map=>$val_map) 
									    		<option value="{{$val_map['uuid']}}"> 
									    			{{$val_map['map_name']}}
									    		</option>
									    	@endforeach
	  									</select>
								 	</div>
								  
								  	<div class="form-group">
								  		<label for="sel1">Select images :</label>
									    <select class="form-control" id="sel1" name="images_uuid">
										    @foreach($data['images_data'] as $key_images=>$val_images) 
									    		<option value="{{$val_images['uuid']}}"> 
									    			{{$val_images['images_name']}}
									    		</option>
									    	@endforeach
	  									</select>
								  	</div>

								  	<button type="submit" class="btn btn-primary btn-block">
								  		Submit
								  	</button>
								</form>
								@endif
							@else
								out of scope
							@endif
						</td>
						<td>
							<div class="btn-group-vertical btn-block"> 
								<button class="btn btn-primary" onclick="submit_sub_data('{{$val->sub_data_uuid}}','{{$key}}')">
									update sub data
								</button>
								<button 
                    			class="btn btn-danger"
                    			onclick="delete_sub_data('{{$val->sub_data_uuid}}')" 
                    			>
	                    			delete sub data
	                    		</button> 
							</div>
						</td>
					</tr>
					<?php $no++;?>
					@endforeach
				</tbody>
			</table>
		</div> 
	</div>


	@include('new_inventory.modal_create_sub_inventory')
	@include('new_inventory.modal_create_new_map')
	@include('new_inventory.modal_create_new_images')
	@include('new_inventory.modal_show_new_map')
	@include('new_inventory.modal_show_new_map_images')

	<script type="text/javascript">
		var uuid = "{{$data['new_inventory_data']->uuid}}";

		function back() {
			var url = "{{URL::to('/')}}"+'/new_inventory?search=on&search_uuid=';
			window.location = url+uuid
		}

		function show_map_location(sub_data_uuid) {
			var url = "{{URL::to('/')}}"+'/map/new_inventory_show_map?sub_data_uuid=';
			window.open(url+sub_data_uuid);
		}

		function edit_map_location(sub_data_uuid) {
			if (confirm('Are you sure to edit this map location ?')) {
				$.ajaxSetup({
			      headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			      }
		    	});
				var data = {
		        "sub_data_uuid":sub_data_uuid
		    	};

		    	$.ajax({
			      type : "POST",
			      url: " {{ route('edit_map_location') }}",
			      contentType: "application/json",
			      data : JSON.stringify(data),
			      success: function(result) {
			        response = JSON.parse(result);
			        if(response.status == true) {
			        	alert("Update success");
			        	window.location = "{{URL::to('/')}}"+'/new_inventory/create?uuid='+uuid;
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

		function submit_sub_data(sub_data_uuid,key) {
			var sub_data_status = $('#sub_data_status'+key).val();
			var sub_data_comment = $('#sub_data_comment'+key).val()
			
			var data = {
		        "sub_data_uuid":sub_data_uuid,
		        "sub_data_status":sub_data_status,
		        "sub_data_comment":sub_data_comment
		    };

    		$.ajaxSetup({
		      headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		      }
		    });


		    $.ajax({
		      type : "POST",
		      url: " {{ route('new_inventory_sub_data_update_ajax') }}",
		      contentType: "application/json",
		      data : JSON.stringify(data),
		      success: function(result) {
		        response = JSON.parse(result);
		        if(response.status == true) {
		        	alert("Update success");
		        } else {
		          alert(response.message);
		        }

		      },
		      error: function( jqXhr, textStatus, errorThrown ){
		        console.log( errorThrown );
		      }
		    });
		}

		function delete_sub_data(sub_data_uuid) {
			
			if (confirm('Delete this sub data ?')) { 
				
				var data = {
		        	"sub_data_uuid":sub_data_uuid
		    	};

		    	$.ajaxSetup({
			      headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			      }
		    	});

		    	$.ajax({
			      type : "POST",
			      url: " {{ route('delete_new_inventory_sub_data') }}",
			      contentType: "application/json",
			      data : JSON.stringify(data),
			      success: function(result) {
			        response = JSON.parse(result);
			        if(response.status == true) {
			        	alert("Deleted success");
			        	var url = "{{URL::to('/')}}"+'/new_inventory/create?uuid=';
						window.location = url+"{{$data['token_main_uuid']}}";
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
