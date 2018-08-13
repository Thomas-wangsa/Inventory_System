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
	<div class="center" style="margin-top: 25px"> 
		<div class="text-center"> 
		please type this form <br/>
		to reset your password
		</div> 
		
		@if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div style="margin-bottom: 10px"> </div>
		<form class="form-horizontal" 
		method="POST" action="{{ route('password.email') }}">
			{{ csrf_field() }}
		    <div class="form-group">
		      <label class="control-label col-xs-1" 
		      for="email" 
		      style="padding-top: 5px!important">
		      	<div class="row">
		      		<span class="glyphicon glyphicon-envelope" 
		      		style="font-size: 23px;">
		      		</span>
		      	</div>
		      </label>
		      <div class="col-xs-11">
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
		        <button type="submit" class="btn btn-block btn-danger">
		        	Reset Password
		        </button>
		      </div>
		    </div>
		</form>

		<div class="text-center"> 
			<a href="{{ route('login') }}" style="color:blue">
				back to login
			</a>
		</div>
	</div>
@endsection
