@extends('layouts.template')

@section('content')
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
<div class="col-sm-12" style="padding: 30px">

  <div class="pull-left"> 
    <form class="form-inline" action="">
      <div class="form-group">
        <label for="from_date">From Date:</label>
        <input type="text" class="form-control datepicker_class" name="from_date" id="from_date"
        value="<?php 
          if(Request::get('from_date')) {
              echo Request::get('from_date');
            } else {
              echo $data["from_date"];
            }
        ?>
        ">
      </div>
      <div class="form-group">
        <label for="to_date">To Date:</label>
        <input type="text" class="form-control datepicker_class" name="to_date" id="to_date"
        value="<?php 
          if(Request::get('to_date')) {
              echo Request::get('to_date');
            } else {
              echo $data["current_date"];
            }
        ?>
        ">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
	<div class="pull-right">
    <a 
    @if($data['report_for'] == "access")
    href="{{route('report_download')}}"
    @elseif($data['report_for'] == "inventory")
    href="{{route('inventory_report_download')}}"
    @endif
    
    >
      <button class="btn btn-primary"> 
        Download Period Report = {{$data['total']}} rows
      </button>
    </a>
  </div>
  <div class="clearfix"> </div>
	<div id="piechart" style="max-width: 900px; height: 600px;"></div>

	
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Status', 'Pcs'],
          @foreach($data['report'] as $key=>$val)
          ["{{$key}}" , {{$val}}],
          @endforeach
        ]);

        var options = {
          title: 'Report Period {{$data["from_date"]}} to {{$data["current_date"]}} ' ,
           slices: {
            @foreach($data['color'] as $key=>$val)
            {{$key}}: {color : "{{$val}}"},
            @endforeach
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

    </script>
@endsection