<!-- Modal -->
  <div class="modal fade" id="modal_edit" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Edit Inventory
          </h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" 
          method="POST" enctype="multipart/form-data"
          action="{{ route('inventory_update_data') }}">
            {{ csrf_field() }}
            <input type="hidden" name="uuid" id="uuid_in_edit_scope">
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Grouping :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="inventory_list_id" disabled="">
              </div>
            </div>


            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tanggal Update :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control datepicker_class" 
                id="tanggal_update_data_id" name="tanggal_update_data" 
                value="" placeholder="example : 2018-09-15" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kategori :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="kategori_id" name="kategori" 
                value="" placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kode Gambar :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="kode_gambar_id" name="kode_gambar" 
                value="" placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                DVR :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="dvr_id" name="dvr" 
                value="" placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Lokasi site :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="lokasi_site_id" name="lokasi_site" 
                value="" placeholder="Optional..." >
              </div>
            </div>

            

            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kode Lokasi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="kode_lokasi_id" name="kode_lokasi" 
                value="" placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Jenis Barang :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="jenis_barang_id" name="jenis_barang" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Merk :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="merk_id" name="merk" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tipe :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="tipe_id" name="tipe" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Model :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="model_id" name="model" value=""
                placeholder="Optional..." >
              </div>
            </div>


            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Serial Number :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="serial_number_id" name="serial_number" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                PSU Adaptor :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="psu_adaptor_id" name="psu_adaptor" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tahun Pembuatan :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="tahun_pembuatan_id" name="tahun_pembuatan" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tahun Pengadaan :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="tahun_pengadaan_id" name="tahun_pengadaan" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kondisi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="kondisi_id" name="kondisi" value=""
                placeholder="Optional..." >
              </div>
            </div>



            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Deskripsi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="deskripsi_id" name="deskripsi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Asuransi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="asuransi_id" name="asuransi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Lampiran :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="lampiran_id" name="lampiran" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tanggal Retired :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="tanggal_retired_id" name="tanggal_retired" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                PO :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="po_id" name="po" value=""
                placeholder="Optional..." >
              </div>
            </div>



            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                QTY :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="qty_id" name="qty" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Keterangan :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="keterangan_id" name="keterangan" value=""
                placeholder="Optional..." >
              </div>
            </div>




            <div class="form-group"> 
              <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block">
                  Update Inventory Data
                </button>
              </div>
            </div>
          </form>          
        </div> <!--modal-body-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Close
          </button>
        </div>
      </div>
      
    </div>
  </div>

<script type="text/javascript">
  function edit(uuid) {
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
      url: " {{ route('get_inventory_detail_ajax_by_uuid') }}",
      contentType: "application/json",
      data : JSON.stringify(data),
      success: function(result) {
        response = JSON.parse(result);
        if(response.status == true) {

          $('#uuid_in_edit_scope').val(response.data.uuid);
          $('#inventory_list_id').val(response.data.inventory_list_name);

          $('#tanggal_update_data_id').val(response.data.tanggal_update_data);
          $('#kategori_id').val(response.data.kategori);
          $('#kode_gambar_id').val(response.data.kode_gambar);
          $('#dvr_id').val(response.data.dvr);
          $('#lokasi_site_id').val(response.data.lokasi_site);


          $('#kode_lokasi_id').val(response.data.kode_lokasi);
          $('#jenis_barang_id').val(response.data.jenis_barang);
          $('#merk_id').val(response.data.merk);
          $('#tipe_id').val(response.data.tipe);
          $('#model_id').val(response.data.model);

          $('#serial_number_id').val(response.data.serial_number);
          $('#psu_adaptor_id').val(response.data.psu_adaptor);
          $('#tahun_pembuatan_id').val(response.data.tahun_pembuatan);
          $('#tahun_pengadaan_id').val(response.data.tahun_pengadaan);
          $('#kondisi_id').val(response.data.kondisi);


          $('#deskripsi_id').val(response.data.deskripsi);
          $('#asuransi_id').val(response.data.asuransi);
          $('#lampiran_id').val(response.data.lampiran);
          $('#tanggal_retired_id').val(response.data.tanggal_retired);
          $('#po_id').val(response.data.po);

          $('#qty_id').val(response.data.qty);
          $('#keterangan_id').val(response.data.keterangan);


          $('#modal_edit').modal('show');
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