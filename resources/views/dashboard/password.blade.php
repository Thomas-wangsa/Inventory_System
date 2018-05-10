@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
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

		
		<div class="center" >
			<div class="center" >
				<div class="center" >
					<form action="{{route('post_password')}}" method="POST">
						{{ csrf_field() }}
					    <div class="form-group">
					      <label for="pwd">Password Lama:</label>
					      <input type="password" class="form-control" id="pwd"  name="now_password" required="">
					    </div>

					    <div class="form-group">
					      <label for="pwd">Password Baru:</label>
					      <input type="password" class="form-control" id="pwd"  name="password" required="">
					    </div>

					    <div class="form-group">
					      <label for="pwd">Ketik Ulang Password :</label>
					      <input type="password" class="form-control" id="pwd"  name="password_confirmation" required="">
					    </div>

					    <div class="text-center">
					    <button type="submit" class="btn btn-warning">
					    	UBAH PASSWORD
					    </button>
						</div>
				  	</form>
			  	</div>
			</div>
		</div>
	</div>
@endsection