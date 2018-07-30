<!-- Modal -->
  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Add New Inventory
          </h4>
        </div>
        <div class="modal-body">
	  			<form method="POST" action="{{ route('post_setting_add_inventory') }}">
				  {{ csrf_field() }}
				  <input type="hidden" name="updated_by" value="{{$data['credentials']->id}}">
				  <div class="form-group">
				    <label for="staff_nama"> Nama List Inventory :</label>
				    <input type="text" class="form-control" id="nama" name="inventory" required="">
				  </div>
				  
				 
				  <button type="submit" class="btn btn-block btn-warning"> 
            Daftarkan Inventory 
          </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

