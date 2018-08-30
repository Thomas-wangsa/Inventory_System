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
                <th> notification data name </th>
                <th>  status data id </th>
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
                            {{$val->request_name}} 
                        </td>
                        <td>
                            {{$val->divisi_name}}
                        </td>
                        <td>
                            {{$val->notification_data_name}}
                        </td>
                        <td style="color:{{$val->status_data_id_color}}">
                            {{$val->status_data_id}}
                        </td>
                        <td>
                            {{$val->status_notify_name}} 
                        </td>
                        <td>
                            {{$val->created_at}}
                        </td>
                        <td>
                            <a href="{{route('akses')}}?search=on&search_uuid=">
                                <button class="btn btn-info">
                                    Cek Access Card
                                </button>
                            </a>
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
