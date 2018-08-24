@extends('layouts.login')

@section('content')
<style type="text/css">

textarea {
    resize: none;
}
</style>
	<div class="center_form" style="margin-top: 30px">
		<div class="text-center">
			<div class="text-danger" style="font-weight: bold">
				Please input the reason
			</div> 

			<div style="margin-top: 10px"> </div>

			<div style="width: 100%" style="background-color: blue">
				<form method="POST" action="{{route('proses_reject')}}">
					{{ csrf_field() }}{{ csrf_field() }}
					<input type="hidden" name="uuid" value="{{$data['uuid']}}">
					
					<div class="form-group">
					<textarea rows="6" cols="50" name="desc" required autofocus placeholder="at least 20 character"></textarea>
					</div>

					<div class="text-center">
						<button type="submit" class="btn btn-danger"
						style="width: 300px">	
				        REJECT 
						</button>
					</div>
					
					<div class="clearfix"> </div>
				</form>
			</div>
		</div>


		

		
	</div>
@endsection
