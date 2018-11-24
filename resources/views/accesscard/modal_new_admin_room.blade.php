<!-- modal -->
<div class="modal fade" id="modal_new_admin_room" role="dialog">
  <div class="modal-dialog">
    <!-- modal content-->
    <div class="modal-content">

      
      <!-- modal header-->
      <div class="modal-header" style="border-bottom:0px">
          <button type="button" 
          class="close" data-dismiss="modal">
            &times;
          </button>
          <h4 class="modal-title text-center">
            add new admin room category
          </h4>
      </div>
      <!-- modal header-->


      <!-- modal body-->
      <div class="modal-body">
        <form method="POST" action="{{ route('new_admin_room_list') }}">
          {{ csrf_field() }}
          
          <div class="form-group">
            <label for="staff_nama"> 
              admin room :
            </label>
            <input type="text" 
            name="vendor_name" id="nama" class="form-control"  
            required="" placeholder="ex : abc">
          </div>

          <div class="form-group">
            <label for="staff_nama"> 
              admin room detail :
            </label>
            <input type="text" 
            name="vendor_detail_name" id="nama" class="form-control"  
            required="" placeholder="LANTAI 17">
          </div>
          <button type="submit" class="btn btn-block btn-warning">
            REGISTER ADMIN ROOM CATEGORY 
          </button>
        </form>
      </div>
      <!-- modal body-->

      <!-- modal footer-->
      <div class="modal-footer">
        <button type="button" 
        class="btn btn-danger" data-dismiss="modal">
          Close
      </button>
      </div>
      <!-- modal footer-->


    </div>
    <!-- modal content-->
  </div>
</div>
<!-- modal -->


