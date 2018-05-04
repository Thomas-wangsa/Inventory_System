@extends('layouts.login')

@section('content')
    <div class="center_form" style="margin-top: 20px"> 
        <div class="text-center"> 
        Reset Password
        </div> 
        

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
            <a href="{{ route('login') }}">
                kembali ke login
            </a>
        </div>
    </div>
@endsection
