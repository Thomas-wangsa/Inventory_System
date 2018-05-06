@extends('layouts.template')

@section('content')
<div class="col-sm-12" style="margin-top: 30px">
	
	@if ($errors->any())
    <div class="alert alert-danger">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
	@endif

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

	<div class="col-sm-6">
		<div class="row">
			<b> Unggah Tema Gambar </b>

			<form action="{{route('update_background')}}" 
			method="POST" enctype="multipart/form-data" 
			style="margin-top: 15px">
				{{ csrf_field() }}
			  	<div class="form-group">
				    <input type="file" class="form-control" name="background" required>
				</div>
			  	<button type="submit" class="btn btn-primary">
			  		Ganti Background
			  	</button>
			</form>
		</div>
	</div>

	<div class="col-sm-6">
		<img class="img-responsive img-thumbnail" 
		style="max-height: 300px" 
		src="{{URL::asset(DB::table('design')->pluck('logo')->first())}}"
		/>

		<div class="" style="margin-top: 7px">
			Gambar yang anda unggah akan tampil di halaman login sebagai background
		</div>
	</div>
</div>
@endsection