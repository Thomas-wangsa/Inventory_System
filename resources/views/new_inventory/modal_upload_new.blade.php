<!-- Modal -->
  <div class="modal fade" id="modal_upload_new" role="dialog">
    <div class="modal-dialog modal-lg">
    
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
            action="{{ route('new_upload_excel') }}">
              {{ csrf_field() }}
              <div class="form-group">
                <label class="control-label col-sm-3" for="pwd">
                  Upload Excel
                </label>
                <div class="col-sm-9"> 
                  <input type="file" class="form-control" 
                  name="excel_file" id="excel_file_event" required>
                </div>
              </div>

              <div class="form-group" id="header_sheet_name">
                <label class="control-label col-sm-3" for="pwd">
                  Select sheet
                </label>
                <div class="col-sm-9"> 
                  <select class="form-control col-sm-9" name="sheet_name" id="sheet_name" required="">
                    <option value=""> Select sheet </option>
                  </select>
                </div>
              </div>


              <div class="form-group"> 
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block" id="upload_excel_button" disabled="">
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
              please ask administrator to request new information.
            </p>
            <p>
              - every single rows with values like ("null","space","-") will convert to "-" automaticly, 
              except 0(zero) will convert as 0(zero).
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

<script type="text/javascript">
  $('#header_sheet_name').hide();

  $("#excel_file_event").change(function(){
    $('#sheet_name')
          .find('option')
          .remove()
          .end();
    $('#sheet_name')
    .append($("<option></option>")
        .attr("value","")
        .text("Select sheet")
    );

    $("#upload_excel_button").attr("disabled", true); 
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var file_data = $('#excel_file_event').prop('files')[0];
    var form_data = new FormData();
    form_data.append('excel_file_event', file_data);
    
     $.ajax({
        url         : "{{ route('checking_upload') }}",
        dataType    : 'text',           // what to expect back from the PHP script, if anything
        cache       : false,
        contentType : false,
        processData : false,
        data        : form_data,                         
        type        : 'post',
        success     : function(result){
          response = JSON.parse(result);
          if(response.error == true) {
              alert(response.messages);
            } else {
              $('#header_sheet_name').show();
              $("#upload_excel_button").attr("disabled", false); 
              $.each(response.data, function(key, value) {   
                  $('#sheet_name')
                      .append($("<option></option>")
                                  .attr("value",value)
                                  .text(value)
                      );
              });
            }
        }
    });
    //alert('aaa');
         //submit the form here
 });
</script>
