@extends('layouts.template')

@section('content')
<style type="text/css">
	th,td {text-align: center}
	.table>tbody>tr>td,.table>thead>tr>th {vertical-align: middle}
	.main_section {margin-top: 40px}
	.main_section_body{margin-top: 10px}
	.interactive_action{margin-top: 15px;margin-bottom: 10px}
</style>

	<div class="main_section">
		<div class="main_section_body">
			<table class="table table-bordered table-striped">
				<thead>
					<tr> 
						<th> No </th>
						<th> Status </th>
						<th> Additional Note </th>
						<th> Map Location </th>
					</tr>
				</thead>
				<tbody>
					@if(count($data['sub_data']) < 1)
					<tr> 
						<td colspan="10"> No Data Found! </td>
					</tr>
					@else
						<?php $no = 0;?>
						@foreach($data['sub_data'] as $key=>$val)
						<tr>
							<td> {{$no+1}}</td>
							<td> {{$val->sub_data_status}} </td>
							<td> {{$val->comment}}</td>
							<td> on progress</td>
						</tr>
						<?php $no++;?>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>



 
@endsection
