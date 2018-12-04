<!-- modal -->
<div class="modal fade" id="modal_lost_access_card" role="dialog">
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
            lost access card
          </h4>
      </div>
      <!-- modal header-->


      <!-- modal body-->
      <div class="modal-body">
        
        <!-- modal note-->
        <div id="new_note" style="margin-bottom: 20px">
          <div> 
            <strong> Note : </strong>
          </div>
          <div>
            1. Full Name,Access Card Number,Police Letter Receipt are mandatory
            <br/>
            2. Additional Note is optional
            <br/>
            3. only for <b> new worker </b> in status access card is active. 
          </div>
        </div>
        <!-- modal note-->

        <!-- first validation -->
        <div class="form-group">
          <label for="staff_nama"> 
            Access Card Number :
          </label>
          <input type="text" 
          id="lost_check_access_card_number" class="form-control"
          value=""  
          required="">

          <div class="btn btn-success btn-block" 
          style="margin-top: 10px" onclick="lost_check_access_card_number_function()"> 
            Check Access Card Number :
          </div>
        </div>
        <!-- first validation -->

        <div id="parent_lost_create_access_card">
          <form method="POST" enctype="multipart/form-data"
          action="{{ route('post_lost_access_card_number') }}">
            {{ csrf_field() }}
            
            <input type="hidden" name="lost_create_uuid" id="lost_create_uuid">

            <input type="hidden" 
              name="lost_create_register_status" id="lost_create_register_status" 
              class="form-control"  
              required="" placeholder="ex : abc">

            <div class="form-group">
              <label for="staff_nama"> 
                register status:
              </label>
              <input type="text" 
              id="show_lost_register_status" 
              class="form-control"  
              required="" placeholder="ex : abc">
            </div>

            <div class="form-group">
              <label for="staff_nama"> 
                Full name :
              </label>
              <input type="text" 
              name="lost_create_full_name" id="lost_create_full_name" class="form-control"  
              required="" placeholder="ex : abc">
            </div>

            <div class="form-group">
              <label for="staff_nama"> 
                Access card:
              </label>
              <input type="text" 
              name="lost_create_accesscard" id="lost_create_accesscard" 
              class="form-control"  
              required="" placeholder="ex : abc">
            </div>

           
            <div class="form-group" id="parent_new_extend_po">
              <label for="staff_nama"> Police Letter Receipt : </label>
              <input type="file" 
              name="lost_document" class="form-control" id="lost_document"
              required="" 
              >
            </div>

            <div class="form-group">
              <label for="staff_nama"> Additional Note : </label>
              <input type="text" 
              name="lost_additional_note" class="form-control" id="nama"  
              value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->text}} @endif" 
              placeholder="optional for add any information">
            </div>

            

            <button type="submit" class="btn btn-block btn-primary">
              REQUEST RECOVER ACCESS CARD
            </button>
          </form>
        </div>
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

<script type="text/javascript">

  $('#parent_lost_create_access_card').hide();


  function lost_check_access_card_number_function() {
    value = $('#lost_check_access_card_number').val();
    if(value == "") {
      alert("Please input the access card number!");
      return false;
    }

    var data = {
        "access_card_no":value
    };

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type : "POST",
      url: " {{ route('post_extend_check_access_card_number') }}",
      contentType: "application/json",
      data : JSON.stringify(data),
      success: function(result) {
        var response = JSON.parse(result);
        if(response.error) {
          alert(response.message);
        } else {
          var register_name = "";

          if(response.data.register_type == 1) {
            register_name = "Permanent";
          } else if (response.data.register_type ==  2){
            register_name = "Non Permanent";
          }

          $('#lost_create_uuid').val(response.data.uuid);
          $('#lost_create_full_name').val(response.data.name);
          $('#lost_create_accesscard').val(response.data.no_access_card);
          $('#lost_create_register_status').val(response.data.register_type);
          $('#show_lost_register_status').val(register_name);

          $('#lost_create_full_name').prop("readonly", true);
          $('#lost_create_accesscard').prop("readonly", true);
          $('#show_lost_register_status').prop("readonly", true);
          $('#parent_lost_create_access_card').show();
        }
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    })
    //alert(value);
  }
</script>
