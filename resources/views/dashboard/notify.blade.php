@extends('layouts.template')

@section('content')
<style type="text/css">
    th,td {text-align: center}
    .table>tbody>tr>td,.table>thead>tr>th {vertical-align: middle}
</style>
    <div style="padding: 25px 30px">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th> No </th>
                <th> created by </th>
                <th> category </th>
                <th> data name  </th>
                <th>  status data name </th>
                <th> status notification </th>
                <th> created at </th>
                <th> check data </th>
              </tr>
            </thead>
            <tbody>
                @if(count($data['notify']) < 1)
                    <tr>
                        <td colspan="10" class="text-center"> No Data Found </td>
                    </tr>
                @else
                    @foreach($data['notify'] as $key=>$val)
                    <tr style="font-family: tahoma">
                        <td>
                            {{ ($data['notify']->currentpage()-1) 
                            * $data['notify']->perpage() + $key + 1 }}
                        </td>
                        <td>
                            {{$data['info'][$key]->request_name}}
                        </td>
                        <td>
                            {{$val->divisi_name}}
                        </td>
                        <td>
                            {{$data['info'][$key]->notification_data_name}}
                        </td>
                        <td style="color:{{$data['info'][$key]->notification_status_data_color}}">
                            {{$data['info'][$key]->notification_status_data_name}}
                        </td>
                        <td >
                            {{$val->sub_notify_name}}
                        </td>
                        <td>
                            {{$val->created_at}}
                        </td>
                        <td>
                            @if($data['info'][$key]->notification_category == 2 
                                ||
                                $data['info'][$key]->notification_category == 3)
                            <a href="{{route('akses')}}?search=on&search_uuid={{$data['info'][$key]->notification_data_uuid}}">
                                <button class="btn btn-info">
                                    Cek Access Card
                                </button>
                            </a>
                            @elseif($data['info'][$key]->notification_category == 4)
                            <a href="{{route('inventory')}}?search=on&search_uuid={{$data['info'][$key]->notification_data_uuid}}">
                                <button class="btn btn-info">
                                    Cek Inventory Data
                                </button>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
                    
            </tbody>
        </table>
        <div class="pull-right" style="margin-top: -15px!important"> 
            {{ $data['notify']->links() }}
        </div>
        <div class="clearfix"> </div>
    </div>
@endsection
