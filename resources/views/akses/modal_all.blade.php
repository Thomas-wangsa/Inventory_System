<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 -->  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script>
  $( function() {
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
  <div class="modal fade" id="modal_all" role="dialog">
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
  				    <label for="staff_nama"> Full Name :</label>
  				    <input type="text" class="form-control" id="nama" 
              			name="name" value="{{$data['faker']->name}}"
              		placeholder="full name is required" required="">
				    </div>
				  
  				  <div class="form-group">
  				    <label for="email">Email :</label>
  				    <input type="email" class="form-control" 
  				        id="email" name="email" value="{{$data['faker']->email}}"
              		placeholder="email is required" required="">
  				  </div>

            <div class="form-group">
              <label for="staff_nama"> NIK :</label>
              <input type="text" class="form-control" id="nama" 
                    name="nik" value="1234456"
                  placeholder="NIK is required" required="">
            </div>

            <div class="form-group">
              <label for="staff_nama"> Pic Category :</label>
              <select class="form-control" name="pic_list_id" required="">
                  <option value=""> select pic category </option>
                  @foreach($data['pic_list'] as $key=>$val)
                    <option value="{{$val->id}}"> 
                      {{$val->vendor_name}} ({{$val->vendor_detail_name}})
                    </option>
                  @endforeach

              </select>
            </div>

            <div class="form-group">
              <label for="staff_divisi"> No. Access Card :</label>
              <input type="text" class="form-control" 
              id="nama" name="no_access_card" value="757585858"
              placeholder="Optional" required="">
            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Start Active Card :</label>
  				    <input type="text" class="form-control" 
  				    id="start_card_new" name="date_start" value="2018-09-01" 
              		placeholder="example : 2018-09-01" required="">
  				  </div>

	            <div class="form-group">
	              <label for="staff_nama"> End Active Card :</label>
	              <input type="text" class="form-control" 
	              id="end_card_new" name="date_end" value="2019-09-01"
	              placeholder="example : 2019-09-01" required="">
	            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Floor Activities : </label>
  				    <input type="text" class="form-control" 
              id="nama" name="floor" value="Lantai 12" 
              placeholder="example : lantai 11,21" required="">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Additional Note : </label>
  				    <input type="text" class="form-control" 
              id="nama" name="additional_note" value="Kerja di {{$data['faker']->company}}" 
              placeholder="optional for add any information ">
  				  </div>

            <div class="form-group">
              <label for="staff_nama"> Kontrak Kerja (PO) :</label>
              <input type="file" class="form-control" id="nama" name="po" required="">
            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> ID Card :</label>
  				    <input type="file" class="form-control" id="nama" name="foto" required="">
  				  </div>

				  <button type="submit" class="btn btn-block btn-primary">
            Request Access Card 
          </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

 
