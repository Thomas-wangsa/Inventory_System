<!-- modal -->
<div class="modal fade" id="modal_new_update_access_card" role="dialog">
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
            set access card number 
          </h4>
      </div>
      <!-- modal header-->


      <!-- modal body-->
      <div class="modal-body">
        <form method="POST" action="{{ route('post_new_set_access_card_number') }}">
          {{ csrf_field() }}
          
          <input type="hidden" 
          name="uuid" id="modal_new_update_access_card_uuid" class="form-control"  
          >

          <div class="form-group">
            <label for="staff_nama"> 
              for worker :
            </label>
            <input type="text" 
            name="vendor_name" id="modal_new_update_access_card_name" class="form-control"  
            disabled>
          </div>

          <div class="form-group">
            <label for="staff_nama"> 
              set accesscard number :
            </label>
            <input type="text" 
            name="accesscard_number" id="nama" class="form-control"  
            value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->uuid}} @endif" 
            required="" placeholder="">
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


