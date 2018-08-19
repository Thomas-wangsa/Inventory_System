<!-- Modal -->
  <div class="modal fade" id="modal_edit" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Update Users
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" action="{{ route('edit_user') }}">
				  {{ csrf_field() }}
				  <input type="hidden" name="uuid" id="edit_uuid" value="">
				  <div class="form-group">
				    <label for="staff_nama"> Name :</label>
				    <input type="text" class="form-control" id="edit_nama" 
				    name="name" value="" required="" 
				    placeholder="Only letters and whitespace allowed...">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> NIK :</label>
				    <input type="text" class="form-control" id="edit_nik" 
				    name="nik" value="" required="" 
				    placeholder="NIK is required">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email Default :</label>
				    <input type="email" class="form-control" id="edit_email" 
				    name="email" value="" required="" 
				    placeholder="example@example.com">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email Secondary :</label>
				    <input type="email" class="form-control" id="edit_email2" 
				    name="email_second" value=""  
				    placeholder="example@example.com (Optional)">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Mobile :</label>
				    <input type="text" class="form-control" id="edit_phone" 
				    name="mobile" value="" required="" 
				    placeholder="Only numbers allowed...">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Company :</label>
				    <input type="text" class="form-control" id="edit_company" 
				    name="company" value="" required="" 
				    placeholder="Company name is required">
				  </div>
				  
				  
				  <button type="submit" class="btn btn-block btn-warning">
				  Update Users
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
				$('#edit_nik').val(response.nik);
				$('#edit_email').val(response.email);
				$('#edit_email2').val(response.email_2);
				$('#edit_phone').val(response.mobile);
				$('#edit_uuid').val(response.uuid);
				$('#edit_company').val(response.company);
 				$('#modal_edit').modal('show'); 
			},
			error: function( jqXhr, textStatus, errorThrown ){
				console.log( errorThrown );
			}
		})
    }
</script>