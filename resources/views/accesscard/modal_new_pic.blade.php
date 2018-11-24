<!-- modal -->
<div class="modal fade" id="modal_new_pic" role="dialog">
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
            add new pic category
          </h4>
      </div>
      <!-- modal header-->


      <!-- modal body-->
      <div class="modal-body">
        <form method="POST" action="{{ route('new_pic_list') }}">
          {{ csrf_field() }}
          
          <div class="form-group">
            <label for="staff_nama"> 
              pic category :
            </label>
            <input type="text" 
            name="vendor_name" id="nama" class="form-control"  
            required="" placeholder="ex : abc">
          </div>

          <div class="form-group">
            <label for="staff_nama"> 
              pic category detail name :
            </label>
            <input type="text" 
            name="vendor_detail_name" id="nama" class="form-control"  
            required="" placeholder="PT. ALFA BRAVO CHARLIE ">
          </div>
          <button type="submit" class="btn btn-block btn-warning">
            REGISTER PIC CATEGORY 
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


