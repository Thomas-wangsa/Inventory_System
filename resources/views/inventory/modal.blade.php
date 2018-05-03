<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Tambah Barang
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" action="{{ route('create_new_users') }}">
				  {{ csrf_field() }}
				  <div class="form-group">
				    <label for="staff_nama"> Nama Barang :</label>
				    <input type="text" class="form-control" id="nama" name="nama_barang" value="Thomas">
				  </div>

				  <div class="form-group">
				    <label for="staff_divisi"> Kategori :</label>
				    <select class="form-control" id="select_divisi" name="kategori" required="">
				    	<option value=""> </option>
				    </select>
				  </div>
				  
				  <div class="form-group">
				    <label for="email"> Serial Number : </label>
				    <input type="text" class="form-control" id="email" name="SN" value="9">
				  </div>

				  <div class="form-group">
				    <label for="email"> Jumlah : </label>
				    <input type="text" class="form-control" id="email" name="jumlah" value="9">
				  </div>

				  <div class="form-group">
				    <label for="email"> Tempat : </label>
				    <input type="text" class="form-control" id="email" name="tempat" value="9">
				  </div>

				  <div class="form-group">
				    <label for="email"> Keterangan : </label>
				    <input type="text" class="form-control" id="email" name="keterangan" value="9">
				  </div>
				  
				  
				  <button type="submit" class="btn btn-block btn-warning">Tambah Barang </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>