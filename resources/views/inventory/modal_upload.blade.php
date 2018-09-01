<!-- Modal -->
  <div class="modal fade" id="modal_upload" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Upload Excel
          </h4>
        </div>
        <div class="modal-body">
          <div style="margin-bottom: 20px">
            <form class="form-horizontal" 
            method="POST" enctype="multipart/form-data"
            action="{{ route('upload_excel') }}">
              {{ csrf_field() }}
              <div class="form-group">
                <label class="control-label col-xs-3" for="email">
                  Upload AS :
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
                <label class="control-label col-sm-3" for="pwd">
                  Upload Excel
                </label>
                <div class="col-sm-9"> 
                  <input type="file" class="form-control" 
                  name="excel_file" required>
                </div>
              </div>
              <div class="form-group"> 
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block">
                    Upload Excel
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div>
            <h5 style="color:red"> <strong> Notes : </strong> </h5>
            <p> 
              - File extension must be xls or xlsx.
            </p>
            <p> 
              - The patern of the data must same as master file (please ask the administrator).
            </p>
            <p>
              - The system only record fields based on master file,
              <br/> please ask administrator to request new information.
            </p>
            <p>
              - every single rows with values like ("null","space","-") will convert to "-" automaticly, 
              <br/>except 0(zero) will convert as 0(zero).
            </p>

          </div>
        </div> <!--modal-body-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Close
          </button>
        </div>
      </div>
      
    </div>
  </div>

