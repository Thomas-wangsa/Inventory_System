@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
		<div >
			<div class="pull-left"> asa </div>
			<div class="pull-right">  
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#myModal">
					Tambah Barang
				</button>
			</div>
			<div class="clearfix"> </div>

		</div>
		<div style="margin-top: 10px"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			        <th> Nama Barang </th>
			        <th> Kategori </th>
			        <th> Jumlah </th>
			        <th> Updated By </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			    	@if(count($data['inventory_data']) == 0) 
					<td colspan="7" class="text-center"> Kosong </td>			    	
					@else
						@foreach($data['inventory_data'] as $key=>$val)
						<tr> 
							<td> {{$key+1}}</td>
							<td> {{$val->inventory_sub_list_name}}</td>
							<td> {{$val->inventory_name}}</td>
							<td> {{$val->count}}</td>
							<td> {{$val->username}}</td>
							<td> {{$val->status_name}}</td>
							<td>
								@switch($val->status_inventory)
									@case(1) 
										<form method="POST" action="{{route('post_approve_by_head')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Setuju  </button>
			    						</form>
			    						@break
			    					@case(2)
			    						<form method="POST" action="{{route('post_approve_by_admin')}}">
			    							{{ csrf_field() }}
			    							<input type="hidden" name="data_id" value="{{$val->id}}" >
			    							<button type="submit" class="btn btn-warning"> Setuju  </button>
			    						</form>
	    						@endswitch
							</td>
						</tr>
						@endforeach 
					

			    	@endif

			    </tbody>
			</table>
		</div>
	</div>

	@include('inventory.modal');
@endsection