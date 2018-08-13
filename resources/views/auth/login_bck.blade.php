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
		    
		    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		      <label class="control-label col-sm-2" for="email" 
		      style="padding-top: 5px!important">
		      	<div class="row">
			      	<span class="glyphicon glyphicon-envelope" 
			      	style="font-size: 25px;">
			      		
			      	</span>
		      	</div>
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
		    
		    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		      	<label class="control-label col-sm-2" for="pwd"
		      	style="padding-top: 5px!important">
		      		<div class="row">
		      			<span class="glyphicon glyphicon-lock" style="font-size: 25px;"></span>
	      			</div>
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

		    <div class="form-group" >
		    	<label class="control-label col-sm-2" for="pwd"
		      	style="padding-top: 8px!important">
		      		<div class="row">
		      			<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
	      			</div>
	      		</label>
		      	<div class="col-sm-10">
		        	<div class="checkbox">
		          		Remember me
		        	</div>
		      	</div>
		    </div>
		    <div class="form-group">        
		      <div class="col-sm-12">
		        <button type="submit" class="btn btn-block btn-danger MASUK" 
		        style="background-color: red">	
		        	Masuk
		        </button>
		      </div>
		    </div>
		</form>

		<div class="text-center"> 
			<a href="{{ route('password.request') }}" style="color:red">
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
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
          @if(Session::has('alert-' . $msg))
          	<div style="margin-top: 30px">
				<div class="text-center" style="color:red;border: 1px solid red;padding: 10px">
				{{ Session::get('alert-' . $msg) }}
			</div>
          @endif
        @endforeach
	</div>
@endsection
