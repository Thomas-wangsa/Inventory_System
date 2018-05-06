<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
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
          			Staff Kami
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
				    <input type="text" class="form-control" id="nama" name="staff_nama" value="Thomas">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_divisi"> Divisi :</label>
				    <input type="text" class="form-control" id="nama" name="staff_divisi" value="divisi">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_jabatan"> Jabatan :</label>
				    <input type="text" class="form-control" id="nama" name="staff_jabatan" value="Staff">
				  </div>
				  
				  <div class="form-group">
				    <label for="email"> Email :</label>
				    <input type="email" class="form-control" id="email" name="staff_email" value="thomas@gmail.com">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_nama"> NIK :</label>
				    <input type="text" class="form-control" id="nama" name="staff_nik" value="11414">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Foto :</label>
				    <input type="file" class="form-control" id="nama" name="staff_foto">
				  </div>
				  
				  <button type="submit" class="btn btn-block btn-warning">Tambah Pengguna </button>
				</form>
        	</div>

        	<div id="vendor">
	  			<form method="POST" action="{{ route('post_pendaftaran_akses') }}">
	  			  {{ csrf_field() }}
	  			  <input type="hidden" name="type_daftar" value="vendor">
	  			  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" id="nama" name="vendor_nama" value="Vendor">
				  </div>
				  
				  <div class="form-group">
				    <label for="email">Email :</label>
				    <input type="email" class="form-control" id="email" name="vendor_email" value="vendor@gmail.com">
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
<script type="text/javascript">
	$(document).ready(function(){
		$("#vendor").hide();
		
	    $("#staff_main").css("border-bottom","3px solid #e1a435");
	    $("#vendor_main").css("border-bottom","1px solid #979797")

	    $("#staff_main").click(function(){
	        $("#staff").show();
	        $("#vendor").hide();
	        $("#staff_main").css("border-bottom","3px solid #e1a435");
	        $("#vendor_main").css("border-bottom","1px solid #979797");
	    });

	    $("#vendor_main").click(function(){
	        $("#staff").hide();
	        $("#vendor").show();
	        $("#vendor_main").css("border-bottom","3px solid #e1a435");
	        $("#staff_main").css("border-bottom","1px solid #979797");
	    });
	});
</script>