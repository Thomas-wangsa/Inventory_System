<!-- Modal -->
  <div class="modal fade" id="modal_edit_config" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Edit Configuration
          </h4>
        </div>
        <div class="modal-body">
         
	  			<form method="POST" action="{{ route('helper.update',$id_edit_category) }}">
          {{ method_field('PUT') }}
				  {{ csrf_field() }}

          <input type="hidden" id="edit_config_uuid" name="edit_config_uuid">

          <div class="form-group">
            <label for="staff_nama"> Category :</label>
            <input type="text" class="form-control" id="nama" required=""  
            name="edit_config_category" value="{{$category_value}}" disabled="">
          </div>


				  <div class="form-group">
            <label for="staff_nama"> Main Category :</label>
            <input type="text" class="form-control" id="edit_main_category" required="" 
            name="config_main">
          </div>
          <div class="form-group">
            <label for="staff_nama"> Additional information :</label>
            <input type="text" class="form-control" id="edit_additional_category" required="" 
            name="config_additional">
          </div>
          
				  <button type="submit" class="btn btn-block btn-warning"> 
            EDIT Configuration 
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

