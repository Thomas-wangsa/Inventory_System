<!-- Modal -->
  <div class="modal fade" id="modal_deactive" role="dialog">
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
          <form method="POST" action="{{ route('deactivated_access_card') }}">
          {{ csrf_field() }}
          <input type="hidden" id="deactivated_uuid" name="uuid" value="self">
          <div class="form-group">
            <label for="staff_nama"> Access Card No :</label>
            <input type="text" class="form-control" id="no_access_card_deactivated" required="" 
            name="no_access_card" value="" placeholder="ex : abc" readonly="">
          </div>
          <div class="form-group">
            <label for="staff_nama"> Note :</label>
            <input type="text" class="form-control" id="nama" required="" 
            name="note" value="" placeholder="eg : want to update this card.">
          </div>

          
         
          <button type="submit" class="btn btn-block btn-danger">
            Deactivated Access Card
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
  function deactive_access_card(no_access,uuid) {
      if (confirm('deactive this card ?')) {
        $('#deactivated_uuid').val(uuid);
        $('#no_access_card_deactivated').val(no_access);
        $('#modal_deactive').modal('show');
      }
    }
</script>