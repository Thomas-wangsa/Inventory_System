@extends('layouts.template')

@section('content')
    <div style="padding: 25px 30px">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th> No </th>
                <th> Pemberitahuan </th>
              </tr>
            </thead>
            <tbody>
                @if($data['notify'] < 1)
                    <tr><td colspan="2" class="text-center"> Tidak ada Data</td></tr>
                @else
                    @foreach($data['notify_data'] as $key=>$val)
                    <tr> 
                        <td>
                            {{$key+1}}
                        </td>
                        <td>
                            {{$val->username}}{{$data['desc']}}{{$val->name}}
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
