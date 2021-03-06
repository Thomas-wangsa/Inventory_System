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
                <th> name </th>
                <th> created by </th>
                <th> category </th>
                <th> notify type  </th>
                <th> notify status  </th>
                <th> created at </th>
                <th> note </th>
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
                           {{ $val['name'] }}
                        </td>
                        <td>
                            {{ $val['created_by'] }}
                        </td>
                        <td>
                            {{ $val['category_name'] }}
                        </td>
                        <td>
                            {{ $val['notify_type'] }}
                        </td>
                        <td style="color:{{$val['notify_status_color']}}">
                            {{ $val['notify_status_name'] }}
                        </td>
                        <td>
                            {{ $val['created_at'] }}
                        </td>
                        <td>
                            {{ $val['note'] }}
                        </td>
                        <td>
                            @if($val['category'] == 1)
                                <a href="{{route('accesscard')}}?search=on&search_uuid={{$val['data_uuid']}}">
                                    <button class="btn btn-info">
                                        View Access Card
                                    </button>
                                </a>
                            @elseif($val['category'] == 2)
                                <a href="{{route('new_inventory.index')}}?search=on&search_uuid={{$val['data_uuid']}}">
                                    <button class="btn btn-info">
                                        View Inventory
                                    </button>
                                </a>
                            @else
                                -
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
