<!-- modal -->
<div class="modal fade" id="modal_extend_access_card" role="dialog">
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
            extend access card
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
            1. Full Name,Access Card Number,Start & End Work are mandatory
            <br/>
            2. Additional Note is optional
            <br/>
            3. <strong> Non Permanent : </strong> Contract PO is mandatory 
            <br/>
            5. only for <b> new worker </b> in active status. 
          </div>
        </div>
        <!-- modal note-->

        <!-- first validation -->
        <div class="form-group">
          <label for="staff_nama"> 
            access card number :
          </label>
          <input type="text" 
          id="extend_check_access_card_number" class="form-control"  
          required="">

          <div class="btn btn-primary btn-block" 
          style="margin-top: 10px" onclick="extend_check_access_card_number_function()"> 
            Check access card number :
          </div>
        </div>
        <!-- first validation -->

        <div id="parent_extend_create_access_card">
          <form method="POST" action="{{ route('new_admin_room_list') }}">
            {{ csrf_field() }}
            
            <input type="hidden" name="extend_create_uuid" id="extend_create_uuid">
            <div class="form-group">
              <label for="staff_nama"> 
                full name :
              </label>
              <input type="text" 
              name="extend_create_full_name" id="extend_create_full_name" class="form-control"  
              required="" placeholder="ex : abc">
            </div>

            <div class="form-group">
              <label for="staff_nama"> 
                access card:
              </label>
              <input type="text" 
              name="extend_create_accesscard" id="extend_create_accesscard" 
              class="form-control"  
              required="" placeholder="ex : abc">
            </div>

            <div class="form-group">
              <label for="staff_nama"> 
                admin room detail :
              </label>
              <input type="text" 
              name="vendor_detail_name" id="nama" class="form-control"  
              required="" placeholder="LANTAI 17">
            </div>
            <button type="submit" class="btn btn-block btn-warning">
              REGISTER ADMIN ROOM CATEGORY 
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

  $('#parent_extend_create_access_card').hide();

  function extend_check_access_card_number_function() {
    value = $('#extend_check_access_card_number').val();
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
          $('#extend_create_full_name').val(response.data.name);
          $('#extend_create_accesscard').val(response.data.no_access_card)

          $('#extend_create_full_name').prop("disabled", true)
          $('#extend_create_accesscard').prop("disabled", true)
          $('#parent_extend_create_access_card').show();
        }
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    })
    //alert(value);


  }
</script>
