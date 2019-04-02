<!-- Modal -->
  <div class="modal fade" id="modal_create_sub_inventory" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Add New Sub Data
          </h4>
        </div>
        <div class="modal-body">
	  			<form method="POST" action="{{ route('save_new_inventory_sub_data') }}">
				  {{ csrf_field() }}
				  
          <div class="form-group">
            <label for="staff_nama"> Select Category :</label>
            <select class="form-control" name="sub_data_status" required="">
              <option value="good"> good </option>
              <option value="bad"> bad </option>
              <option value="others"> others </option>                
            </select>
          </div>
          
          <div class="form-group">
            <label for="staff_nama"> Additional information :</label>
            <input type="text" class="form-control" id="nama"
            name="sub_data_additional" value="">
          </div>
            
            <input type="hidden" class="form-control" id="nama" 
            name="token_main_uuid" value="{{$data['token_main_uuid']}}">
          

				  <button type="submit" class="btn btn-block btn-warning"> 
            Submit New Sub Data 
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

