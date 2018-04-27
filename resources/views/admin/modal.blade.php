<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Tambah Akun
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" action="{{ route('post_pendaftaran_akses') }}">
				  {{ csrf_field() }}
				  <input type="hidden" name="type_daftar" value="staff">
				  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" id="nama" name="staff_nama" value="Thomas">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email :</label>
				    <input type="email" class="form-control" id="email" name="staff_email" value="thomas@gmail.com">
				  </div>

				  <div class="form-group">
				    <label for="email"> No Handphone :</label>
				    <input type="text" class="form-control" id="email" name="staff_mobile" value="92-595305">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_divisi"> Akses Level :</label>
				    <select class="form-control" id="select_divisi" name="select_divisi">
				    	<option value=""> </option>
				    	@foreach($divisi as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->name}}</option>
				    	@endforeach 
				    </select>
				  </div>

				  <div class="form-group">
				    <label for="staff_divisi"> Posisi :</label>
				    <select class="form-control" id="select_posisi" name="select_posisi">
				    	<option value=""> </option>
				    	
				    </select>
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
		$('#select_divisi').change(function(){
			$('#select_posisi').prop('disabled',false);
			var value = $('#select_divisi').val();
			//alert(value);
			switch(value) {
				case "1" : 
					$('#select_posisi').prop('disabled',true);
					$('#select_posisi').val("0");
					break;
				case "2" : alert("dua nih");break;
				case "3" : alert("tiga nih");break;
				default : alert("Please contact your administrator");break
			}
		})
	});
</script>
