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


	<div style="margin-top: 10px" class="scrollme"> 	
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th> No </th>
		      	<th> 
		      		History Date
		      	</th>
		        <th> 
		        	View Data 
		        </th>
		      </tr>
		    </thead>
		    <tbody>
		    @if(count($data['history']) > 0)
		    	@foreach($data['history'] as $key=>$val)
		    	<?php $id_history = $val["id"];?>
		    	<tr>
		    		<td> {{$key+1}} </td>
		    		<td> {{$val['trigger_at']}} </td>
		    		<td> 
		    			<button class="btn btn-info" onclick='info("{{$id_history}}")'>
		    				view history
		    			</button>
		    		</td>
		    	</tr>
		    	@endforeach 
	    	@else
		    	<tr> <td colspan="10"> NO DATA FOUND! </td></tr> 
		    @endif
		    </tbody>
		</table>
	</div>

@endsection

@include('new_inventory.modal_history_info')