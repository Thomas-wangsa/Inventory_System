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

            <div class="form-group">
              <label for="staff_nama"> 
                register status :
              </label>
              <select class="form-control" 
              name="extend_create_register_status" id="extend_create_register_status"
              onchange="conditional_extend_access_card_status()">
                @foreach($data['register_type'] as $key=>$val)
                  <option value="{{$val->id}}" > 
                    {{ucfirst($val->register_name)}}
                  </option>
                @endforeach 
              </select>
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


  function conditional_extend_access_card_status() {
    register_status = $('#extend_create_register_status').val();

    if(register_status == 1) {
      $('#parent_new_extend_po').hide();
      $("new_extend_po").prop('required',false);
    } else if(register_status == 2) {
      $('#parent_new_extend_po').show();
      $("#new_extend_po").prop('required',true);
    } else {
      alert("ERROR,PLEASE CONTACT YOUR ADMIN")
    }
  }

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
          $('#extend_create_uuid').val(response.data.uuid);
          $('#extend_create_full_name').val(response.data.name);
          $('#extend_create_accesscard').val(response.data.no_access_card)

          $('#extend_create_full_name').prop("readonly", true)
          $('#extend_create_accesscard').prop("readonly", true)
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
