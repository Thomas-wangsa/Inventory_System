<!-- Modal -->
  <div class="modal fade" id="modal_new_inventory" role="dialog">
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
	  			<form method="POST" action="{{ route('add_inventory') }}">
				  {{ csrf_field() }}
				  <div class="form-group">
            <label for="staff_nama"> Inventory Category :</label>
            <input type="text" class="form-control" id="nama" required="" 
            name="inventory_name" value="" placeholder="ex : cctv management">
          </div>
          <div class="form-group">
            <label for="staff_nama"> Additional information :</label>
            <input type="text" class="form-control" id="nama" required="" 
            name="inventory_detail_name" value="" 
            placeholder="Optional">
          </div>
				  <button type="submit" class="btn btn-block btn-warning"> 
            Register Inventory Category 
          </button>
				</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Close
          </button>
        </div>
      </div>
      
    </div>
  </div>

