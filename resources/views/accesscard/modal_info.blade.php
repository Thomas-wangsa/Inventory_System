<style type="text/css">
	tbody #info_tbody tr th {background-color: red} 
</style>

<!-- Modal -->
  <div class="modal fade" id="modal_info" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center" >
          	Detail information access card
          </h4>
        </div>
        <div class="modal-body">
  			<div class="panel panel-primary">
		      <div class="panel-heading text-center">
		      	Basic Information
		      </div>
		      	<div class="panel-body">
			      	<div class="table-responsive">          
					 	<table class="table 
					 	table-condensed table-hover table-bordered table-striped">
						    <tbody id="info_tbody">
						      <tr class="info">
						        <th> request </th>
						        <td id="modal_info_request_type">  </td>
						      </tr>
						      <tr>
						        <th> register </th>
						        <td id="modal_info_register_type">  </td>
						      </tr>		
						      <tr>
						        <th> name </th>
						        <td id="modal_info_name">  </td>
						      </tr>
						      <tr>
						        <th> email </th>
						        <td id="modal_info_email">  </td>
						      </tr>
						      <tr>
						        <th> nik </th>
						        <td id="modal_info_nik">  </td>
						      </tr>
						      <tr class="info">
						        <th> no access card </th>
						        <td id="modal_info_no_access_card">  </td>
						      </tr>
						      <tr>
						        <th> start access card active </th>
						        <td id="modal_info_date_start">  </td>
						      </tr>
						      <tr>
						        <th> end access card active </th>
						        <td id="modal_info_date_end">  </td>
						      </tr>	
						<!--       <tr class="info">
						        <th> type </th>
						        <td id="modal_info_type_daftar">  </td>
						      </tr> -->
						      <tr id="head_modal_info_pic_list">
						        <th> pic category </th>
						        <td id="modal_info_pic_list">  </td>
						      </tr>
						      <tr id="head_modal_info_admin_room_list">
						        <th> admin room </th>
						        <td id="modal_info_admin_room_list">  </td>
						      </tr>
						      <tr id="head_modal_info_floor">
						        <th> floor </th>
						        <td id="modal_info_floor">  </td>
						      </tr>
						      <tr id="head_modal_info_divisi">
						        <th> division </th>
						        <td id="modal_info_divisi">  </td>
						      </tr>
						      <tr id="head_modal_info_jabatan">
						        <th> position </th>
						        <td id="modal_info_jabatan">  </td>
						      </tr>
						      <tr class="info">
						        <th> status </th>
						        <td id="modal_info_status_akses">  </td>
						      </tr>
						      <tr>
						        <th> created by </th>
						        <td id="modal_info_created_by">  </td>
						      </tr>
						      <tr>
						        <th> updated by </th>
						        <td id="modal_info_updated_by">  </td>
						      </tr>
						      <tr>
						        <th style="min-width: 200px"> note </th>
						        <td id="modal_info_additional_note">  </td>
						      </tr>
						      <tr id="head_modal_info_comment">
						        <th> comment </th>
						        <td id="modal_info_comment">  </td>
						      </tr>


						    </tbody>
					  	</table>
					</div>
		      	</div> <!--panel body-->
		    </div> <!--panel-->

		    <div class="panel panel-primary" id="head_modal_info_po">
		      <div class="panel-heading text-center">
		      	Kontrak Kerja / PO
		      </div>
		      	<div class="panel-body">
			      	<img class="img-responsive"
			      	id="modal_info_po" 
			      	src="" 
			      	alt="Chania">

		      	</div> <!--panel body-->
		      	<div class="panel-footer"></div>
		    </div> <!--panel-->

		    <div class="panel panel-primary">
		      <div class="panel-heading text-center">
		      	ID Card
		      </div>
		      	<div class="panel-body">
			      	<img class="img-responsive"
			      	id="modal_info_foto" 
			      	src="" 
			      	alt="Chania">

		      	</div> <!--panel body-->
		      	<div class="panel-footer"></div>
		    </div> <!--panel-->
  		</div> <!--modal body-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      
     </div> <!--modal content-->


    </div>
  </div>



<script type="text/javascript">
	function info(status,uuid) {
		$('#head_modal_info_pic_list').hide();
		$('#head_modal_info_floor').hide();
		$('#head_modal_info_divisi').hide();
		$('#head_modal_info_jabatan').hide();
		$('#head_modal_info_comment').hide();
		$('#head_modal_info_po').hide();

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

					$('#modal_info_register_type').html(response.data['register_name']);
					$('#modal_info_request_type').html(response.data['request_name']);
					$('#modal_info_name').html(response.data['name']);
					$('#modal_info_email').html(response.data['email']);
					$('#modal_info_nik').html(response.data['nik']);
					$('#modal_info_no_access_card').html(response.data['no_access_card']);
					$('#modal_info_date_start').html(response.data['date_start']);
					$('#modal_info_date_end').html(response.data['date_end']);
					$('#modal_info_type_daftar').html(response.data['type_daftar']);
					$('#modal_info_admin_room_list').html(response.data['admin_room_name']);

					if(response.data['register_type'] == "2") {
						$('#head_modal_info_pic_list').show();
						$('#head_modal_info_floor').show();
						$('#head_modal_info_po').show();
						pic_list_detail = response.data['pic_list_vendor_name'] +
										   " ( " +
										   response.data['pic_list_vendor_detail_name'] +
										   " ) ";
						$('#modal_info_pic_list').html(pic_list_detail);
						$('#modal_info_floor').html(response.data['floor']);
					} else if(response.data['register_type'] == "1") {
						$('#head_modal_info_divisi').show();
						$('#head_modal_info_jabatan').show();
						$('#modal_info_divisi').html(response.data['divisi']);
						$('#modal_info_jabatan').html(response.data['jabatan']);
					}

					status_akses_bundle = response.data['status_name'];
								

					if(response.data['status_akses'] == 1) {
						status_akses_bundle = status_akses_bundle+
								" "+
								response.data['pic_list_vendor_name']+
								" ( " +
							   response.data['pic_list_vendor_detail_name'] +
							   " ) ";
					}

					$('#modal_info_status_akses').html(status_akses_bundle);
					$('#modal_info_created_by').html(response.data['created_by_username'] + " = " + response.data['created_at']);
					$('#modal_info_updated_by').html(response.data['updated_by_username'] + " = " + response.data['updated_at']);
					$('#modal_info_additional_note').html(response.data['additional_note']);

					$('#modal_info_po').attr("src","{{URL::to('/')}}"+response.data['po']);

					$('#modal_info_foto').attr("src","{{URL::to('/')}}"+response.data['foto']);
					$('#modal_info').modal('show');
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