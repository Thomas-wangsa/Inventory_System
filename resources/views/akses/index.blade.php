@extends('layouts.template')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<div style="padding: 0 30px;margin-top: 40px">
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

		<div>
			<div class="pull-left">
			 	<form class="form-inline" action="{{route('route_admin')}}">
				    
				    <div class="input-group">
				    	<span class="input-group-addon">
				    		<i class="glyphicon glyphicon-search">
				    		</i>
				    	</span>
				    	<input type="text" class="form-control" 
				    	name="search_email" placeholder="Cari Nama...">
				  	</div>
					
					<div class="form-group">
				      	<select class="form-control" name="search_filter">
				      		<option value=""> Filter Berdasarkan </option>
				        	@foreach($data['status_akses'] as $key=>$val)
                            <option value="{{$val->id}}"> {{ucfirst($val->name)}}</option>
                            @endforeach  
				      	</select>
				  	</div>

				  	<button type="submit" class="btn btn-info"> Cari </button>
			  	</form> 
			</div>
			<div class="pull-right">

				@if(in_array(1,$data['jabatan']))
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#modal_self">
                    Daftar
                </button>
                @endif
                
                @if(in_array(1,$data['jabatan']) || in_array(2,$data['jabatan']))
                <button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#modal_vendor">
                    Daftarkan Vendor
                </button>
                @endif 

                @if(in_array(1,$data['jabatan']))
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#modal_staff">
					Daftarkan Staff
				</button>
				@endif
			</div>
			<div class="clearfix"> </div>

		</div>
		<div style="margin-top: 10px"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			        <th> Nama </th>
			        <th> Email </th>
			        <th> No /Period Card </th>
			        <th> Ref </th>
			        <th> Keterangan </th>
			        <th> Updated By </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
				    @if(count($data['data']) < 1)
	                    <tr>
	                        <td colspan="10" class="text-center"> No Data Found </td>
	                    </tr>
	                @else 
	                    <?php $no = 1; ?>
	                    @foreach($data['data'] as $key=>$val)
	                    <tr>
	                        <td> {{$no}}</td>
	                        <td> {{$val->name}}</td>
	                        <td> {{$val->email}}</td>
	                        <td> 
	                        	@if($val->type == 'self')
	                        		{{$val->no_card}}
	                        	@else
	                        		Expiry : {{$val->date_start}}
	                        				- {{$val->date_end}}
	                        	@endif
	                        </td>
	                        <td> Feature is on progres </td>
	                        <td> {{$val->comment}}</td>
	                        <td> {{$val->username}}</td>
	                        <td style="color:{{$val->status_color}}"> {{$val->status_name}}</td>
	                        <td>
                        		<span class="glyphicon glyphicon-check"
                        		onclick="approve('{{$val->uuid}}')"
                        		style="color:green">	
                        		</span>
                        		<span class="glyphicon glyphicon-edit" style="color:black">
                        		</span>
                        		<span class="glyphicon glyphicon-remove"
                        		onclick="remove('{{$val->uuid}}')" 
                        		style="color:red">
                        		</span>

	                        </td>

	                    </tr>
	                    <?php $no++;?>
	                    @endforeach
	                @endif
			    </tbody>
			</table>
			<div class="clearfix"> </div>
		</div>
	</div>
  	
  	@include('akses.modal_self')
    @include('akses.modal_vendor')
	@include('akses.modal_all');

<script type="text/javascript">
	
	function approve(uuid) {
		var url = window.location.protocol+"//"+window.location.host+'/akses_approval?uuid=';
		window.location = url+uuid;
	}


	function remove(uuid) {
		var url = window.location.protocol+"//"+window.location.host+'/akses_reject?uuid=';
		window.location = url+uuid;
	}

</script>



<script type="text/javascript">
	$(document).ready(function(){
		$("#vendor").hide();
	    $("#staff_main").css("border-bottom","3px solid #e1a435");
	    $("#vendor_main").css("border-bottom","1px solid #979797")

	    $("#staff_main").click(function(){
	        $("#staff").show();
	        $("#vendor").hide();
	        $("#staff_main").css("border-bottom","3px solid #e1a435");
	        $("#vendor_main").css("border-bottom","1px solid #979797");
	    });

	    $("#vendor_main").click(function(){
	        $("#staff").hide();
	        $("#vendor").show();
	        $("#vendor_main").css("border-bottom","3px solid #e1a435");
	        $("#staff_main").css("border-bottom","1px solid #979797");
	    });
	});
</script>


@endsection

