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
	  			  <form class="form-inline"
            method="POST" enctype="multipart/form-data"
            action="{{ route('upload_excel') }}">
  				    {{ csrf_field() }}
    				  <div class="form-group">
                <input type="file" class="form-control" name="excel_file" required>
              </div>
              <button type="submit" class="btn btn-primary">
                Upload Excel
              </button>
				    </form>
          </div>
          <div>
            <h5> <strong> Notes : </strong> </h5>

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

