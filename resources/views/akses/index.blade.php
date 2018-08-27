@extends('layouts.template')

@section('content')
<style type="text/css">
	th,td {text-align: center}
	.table>tbody>tr>td,.table>thead>tr>th {vertical-align: middle}
	.scrollme {overflow-y: auto;}
	.conditional{
/*		display: none
*/	}
</style>
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
				<div class="btn btn-primary" data-toggle="modal" data-target="#modal_new_vendor">
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
				    	name="search_nama" placeholder="Find Name..."
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
				<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal_all">
					Add New Access Card
				</button>
				@endif
			</div>
			<div class="clearfix"> </div>

		</div>
		<div style="margin-top: 10px" class="scrollme"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			      	<th> Name </th>
			        <th > Email </th>
			        <th > NIK </th>
			        <th > No Access Card </th>
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
	                    <tr style="font-family: tahoma">
	                    	<td>
	                    		{{ ($data['data']->currentpage()-1) 
			    				* $data['data']->perpage() + $key + 1 }}
	                    	</td>
	                    	<td> 
	                    		{{$val->name}}
	                    	</td>
	                    	<td class="conditional"> 
	                    		{{$val->email}}
	                    	</td>
	                    	<td class="conditional"> 
	                    		{{$val->nik}}
	                    	</td>
	                    	<td> 
	                    		@if($val->no_access_card == null)
	                    			<div style="color:red">
	                    				New Access Card
	                    			</div> 
	                    		@else 
	                    			{{$val->no_access_card}}
	                    		@endif                    		
	                    	</td>
	                    	<td> 
	                    		<div style="color:{{$val->status_color}}"> 
	                    			{{$val->status_name}}
	                    		</div>
	                    	</td>
	                    	<td style="font-family: verdana"> 
	                    		@switch($val->status_akses)
	                    		@case("1")
	                    			<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
		                    			@if(in_array(1,$user_divisi)
		                    				||
		                    				$data['sponsor_access_data'][$key]
		                    				)
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(2,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick="edit('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Edit Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove(8,'{{$val->uuid}}')" 
		                    			>
		                    				Reject Access Card
		                    			</button>
		                    		</div>
	                    			@endif
	                    			@break
	                    		@case("2")
	                    			<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
		                    			@if(in_array(1,$user_divisi)
	                    				||
	                    				$data['staff_pendaftaran_data'] == true)	                    			
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(3,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick="edit('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Edit Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove(9,'{{$val->uuid}}')" 
		                    			>
		                    				Reject Access Card
		                    			</button>
		                    			@endif
	                    			</div>
	                    			
	                    			@break
	                    		@case("3")
	                    			<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
		                    			@if(in_array(1,$user_divisi)
		                    			||
		                    			$data['staff_pencetakan_data'] == true)
	                    			
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(4,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick="edit('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Edit Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove(10,'{{$val->uuid}}')" 
		                    			>
		                    				Reject Access Card
		                    			</button>
		                    			@endif
		                    		</div>
	                    			@break
	                    		@case("4")
	                    			<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
	                    			@if(in_array(1,$user_divisi)
	                    			||
	                    			$data['manager_pencetakan_data'] == true)
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(5,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick="edit('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Edit Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove(11,'{{$val->uuid}}')" 
		                    			>
		                    				Reject Access Card
		                    			</button>
		                    		@endif
	                    			</div>
	                    			@break
	                    		@case("5")
	                    			<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
		                    			@if(in_array(1,$user_divisi)
		                    			||
		                    			$data['staff_pengaktifan_data'] == true)
	                    			
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(6,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick="edit('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Edit Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove(12,'{{$val->uuid}}')" 
		                    			>
		                    				Reject Access Card
		                    			</button>
		                    			@endif
	                    			</div>
	                    			@break
	                    		@case("6")
	                    			<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
		                    			@if(in_array(1,$user_divisi)
		                    			||
		                    			$data['manager_pengaktifan_data'] == true)
	                    			
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(7,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-warning"
		                    			onclick="edit('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Edit Access Card
		                    			</button>
		                    			<button 
		                    			class="btn btn-danger"
		                    			onclick="remove(13,'{{$val->uuid}}')" 
		                    			>
		                    				Reject Access Card
		                    			</button>
		                    			@endif
	                    			</div>
	                    			@break
	                    		@case("7")
	                    			<div class="btn-group-vertical">
	                    				<button 
		                    			class="btn btn-info"
		                    			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
		                    			>
		                    				Info Access Card
		                    			</button>
	                    			</div>
	                    			@break
	                    		@case("8")
	                    		@case("9")
	                    		@case("10")
	                    		@case("11")
	                    		@case("12")
	                    		@case("13")
	                    			{{$val->comment}}
	                    			@break;
	                    		@default
	                    			Status Error
	                    			@break
	                    		@endswitch
	                    	</td>
	                    </tr>
	                    @endforeach
	                    
	                @endif
			    </tbody>
			</table>
		</div> <!--scrolme-->
		<div class="pull-right" style="margin-top: -20px!important"> 
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
  	
  	@include('akses.modal_new_vendor')
  	@include('akses.modal_info')
  	@include('akses.modal_edit_vendor')
	@include('akses.modal_all')
	


<script type="text/javascript">
	

	function approve(status,uuid) {
		if (confirm('Approve this request ?')) { 
			var url = window.location.protocol+"//"+window.location.host+'/akses_approval?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+status;
		}
	}


	function remove(status,uuid) {
		if (confirm('Reject this request ?')) {
			var url = window.location.protocol+"//"+window.location.host+'/akses_reject?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+status;
		}
	}


	function reset_filter() {
    	window.location = "{{route('akses')}}";
    }
</script>






@endsection

