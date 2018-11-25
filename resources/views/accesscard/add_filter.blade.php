<div class="pull-left">
	<form class="form-inline" action="{{route('accesscard')}}">
		<input type="hidden" name="search" value="on">
		<div class="input-group">
	    	<span class="input-group-addon">
	    		<i class="glyphicon glyphicon-search">
	    		</i>
	    	</span>
	    	<input type="text" class="form-control" 
	    	name="search_nama" placeholder="Find Name..."
	    	value="{{Request::get('search_nama')}}">
	  	</div> 
	  	<div class="form-group">
	      	<select class="form-control" name="search_filter">
	      		<option value=""> Filter  </option>
	        	@foreach($data['status_akses'] as $key=>$val)
	    		<option value="{{$val->id}}" 
	    			@if($val->id == Request::get('search_filter')) 
	    				selected
	    			@endif
	    			> 
	    			{{ucfirst($val->name)}}
	    		</option>
	    		@endforeach 
	      	</select>
	  	</div>
	  	<div class="form-group">
	      	<select class="form-control" name="search_order">
	      		<option value=""> Sort  </option>
	        	<option value="name"
	        		@if('name' == Request::get('search_order')) 
	    				selected
	    			@endif
	    			> 
	        		Name
	        	</option>
	        	<option value="email"
	        		@if('email' == Request::get('search_order')) 
	    				selected
	    			@endif
	        		> 
	        		Email 
	        	</option>
	      	</select>
	  	</div>
	  	<button type="submit" class="btn btn-info"> Filter </button>
	  	<button type="reset" 
	  	class="btn"
	  	onclick="reset_filter()"> 
	  		Clear Filter 
	  	</button>
	</form>
</div>

<div class="clearfix" style="margin-bottom: 20px"> </div>

<script type="text/javascript">
	function reset_filter() {
    	window.location = "{{route('accesscard')}}";
    }
</script>