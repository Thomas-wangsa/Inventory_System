<style type="text/css">
	tbody #info_tbody tr th {background-color: red} 
</style>

<!-- Modal -->
  <div class="modal fade" id="modal_inventory_info" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center" >
          	Detail information inventory data
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
						        <th> Grouping </th>
						        <td id="new_inventory_info_level">  </td>
						      </tr>
						      <tr>
						        <th> kota </th>
						        <td id="new_inventory_info_group1">  </td>
						      </tr>
						      <tr>
						        <th> gedung </th>
						        <td id="new_inventory_info_group2">  </td>
						      </tr>
						      <tr>
						        <th> divisi indosat </th>
						        <td id="new_inventory_info_group3">  </td>
						      </tr>
						      <tr>
						        <th> sub divisi indosat </th>
						        <td id="new_inventory_info_group4">  </td>
						      </tr>
						      <tr>
						        <th> inventory category </th>
						        <td id="new_inventory_info_category">  </td>
						      </tr>
						      <tr>
						        <th> inventory name </th>
						        <td id="new_inventory_info_name">  </td>
						      </tr>
						      <tr>
						        <th> event </th>
						        <td id="new_inventory_info_event">  </td>
						      </tr>
						      <tr>
						        <th> sub inventory list </th>
						        <td id="new_inventory_info_sub_inventory_list">  </td>
						      </tr>


						      <tr class="info">
						        <th> file name upload </th>
						        <td id="file_name_upload">  </td>
						      </tr>
						      <tr>
						        <th> qty </th>
						        <td id="qty">  </td>
						      </tr>
						      <tr>
						        <th> tanggal update data </th>
						        <td id="tanggal_update_data">  </td>
						      </tr>
						      <tr>
						        <th> ket 1 </th>
						        <td id="kategori">  </td>
						      </tr>
						      <tr>
						        <th> ket 2 </th>
						        <td id="kode_gambar">  </td>
						      </tr>
						      <tr>
						        <th> ket 3 </th>
						        <td id="dvr">  </td>
						      </tr>
						      <tr>
						        <th> ket 4 </th>
						        <td id="lokasi_site">  </td>
						      </tr>	
						      <tr>
						        <th> ket 5 </th>
						        <td id="kode_lokasi">  </td>
						      </tr>
						      <tr>
						        <th> ket 6 </th>
						        <td id="jenis_barang">  </td>
						      </tr>
						      <tr>
						        <th> ket 7 </th>
						        <td id="merk">  </td>
						      </tr>
						      <tr>
						        <th> ket 8 </th>
						        <td id="tipe">  </td>
						      </tr>
						      <tr>
						        <th> ket 9 </th>
						        <td id="model">  </td>
						      </tr>
						      <tr>
						        <th> ket 10 </th>
						        <td id="serial_number">  </td>
						      </tr>

						      <tr>
						        <th> ket 11 </th>
						        <td id="psu_adaptor">  </td>
						      </tr>
						      <tr>
						        <th> ket 12 </th>
						        <td id="tahun_pembuatan">  </td>
						      </tr>
						      <tr>
						        <th> ket 13 </th>
						        <td id="tahun_pengadaan">  </td>
						      </tr>
						      <tr>
						        <th> ket 14	 </th>
						        <td id="kondisi">  </td>
						      </tr>
						      <tr>
						        <th> ket 15 </th>
						        <td id="deskripsi">  </td>
						      </tr>

						      <tr>
						        <th> ket 16 </th>
						        <td id="asuransi">  </td>
						      </tr>
						      <tr>
						        <th> ket 17 </th>
						        <td id="lampiran">  </td>
						      </tr>
						      <tr>
						        <th> ket 18 </th>
						        <td id="tanggal_retired">  </td>
						      </tr>
						      <tr>
						        <th> ket 19 </th>
						        <td id="po">  </td>
						      </tr>
						      
						      <tr>
						        <th> ket 20 </th>
						        <td id="keterangan">  </td>
						      </tr>
						      
						      <tr class="info">
						        <th> status </th>
						        <td id="modal_info_status_inventory">  </td>
						      </tr>
						      <tr>
						        <th> created by </th>
						        <td id="modal_info_created_by">  </td>
						      </tr>
						      <tr>
						        <th> updated by </th>
						        <td id="modal_info_updated_by">  </td>
						      </tr>
						      <tr id="head_modal_info_comment">
						        <th> comment </th>
						        <td id="inventory_comment" style="color:red">  </td>
						      </tr>

						        	
						    </tbody>
					  	</table>
					</div>
		      	</div> <!--panel body-->
		    </div> <!--panel-->

		    <div class="panel panel-primary" id="head_modal_info_map_location">
		      <div class="panel-heading text-center">
		      	Map 
		      </div>
		      	<div class="panel-body">
			      	<img class="img-responsive"
			      	id="modal_info_map_location" 
			      	src="" 
			      	alt="Chania">

		      	</div> <!--panel body-->
		      	<div class="panel-footer"></div>
		    </div> <!--panel-->

		    <div class="panel panel-primary" id="head_modal_info_images_location">
		      <div class="panel-heading text-center">
		      	Images 
		      </div>
		      	<div class="panel-body">
			      	<img class="img-responsive"
			      	id="modal_info_images_location" 
			      	src="" 
			      	alt="Chania">

		      	</div> <!--panel body-->
		      	<div class="panel-footer"></div>
		    </div> <!--panel-->


		    <div class="btn btn-block btn-info" onclick="get_history()">
				view history
			</div>


  		</div> <!--modal body-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      
     </div> <!--modal content-->


    </div>
  </div>



