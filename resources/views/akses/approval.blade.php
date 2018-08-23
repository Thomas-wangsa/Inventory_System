@extends('layouts.login')

@section('content')
	<div class="center_form" style="margin-top: 100px">
		<div class="text-center">
			Thank you for your time
		</div>
		<div class="text-center">
			Access card already approved
		</div>

		<a href="{{route('home')}}">
			<button type="submit" class="btn btn-block btn-danger" >	
			        Dashboard
			</button>
		</a>
	</div>
@endsection
