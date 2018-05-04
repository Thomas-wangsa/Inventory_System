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

.Rectangle_Error {
  width: 307px;
  height: 82px;
  border-radius: 5px;
  background-color: #ffffff;
  border: solid 1px #ee1d24;
  border: solid 1px var(--pinkish-red);
}

.Maaf-email-dan-pass {
  width: 285px;
  height: 63px;
  font-family: Roboto;
  font-size: 13px;
  font-weight: normal;
  font-style: normal;
  font-stretch: normal;
  line-height: 1.62;
  letter-spacing: normal;
  text-align: left;
  color: #ee1d24;
  color: var(--pinkish-red);
}



	</style>
	<div class="center_form" style="margin-top: 20px"> 
		<form class="form-horizontal" method="POST" action="{{ route('login') }}">
			{{ csrf_field() }}
		    <div class="form-group">
		      <label class="control-label col-sm-2" for="email">
		      	<span class="glyphicon glyphicon-envelope" style="font-size: 30px;"></span>
		      </label>
		      <div class="col-sm-10">
		        <input type="email" class="form-control" id="email" placeholder="Email" 
		        name="email" value="{{ old('email') }}" required="required" autofocus>
		        @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
		      </div>
		    </div>
		    <div class="form-group">
		      <label class="control-label col-sm-2" for="pwd">
		      	<span class="glyphicon glyphicon-envelope" style="font-size: 30px;"></span>
		      </label>
		      <div class="col-sm-10">          
		        <input type="password" class="form-control" id="pwd" placeholder="password" name="password" required="required">
		        @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
		      </div>
		    </div>
		    <div class="form-group">        
		      <div class="col-sm-10" style="padding-left: 30px">
		        <div class="checkbox">
		          <label><input type="checkbox" name="remember"> Remember me</label>
		        </div>
		      </div>
		    </div>
		    <div class="form-group">        
		      <div class="col-sm-12">
		        <button type="submit" class="btn btn-block btn-danger MASUK" style="background-color: red">Masuk</button>
		      </div>
		    </div>
		</form>

		<div class="text-center"> 
			<a href="{{ route('password.request') }}">
				lupa password ? 
			</a>
		</div>


		@if ($errors->has('email'))
			<div style="margin-top: 30px">
				<div class="text-center" style="color:red;border: 1px solid red;padding: 10px">
				Maaf email dan password anda belum terdaftar <br/>
				Silahkan kontak administrator kami jika <br/>
				Anda mengalami gangguan
			</div>
        @endif
	</div>
@endsection
