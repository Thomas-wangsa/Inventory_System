<!-- Modal -->
  <div class="modal fade" id="modal_staff" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Tambah Pengguna
          	<div style="width: 100%">
          		<div class="col-sm-6" id="staff_main"  
          		style="cursor: pointer">
          			Staff Indosat
          		</div>
          		<div class="col-sm-6" id="vendor_main"
          		style="cursor: pointer">
          			Vendor
          		</div>
          		<div class="clearfix"> </div>
          	</div> 
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" enctype="multipart/form-data"
	  			action="{{ route('post_pendaftaran_akses') }}">
				  {{ csrf_field() }}
				  <input type="hidden" name="type_daftar" value="staff">
				  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" 
				    id="nama" name="staff_nama" value=""
				    placeholder="Masukan nama staff" required="">
				  </div>
				  
				  <div class="form-group">
				    <label for="email"> Email :</label>
				    <input type="email" class="form-control" 
				    id="email" name="staff_email" 
				    value=""
				    placeholder="Masukan email staff" required="">
				  </div>

				  <div class="form-group">
				    <label for="staff_divisi"> No Kartu Akses :</label>
				    <input type="text" class="form-control" 
				    id="nama" name="staff_no_card" value=""
				    placeholder="Masukan nomor kartu akses" required="">
				  </div>

				  <div class="form-group">
				    <label for="staff_divisi"> Catatan :</label>
				    <input type="text" class="form-control" 
				    id="nama" name="staff_note" value=""
				    placeholder="Isi catatan bila perlu" >
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_nama"> Identitas Diri :</label>
				    <input type="file" class="form-control" id="nama" name="staff_foto" required="">
				  </div>
				  
				  <button type="submit" class="btn btn-block btn-warning">Tambah Pengguna </button>
				</form>
        	</div>

        	<div id="vendor">
	  			<form method="POST" enctype="multipart/form-data"
	  			action="{{ route('post_pendaftaran_akses') }}">
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
  				    <input type="email" class="form-control" 
  				    id="email" name="vendor_email" value=""
              		placeholder="isikan email" required="">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Aktifkan Kartu :</label>
  				    <input type="text" class="form-control" 
  				    id="start_card_new" name="start_card" 
              		placeholder="tanggal mulai kartu aktif" required="">
  				  </div>

	            <div class="form-group">
	              <label for="staff_nama"> Matikan Kartu :</label>
	              <input type="text" class="form-control" 
	              id="end_card_new" name="end_card"
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
	  			  <!-- <input type="hidden" name="type_daftar" value="vendor">
	  			  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" id="nama" name="vendor_nama" value="Vendor">
				  </div>
				  
				  <div class="form-group">
				    <label for="email">Email :</label>
				    <input type="email" class="form-control" id="email" name="vendor_email" value="vendor@gmail.com">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Masa Berlaku :</label>
				    <input type="text" class="form-control" id="nama" name="expiry" placeholder="yyyy-mm-dd">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Akses Lantai </label>
				    <input type="text" class="form-control" id="nama" name="lantai">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Pekerjaan </label>
				    <input type="text" class="form-control" id="nama" name="pekerjaan">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Foto :</label>
				    <input type="file" class="form-control" id="nama" name="staff_foto">
				  </div> -->
				 
				  <button type="submit" class="btn btn-block btn-warning">Tambah Pengguna </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

 
