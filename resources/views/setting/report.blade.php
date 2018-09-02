@extends('layouts.template')

@section('content')
<div class="col-sm-12" style="padding: 30px">
	<div class="pull-right">
    <a 
    @if($data['report_for'] == "access")
    href="{{route('report_download')}}"
    @elseif($data['report_for'] == "inventory")
    href="{{route('inventory_report_download')}}"
    @endif
    
    >
      <button class="btn btn-primary"> 
        Download Weekly Report = {{$data['total']}} rows
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
          title: 'Weekly Report Period {{$data["from_date"]}} - {{$data["current_date"]}} ' ,
           slices: {
            @foreach($data['color'] as $key=>$val)
            {{$key}}: {color : "{{$val->color}}"},
            @endforeach
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

    </script>
@endsection