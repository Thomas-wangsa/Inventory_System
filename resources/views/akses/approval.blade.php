@extends('layouts.login')

@section('content')
<style type="text/css">
	.MASUK {
  font-family: Roboto;
  font-size: 18px;
  font-weight: bold;
  font-style: normal;
  font-stretch: normal;
  line-height: normal;
  letter-spacing: normal;
  text-align: center;
  color: #ffffff;
}
</style>
	<div class="center_form" style="margin-top: 30px">
		<div class="text-center">
			Terima kasih untuk waktu Anda
		</div>
		<div class="text-center">
			kartu akses telah di setujui
		</div>

		<a href="{{route('home')}}">
			<button type="submit" class="btn btn-block btn-danger MASUK" 
			        style="background-color: red;margin-top: 30px">	
			        Dashboard
			</button>
		</a>
	</div>
@endsection
