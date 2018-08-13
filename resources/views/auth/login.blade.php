@extends('layouts.login')


@section('content')

<div class="center" style="margin-top: 30px">
	<form class="form-horizontal" method="POST" action="{{ route('login') }}">	
		{{ csrf_field() }}
	    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
	      <label class="control-label col-xs-1" for="email" 
	      style="padding-top: 5px!important">
	      	<div class="row">
		      	<span id="email_glyphicon" 
		      	class="glyphicon glyphicon-envelope" 
		      	style="font-size: 23px;">
		      	</span>
	      	</div>
	      </label>
	      <div class="col-xs-11">
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
	      	<label class="control-label col-xs-1" for="pwd"
	      	style="padding-top: 5px!important">
	      		<div class="row">
	      			<span id="password_glyphicon"
	      			class="glyphicon glyphicon-lock" 
	      			style="font-size: 23px;">
	      			</span>
      			</div>
      		</label>
	      	<div class="col-xs-11" >          
	        	<input type="password" class="form-control" id="pwd" placeholder="Password" name="password" required="required">
		        @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
	      	</div>
	    </div>

	    <div class="form-group" >
	    	<label class="control-label col-xs-1" for="pwd"
	      	style="padding-top: 8px!important">
	      		<div class="row">
	      			<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
      			</div>
      		</label>
	      	<div class="col-xs-11">
	        	<div class="checkbox">
	          		Remember me
	        	</div>
	      	</div>
	    </div>
	    <div class="form-group">        
	      <div class="col-sm-12 col-xs-12">
	        <button type="submit" 
	        class="btn btn-block btn-danger" >	
	        	Log In
	        </button>
	      </div>
	    </div>
	</form>

	<div class="text-center"> 
		<a href="{{ route('password.request') }}" style="color:blue">
			forgot password ? 
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
