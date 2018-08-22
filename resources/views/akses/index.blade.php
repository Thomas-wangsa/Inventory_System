@extends('layouts.template')

@section('content')

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
			@if(in_array(1,$user_divisi) || in_array(3,$user_setting))
			<div class="pull-left">
				<div class="btn btn-primary" data-toggle="modal" data-target="#modal_self">
					Add New Vendor List 
				</div>
			</div>
			<div class="clearfix" style="margin-bottom: 10px"> </div>
			@endif
			<div class="pull-left">
			 	<form class="form-inline" action="{{route('akses')}}">
				    
				    <input type="hidden" name="search" value="on"> </input>
				    <div class="input-group">
				    	<span class="input-group-addon">
				    		<i class="glyphicon glyphicon-search">
				    		</i>
				    	</span>
				    	<input type="text" class="form-control" 
				    	name="search_nama" placeholder="Find Access..."
				    	value="{{Request::get('search_nama')}}">
				  	</div>
					
					<div class="form-group">
				      	<select class="form-control" name="search_filter">
				      		<option value=""> Filter  </option>
				        	@foreach($data['status_akses'] as $key=>$val)
				    		<option value="{{$val->id}}" 
				    			@if($val->id == Request::get('search_filter')) 
				    				selected
				    			@endif
				    			> 
				    			{{ucfirst($val->name)}}
				    		</option>
				    		@endforeach 
				      	</select>
				  	</div>

				  	<div class="form-group">
				      	<select class="form-control" name="search_order">
				      		<option value=""> Sort  </option>
				        	<option value="name"
				        		@if('name' == Request::get('search_order')) 
				    				selected
				    			@endif
				    			> 
				        		Name
				        	</option>
				        	<option value="email"
				        		@if('email' == Request::get('search_order')) 
				    				selected
				    			@endif
				        		> 
				        		Email 
				        	</option>
				      	</select>
				  	</div>
				  
				  	<button type="submit" class="btn btn-info"> Find </button>
				  	<button type="reset" 
				  	class="btn btn-danger"
				  	onclick="reset_filter()"> 
				  		Reset 
				  	</button>
			  	</form>
			</div>
			<div class="pull-right">


                @if($data['insert_access_data'])
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#modal_all">
					Daftar Akses
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
	                        <td> 

	                        	{{ ($data['data']->currentpage()-1) 
			    				* $data['data']->perpage() + $key + 1 }}
	                        </td>
	                        <td> {{$val->name}}</td>
	                        <td> {{$val->email}}</td>
	                        <td> 
	                        	@if($val->type == 'self' || $val->type == 'staff')
	                        		{{$val->no_card}}
	                        	@else
	                        		Expiry : {{$val->date_start}}
	                        				- {{$val->date_end}}
	                        	@endif
	                        </td>
	                        <td> 
			    				<a href="{{$val->foto}}" target="_blank" >
			    					<img src="{{$val->foto}}"/ width="80px"> 
			    				</a>
			    				@if($val->type == 'vendor')
			    				<div style="margin-top: 5px"> </div>
			    				<a href="{{$val->po}}" target="_blank" >
			    					<img src="{{$val->po}}"/ width="80px"> 
			    				</a>
			    				@endif

	                        </td>
	                        <td> {{$val->comment}}</td>
	                        <td> {{$val->username}}</td>
	                        <td style="color:{{$val->status_color}}"> {{$val->status_name}}</td>
	                        <td>
	                        	@if($val->status_akses == 1) 
	                        		<?php 
	                        		if(count($data['jabatan']) == 1 && in_array(1, $data['jabatan'])) {
	                        			echo "Pending For Staff Pendaftaran Action";
	                        		} else if(in_array(2, $data['jabatan'])) { ?>
	                        			<div class="btn-group-vertical">
										  <button type="button" class="btn btn-primary"
										  onclick="approve(2,'{{$val->uuid}}')">
										  	Setuju Daftar
										  </button>
										  <button type="button" class="btn btn-info hidden" onclick="edit()">
										  	Edit Daftar
										  </button>
										  <button type="button" class="btn btn-danger"
										  onclick="remove(5,'{{$val->uuid}}')">
										  	Tolak Daftar
										  </button>
										</div>
	                        		<?php } ?>	
	                        	@endif
	                        	@if($val->status_akses == 2) 
	                        		<?php 
	                        		if(count($data['jabatan']) == 1 && in_array(2, $data['jabatan'])) {
	                        			echo "Pending For Staff Pencetakan Action";
	                        		} else if(in_array(3, $data['jabatan'])) { ?>
	                        			<div class="btn-group-vertical">
										  <button type="button" class="btn btn-primary"
										  onclick="approve(3,'{{$val->uuid}}')">
										  	Setuju Cetak
										  </button>
										  <button type="button" class="btn btn-info hidden" onclick="edit()">
										  	Edit Cetak
										  </button>
										  <button type="button" class="btn btn-danger"
										  onclick="remove(6,'{{$val->uuid}}')">
										  	Tolak Cetak
										  </button>
										</div>
	                        		<?php } ?>	
	                        	@endif
	                        	@if($val->status_akses == 3) 
	                        		<?php 
	                        		if(count($data['jabatan']) == 1 && in_array(3, $data['jabatan'])) {
	                        			echo "Pending For Staff Pengaktifan Action";
	                        		} else if(in_array(4, $data['jabatan'])) { ?>
	                        			<div class="btn-group-vertical">
										  <button type="button" class="btn btn-primary"
										  onclick="approve(4,'{{$val->uuid}}')">
										  	Setuju Aktif
										  </button>
										  <button type="button" class="btn btn-info hidden" onclick="edit()">
										  	Edit Kartu
										  </button>
										  <button type="button" class="btn btn-danger"
										  onclick="remove(7,'{{$val->uuid}}')">
										  	Tolak Aktif
										  </button>
										</div>
	                        		<?php } ?>	
	                        	@endif
	                        	@if($val->status_akses == 4) 
	                        		Kartu Aktif	
	                        	@endif
     <!--                    		<span class="glyphicon glyphicon-check"
                        		onclick="approve('{{$val->uuid}}')"
                        		style="color:green">	
                        		</span>
                        		<span class="glyphicon glyphicon-edit" style="color:black">
                        		</span>
                        		<span class="glyphicon glyphicon-remove"
                        		onclick="remove('{{$val->uuid}}')" 
                        		style="color:red">
                        		</span> -->

	                        </td>

	                    </tr>
	                    <?php $no++;?>
	                    @endforeach
	                @endif
			    </tbody>
			</table>
			<div class="pull-right" style="margin-top: -30px!important"> 
			{{ $data['data']->appends(
				[
				'search' => Request::get('search'),
				'search_nama' => Request::get('search_nama'),
				'search_filter' => Request::get('search_filter'),
				'search_order' => Request::get('search_order')
				])->links() }}
	
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
  	
	@include('akses.modal_all')
	@include('akses.modal_self')


<script type="text/javascript">
	

	function approve(status,uuid) {
		var url = window.location.protocol+"//"+window.location.host+'/akses_approval?uuid=';
		var url_status = "&next_status=";
		window.location = url+uuid+url_status+status;
	}


	function remove(status,uuid) {
		var url = window.location.protocol+"//"+window.location.host+'/akses_reject?uuid=';
		var url_status = "&next_status=";
		window.location = url+uuid+url_status+status;
	}

	function edit() {
		alert('on_progress');
	}

	function reset_filter() {
    	window.location = "{{route('akses')}}";
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

