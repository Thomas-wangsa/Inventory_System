@extends('layouts.template')

@section('content')
	<div id="parent_chart" class="col-sm-12" style="padding: 10px">

		<div class="pull-left">
			Data Inventory Period : {{$data['from_date']}} - {{$data['current_date']}} 
		</div>

	  	<div class="pull-right">
	    	<a 
	    	href="{{route('inventory_report_download')}}"    
	    	>
	      	<button class="btn btn-primary" style="margin-bottom: 25px"> 
	        	Download Full Report = {{count($data['new_inventory_data'])}} rows
	      	</button>
	    	</a>
	  	</div>

	  	<div class="clearfix"> </div>

	  	<div class="pull-left" style="margin-right: 10px">
	  		<form class="form-inline" action="">
				<div class="form-group">
			      	<select class="form-control" id="id_request_type" required=""> 
			        	 <option value=""> select position </option>
			        	 @if(count($data['list_new_inventory_role']) > 0)
			        	 	@foreach($data['list_new_inventory_role'] as $key=>$val)
			        	 	<option value="{{$val['jabatan']}}"> {{$val['nama_jabatan']}}</option>
			        	 	@endforeach
			        	 @endif
			      	</select>
	  			</div>

	  			<button type="submit" class="btn btn-primary"> 
			  		Show Report Data  
	  			</button>

  			</form>
  		</div>

		<div class="clearfix"> </div>

	</div>
@endsection