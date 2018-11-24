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
	<!--top position access card-->
	<div id="parent_access_card" style="margin-top: 40px">
		@include('accesscard.parent')
	</div> 
	<!--top position access card-->


	<!--body position access card-->
	<div id="body_access_card"> 
		@include('accesscard.add_category')
		@include('accesscard.add_filter')
		
			  	
	</div>
	<!--body position access card-->





@endsection

