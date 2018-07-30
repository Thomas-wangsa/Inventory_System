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
	  			<form method="POST" action="{{ route('create_new_inventory') }}">
				  {{ csrf_field() }}
				  <div class="form-group">
				    <label for="staff_nama"> Nama Barang :</label>
				    <input type="text" class="form-control" id="nama" name="nama_barang" value="CCTV ku">
				  </div>

				  <div class="form-group">
				    <label for="staff_divisi"> Kategori :</label>
				    <select class="form-control" id="select_divisi" name="kategori" required="">
				    	<option value=""> </option>
				    	@if(count($data['inventory']) > 0 )
				    		@foreach($data['inventory'] as $key=>$val)
				    			<option value="{{$val->id}}"> {{$val->inventory_name}}</option>
				    		@endforeach
				    	@endif
				    </select>
				  </div>
				  
				  <div class="form-group">
				    <label for="email"> Serial Number : </label>
				    <input type="text" class="form-control" id="email" name="SN" value="6356868-111x">
				  </div>

				  <div class="form-group">
				    <label for="email"> Jumlah : </label>
				    <input type="text" class="form-control" id="email" name="jumlah" value="9">
				  </div>

				  <div class="form-group">
				    <label for="email"> Tempat : </label>
				    <input type="text" class="form-control" id="email" name="tempat" value="Lantai 1">
				  </div>

				  <div class="form-group">
				    <label for="email"> Denah : </label>
				    <input type="file" class="form-control" name="background">
				  </div>

				  <div class="form-group">
				    <label for="email"> Foto : </label>
				    <input type="file" class="form-control" name="background">
				  </div>

				  <div class="form-group">
				    <label for="email"> Lokasi : </label>
				    <span onclick="set_location()" style="color:blue">
				    <span class="glyphicon glyphicon-zoom-in"></span> Set Location
					</span>
				  </div>

				  <div class="form-group">
				    <label for="email"> Keterangan : </label>
				    <input type="text" class="form-control" id="email" name="keterangan" value="Dari Thomas">
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
</div>
<script type="text/javascript">
	
	function set_location() {
		var url = window.location.protocol+"//"+window.location.host+'/map_location';
		//window.location = url;

		window.open(url, '_blank');

	}
</script>