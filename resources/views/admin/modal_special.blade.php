<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<!-- Modal -->
  <div class="modal fade" id="modal_special" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
            Add Special Feature to : <span id="username_in_modal_special"> </span>
          </h4>
        </div>
        <div class="modal-body" style="padding-top: 0px">
          <div class="pull-right">
            <button class="btn btn-primary"
            style="margin-bottom: 10px" 
            id="add_role_btn_special"
            onclick="append_table_special()""> 
              add special feature
            </button>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center"> Special Feature </th>
                <th class="text-center"> Action </th>
              </tr>
            </thead>
            <tbody id="tbody_edit_special">
            </tbody>
         </table>
        </div>
        <div class="modal-footer">
          <div id="uuid_edit_special" class="hidden">  </div>
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
      
    </div>
  </div>

<script type="text/javascript">
  no_id_unique_special = 1;

  function get_features_user(uuid,username) {
    no_id_unique_special = 1;
    $('#username_in_modal_special').html(username);
    $('#uuid_edit_special').text(uuid);
    $('#add_role_btn_special').prop('disabled',false);
     var data = {
        "uuid":uuid
      };

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type : "POST",
      url: " {{ route('get_special_level') }}",
      contentType: "application/json",
      data : JSON.stringify(data),
      success: function(result) {
        $('#tbody_edit_special').empty();
        var response = JSON.parse(result);
        var data = "";
        if(response.status == true) {
          $.each(response.data, function (key,val) {
            data += '<tr id="tr_special'+no_id_unique_special+'" ';
            if(val.setting_role_deleted != null) {
              data += 'class="unselectable" ';
            }  
            data += '>';
            data +=   '<td class="text-center">' +
                        val.setting_name +
                      '</td>';
            data += '<td id="td_button_no_special'+no_id_unique_special+'" '+
                  'class="text-center ';
            data +=  '">';
            if(val.setting_role_deleted != null) {
              data +=   '<button class="btn btn-primary" '+
                          'onclick="restore_role_special('+val.setting_role_id+','+no_id_unique_special+')">'+
                            'Set Active' +
                        '</button>';
            } else {
              data +=   '<button class="btn btn-danger" '+
                          'onclick="delete_role_special('+val.setting_role_id+','+no_id_unique_special+')">'+
                            'Delete feature' +
                        '</button>';
            }
            data += '</td>';       
            data += '</tr>';
            no_id_unique_special++;
          });
          $('#tbody_edit_special').append(data);
        } else {
          alert(response.message);
        }
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    });
    $('#modal_special').modal('show'); 
  }

  function append_table_special() {
    data = '<tr id="tr_special'+no_id_unique_special+'" >' +
          '<td>' +
            '<div class="form-group">' +
                '<select '+ 
                'class="form-control" '+
                'id="select_features'+no_id_unique_special+'" '+
                'required="">' +
                  @foreach($data['setting_list'] as $key=>$val)
                  '<option value="{{$val->id}}">'+
                    '{{$val->setting_name}}'+
                  '</option>' +
                  @endforeach
                '</select>'+
              '</div>'+
          '</td>' +
         
          '<td id="td_button_no_special'+no_id_unique_special+'" >' +
            '<button class="btn btn-success text-center"'+
            'onclick="add_role_special('+no_id_unique_special+')">'+
              'Submit Features' +
            '</button>' +
          '</td>' +
        '</tr>';
    $('#tbody_edit_special').append(data);
    no_id_unique_special++;
    $('#add_role_btn_special').prop('disabled',true);
  }


  function add_role_special(no_id_unique_param) {
    var uuid          = $('#uuid_edit_special').text();
    var feature_role   = $('#select_features'+no_id_unique_param).val();


    var data = {
      "uuid":uuid,
      "feature_role":feature_role
    };

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type : "POST",
      url: " {{ route('add_role_special_user') }}",
      contentType: "application/json",
      data : JSON.stringify(data),
      success: function(result) {
        response = JSON.parse(result);
        if(response.status == true) {
          var append = '<button class="btn btn-danger" '+
                        'onclick="delete_role_special('+response.role_id+','+no_id_unique_param+')">'+
                          'Delete feature' +
                      '</button>';
            $('#td_button_no_special'+no_id_unique_param).find('button')
                              .remove()
                              .end();
            $('#td_button_no_special'+no_id_unique_param).append(append);
            $('#tr_special'+no_id_unique_param).removeClass('unselectable');
            $('#add_role_btn_special').prop('disabled',false);

            $('#feature_role'+no_id_unique_param).prop('disabled',true);
        } else {
          alert(response.message);
        }
        
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    });
  }

  function restore_role_special(role_id,no_id_unique_param) {
    if (confirm('Apakah anda yakin ingin mengaktifkan feature ini ?')) {
      var data = {
        "role_id":role_id
      };

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type : "POST",
        url: " {{ route('restore_role_special_user') }}",
        contentType: "application/json",
        data : JSON.stringify(data),
        success: function(result) {
          response = JSON.parse(result);
          if(response.status == true) {
            var append = '<button class="btn btn-danger" '+
                        'onclick="delete_role_special('+role_id+','+no_id_unique_param+')">'+
                          'Delete feature' +
                      '</button>';
            $('#td_button_no_special'+no_id_unique_param).find('button')
                              .remove()
                              .end();
            $('#td_button_no_special'+no_id_unique_param).append(append);
            $('#tr_special'+no_id_unique_param).removeClass('unselectable');
          } else {
            alert(response.message);
          }
          
        },
        error: function( jqXhr, textStatus, errorThrown ){
          console.log( errorThrown );
        }
      });
    }
  }

  function delete_role_special(role_id,no_id_unique_param) {
    if (confirm('Apakah anda yakin ingin menghapus Feature ini ?')) {
      var data = {
        "role_id":role_id
      };

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type : "POST",
        url: " {{ route('delete_role_special_user') }}",
        contentType: "application/json",
        data : JSON.stringify(data),
        success: function(result) {
          response = JSON.parse(result);
            if(response.status == true) {
              var append = '<button class="btn btn-primary" '+
                              'onclick="restore_role_special('+role_id+','+no_id_unique_param+')">'+
                                'Set Active' +
                            '</button>';
              $('#td_button_no_special'+no_id_unique_param).find('button')
                                .remove()
                                .end();
              $('#td_button_no_special'+no_id_unique_param).append(append);
              $('#tr_special'+no_id_unique_param).addClass('unselectable');

              
            } else {
              alert(response.message);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
          console.log( errorThrown );
        }
      });
    }     
  }

</script>