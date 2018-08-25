<!-- Modal -->
  <div class="modal fade" id="modal_edit_vendor" role="dialog">
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
  			<form method="POST" 
	  			action="{{ route('update_access_card') }}">
	  			  {{ csrf_field() }}
	  			<input type="hidden" id="edit_type_daftar" 
	  			name="type_daftar" value="">

	  			<input type="hidden" id="edit_uuid" 
	  			name="uuid" value="">

	  			 <div class="form-group">
  				    <label for="staff_nama"> Full Name :</label>
  				    <input type="text" class="form-control" id="edit_name" 
              			name="name" value=""
              		placeholder="full name is required" required="">
				</div>
				  
  				<div class="form-group">
  				    <label for="email">Email :</label>
  				    <input type="email" class="form-control" 
  				        id="edit_email" name="email" value=""
              		placeholder="email is required" required="">
  				</div>

	            <div class="form-group">
	              <label for="staff_nama"> NIK :</label>
	              <input type="text" class="form-control" id="edit_nik" 
	                    name="nik" value=""
	                  placeholder="NIK is required" required="">
	            </div>

	            <div class="form-group">
	              <label for="staff_divisi"> No. Access Card :</label>
	              <input type="text" class="form-control" 
	              id="edit_no_access_card" name="no_access_card" value=""
	              placeholder="Optional" required="">
	            </div>

				  <div class="form-group">
				    <label for="staff_nama"> Start Active Card :</label>
				    <input type="text" class="form-control datepicker_class" 
				    id="edit_date_start" name="date_start" value="" 
	      			placeholder="example : 2018-09-01" required="">
				  </div>

	            <div class="form-group">
	              <label for="staff_nama"> End Active Card :</label>
	              <input type="text" class="form-control datepicker_class" 
	              id="edit_date_end" name="date_end" value=""
	              placeholder="example : 2019-09-01" required="">
	            </div>

	            <div class="form-group" id="head_edit_pic_category">
  				    <label for="staff_nama"> Pic Category : </label>
  				    <input type="text" class="form-control" 
              		id="edit_pic_category"  
              		disabled="">
  				</div>

  				<div class="form-group" id="head_edit_floor">
  				    <label for="staff_nama"> Floor Activities : </label>
  				    <input type="text" class="form-control" 
              		id="edit_floor" name="floor" value="" 
              		placeholder="example : lantai 11,21" >
  				</div>

  				<div class="form-group" id="head_edit_division">
  				    <label for="staff_nama"> Division : </label>
  				    <input type="text" class="form-control" 
              		id="edit_division" name="divisi" value="" 
              		placeholder="">
  				</div>

  				<div class="form-group" id="head_edit_position">
  				    <label for="staff_nama"> Position : </label>
  				    <input type="text" class="form-control" 
              		id="edit_position" name="jabatan" value="" 
              		placeholder="">
  				</div>

  				<div class="form-group">
  				    <label for="staff_nama"> Additional Note : </label>
  				    <input type="text" class="form-control" 
              		id="edit_additional_note" name="additional_note" value="" 
              		placeholder="optional for add any information ">
  				</div>


				<button type="submit" class="btn btn-block btn-primary">
            		Update Access Card 
          		</button>
			</form>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
     </div> 
    </div>
  </div>



<script type="text/javascript">
	function edit(status,uuid) {
		$('#head_edit_pic_category').hide();
		$('#head_edit_floor').hide();
		$('#head_edit_division').hide();
		$('#head_edit_position').hide();

		var data = {
			"status":status,
	        "uuid":uuid
	        };

	    $.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});

		$.ajax({
			type : "POST",
			url: " {{ route('akses_get_info') }}",
			contentType: "application/json",
			data : JSON.stringify(data),
			success: function(result) {
				response = JSON.parse(result);
				if(response.status == true) {
					
					$('#edit_type_daftar').val(response.data.type_daftar);
					$('#edit_uuid').val(response.data.uuid);
					$('#edit_name').val(response.data.name);
					$('#edit_email').val(response.data.email);
					$('#edit_nik').val(response.data.nik);
					$('#edit_no_access_card').val(response.data.no_access_card);
					$('#edit_date_start').val(response.data.date_start);
					$('#edit_date_end').val(response.data.date_end);
					$('#edit_floor').val(response.data.floor);
					$('#edit_division').val(response.data.divisi);
					$('#edit_position').val(response.data.jabatan);
					$('#edit_additional_note').val(response.data.additional_note);

					if(response.data['type_daftar'] == "vendor") { 
						pic_list_detail = response.data['pic_list_vendor_name'] +
										   " ( " +
										   response.data['pic_list_vendor_detail_name'] +
										   " ) ";
						$('#edit_pic_category').val(pic_list_detail);				   
						$('#head_edit_pic_category').show();
						$('#head_edit_floor').show();
		
					} else if(response.data['type_daftar'] == "staff") {
						$('#head_edit_division').show();
						$('#head_edit_position').show();
					}

					$('#modal_edit_vendor').modal('show');
				} else {
					alert(response.message);
				}
			},
			error: function( jqXhr, textStatus, errorThrown ){
				console.log( errorThrown );
			}
		});

				
	}
</script>