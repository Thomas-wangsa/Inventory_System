@extends('layouts.template')

@section('content')
<style type="text/css">
	th,td {text-align: center}
	.table>tbody>tr>td,.table>thead>tr>th {vertical-align: middle}
	.main_section {margin-top: 40px}
</style>

	<div class="main_section">	

		<div class="main_section_information">
			<strong> Grouping : </strong> {{$data['new_inventory_data']->grouping_detail}} 
			<br/>
			<strong> Inventory Name : </strong> {{$data['new_inventory_data']->inventory_name}} 
			<br/>
			<strong> Quantity : </strong> {{$data['new_inventory_data']->qty}} 
			<br/>
		</div>


	</div>
  


@endsection
