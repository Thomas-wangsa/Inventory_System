<!-- Modal -->
  <div class="modal fade" id="modal_new_config" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Add New Configuration
          </h4>
        </div>
        <div class="modal-body">
	  			<form method="POST" action="{{ route('helper.store') }}">
				  {{ csrf_field() }}
				  <div class="form-group">
            <label for="staff_nama"> Main Category :</label>
            <input type="text" class="form-control" id="nama" required="" 
            name="config_main" value="">
          </div>
          <div class="form-group">
            <label for="staff_nama"> Additional information :</label>
            <input type="text" class="form-control" id="nama" required="" 
            name="config_additional" value="">
          </div>
          <div class="form-group">
            <label for="staff_nama"> Select Category :</label>
            <select class="form-control" name="config_category" required="">
              <option value=""> Select Category </option>

              @foreach($data['config'] as $key=>$val)
                <option value="{{$val}}">
                  {{$key}}
                </option>
              @endforeach

                
            </select>
          </div>
				  <button type="submit" class="btn btn-block btn-warning"> 
            Submit New Configuration 
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

