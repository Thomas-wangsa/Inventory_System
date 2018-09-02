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

textarea {
    resize: none;
}
</style>
	<div class="center_form" style="margin-top: 30px">
		<div class="text-center">
			Berikan alasan kenapa Anda menolak barang pada kolom yang telah disediakan di bawah

			<div style="margin-top: 30px"> </div>

			<div style="width: 100%">
				<form method="POST" action="{{route('proses_reject_inventory')}}">
					{{ csrf_field() }}
					<input type="hidden" name="uuid" value="{{$data['uuid']}}">
					
					<div class="form-group">
					<textarea rows="6" cols="50" name="desc" required autofocus placeholder="Ketik Alasan Anda"></textarea>
					</div>

					<div class="pull-left" style="padding-left: 25px;margin-top: 5px">
						<span> minimal 20 karakter </span>
					</div>

					<div class="pull-right" style="padding-right: 25px">
						<button type="submit" class="btn btn-block btn-danger MASUK" 
				        style="background-color: red">	
				        KIRIM
						</button>
					</div>
					
					<div class="clearfix"> </div>
				</form>
			</div>
		</div>


		

		
	</div>
@endsection
