<!-- Modal -->
  <div class="modal fade" id="modal_role" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Add New Position for : <span id="username_in_modal_role"> </span>
          </h4>
        </div>
        <div class="modal-body">
        	<table class="table table-bordered">
  			    <thead>
  			      <tr>
  			        <th class="text-center"> Level Authority </th>
                <th class="text-center"> Sub Level Authority </th>
                <th class="text-center"> Position </th>
  			        <th class="text-center"> Action </th>
  			      </tr>
  			    </thead>
  			    <tbody id="tbody_edit">
  			    </tbody>
			   </table>
        </div>
        <div class="modal-footer">
          <div id="uuid_edit" class="hidden">  </div>
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
      
    </div>
  </div>

<script type="text/javascript">

  var no = 1;

  function get_role_user(uuid,username) {
    $('#username_in_modal_role').html(username);
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
      url: " {{ route('get_role_user') }}",
      contentType: "application/json",
      data : JSON.stringify(data),
      success: function(result) {
        $('#tbody_edit').empty();
        var response = JSON.parse(result);
        var data = "";
        $.each(response, function (key,val) {
          if(key == 0) {
            $('#uuid_edit').text(val.uuid);
            data += '<tr>' +
                  '<td colspan=4 class="text-center">' +
                    '<button '+
                    'class="btn btn-primary" ' +
                    'id="add_role_btn" ' +
                    'onclick="append_table()" >' +
                      'ADD ROLE' +
                     '</button>' +
                  '</td>' +
                '</tr>';
          }
          data += '<tr>' +
                '<td class="text-center">' +
                  val.divisi_name +
                '</td>' +
                '<td class="text-center">' +
                  val.sub_level +
                '</td>' +
                '<td class="text-center">' +
                  val.jabatan_name +
                '</td>' +
                '<td class="text-center">' +
                  '<button class="btn btn-danger" onclick="delete_role('+val.role_id+')">'+
                    'Delete Role' +
                   '</button>' +
                '</td>' +
              '</tr>';
          
              // alert(val.divisi_name);
        });
        $('#tbody_edit').append(data);
        $('#modal_role').modal('show'); 
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    })
  }

  function append_table() {
    data = '<tr>' +
          '<td>' +
            '<div class="form-group">' +
                '<select '+ 
                'class="form-control" '+
                'id="select_divisi_edit" '+
                'onchange="conditional_add_role()" '+
                'required="">' +
                  @foreach($data['divisi'] as $key=>$val)
                  '<option value="{{$val->id}}">'+
                    '{{ucfirst($val->name)}}'+
                  '</option>' +
                  @endforeach
                '</select>'+
              '</div>'+
          '</td>' +
          '<td id="edit_inventory_role">' +
            '<div class="form-group" id="inventory_head_edit">' +
                '<select '+ 
                'class="form-control" '+
                'id="inventory_role" '+
                '>' +
                  @foreach($data['inventory_list'] as $key=>$val)
                  '<option value="{{$val->id}}">'+
                    '{{ucfirst($val->inventory_name)}}'+
                  '</option>' +
                  @endforeach
                '</select>'+
              '</div>'+
              '<div class="form-group" id="pic_list_html">' +
                '<select '+ 
                'class="form-control" '+
                'id="pic_role" '+
                '>' +
                  @foreach($data['pic_list'] as $key=>$val)
                  '<option value="{{$val->id}}">'+
                    '{{$val->vendor_name}}'+
                  '</option>' +
                  @endforeach
                '</select>'+
              '</div>'+
          '</td>' +
          '<td>' +
            '<div class="form-group">' +
              '<select '+ 
              'class="form-control" '+
              'id="select_posisi_edit" '+
              'required="">' +
              '</select>'+
              '</div>'+
          '</td>' +
          '<td>' +
            '<button class="btn btn-success text-center"'+
            'onclick="add_role()">'+
              'Submit Role' +
            '</button>' +
          '</td>' +
        '</tr>';
    $('#tbody_edit').append(data);
    $('#inventory_head_edit').hide();
    $('#pic_list_html').hide();
    no++;
    // $('#add_role_btn').prop('disabled',true);
  }

  function conditional_add_role() {
    $('#select_posisi_edit').prop('disabled',false);
    $('#inventory_head_edit').hide();
    $('#pic_list_html').hide();
    var divisi_id = $('#select_divisi_edit').val();
    $('#select_posisi_edit')
          .find('option')
          .remove()
          .end()

    switch(divisi_id) {
      case "1" : 
        $('#select_posisi_edit').prop('disabled',true);
        $('#select_posisi_edit').val("0");
      break;
      case "2" :
        $('#pic_list_html').show(); 
        $.ajax({
          url:  "{{route('get_pic_level')}}",
          method: "POST", 
          contentType : "application/json; charset=utf-8",
          data : JSON.stringify(data),
          success: function(result){
                $.each(JSON.parse(result), function(key, value) {   
                 $('#select_posisi_edit')
                     .append($("<option></option>")
                                .attr("value",value.id)
                                .text(value.pic_level_name));
            });
          }
        });
        break;
      case "3" : 
        $.ajax({
          url:  "{{route('get_akses_role')}}",
          method: "POST", 
          contentType : "application/json; charset=utf-8",
          data : JSON.stringify(data),
          success: function(result){
            $.each(JSON.parse(result), function(key, value) {   
             $('#select_posisi_edit')
                 .append($("<option></option>")
                            .attr("value",value.id)
                            .text(value.name));
            });
          }
        });
      break;
      case "4" :
        $('#inventory_head_edit').show(); 
        $.ajax({
          url:  "{{route('get_inventory_level')}}",
          method: "POST", 
          contentType : "application/json; charset=utf-8",
          data : JSON.stringify(data),
          success: function(result){
                $.each(JSON.parse(result), function(key, value) {   
                 $('#select_posisi_edit')
                     .append($("<option></option>")
                                .attr("value",value.id)
                                .text(value.inventory_level_name));
            });
          }
        });
        break;
    }
  }

  function add_role() {
    var uuid          = $('#uuid_edit').text();
    var divisi_role   = $('#select_divisi_edit').val();
    var jabatan_role  = $('#select_posisi_edit').val();
    var inv_role      = $('#inventory_role').val();
    var pic_role      = $('#pic_role').val();

    var data = {
      "uuid":uuid,
      "divisi_role":divisi_role,
      "jabatan_role":jabatan_role,
      "inv_role":inv_role,
      "pic_role":pic_role
    };

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type : "POST",
      url: " {{ route('add_role_user') }}",
      contentType: "application/json",
      data : JSON.stringify(data),
      success: function(result) {
        response = JSON.parse(result);
        if(response.status == true) {
          window.location = "{{route('add_role_notif')}}";
        } else {
          alert(response.message);
        }
        
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    });
  }

  function delete_role(role_id) {
      if (confirm('Apakah anda yakin ingin menghapus Akun ini ?')) {
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
          url: " {{ route('delete_role_user') }}",
          contentType: "application/json",
          data : JSON.stringify(data),
          success: function(result) {
            response = JSON.parse(result);
              if(response.status == true) {
                window.location = "{{route('delete_role_notif')}}";
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