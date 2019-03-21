<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	New User
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" enctype="multipart/form-data" 
	  			action="{{ route('create_new_users') }}">
				  {{ csrf_field() }}
				  <div class="form-group">
				    <label for="staff_nama"> Name :</label>
				    <input type="text" class="form-control" id="nama" 
				    name="name" 
				    value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->name}} @endif"
				    required="" 
				    placeholder="Only letters and whitespace allowed...">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> NIK :</label>
				    <input type="text" class="form-control" id="nama" 
				    name="nik" 
				    value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->uuid}} @endif" 
				    required="" 
				    placeholder="NIK is required">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email Default :</label>
				    <input type="email" class="form-control" id="nama" 
				    name="email" value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->email}} @endif" required="" 
				    placeholder="example@example.com">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email Secondary :</label>
				    <input type="email" class="form-control" id="nama" 
				    name="email_second" value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->email}} @endif"  
				    placeholder="example@example.com (Optional)">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Mobile :</label>
				    <input type="text" class="form-control" id="nama" 
				    name="mobile" value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->phoneNumber}} @endif" required="" 
				    placeholder="Only numbers allowed...">
				  </div>
				  	
				  <div class="form-group">
              		<label for="staff_nama"> Personal Identity : </label>
              		<input type="file" class="form-control" id="nama" 
              		name="Personal_Identity" 
              		required="">
              		<span class="text-danger"> note : max 5mb </span>              		
            	 </div>

				  <div class="form-group">
				    <label for="staff_divisi"> User Level :</label>
				    <select class="form-control" id="select_divisi" 
				    name="divisi" required="">
				    	<option value=""> </option>
				    	@foreach($data['divisi'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->name}}</option>
				    	@endforeach 
				    </select>
				  </div>

				  <div class="form-group" id="pic_head_html">
				    <label for="staff_divisi"> For PIC :</label>
				    <select class="form-control" id="pic_role" 
				    name="pic_list">
				    	@foreach($data['pic_list'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->vendor_name}} </option>
				    	@endforeach
				    </select>
				  </div>

				  <div class="form-group" id="admin_room_head_html">
				    <label for="staff_divisi"> For Admin Room :</label>
				    <select class="form-control" id="admin_room_role" 
				    name="admin_room_list">
				    	@foreach($data['admin_room_list'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->admin_room}} </option>
				    	@endforeach
				    </select>
				  </div>

				  <div class="form-group" id="id_group1_head">
				    <label for="staff_divisi"> Group 1 :</label>
				    <select class="form-control" id="id_group1_list" 
				    name="name_group1_list">
				    	@foreach($data['group_1'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->group1_name}} </option>
				    	@endforeach
				    </select>
				  </div>

				  <div class="form-group" id="id_group2_head">
				    <label for="staff_divisi"> Group 2 :</label>
				    <select class="form-control" id="id_group2_list" 
				    name="name_group2_list">
				    	@foreach($data['group_2'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->group2_name}} </option>
				    	@endforeach
				    </select>
				  </div>

				  <div class="form-group" id="id_group3_head">
				    <label for="staff_divisi"> Group 3 :</label>
				    <select class="form-control" id="id_group3_list" 
				    name="name_group3_list">
				    	@foreach($data['group_3'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->group3_name}} </option>
				    	@endforeach
				    </select>
				  </div>

				  <div class="form-group" id="id_group4_head">
				    <label for="staff_divisi"> Group 4 :</label>
				    <select class="form-control" id="id_group4_list" 
				    name="name_group4_list">
				    	<option value=""> </option>
				    	@foreach($data['group_4'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->group4_name}} </option>
				    	@endforeach
				    </select>
				    <span class="text-info"> note : optional </span> 
				  </div>


				  <div class="form-group" id="inventory_head">
				    <label for="staff_divisi"> For Inventory :</label>
				    <select class="form-control" id="inventory_role" 
				    name="inventory_list">
				    	@foreach($data['inventory_list'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->inventory_name}} </option>
				    	@endforeach
				    </select>
				  </div>

				  <div class="form-group">
				    <label for="staff_divisi"> Position :</label>
				    <select class="form-control" id="select_posisi" 
				    name="position" required="">
				    	<option value=""> </option>
				    </select>
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Company :</label>
				    <input type="text" class="form-control" id="nama" 
				    name="company" value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->company}} @endif" required="" 
				    placeholder="Company name is required">
				  </div>
				  
				  <button type="submit" class="btn btn-block btn-primary">
				  	Submit  
				  </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
     </div> 
    </div>
  </div>


