@extends('layouts.template')

@section('content')
<style type="text/css">
	.barchart {width: 800px;height: 500px }
</style>
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
	  			<input type="hidden" name="search" value="on"> </input>
				<div class="form-group">
			      	<select class="form-control" name="position" id="id_request_type" required=""> 
			        	 <option value=""> select position </option>
			        	 @if(count($data['list_new_inventory_role']) > 0)
			        	 	@foreach($data['list_new_inventory_role'] as $key=>$val)
			        	 	<option value="{{$val['jabatan']}}" 
			        	 	@if($val["jabatan"] == Request::get('position')) 
			    				selected
			    			@endif
			        	 	> 
			        	 		{{$val['nama_jabatan']}}
			        	 	</option>
			        	 	@endforeach
			        	 @endif
			      	</select>
	  			</div>

	  			<button type="submit" class="btn btn-primary"> 
			  		Show Report Data  
	  			</button>

  			</form>
  		</div>

		<div class="clearfix" style="margin-bottom: 20px"> </div>
	</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

  	google.charts.load('current', {'packages':['corechart']});
 	google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

  	@if($data['each_inventory'] != null && count($data['each_inventory']) > 0)
    	@foreach($data['each_inventory'] as $key=>$val)
	      	var append  ='<div class="panel panel-info">';
	        append     +='<div class="panel-heading">'+
	                          '<div class="pull-left">'+
	                            '<h5>'+
	                            'report for '+"{{$val['inventory_name']}}" +
	                            '</h5>'+
	                          '</div>'+
	                          '<div class="pull-right">'+
	                            '<a href="{{route("inventory_report_each_download")}}?uuid={{$val["uuid"]}}">' +
	                              '<button id="download_sub_data{{$key}}" class="btn btn-primary">'+
	                                'Download'+" {{$val['inventory_name']}} "+"Report "+
	                                '='+" {{$val['qty']}} "+"rows"+
	                              '</button>'+
	                            '</a>'+
	                          '</div>'+
	                          '<div class="clearfix"> </div>'+
	                        '</div>';
	        append     +='<div class="panel-body" style="padding:30px">'+
	                          '<div class="barchart" id="barchart{{$key}}">  </div>'+  
	                        '</div>';
	        append     +='<div class="panel-footer"></div>';
	        append +='</div>';
	      	

	      	$('#parent_chart').append(append);

		    if({{count($val['inventory_sub_data'])}} < 1) {
		      	$('#download_sub_data{{$key}}').prop('disabled', true);
		      	$('#download_sub_data{{$key}}').html("please set the sub data first");
		      	$('#barchart{{$key}}').removeClass("barchart");

		    } else {
		    	var data = google.visualization.arrayToDataTable([
		        	["STATUS", "QTY"],
			        @foreach($val['inventory_sub_data'] as $key_data=>$val_data)
			          [
			          "{{$val_data['sub_data_status']}}",
			          {{$val_data['total']}}
			          ],
			        @endforeach
		    	]);

		    	var options = {
		          	title: 'Weekly Report '
		                +"{{$val['inventory_name']}} "+
		                'Period = '+"{{$data['from_date']}}"+" to "+"{{$data['current_date']}}",
		            slices: {
			            @foreach($val['inventory_sub_data'] as $key_data=>$val_data)
			            {{$key_data}}: {color : "{{$val_data['color']}}"},
			            @endforeach
          			}
			        
		      	};

		      	var chart = new google.visualization.PieChart(document.getElementById('barchart{{$key}}'));

        		chart.draw(data, options);

		      
		    }
    	@endforeach
    @endif  
  }

</script>
@endsection