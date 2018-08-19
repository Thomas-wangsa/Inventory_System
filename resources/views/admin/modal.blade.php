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
				    name="name" value="" required="" 
				    placeholder="Only letters and whitespace allowed...">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> NIK :</label>
				    <input type="text" class="form-control" id="nama" 
				    name="nik" value="" required="" 
				    placeholder="NIK is required">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email Default :</label>
				    <input type="email" class="form-control" id="nama" 
				    name="email" value="" required="" 
				    placeholder="example@example.com">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email Secondary :</label>
				    <input type="email" class="form-control" id="nama" 
				    name="email_second" value=""  
				    placeholder="example@example.com (Optional)">
				  </div>

				  <div class="form-group">
				    <label for="staff_nama"> Mobile :</label>
				    <input type="text" class="form-control" id="nama" 
				    name="mobile" value="" required="" 
				    placeholder="Only numbers allowed...">
				  </div>
				  	
				  <div class="form-group">
              		<label for="staff_nama"> Personal Identity : </label>
              		<input type="file" class="form-control" id="nama" 
              		name="Personal_Identity" required="">
            	</div>

				  <div class="form-group">
				    <label for="staff_divisi"> Level Authority :</label>
				    <select class="form-control" id="select_divisi" 
				    name="divisi" required="">
				    	<option value=""> </option>
				    	@foreach($data['divisi'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->name}}</option>
				    	@endforeach 
				    </select>
				  </div>

				  <div class="form-group" id="inventory_head">
				    <label for="staff_divisi"> Inventory Authority :</label>
				    <select class="form-control" id="inventory_role" 
				    name="inventory_list">
				    	@foreach($data['inventory_list'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->inventory_name}} </option>
				    	@endforeach
				    </select>
				  </div>


				  <div class="form-group" id="pic_head_html">
				    <label for="staff_divisi"> PIC Authority :</label>
				    <select class="form-control" id="pic_role" 
				    name="pic_list">
				    	@foreach($data['pic_list'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->vendor_name}} </option>
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
				    name="company" value="" required="" 
				    placeholder="Company name is required">
				  </div>
				  
				  <button type="submit" class="btn btn-block btn-primary">
				  	Add User 
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
		$('#select_divisi').change(function(){
			$('#inventory_head').hide();
			$('#pic_head_html').hide();
			$('#select_posisi').prop('disabled',false);
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
				default : alert("Please contact your administrator");break
			}
		})
	});
</script>
