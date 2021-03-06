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
						        <th> file name upload </th>
						        <td id="file_name_upload">  </td>
						      </tr>
						      <tr>
						        <th> tanggal update data </th>
						        <td id="tanggal_update_data">  </td>
						      </tr>
						      <tr>
						        <th> kategori </th>
						        <td id="kategori">  </td>
						      </tr>
						      <tr>
						        <th> kode gambar </th>
						        <td id="kode_gambar">  </td>
						      </tr>
						      <tr>
						        <th> dvr </th>
						        <td id="dvr">  </td>
						      </tr>
						      <tr>
						        <th> lokasi site </th>
						        <td id="lokasi_site">  </td>
						      </tr>	
						      <tr>
						        <th> kode lokasi </th>
						        <td id="kode_lokasi">  </td>
						      </tr>
						      <tr>
						        <th> jenis barang </th>
						        <td id="jenis_barang">  </td>
						      </tr>
						      <tr>
						        <th> merk </th>
						        <td id="merk">  </td>
						      </tr>
						      <tr>
						        <th> tipe </th>
						        <td id="tipe">  </td>
						      </tr>
						      <tr>
						        <th> model </th>
						        <td id="model">  </td>
						      </tr>
						      <tr>
						        <th> serial number </th>
						        <td id="serial_number">  </td>
						      </tr>

						      <tr>
						        <th> psu adaptor </th>
						        <td id="psu_adaptor">  </td>
						      </tr>
						      <tr>
						        <th> tahun pembuatan </th>
						        <td id="tahun_pembuatan">  </td>
						      </tr>
						      <tr>
						        <th> tahun pengadaan </th>
						        <td id="tahun_pengadaan">  </td>
						      </tr>
						      <tr>
						        <th> kondisi	 </th>
						        <td id="kondisi">  </td>
						      </tr>
						      <tr>
						        <th> deskripsi </th>
						        <td id="deskripsi">  </td>
						      </tr>

						      <tr>
						        <th> asuransi </th>
						        <td id="asuransi">  </td>
						      </tr>
						      <tr>
						        <th> lampiran </th>
						        <td id="lampiran">  </td>
						      </tr>
						      <tr>
						        <th> tanggal retired </th>
						        <td id="tanggal_retired">  </td>
						      </tr>
						      <tr>
						        <th> po </th>
						        <td id="po">  </td>
						      </tr>
						      <tr>
						        <th> qty </th>
						        <td id="qty">  </td>
						      </tr>
						      <tr>
						        <th> keterangan </th>
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
						        <td id="inventory_comment">  </td>
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


  		</div> <!--modal body-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      
     </div> <!--modal content-->


    </div>
  </div>



<script type="text/javascript">
	function info(uuid) {
		$('#head_modal_info_map_location').hide();
		$('#head_modal_info_images_location').hide();
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
			url: " {{ route('get_inventory_data_ajax') }}",
			contentType: "application/json",
			data : JSON.stringify(data),
			success: function(result) {
				response = JSON.parse(result);
				if(response.status == true) { 


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