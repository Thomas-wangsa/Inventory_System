@extends('layouts.template')

@section('content')
<style type="text/css">
    .Rectangle-5 {
  width: 207px;
  height: 196px;
  border-radius: 10px;
  background-color: #ffffff;
  border: solid 0.8px #979797;
}

.id-card {
  width: 56px;
  height: 84px;
  object-fit: contain;
}

</style>
        <div style="padding: 0 30px;margin-top: 40px">
            <div class="flash-message center">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                  @if(Session::has('alert-' . $msg))

                  <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} 
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">
                      &times;
                    </a>
                 </p>
                  @endif
                @endforeach
            </div> <!-- end .flash-message -->
        <div>
            <div class="pull-left">
                <form class="form-inline" action="{{route('route_admin')}}">
                    
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-search">
                            </i>
                        </span>
                        <input type="text" class="form-control" 
                        name="search_email" placeholder="Cari Nama...">
                    </div>
                    
                    <div class="form-group">
                        <select class="form-control" name="search_filter">
                            <option value=""> Filter Berdasarkan </option>
                            @foreach($data['status_akses'] as $key=>$val)
                            <option value="{{$val->id}}"> {{ucfirst($val->name)}}</option>
                            @endforeach 
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="search_order">
                            <option value=""> Sort Berdasarkan </option>
                            <option value="1"> Nama </option>
                            <option value="2"> Email </option>
                            <option value="3"> Handphone </option>
                        </select>
                    </div>
                  
                    <button type="submit" class="btn btn-info"> Cari </button>
                </form> 
            </div>
            <div class="pull-right"> 
                <button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#myModal1">
                    Daftar
                </button>
                <button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#myModal2">
                    Daftarkan Vendor
                </button>
            </div>
            <div class="clearfix"> </div>

        </div>
        <div style="margin-top: 10px"> 
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th> No </th>
                    <th> Nama Lengkap </th>
                    <th> Divisi </th>
                    <th> Email </th>
                    <th> Keterangan </th>
                    <th> Status </th>
                    <th> Action </th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($data['data']) < 1)
                    <tr>
                        <td colspan="10" class="text-center"> No Data Found </td>
                    </tr>
                  @else 
                    <?php $no = 1; ?>
                    @foreach($data['data'] as $key=>$val)
                    <tr>
                        <td> {{$no}}</td>
                        <td> {{$val->name}}</td>
                        <td> {{$val->divisi}}</td>
                        <td> {{$val->email}}</td>
                        <td> {{$val->comment}}</td>
                        <td style="color:{{$val->status_color}}"> {{$val->status_name}}</td>
                        <td> </td>

                    </tr>
                    <?php $no++;?>
                    @endforeach
                  @endif
                </tbody>
            </table>
            <div class="pull-right" style="margin-top: -30px!important"> 
            </div>
            <div class="clearfix"> </div>
        </div>
        </div>
        @include('dashboard.modal_staff')
        @include('dashboard.modal_vendor')
    
@endsection