<script type="text/javascript">
	$(document).ready(function(){
		$('#inventory_head').hide();
		$('#pic_head_html').hide();
		$('#admin_room_head_html').hide();
		$('#id_group1_head').hide();
		$('#id_group2_head').hide();
		$('#id_group3_head').hide();
		$('#id_group4_head').hide();
		$('#select_divisi').change(function(){
			$('#inventory_head').hide();
			$('#pic_head_html').hide();
			$('#admin_room_head_html').hide();
			$('#id_group1_head').hide();
			$('#id_group2_head').hide();
			$('#id_group3_head').hide();
			$('#id_group4_head').hide();
			$('#select_posisi').prop('disabled',false);
			$("#id_group1_list").prop('required',false);
			$("#id_group2_list").prop('required',false);
			$("#id_group3_list").prop('required',false);
			var value = $('#select_divisi').val();
			var data = {"divisi":value};
			$('#select_posisi')
			    .find('option')
			    .remove()
			    .end()

			 $.ajaxSetup({
			    	headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    	}
			});
			//alert(value);
			switch(value) {
				case "1" :
					$('#select_posisi').prop('disabled',true);
					$('#select_posisi').val("0");
					break;
				case "2" :
					$('#pic_head_html').show();
					$.ajax({
						url: 	"{{route('get_pic_level')}}",
						method: "POST", 
						contentType	: "application/json; charset=utf-8",
						data : JSON.stringify(data),
						success: function(result){
	        				$.each(JSON.parse(result), function(key, value) {   
							     $('#select_posisi')
							         .append($("<option></option>")
							                    .attr("value",value.id)
							                    .text(value.pic_level_name));
							});
	    				}
    				});
					break;
				case "3" : 
					$.ajax({
						url: 	"{{route('get_akses_role')}}",
						method: "POST", 
						contentType	: "application/json; charset=utf-8",
						data : JSON.stringify(data),
						success: function(result){
	        				$.each(JSON.parse(result), function(key, value) {   
							     $('#select_posisi')
							         .append($("<option></option>")
							                    .attr("value",value.id)
							                    .text(value.name));
							});
	    				}
	    			});
					break;
				case "4" :
					$('#inventory_head').show(); 
					$.ajax({
						url: 	"{{route('get_inventory_level')}}",
						method: "POST", 
						contentType	: "application/json; charset=utf-8",
						data : JSON.stringify(data),
						success: function(result){
	        				$.each(JSON.parse(result), function(key, value) {   
							     $('#select_posisi')
							         .append($("<option></option>")
							                    .attr("value",value.id)
							                    .text(value.inventory_level_name));
							});
	    				}
	    			});
	    			break;
	    		case "5" :
					$('#admin_room_head_html').show();
					$('#select_posisi').prop('disabled',true);
					$('#select_posisi').val("0"); 	
					break;
				case "6" :
					$('#id_group1_head').show();
					$('#id_group2_head').show();
					$('#id_group3_head').show();
					$('#id_group4_head').show();
					$('#inventory_head').show();
					$("#id_group1_list").prop('required',true);
					$("#id_group2_list").prop('required',true);
					$("#id_group3_list").prop('required',true);
 
					$.ajax({
						url: 	"{{route('get_inventory_level')}}",
						method: "POST", 
						contentType	: "application/json; charset=utf-8",
						data : JSON.stringify(data),
						success: function(result){
	        				$.each(JSON.parse(result), function(key, value) {   
							     $('#select_posisi')
							         .append($("<option></option>")
							                    .attr("value",value.id)
							                    .text(value.inventory_level_name));
							});
	    				}
	    			});
					break;
				default : alert("Please contact your administrators");break
			}
		})
	});
</script>
