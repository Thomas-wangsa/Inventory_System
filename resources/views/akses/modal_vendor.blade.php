<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script>
  $( function() {
    $("#start_card" ).datepicker({
      dateFormat: 'yy-mm-dd' ,
      showButtonPanel: true
    });
    $("#end_card" ).datepicker({
      dateFormat: 'yy-mm-dd' ,
      showButtonPanel: true
    });
    $("#start_card_new" ).datepicker({
      dateFormat: 'yy-mm-dd' ,
      showButtonPanel: true
    });
    $("#end_card_new" ).datepicker({
      dateFormat: 'yy-mm-dd' ,
      showButtonPanel: true
    });
  });
  </script>

<!-- Modal -->
  <div class="modal fade" id="modal_vendor" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Daftar Vendor
          </h4>
        </div>
        <div class="modal-body">
	  			<form method="POST" enctype="multipart/form-data"
          action="{{ route('post_pendaftaran_pic') }}">
	  			  {{ csrf_field() }}
	  			  <input type="hidden" name="type_daftar" value="vendor">
	  			  <div class="form-group">
  				    <label for="staff_nama"> Nama Lengkap :</label>
  				    <input type="text" class="form-control" id="nama" 
              name="vendor_nama" value=""
              placeholder="isikan nama" required="">
				    </div>
				  
  				  <div class="form-group">
  				    <label for="email">Email :</label>
  				    <input type="email" class="form-control" id="email" name="vendor_email" value=""
              placeholder="isikan email required">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Aktifkan Kartu :</label>
  				    <input type="text" class="form-control" id="start_card" name="start_card" placeholder="yyyy-mm-dd"
              placeholder="tanggal mulai kartu aktif" required="">
  				  </div>

            <div class="form-group">
              <label for="staff_nama"> Matikan Kartu :</label>
              <input type="text" class="form-control" id="end_card" name="end_card" placeholder="yyyy-mm-dd"
              placeholder="tanggal berakhir kartu aktif" required="">
            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Akses Lantai </label>
  				    <input type="text" class="form-control" id="nama" name="floor"
              placeholder="input nomor lantai" required="">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Pekerjaan </label>
  				    <input type="text" class="form-control" id="nama" name="pekerjaan"
              placeholder="pekerjaan yang dilakukan" required="">
  				  </div>

            <div class="form-group">
              <label for="staff_nama"> Kontrak Kerja (PO) :</label>
              <input type="file" class="form-control" id="nama" name="po" required="">
            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Identitas Diri :</label>
  				    <input type="file" class="form-control" id="nama" name="foto" required="">
  				  </div>
  				 
  				  <button type="submit" class="btn btn-block btn-warning">Tambah Pengguna </button>
  				</form>
        	</div>
        <div class="modal-footer">

          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

 


