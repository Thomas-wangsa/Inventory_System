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
@include('accesscard.modal_extend_access_card')
@include('accesscard.modal_broken_access_card')
@include('accesscard.modal_lost_access_card')

<script type="text/javascript">
	function access_card_request_action() {
		value = $('#id_request_type').val();

		switch(value) {
			case "1" : 
        $('#modal_new_access_card').modal('show');;
				break;
      case "2" : 
        $('#modal_extend_access_card').modal('show');;
        break;
      case "3" : 
        $('#modal_broken_access_card').modal('show');;
        break;
      case "4" : 
        $('#modal_lost_access_card').modal('show');;
        break;
			default :
				alert("out of scope");
				break;
		}
		//alert(value)
	}
</script>


<script type="text/javascript">

  function set_photo(uuid) {
    alert("waiting for open smtm connection");
  }

  function set_access_card(name,uuid) {
    $('#modal_new_update_access_card_name').val(name);
    $('#modal_new_update_access_card_uuid').val(uuid);
    $('#modal_new_update_access_card').modal('show');
  }

  function check_admin_room(name,uuid) {
    $('#modal_new_set_admin_room_name').val(name);
    $('#modal_new_set_admin_room_uuid').val(uuid);
    $('#modal_new_set_admin_room').modal('show');
  }

  function approve(status,uuid) {
    if (confirm('Approve this request ?')) { 
      var url = "{{URL::to('/')}}"+'/akses_approval?uuid=';
      var url_status = "&next_status=";
      window.location = url+uuid+url_status+status;
    }
  }


  function remove(status,uuid) {
    if (confirm('Reject this request ?')) {
      var url = "{{URL::to('/')}}"+'/akses_reject?uuid=';
      var url_status = "&next_status=";
      window.location = url+uuid+url_status+status;
    }
  }
</script>