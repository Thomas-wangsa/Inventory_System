<div class="col-sm-6">
	<div class="row text-center">
	<form action="{{route('akses_reject')}}">
		{{ csrf_field() }}
		<input type="hidden" name="uuid" value="{{$val->uuid}}" >
		<button type="submit" class="btn btn-danger">
			@if($val->status_akses == 1)
				Tolak Daftar
			@elseif($val->status_akses == 3)
				Tolak Cetak
			@elseif($val->status_akses == 5)
				Tolak Aktif
			@endif
		</button>
	</form>
	</div>
</div>

<div class="col-sm-6">
	<div class="row text-center">
	<form action="{{route('akses_approval')}}">
	{{ csrf_field() }}
	<input type="hidden" name="uuid" value="{{$val->uuid}}" >
	<button type="submit" class="btn btn-success"> 
		@if($val->status_akses == 1)
			Setuju Daftar
		@elseif($val->status_akses == 3)
			Setuju Cetak
		@elseif($val->status_akses == 5)
			Setuju Aktif
		@endif  
	</button>
	</form>
	</div>
</div>

<div class="clearfix"> </div>