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
          ['Status', 'Pcs'],
          ['Pending Daftar',{{$data['pending_daftar']}}],
          ['Pending Cetak',{{$data['pending_cetak']}}],
          ['Pending Aktif',{{$data['pending_aktif']}}],
          ['Kartu Aktif',{{$data['kartu_aktif']}}],
          ['Ditolak Daftar',{{$data['tolak_daftar']}}],
          ['Ditolak Cetak',{{$data['tolak_cetak']}}],
          ['Ditolak Aktif ',{{$data['tolak_aktif']}}]
        ]);

        var options = {
          title: 'Weekly Report ' +"{{$data['period']}}" ,
           slices: {
            0: { color: '#000000' },
            1: { color: '#FFA500' },
            2: { color: '#00FF00' },
            3: { color: '#0000FF' },
            4: { color: '#DC143C' },
            5: { color: '#B22222' },
            6: { color: '#8B0000' }
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
@endsection