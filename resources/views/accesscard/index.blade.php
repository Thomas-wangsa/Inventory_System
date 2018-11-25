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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
  $( function() {

    $(".datepicker_class" ).datepicker({
      dateFormat: 'yy-mm-dd' ,
      showButtonPanel: true
    });
  });
</script>


	<!--top position access card-->
	<div id="parent_access_card" style="margin-top: 40px">
		@include('accesscard.parent')
	</div> 
	<!--top position access card-->


	<!--body position access card-->
	<div id="body_access_card"> 
		@include('accesscard.add_request')
		@include('accesscard.add_category')
		@include('accesscard.add_filter')
		
		@include('accesscard.add_table')
	</div>
	<!--body position access card-->





@endsection

