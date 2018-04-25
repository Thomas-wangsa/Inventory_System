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
    <div style="padding: 25px 30px">
        <h2> Hi Thomas </h2>
        <h4> Apa yang anda lakukan hari ini </h4>

        <div> 
            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <a href="/akses">
                            <img  class="center" src="{{ asset('images/logo/id-card.png')}}"  style="width:30%;margin-top: 35px" / >
                        </a>
                        <div style="margin-top: 10px"> </div>
                        <div class="text-center"> Akses </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <img  class="center" src="{{ asset('images/logo/checklist.png')}}"  style="width:30%;margin-top: 35px" / >
                        <div style="margin-top: 10px"> </div>
                        <div class="text-center"> Inventory </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <img  class="center" src="{{ asset('images/logo/user.png')}}"  style="width:30%;margin-top: 45px" / >
                        <div style="margin-top: 20px"> </div>
                        <div class="text-center"> Kelola Akun </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="row">
                    <div class="Rectangle-5">
                        <img  class="center" src="{{ asset('images/logo/settings.png')}}"  style="width:30%;margin-top: 45px" / >
                        <div style="margin-top: 20px"> </div>
                        <div class="text-center"> Pengaturan </div>
                    </div>
                </div>
            </div>

        </div> 
    </div>
@endsection
