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
			 					<?php $no=1; ?>
			 					@foreach($data['rows'] as $key=>$val)
			 					<tr>
			 						<td> {{$no}}</td>
			 						<td> 
			 							@foreach($data['config'] as $key_config=>$val_config)
			 								@if($val_config == Request::get('search_filter')) 
				    							{{$key_config}}
				    						@endif
			 							@endforeach
			 						</td>
			 						<td> 
			 							@switch(Request::get('search_filter'))
										    @case(1)
										        {{$val->group1_name}}
										        @break
										    @case(2)
										    	{{$val->group2_name}}
										        @break
										    @case(3)
										    	{{$val->group3_name}}
										        @break
										    @case(4)
										    	{{$val->group4_name}}
										        @break
										    @case(5)
										    	{{$val->inventory_name}}
										        @break
										    @default
										    	<span> Error </span>
										    	@break
										@endswitch
			 						</td>
			 						<td> 
			 							@switch(Request::get('search_filter'))
										    @case(1)
										        {{$val->group1_detail}}
										        @break
										    @case(2)
										    	{{$val->group2_detail}}
										        @break
										    @case(3)
										    	{{$val->group3_detail}}
										        @break
										    @case(4)
										    	{{$val->group4_detail}}
										        @break
										    @case(5)
										    	{{$val->inventory_detail_name}}
										        @break
										    @default
										    	<span> Error </span>
										    	@break
										@endswitch
			 						</td>
			 						<td> {{$val->created_by_user}}</td>
			 						<td> {{$val->updated_by_user}}</td>
			 						<td>  
			 							<button 
		                    			class="btn btn-warning"
		                    			onclick="edit_config('{{$val->id}}')"
		                    			>
		                    				Edit Access Card
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

	<script type="text/javascript">
		function reset_filter() {
			window.location = "{{route('helper.index')}}";
		}

		function edit_config(id) {
			alert(id);
			alert("{{Request::get('search_filter')}}");
		}
	</script>
	@include('helper.modal_new_config')
@endsection