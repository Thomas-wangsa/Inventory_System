<div class="scrollme"> 
	<table class="table table-bordered">
	    <thead>
	      <tr>
	      	<th> No </th>
	      	<th> Request </th>
	      	<th> Type </th>
	      	<th> Name </th>
	        <th> Email </th>
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
                @foreach($data['data'] as $key=>$val) 
                <tr style="font-family: tahoma">
                	<td>
                		{{ ($data['data']->currentpage()-1) 
	    				* $data['data']->perpage() + $key + 1 }}
                	</td>
                	<td> 
                		{{$val->request_name}}
                	</td>
                	<td> 
                		{{$val->register_name}}
                	</td>
                	<td> 
                		{{$val->name}}
                	</td>
                	<td class="conditional"> 
                		{{$val->email}}
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
		                    			class="btn btn-danger"
		                    			onclick="remove(10,'{{$val->uuid}}')" 
		                    			>
		                    				Reject Access Card
		                    			</button>
		                    		@endif
	                    		</div>
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
		                    				$data['verification'] == true
		                    				)
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(3,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
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
		                    				$data['approval_verification'] == true
		                    				)
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(4,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
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
		                    				$data['card_printing'] == true
		                    				)
		                    			<button 
		                    			class="btn btn-primary"
		                    			onclick="approve(5,'{{$val->uuid}}')"
		                    			>
		                    				Approve Access Card
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
	                	@endswitch
	                </td>
	            </tr>
	            @endforeach
	        @endif 
	    </tbody>
	</table>
</div> <!--scroll me-->

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

@include('accesscard.modal_info')

<script type="text/javascript">
	function approve(status,uuid) {
		if (confirm('Approve this request ?')) { 
			var url = "{{URL::to('/')}}"+'/akses_approval?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+status;
		}
	}


	function remove(status,uuid) {
		if (confirm('Reject this request ?')) {
			var url = "{{URL::to('/')}}"+'/akses_reject?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+status;
		}
	}
</script>