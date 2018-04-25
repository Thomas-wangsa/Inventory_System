@extends('layouts.template')

@section('content')
	<div style="padding: 0 30px;margin-top: 40px">
		<div >
			<div class="pull-left"> asa </div>
			<div class="pull-right">  
				<button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#myModal">
					Tambah Pengguna
				</button>
			</div>
			<div class="clearfix"> </div>

		</div>

		<div style="margin-top: 10px"> 
			<table class="table table-bordered">
			    <thead>
			      <tr>
			      	<th> No </th>
			        <th> Nama Lengkap </th>
			        <th> Divisi </th>
			        <th> Email </th>
			        <th> Keterangan </th>
			        <th> Status </th>
			        <th> Action </th>
			      </tr>
			    </thead>
			    <tbody>
			      
			    </tbody>
			</table>
		</div>
	</div>
  

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
          		style="border-bottom:3px solid #e1a435;cursor: pointer">
          			Staff Kami
          		</div>
          		<div class="col-sm-6" id="vendor_main"
          		style="border-bottom:3px solid #979797;cursor: pointer">
          			Vendor
          		</div>
          		<div class="clearfix"> </div>
          	</div> 
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" action="{{ route('post_pendaftaran_akses') }}">
				  {{ csrf_field() }}
				  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" id="nama" name="staff_nama">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_divisi"> Divisi :</label>
				    <input type="text" class="form-control" id="nama" name="staff_divisi">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_jabatan"> Jabatan :</label>
				    <input type="text" class="form-control" id="nama" name="staff_jabatan">
				  </div>
				  
				  <div class="form-group">
				    <label for="email"> Email :</label>
				    <input type="email" class="form-control" id="email" name="staff_email">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_nama"> NIK :</label>
				    <input type="text" class="form-control" id="nama" name="staff_nik">
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
	  			  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" id="nama" name="vendor_nama">
				  </div>
				  
				  <div class="form-group">
				    <label for="email">Email :</label>
				    <input type="email" class="form-control" id="email" name="vendor_email">
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

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#vendor").hide();
	    
	    $("#staff_main").click(function(){
	        $("#staff").show();
	        $("#vendor").hide();
	    });

	    $("#vendor_main").click(function(){
	        $("#staff").hide();
	        $("#vendor").show();
	    });
	});
</script>