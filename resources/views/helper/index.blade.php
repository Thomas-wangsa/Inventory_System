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
			 						<td> {{$no}} </td>
			 						<td> {{$no}}</td>
			 						<td> {{$no}}</td>
			 						<td> {{$val->created_by}}</td>
			 						<td> {{$val->updated_by}}</td>
			 						<td>  </td>
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
@endsection