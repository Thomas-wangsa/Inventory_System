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
	                	@if($val->request_type == 1)
	                		@include('accesscard.conditional_new_access_card')
	                	@elseif($val->request_type == 2)
	                		@include('accesscard.conditional_extend_access_card')
	                	@elseif($val->request_type == 3)
	                		@include('accesscard.conditional_new_access_card')
	                	@elseif($val->request_type == 4)
	                		@include('accesscard.conditional_new_access_card')
	                	@elseif($val->request_type == 5)
	                		@switch($val->register_type)
	                			@case("1")
	                				@include('accesscard.conditional_leveling_access_card_permanent')
	                				@break
	                			@case("2")
	                				@include('accesscard.conditional_leveling_access_card_non_permanent')
	                				@break
	                			@default
	                				@break
	                		@endswitch
	                	@endif
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
		'request_type_filter' => Request::get('request_type_filter'),
		'search_pic' => Request::get('search_pic'),
		'search_filter' => Request::get('search_filter'),
		'search_order' => Request::get('search_order')
		])->links() }}

</div>
<div class="clearfix"> </div>

@include('accesscard.modal_new_update_access_card')
@include('accesscard.modal_new_set_admin_room')
@include('akses.modal_deactive')
@include('accesscard.modal_info')

