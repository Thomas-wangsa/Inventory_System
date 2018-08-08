<!-- Modal -->
  <div class="modal fade" id="modal_edit" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Edit Users
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" action="{{ route('edit_user') }}">
				  {{ csrf_field() }}
				  <input type="hidden" name="uuid" id="edit_uuid" value="">
				  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" id="edit_nama" 
				    name="staff_nama" value="Thomas" 
				    placeholder="Username" required="">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email :</label>
				    <input type="email" class="form-control" id="edit_email" 
				    name="staff_email" value="thomas@gmail.com" 
				    placeholder="User Email Default" required="">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email-2 :</label>
				    <input type="email" class="form-control" id="edit_email2" 
				    name="staff_email2" value="" 
				    placeholder="User Email Secondary">
				  </div>

				  <div class="form-group">
				    <label for="email"> No Handphone :</label>
				    <input type="text" class="form-control" id="edit_phone" name="staff_mobile" value="081222117788"
				    placeholder="User Phone">
				  </div>
				  
				  
				  <button type="submit" class="btn btn-block btn-warning">
				  Edit Users 
				  </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
     </div> 
    </div>
  </div>



<script type="text/javascript">
	function get_data_user(uuid) {
    	var data = {
    		"uuid":uuid
    	};

	    $.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		$.ajax({
			type : "POST",
			url: " {{ route('get_data_user') }}",
			contentType: "application/json",
			data : JSON.stringify(data),
			success: function(result) {
				var response = JSON.parse(result);
				$('#edit_nama').val(response.name);
				$('#edit_email').val(response.email);
				$('#edit_email2').val(response.email_2);
				$('#edit_phone').val(response.mobile);
				$('#edit_uuid').val(response.uuid);
 				$('#modal_edit').modal('show'); 
			},
			error: function( jqXhr, textStatus, errorThrown ){
				console.log( errorThrown );
			}
		})
    }
</script>