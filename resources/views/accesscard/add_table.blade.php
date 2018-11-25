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
	                	@include('accesscard.conditional_new_access_card')
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

