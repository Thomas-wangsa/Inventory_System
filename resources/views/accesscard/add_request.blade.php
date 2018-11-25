<div class="pull-left" style="margin-right: 10px">
	<div class="form-group">
      	<select class="form-control" id="id_request_type">
        	@foreach($data['request_type'] as $key=>$val)
    		<option value="{{$val->id}}" 
    			> 
    			{{$val->request_name}}
    		</option>
    		@endforeach 
      	</select>
  	</div>
</div>
<div class="pull-left">
  	<button type="submit" 
  	class="btn btn-primary"
  	onclick="access_card_request_action()"> 
  		Access Card Request 
  	</button>
</div>
<div class="clearfix"> </div>

@include('accesscard.modal_new_access_card')

<script type="text/javascript">
	function access_card_request_action() {
		value = $('#id_request_type').val();

		switch(value) {
			case "1" : $('#modal_new_access_card').modal('show');;
				break;
			default :
				alert("out of scope");
				break;
		}
		//alert(value)
	}
</script>