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
            4. only for <b> new worker </b> in status access card is active.  
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
          value="@if(env('ENV_STATUS', 'development') == 'development')f87fee4b-7417-3bc5-911e-18e9b3f6d518   @endif"  
          required="">

          <div class="btn btn-success btn-block" 
          style="margin-top: 10px" onclick="extend_check_access_card_number_function()"> 
            check access card number :
          </div>
        </div>
        <!-- first validation -->

        <div id="parent_extend_create_access_card">
          <form method="POST" enctype="multipart/form-data"
          action="{{ route('post_extending_access_card_number') }}">
            {{ csrf_field() }}
            
            <input type="hidden" name="extend_create_uuid" id="extend_create_uuid">

            <input type="hidden" 
              name="extend_create_register_status" id="extend_create_register_status" 
              class="form-control"  
              required="" placeholder="ex : abc">

            <div class="form-group">
              <label for="staff_nama"> 
                register status:
              </label>
              <input type="text" 
              id="show_register_status" 
              class="form-control"  
              required="" placeholder="ex : abc">
            </div>


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
                ktp number :
              </label>
              <input type="text" 
              name="extend_create_ktp_detail" id="extend_create_ktp_detail" 
              class="form-control"  
              required="" placeholder="optional parameter">
            </div>

            <div class="form-group" id="parent_new_extend_po_detail">
              <label for="staff_nama"> 
                po number :
              </label>
              <input type="text" 
              name="extend_create_po_detail" id="extend_create_po_detail" 
              class="form-control"  
              required="" placeholder="optional parameter">
            </div>

            <div class="form-group">
              <label for="staff_nama"> Start Active Work :</label>
              <input type="text" 
              name="new_extend_date_start" 
              class="form-control datepicker_class" id=""
              value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->date}} @endif" 
              placeholder="example : 2018-09-01" required="">
            </div>

            <div class="form-group">
              <label for="staff_nama"> End Active Work :</label>
              <input type="text" 
              name="new_extend_date_end" 
              class="form-control datepicker_class" id=""
              value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->date}} @endif" 
              placeholder="example : 2018-09-01" required="">
            </div>

            <div class="form-group">
              <label for="staff_nama"> Additional Note : </label>
              <input type="text" 
              name="new_extend_additional_note" class="form-control" id="nama"  
              value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->text}} @endif" 
              placeholder="optional for add any information">
            </div>

            <div class="form-group" id="parent_new_extend_po">
              <label for="staff_nama"> Company Work Contract / PO : </label>
              <input type="file" 
              name="new_extend_po" class="form-control" id="new_extend_po" 
              >
            </div>

            <button type="submit" class="btn btn-block btn-primary">
              REQUEST EXTEND ACCESS CARD
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
  $('#parent_new_extend_po').hide();
  $('#parent_new_extend_po_detail').hide();


  function conditional_extend_access_card_status() {
    register_status = $('#extend_create_register_status').val();

    if(register_status == 1) {
      $('#parent_new_extend_po').hide();
      $('#parent_new_extend_po_detail').hide();
      $("new_extend_po").prop('required',false);
    } else if(register_status == 2) {
      $('#parent_new_extend_po').show();
      $('#parent_new_extend_po_detail').show();
      $("#new_extend_po").prop('required',true);
    } else {
      alert("ERROR,PLEASE CONTACT YOUR ADMIN")
    }
  }

  function extend_check_access_card_number_function() {
    value = $('#extend_check_access_card_number').val();
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
            $('#parent_new_extend_po').hide();
            $('#parent_new_extend_po_detail').hide();
            $("new_extend_po").prop('required',false);
          } else if (response.data.register_type ==  2){
            register_name = "Non Permanent";
            $('#parent_new_extend_po').show();
            $('#parent_new_extend_po_detail').show();
            $("#new_extend_po").prop('required',true);
          }

          $('#extend_create_uuid').val(response.data.uuid);
          $('#extend_create_full_name').val(response.data.name);
          $('#extend_create_accesscard').val(response.data.no_access_card);
          $('#extend_create_register_status').val(response.data.register_type);
          $('#show_register_status').val(register_name);
          $('#extend_create_ktp_detail').val(response.data.ktp_detail);
          $('#extend_create_po_detail').val(response.data.po_detail);
          $('#extend_create_full_name').prop("readonly", true);
          $('#extend_create_accesscard').prop("readonly", true);
          $('#show_register_status').prop("readonly",true);
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
