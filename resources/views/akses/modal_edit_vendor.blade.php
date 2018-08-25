<!-- Modal -->
  <div class="modal fade" id="modal_edit_vendor" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Update Users
          </h4>
        </div>
        <div class="modal-body">
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
            		Edit Access Card 
          		</button>
			</form>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
     </div> 
    </div>
  </div>



<script type="text/javascript">

</script>