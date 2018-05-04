@extends('layouts.login')

@section('content')

	<style type="text/css">
		.RESET-PASSWORD {
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
	<div class="center_form" style="margin-top: 20px"> 
		<div class="text-center"> 
		Silahkan isi form di bawah ini dan kami akan mengirimkan
		</div> 
		
		<div class="text-center" style="margin-bottom: 10px">
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

		        @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
		      </div>
		    </div>

		    <div class="form-group">        
		      <div class="col-sm-12">
		        <button type="submit" class="btn btn-block btn-danger RESET-PASSWORD" style="background-color: red">Reset Password</button>
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
