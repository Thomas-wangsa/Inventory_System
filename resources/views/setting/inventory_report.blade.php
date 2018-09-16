@extends('layouts.template')

@section('content')
<div id="parent_chart" class="col-sm-12" style="padding: 10px">
  <div class="pull-right">
    <a 
    href="{{route('inventory_report_download')}}"    
    >
      <button class="btn btn-primary" style="margin-bottom: 25px"> 
        Download Full Report = {{$data['total']}} rows
      </button>
    </a>
  </div>

  <div class="clearfix"> </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    @foreach($data['report_inventory_data'] as $key=>$val)




      var append  ='<div class="panel panel-info">';
          append     +='<div class="panel-heading">'+
                          '<div class="pull-left">'+
                            '<h5>'+
                            'report for '+"{{$val['inventory_list_name']}}" +
                            '</h5>'+
                          '</div>'+
                          '<div class="pull-right">'+
                            '<a href="">' +
                              '<button class="btn btn-primary">'+
                                'Download'+" {{$val['inventory_list_name']}} "+"Report "+
                                '='+" {{$val['count_data']}} "+"rows"+
                              '</button>'+
                            '</a>'+
                          '</div>'+
                          '<div class="clearfix"> </div>'+
                        '</div>';
          append     +='<div class="panel-body" style="padding:30px">'+
                          '<div id="barchart{{$key}}" style="width: 800px; height: 500px">  </div>'+  
                        '</div>';
          append     +='<div class="panel-footer"></div>';
          append +='</div>';
      
      $('#parent_chart').append(append);

      var data = google.visualization.arrayToDataTable([
        ["{{$val['search']}}", "{{$val['inventory_list_name']}}", ],
        @foreach($val['data'] as $key_data=>$val_data)
          ["{{$val_data['merk']}}",{{$val_data['sum_data']}}],
        @endforeach
      ]);

      var options = {
        chart: {
          title: 'Weekly Report '
                +"{{$val['inventory_list_name']}} "+
                'Period = '+"{{$data['from_date']}}"+" to "+"{{$data['current_date']}}",
        }
      };

      var chart = new google.charts.Bar(document.getElementById('barchart{{$key}}'));

      chart.draw(data, google.charts.Bar.convertOptions(options));

    @endforeach  
  }

</script>
@endsection