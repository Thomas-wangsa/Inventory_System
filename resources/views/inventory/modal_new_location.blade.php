<!-- Modal -->
  <div class="modal fade" id="modal_new_map_location" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Add New Map Location
          </h4>
        </div>
        <div class="modal-body">
	  			<form class="form-horizontal"  
          method="POST" enctype="multipart/form-data"
          action="{{ route('set_map_location') }}">
				  {{ csrf_field() }}
          <input type="hidden" class="form-control" id="map_location_uuid" 
          required="" name="map_location_uuid" value="">

          <div class="form-group">
            <label class="control-label col-xs-3" for="email">
              Select Map :
            </label>
            <div class="col-xs-9">
              <select class="form-control" 
              name="map_id" 
              required="">
                <option value=""> Select map </option>
                @foreach($data['map'] as $key=>$val)
                <option value="{{$val->id}}">
                  {{$val->map_name}} ({{$val->map_notes}})
                </option>
                @endforeach
              </select>
            </div>
          </div>
  

          <div class="form-group">
            <label class="control-label col-sm-3" for="pwd">
              Select Images
            </label>
            <div class="col-sm-9"> 
              <input type="file" class="form-control" 
              name="image_location" required>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block">
                Set Map Location
              </button>
            </div>
          </div>
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

<script type="text/javascript">
  function set_location(uuid) {
    $('#map_location_uuid').val(uuid);
    $('#modal_new_map_location').modal('show');
  }
</script>