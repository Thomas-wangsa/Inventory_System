<!-- Modal -->
  <div class="modal fade" id="modal_self" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Daftar Akses
          </h4>
        </div>
        <div class="modal-body">
	  			<form method="POST" action="{{ route('post_pendaftaran_pic') }}">
				  {{ csrf_field() }}
				  <input type="hidden" name="type_daftar" value="self">
				  <div class="form-group">
				    <label for="staff_nama"> Nomor Kartu :</label>
				    <input type="text" class="form-control" id="nama" name="no_kartu" value="482-5j2onwrqt2">
				  </div>
				  
				 
				  <button type="submit" class="btn btn-block btn-warning">Daftar Akses </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

