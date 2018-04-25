@extends('layouts.login')

@section('content')
	<div class="center_form" style="background-color: yellow;"> 
		<form class="form-horizontal" method="POST" action="{{ route('login') }}">
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
		      <label class="control-label col-sm-2" for="pwd">
		      	<span class="glyphicon glyphicon-envelope" style="font-size: 30px;"></span>
		      </label>
		      <div class="col-sm-10">          
		        <input type="password" class="form-control" id="pwd" placeholder="password" name="password" required="required">
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
		        <button type="submit" class="btn btn-block btn-danger">Submit</button>
		      </div>
		    </div>
		</form>

		<div class="text-center"> 
			<a href="{{ route('password.request') }}">
				lupa password ? 
			</a>
		</div>
	</div>
@endsection
