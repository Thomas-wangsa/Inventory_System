<div class="col-sm-6">
	<div class="row text-center">
	<form action="{{route('inventory_reject')}}">
		{{ csrf_field() }}
		<input type="hidden" name="uuid" value="{{$val->uuid}}" >
		<button type="submit" class="btn btn-danger">
			@if($val->status_inventory == 1)
				Tolak Nambah
			@elseif($val->status_inventory == 2)
				Tolak Permintaan
			@endif
		</button>
	</form>
	</div>
</div>

<div class="col-sm-6">
	<div class="row text-center">
	<form action="{{route('inventory_approval')}}">
	{{ csrf_field() }}
	<input type="hidden" name="uuid" value="{{$val->uuid}}" >
	<button type="submit" class="btn btn-success"> 
		@if($val->status_inventory == 1)
			Setuju Nambah
		@elseif($val->status_inventory == 2)
			Setuju Permintaan
		@endif  
	</button>
	</form>
	</div>
</div>

<div class="clearfix"> </div>