@extends('layouts.login')

@section('content')
    <div class="center" style="margin-top: 20px"> 
            
        <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
          
          {{ csrf_field() }}
          
          <input type="hidden" name="token" value="{{ $token }}">

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="control-label col-xs-1" for="email" 
            style="padding-top: 5px!important">
              <div class="row">
                <span class="glyphicon glyphicon-envelope" 
                style="font-size: 25px;">
                </span>
              </div>
            </label>
            <div class="col-xs-11">
              <input type="email" class="form-control" id="email" placeholder="Email" 
              name="email" value="{{ $email or old('email') }}" required="required" autofocus>
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
                  <span class="glyphicon glyphicon-lock" style="font-size: 25px;"></span>
                </div>
              </label>
              <div class="col-xs-11">          
                <input type="password" class="form-control" id="pwd" placeholder="password" name="password" required="required">
                @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
              </div>
          </div>

          <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
              <label class="control-label col-xs-1" for="pwd"
              style="padding-top: 5px!important">
                <div class="row">
                  <span class="glyphicon glyphicon-lock" style="font-size: 25px;"></span>
                </div>
              </label>
              <div class="col-xs-11">          
                <input type="password" class="form-control" id="pwd" placeholder="password" name="password_confirmation" required="required">
                @if ($errors->has('password_confirmation'))
                  <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                  </span>
                @endif
              </div>
          </div>
          
          <div class="form-group">        
            <div class="col-sm-12">
              <button type="submit" class="btn btn-block btn-primary MASUK">
                Reset Password
              </button>
            </div>
          </div>
        
        </form>

        <div class="text-center" > 
            <a href="{{ route('login') }}" style="color:blue">
                back to login
            </a>
        </div>
    </div>
@endsection
