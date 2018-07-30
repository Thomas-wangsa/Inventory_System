@extends('layouts.template')

@section('content')
<div class="col-sm-12" style="margin-top: 30px">
	
	<div id="piechart" style="width: 900px; height: 500px;"></div>

	
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Daftar Akses',     11],
          ['Akses di Aktifkan',      2],
          ['Akses di Tolak',      2],
          ['Cetak Akses',    7]
        ]);

        var options = {
          title: 'Weekly Report'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
@endsection