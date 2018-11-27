<!-- modal -->
<div class="modal fade" id="modal_broken_access_card" role="dialog">
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
            broken access card
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
            1. Full Name,Access Card Number,Old Access Card Receipt are mandatory
            <br/>
            2. Additional Note is optional
            <br/>
            3. only for <b> new worker </b> in active status. 
          </div>
        </div>
        <!-- modal note-->

        <!-- first validation -->
        <div class="form-group">
          <label for="staff_nama"> 
            Access Card Number :
          </label>
          <input type="text" 
          id="broken_check_access_card_number" class="form-control"
          value=""  
          required="">

          <div class="btn btn-success btn-block" 
          style="margin-top: 10px" onclick="broken_check_access_card_number_function()"> 
            Check Access Card Number :
          </div>
        </div>
        <!-- first validation -->

        <div id="parent_broken_create_access_card">
          <form method="POST" enctype="multipart/form-data"
          action="{{ route('post_extending_access_card_number') }}">
            {{ csrf_field() }}
            
            <input type="hidden" name="broken_create_uuid" id="broken_create_uuid">

            <div class="form-group">
              <label for="staff_nama"> 
                register status :
              </label>
              <select class="form-control" 
              name="broken_create_register_status" id="broken_create_register_status"
              >
                @foreach($data['register_type'] as $key=>$val)
                  <option value="{{$val->id}}" > 
                    {{ucfirst($val->register_name)}}
                  </option>
                @endforeach 
              </select>
            </div>

            <div class="form-group">
              <label for="staff_nama"> 
                Full name :
              </label>
              <input type="text" 
              name="broken_create_full_name" id="broken_create_full_name" class="form-control"  
              required="" placeholder="ex : abc">
            </div>

            <div class="form-group">
              <label for="staff_nama"> 
                Access card:
              </label>
              <input type="text" 
              name="broken_create_accesscard" id="broken_create_accesscard" 
              class="form-control"  
              required="" placeholder="ex : abc">
            </div>

           
            <div class="form-group" id="parent_new_extend_po">
              <label for="staff_nama"> Old Access Card Receipt : </label>
              <input type="file" 
              name="broken_document" class="form-control" id="broken_document"
              required="" 
              >
            </div>

            <div class="form-group">
              <label for="staff_nama"> Additional Note : </label>
              <input type="text" 
              name="broken_additional_note" class="form-control" id="nama"  
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

  $('#parent_broken_create_access_card').hide();


  function broken_check_access_card_number_function() {
    value = $('#broken_check_access_card_number').val();
    if(value == "") {
      alert("Please input the access card number!")
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
          $('#broken_create_uuid').val(response.data.uuid);
          $('#broken_create_full_name').val(response.data.name);
          $('#broken_create_accesscard').val(response.data.no_access_card)

          $('#broken_create_full_name').prop("readonly", true)
          $('#broken_create_accesscard').prop("readonly", true)
          $('#parent_broken_create_access_card').show();
        }
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    })
    //alert(value);
  }
</script>
