<!-- Modal -->
  <div class="modal fade" id="modal_self" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Add New Pic Category
          </h4>
        </div>
        <div class="modal-body">
	  			<form method="POST" action="{{ route('new_pic_list') }}">
				  {{ csrf_field() }}
				  <input type="hidden" name="type_daftar" value="self">
				  <div class="form-group">
				    <label for="staff_nama"> Pic category :</label>
				    <input type="text" class="form-control" id="nama" required="" 
            name="vendor_name" value="" placeholder="ex : abc">
				  </div>
          <div class="form-group">
            <label for="staff_nama"> Pic category detail name :</label>
            <input type="text" class="form-control" id="nama" required="" 
            name="vendor_detail_name" value="" placeholder="PT. ALFA BRAVO CHARLIE ">
          </div>

				  
				 
				  <button type="submit" class="btn btn-block btn-warning">
            REGISTER PIC CATEGORY 
          </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