<script type="text/javascript">
	current_uuid = null;

	function get_history() {
		var url = "{{route('checking_history')}}"+"?log=";
		window.location = url+current_uuid;

	}

	function info(uuid) {
		$('#head_modal_info_map_location').hide();
		$('#head_modal_info_images_location').hide();
		$('#new_inventory_info_sub_inventory_list').empty();
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
			url: " {{ route('get_new_inventory_data_ajax') }}",
			contentType: "application/json",
			data : JSON.stringify(data),
			success: function(result) {
				response = JSON.parse(result);
				if(response.status == true) { 

					status_data = "undefined";

					if(response.data.status_data == 1) {
						status_data = "new inventory";
					} else if(response.data.status_data == 2) {
						status_data = "update inventory";
					}
					$('#new_inventory_info_group1').html(response.data.group1_name);
					$('#new_inventory_info_group2').html(response.data.group2_name);
					$('#new_inventory_info_group3').html(response.data.group3_name);
					$('#new_inventory_info_group4').html(response.data.group4_name);
					$('#new_inventory_info_category').html(response.data.inventory_list_name);
					$('#new_inventory_info_level').html(response.data.inventory_level_name);
					$('#new_inventory_info_name').html(response.data.inventory_name);
					$('#new_inventory_info_event').html(status_data).css("font-weight","Bold");

					shortcut_sub_list = '<a href="{{ route("new_inventory.show",["id"=>"%ID%"])}}"'
													+ 'target="_blank">'
													+ 'click here'
													+ '</a>';


					$('#new_inventory_info_sub_inventory_list').append(shortcut_sub_list.replace("%ID%",response.data.uuid));					

					$('#file_name_upload').html(response.data.file_name_upload);

					$('#tanggal_update_data').html(response.data.tanggal_update_data);
					$('#kategori').html(response.data.kategori);
					$('#kode_gambar	').html(response.data.kode_gambar);
					$('#dvr').html(response.data.dvr);
					$('#lokasi_site').html(response.data.lokasi_site);

					$('#kode_lokasi').html(response.data.kode_lokasi);
					$('#jenis_barang').html(response.data.jenis_barang);
					$('#merk').html(response.data.merk);
					$('#tipe').html(response.data.tipe);
					$('#model').html(response.data.model);

					$('#serial_number').html(response.data.serial_number);
					$('#psu_adaptor').html(response.data.psu_adaptor);
					$('#tahun_pembuatan').html(response.data.tahun_pembuatan);
					$('#tahun_pengadaan').html(response.data.tahun_pengadaan);
					$('#kondisi').html(response.data.kondisi);

					$('#deskripsi').html(response.data.deskripsi);
					$('#asuransi').html(response.data.asuransi);
					$('#lampiran').html(response.data.lampiran);
					$('#tanggal_retired').html(response.data.tanggal_retired);
					$('#po').html(response.data.po);

					$('#qty').html(response.data.qty);
					$('#keterangan').html(response.data.keterangan);

					$('#inventory_comment').html(response.data.comment);

					$('#modal_info_created_by').html(response.data.c_username+" "+response.data.created_at);
					$('#modal_info_updated_by').html(response.data.u_username+" "+response.data.updated_at);


					$('#modal_info_status_inventory').html(response.data.status_name);

					if(response.data.map_location_id != null) {
						$('#head_modal_info_map_location').show();
						$('#head_modal_info_images_location').show();

						$('#modal_info_map_location').attr("src","{{URL::to('/')}}"+response.data.map_images);
						$('#modal_info_images_location').attr("src","{{URL::to('/')}}"+response.data.image_location);
					}

					$('#modal_inventory_info').modal('show');
					current_uuid = uuid;
				}else {
					alert(response.message);
				}
				
				
			},
			error: function( jqXhr, textStatus, errorThrown ){
				console.log( errorThrown );
			}
		});


		
		
	}
</script>