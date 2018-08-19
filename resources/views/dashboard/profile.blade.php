@extends('layouts.template')

@section('content')
	<div style="padding: 0 20px;margin-top: 40px">
		@if ($errors->any())
	    <div class="alert alert-danger">
	    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
		@endif

		<div class="flash-message center">
		    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
		      @if(Session::has('alert-' . $msg))

		      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} 
		        <a href="#" class="close" data-dismiss="alert" aria-label="close">
		            &times;
		        </a>
		     </p>
		      @endif
		    @endforeach
		</div> <!-- end .flash-message -->

		
		<div class="center" style="background-color: red">
			<div class="col-xs-6">
				<img class="img-responsive img-thumbnail" 
				style="max-height: 300px" 
				src="{{ URL::asset($user_detail->foto) }}"
				/>

				<div class="" style="margin-top: 7px">
					<b> Upload Profile image </b>

					<form action="{{route('ganti_foto')}}" 
					method="POST" enctype="multipart/form-data" 
					style="margin-top: 15px">
						{{ csrf_field() }}
						<input type="hidden" name="user_uuid" 
						value="{{$user_detail->uuid}}">
					  	<div class="form-group">
						    <input type="file" class="form-control" name="background" required>
						</div>
					  	<button type="submit" class="btn btn-primary btn-block">
					  		Updated Profile
					  	</button>
					</form>
				</div>
			</div>
		</div>

		<div class="center" style="background-color: red">
			<div class="col-xs-6">
				
				<div class="center" >
					<form action="{{route('ganti_profile')}}" method="POST">
						{{ csrf_field() }}
					    <div class="form-group">
					      <label for="pwd"> Name :</label>
					      <input type="text" class="form-control" id="pwd"  name="nama_lengkap" value="{{$user_detail->name}}" required="">
					    </div>



					    <div class="form-group">
					      <label for="pwd">Position:</label>

					      @foreach($user_jabatan as $key=>$val)
					      <input type="text" class="form-control" id="pwd"  name="posisi" value="{{$val->nama_jabatan}}" disabled="">
					      	<?php if(	count($user_jabatan) > 1
					      				&& $key != (count($user_jabatan)-1) 
					      			) 
					      		{ 
					      	?>
					      		<br/>
					      	<?php
					      		 } 
					      	?>
					      
					      @endforeach
					    </div>

					    <div class="form-group">
					      <label for="pwd"> Email :</label>
					      <input type="email" class="form-control" id="pwd"  name="email" value="{{$user_detail->email}}" disabled="">
					    </div>

					    <div class="form-group">
					      <label for="pwd"> Mobile :</label>
					      <input type="text" class="form-control" id="pwd"  name="phone" value="{{$user_detail->mobile}}" required="">
					    </div>

					    <div class="text-center">
					    <button type="submit" class="btn btn-warning btn-block">
					    	Update Data
					    </button>
						</div>

					
				  	</form>
			  	</div>
				
			</div>
		</div>
	</div>
@endsection