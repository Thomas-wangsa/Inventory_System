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
	</div>
@endsection