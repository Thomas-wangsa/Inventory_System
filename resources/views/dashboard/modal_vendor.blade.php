<!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog">
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
	  			<form method="POST" action="{{ route('post_pendaftaran_pic') }}">
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

  				  <div class="form-group">
  				    <label for="staff_nama"> Masa Berlaku :</label>
  				    <input type="text" class="form-control" id="nama" name="expiry" placeholder="yyyy-mm-dd">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Akses Lantai </label>
  				    <input type="text" class="form-control" id="nama" name="floor">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Pekerjaan </label>
  				    <input type="text" class="form-control" id="nama" name="pekerjaan">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Foto :</label>
  				    <input type="file" class="form-control" id="nama" name="staff_foto">
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

 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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