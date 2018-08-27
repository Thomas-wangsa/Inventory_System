@extends('layouts.template')

@section('content')
<div class="col-sm-12" style="padding: 30px;background-color: red">
	<div class="pull-right">
    <button class="btn btn-primary"> 
      Download Weekly Report
    </button>
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
          @foreach($data as $key=>$val)
          ["{{$key}}" , {{$val}}],
          @endforeach
        ]);

        var options = {
          title: 'Weekly Report ' ,
           slices: {
            @foreach($color as $key=>$val)
            {{$key}}: {color : "{{$val->color}}"},
            @endforeach
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

    </script>
@endsection