<!-- modal -->
<div class="modal fade" id="modal_new_set_admin_room" role="dialog">
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
            set to admin room
          </h4>
      </div>
      <!-- modal header-->


      <!-- modal body-->
      <div class="modal-body">
        <form method="POST" action="{{ route('post_new_set_access_card_number') }}">
          {{ csrf_field() }}
          
          <input type="hidden" 
          name="uuid" id="modal_new_set_admin_room_uuid" class="form-control"  
          >

          <div class="form-group">
            <label for="staff_nama"> 
              for worker :
            </label>
            <input type="text" 
            name="vendor_name" id="modal_new_set_admin_room_name" class="form-control"  
            disabled>
          </div>

          <div class="form-group">
            <label for="staff_nama"> 
              choose the admin room :
            </label>
            <select class="form-control" name="selected_admin_room" required="">
              @foreach($data['admin_room_drop_down'] as $key=>$val)
                <option value="{{$val->id}}">
                  Admin Room {{$val->admin_room}} ({{$val->admin_room_detail}})
                </option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-block btn-primary">
            SUBMIT 
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


