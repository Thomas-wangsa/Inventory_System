<!-- modal -->
<div class="modal fade" id="modal_set_photo_schedule" role="dialog">
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
            set schedule photo with worker
          </h4>
      </div>
      <!-- modal header-->


      <!-- modal body-->
      <div class="modal-body">
        <form method="POST" action="{{ route('post_custome_set_photo_schedule') }}">
          {{ csrf_field() }}
          
          <input type="hidden" 
          name="uuid" id="modal_set_photo_schedule_uuid" class="form-control"  
          >

          <div class="form-group">
            <label for="staff_nama"> 
              for worker :
            </label>
            <input type="text" 
            name="vendor_name" id="modal_set_photo_schedule_name" class="form-control"  
            disabled>
          </div>

          <div class="form-group">
            <label for="staff_nama"> Schedule :</label>
            <input type="text" 
            name="modal_set_photo_schedule_date" 
            class="form-control datepicker_class" 
            value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->date}} @endif" 
            placeholder="example : 2018-09-01" required="">
          </div>

          <div class="form-group">
            <label for="staff_nama"> 
              Note : (Optional)
            </label>
            <input type="text" 
            name="modal_set_photo_schedule_photo_note" id="modal_set_photo_schedule_photo_note" 
            class="form-control"  
            value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->text}} @endif" 
            placeholder="">
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


