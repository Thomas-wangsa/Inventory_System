<!-- Modal -->
  <div class="modal fade" id="modal_reject" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
            Please input the reason
          </h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('new_inventory.store') }}">
          {{ csrf_field() }}
          <input type="hidden" id="deactivated_uuid" name="uuid" value="self">
          <div class="form-group">
            <label for="staff_nama"> Inventory Name :</label>
            <input type="text" class="form-control" id="inventory_name_deativated" required="" 
            name="inventory_name_deativated" value="" placeholder="ex : abc" readonly="">
          </div>

          <div class="form-group">
            <label for="staff_nama"> Action :</label>
            <select class="form-control" name="reject_options" required="">
              <option value="">
                Select reject options
              </option>
              <option value="1"> Roll Back </option>
              <option value="4"> Reject Inventory </option>
            </select>
          </div>

          <div class="form-group">
            <label for="staff_nama"> Note :</label>
            <input type="text" class="form-control" id="nama" required="" 
            name="note" value="" placeholder="eg : update the serial number">
          </div>

          
         
          <button type="submit" class="btn btn-block btn-danger">
            Submit Reject Inventory
          </button>
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
  function reject_head(uuid,inventory_name) {
      if (confirm('reject this inventory ?')) {
        $('#deactivated_uuid').val(uuid);
        $('#inventory_name_deativated').val(inventory_name);
        $('#modal_reject').modal('show');
      }
    }
</script>