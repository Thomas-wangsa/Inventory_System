@extends('layouts.login')

@section('content')
	<div class="center_form" style="background-color: yellow;"> 
		<div class="text-center"> 
		Silahkan isi form di bawah ini dan kami akan mengirimkan
		</div> 
		
		<div class="text-center">
		tautan untuk reset password anda
		</div>

		<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
			{{ csrf_field() }}
		    <div class="form-group">
		      <label class="control-label col-sm-2" for="email">
		      	<span class="glyphicon glyphicon-envelope" style="font-size: 30px;"></span>
		      </label>
		      <div class="col-sm-10">
		        <input type="email" class="form-control" id="email" placeholder="Email" name="email" required="required">
		      </div>
		    </div>

		    <div class="form-group">        
		      <div class="col-sm-12">
		        <button type="submit" class="btn btn-block btn-danger">Reset Password</button>
		      </div>
		    </div>
		</form>

		<div class="text-center"> 
			<a href="{{ route('login') }}">
				kembali ke login
			</a>
		</div>
	</div>
@endsection
