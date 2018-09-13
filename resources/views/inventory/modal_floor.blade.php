<!-- Modal -->
  <div class="modal fade" id="modal_new_floor" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Add New Map
          </h4>
        </div>
        <div class="modal-body">
	  			<form class="form-horizontal"  
          method="GET"
          enctype="multipart/form-data"
          action="{{ route('add_map') }}">
				  {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label col-xs-3" for="email">
              Add Map AS :
            </label>
            <div class="col-xs-9">
              <select class="form-control" 
              name="inventory_list_id" 
              required="">
                <option value=""> Select role inventory </option>
                @foreach($data['inventory_list_id'] as $key=>$val)
                <option value="{{$val->id}}">
                  {{$val->inventory_name}} ({{$val->inventory_detail_name}})
                </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-xs-3" for="email">
              Map Name :
            </label>
            <div class="col-xs-9">
              <input type="text" class="form-control" id="nama" required="" 
              name="map_name" value="" placeholder="ex : map lantai 7">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-xs-3" for="email">
              Map Notes :
            </label>
            <div class="col-xs-9">
              <input type="text" class="form-control" id="nama" 
              name="map_notes" value="" placeholder="optional">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-3" for="pwd">
              Map Image
            </label>
            <div class="col-sm-9"> 
              <input type="file" class="form-control" 
              name="map_images" required>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block">
                Register Map
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

