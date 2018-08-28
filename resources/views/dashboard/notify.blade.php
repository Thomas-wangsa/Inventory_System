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
                <th> username </th>
                <th> access card name </th>
                <th> access card status </th>
                <th> status </th>
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
                            {{$val->access_card_name}}
                        </td>
                        <td style="color:{{$val->status_akses_color}}">
                            {{$val->status_akses_name}} 
                        </td>
                        <td>
                            @if($val->read == 0)
                                <div class="text-danger">  
                                    UNREAD
                                </div>
                            @else
                                <div class="text-success">
                                    READ
                                </div>
                            @endif 
                        </td>
                        <td>
                            <a href="{{route('akses')}}?search=on&search_uuid={{$val->uuid}}">
                                <button class="btn btn-warning">
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
