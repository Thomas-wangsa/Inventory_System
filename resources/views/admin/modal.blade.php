<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Tambah Akun
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			<form method="POST" action="{{ route('create_new_users') }}">
				  {{ csrf_field() }}
				  <div class="form-group">
				    <label for="staff_nama"> Nama Lengkap :</label>
				    <input type="text" class="form-control" id="nama" 
				    name="staff_nama" 
				    value="" 
				    placeholder="Username">
				  </div>


				  <div class="form-group">
				    <label for="email"> Email :</label>
				    <input type="email" class="form-control" id="email" 
				    name="staff_email" 
				    value="" 
				    placeholder="User Email Default">
				  </div>

				  <div class="form-group">
				    <label for="email"> Email-2 :</label>
				    <input type="email" class="form-control" id="email" 
				    name="staff_email2" 
				    value="" 
				    placeholder="User Email Secondary">
				  </div>

				  <div class="form-group">
				    <label for="email"> No Handphone :</label>
				    <input type="text" class="form-control" id="email" name="staff_mobile" 
				    value=""
				    placeholder="User Phone">
				  </div>
				  
				  <div class="form-group">
				    <label for="staff_divisi"> Akses Level :</label>
				    <select class="form-control" id="select_divisi" name="select_divisi" required="">
				    	<option value=""> </option>
				    	@foreach($data['divisi'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{ucfirst($val->name)}}</option>
				    	@endforeach 
				    </select>
				  </div>

				  <div class="form-group" id="inventory_head">
				    <label for="staff_divisi"> Inventory Role :</label>
				    <select class="form-control" id="inventory_role" name="inventory_list">
				    	@foreach($data['inventory_list'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->inventory_name}} </option>
				    	@endforeach
				    </select>
				  </div>


				  <div class="form-group" id="pic_head_html">
				    <label for="staff_divisi"> PIC Role :</label>
				    <select class="form-control" id="pic_role" name="pic_list">
				    	@foreach($data['pic_list'] as $key=>$val)
				    	<option value="{{$val->id}}"> {{$val->vendor_name}} </option>
				    	@endforeach
				    </select>
				  </div>

				  <div class="form-group">
				    <label for="staff_divisi"> Jabatan :</label>
				    <select class="form-control" id="select_posisi" name="select_posisi">
				    	<option value=""> </option>
				    </select>
				  </div>
				  
				
				  
				  <button type="submit" class="btn btn-block btn-warning">Tambah Pengguna </button>
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
