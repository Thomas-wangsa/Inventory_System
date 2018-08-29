<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 -->  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script>
  $( function() {

    $(".datepicker_class" ).datepicker({
      dateFormat: 'yy-mm-dd' ,
      showButtonPanel: true
    });
  });
  </script>
<!-- Modal -->
  <div class="modal fade" id="modal_all" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Add New Access Card
          	<div style="width: 100%">
          		<div class="col-sm-6" id="staff_main"  
          		style="cursor: pointer">
          			Staff Indosat
          		</div>
          		<div class="col-sm-6" id="vendor_main"
          		style="cursor: pointer">
          			Vendor
          		</div>
          		<div class="clearfix"> </div>
          	</div> 
          </h4>
        </div>
        <div class="modal-body">
        	<div id="staff">
	  			  <form method="POST" enctype="multipart/form-data"
              action="{{ route('post_pendaftaran_akses') }}">
              {{ csrf_field() }}
              <input type="hidden" name="type_daftar" value="staff">

              <div class="form-group">
                <label for="staff_nama"> Full Name :</label>
                <input type="text" class="form-control" id="nama" 
                      name="name" value="{{$data['faker']->name}}"
                    placeholder="full name is required" required="">
              </div>
          
              <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" 
                    id="email" name="email" value="{{$data['faker']->email}}"
                    placeholder="email is required" required="">
              </div>

              <div class="form-group">
                <label for="staff_nama"> NIK :</label>
                <input type="text" class="form-control" id="nama" 
                      name="nik" value="{{$data['faker']->randomNumber}}"
                    placeholder="NIK is required" required="">
              </div>

              <div class="form-group">
                <label for="staff_divisi"> No. Access Card :</label>
                <input type="text" class="form-control" 
                id="nama" name="no_access_card" value="{{$data['faker']->phoneNumber}}"
                placeholder="Optional" >
              </div>

              <div class="form-group">
                <label for="staff_nama"> Start Active Card :</label>
                <input type="text" class="form-control datepicker_class" 
                id="" name="date_start" value="2018-09-01" 
                    placeholder="example : 2018-09-01" required="">
              </div>

              <div class="form-group">
                <label for="staff_nama"> End Active Card :</label>
                <input type="text" class="form-control datepicker_class" 
                id="" name="date_end" value="2019-09-01"
                placeholder="example : 2018-09-15" required="">
              </div>

              <div class="form-group">
                <label for="staff_divisi"> Division :</label>
                <input type="text" class="form-control" 
                id="nama" name="divisi" value="{{$data['faker']->company}}"
                placeholder="Optional" required="">
              </div>


              <div class="form-group">
                <label for="staff_divisi"> Position :</label>
                <input type="text" class="form-control" 
                id="nama" name="jabatan" value="{{$data['faker']->jobTitle}}"
                placeholder="Positiion" required="">
              </div>


              <div class="form-group">
                <label for="staff_nama"> Additional Note : </label>
                <input type="text" class="form-control" 
                id="nama" name="additional_note" value="Kerja di {{$data['faker']->company}}" 
                placeholder="optional for add any information ">
              </div>

              <div class="form-group">
                <label for="staff_nama"> ID Card :</label>
                <input type="file" class="form-control" id="nama" name="foto" required="">
              </div>

              <div class="form-group">
                <label for="staff_nama"> Register By Level Authority :</label>
                <select class="form-control" name="level_authority" 
                required="" onchange="conditional_show()" id="select_level_authority">
                    <option value=""> select level authority </option>
                    @foreach($data['level_authority']['divisi'] as $key=>$val)
                      <option value="{{$val->id}}"> 
                        {{$val->name}}
                      </option>
                    @endforeach
                </select>
              </div>

              <div class="form-group" id="head_pic_sub_level_authority">
                <label for="staff_nama"> Register By Pic Sub Level Authority  :</label>
                <select class="form-control" name="pic_level_authority">
                    @foreach($data['level_authority']['pic'] as $key=>$val)
                      <option value="{{$val->id}}"> 
                        {{$val->pic_level_name}} {{$val->vendor_name}} ({{$val->vendor_detail_name}})
                      </option>
                    @endforeach
                </select>
              </div>

              <div class="form-group" id="head_access_sub_level_authority">
                <label for="staff_nama"> Register By Access Sub Level Authority  :</label>
                <select class="form-control" name="access_level_authority">
                    @foreach($data['level_authority']['access'] as $key=>$val)
                      <option value="{{$val->id}}"> 
                        {{$val->name}}
                      </option>
                    @endforeach
                </select>
              </div>

              <button type="submit" class="btn btn-block btn-primary">
                Request Access Card 
              </button>
            </form>
        	</div>

        	<div id="vendor">
	  			<form method="POST" enctype="multipart/form-data"
	  			action="{{ route('post_pendaftaran_akses') }}">
	  			  {{ csrf_field() }}
	  			  <input type="hidden" name="type_daftar" value="vendor">

	  			  <div class="form-group">
  				    <label for="staff_nama"> Full Name :</label>
  				    <input type="text" class="form-control" id="nama" 
              			name="name" value="{{$data['faker']->name}}"
              		placeholder="full name is required" required="">
				    </div>
				  
  				  <div class="form-group">
  				    <label for="email">Email :</label>
  				    <input type="email" class="form-control" 
  				        id="email" name="email" value="{{$data['faker']->email}}"
              		placeholder="email is required" required="">
  				  </div>

            <div class="form-group">
              <label for="staff_nama"> NIK :</label>
              <input type="text" class="form-control" id="nama" 
                    name="nik" value="1234456"
                  placeholder="NIK is required" required="">
            </div>

            

            <div class="form-group">
              <label for="staff_divisi"> No. Access Card :</label>
              <input type="text" class="form-control" 
              id="nama" name="no_access_card" value="757585858"
              placeholder="Optional" >
            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Start Active Card :</label>
  				    <input type="text" class="form-control datepicker_class" 
  				    id="start_card_new" name="date_start" value="2018-09-01" 
              		placeholder="example : 2018-09-01" required="">
  				  </div>

	            <div class="form-group">
	              <label for="staff_nama"> End Active Card :</label>
	              <input type="text" class="form-control datepicker_class" 
	              id="end_card_new" name="date_end" value="2019-09-01"
	              placeholder="example : 2019-09-15" required="">
	            </div>

            <div class="form-group">
              <label for="staff_nama"> Pic Category :</label>
              <select class="form-control" name="pic_list_id" required="">
                  <option value=""> select pic category </option>
                  @foreach($data['pic_list'] as $key=>$val)
                    <option value="{{$val->id}}"> 
                      {{$val->vendor_name}} ({{$val->vendor_detail_name}})
                    </option>
                  @endforeach

              </select>
            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Floor Activities : </label>
  				    <input type="text" class="form-control" 
              id="nama" name="floor" value="Lantai 12" 
              placeholder="example : lantai 11,21" required="">
  				  </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> Additional Note : </label>
  				    <input type="text" class="form-control" 
              id="nama" name="additional_note" value="Kerja di {{$data['faker']->company}}" 
              placeholder="optional for add any information ">
  				  </div>

            <div class="form-group">
              <label for="staff_nama"> Kontrak Kerja (PO) :</label>
              <input type="file" class="form-control" id="nama" name="po" required="">
            </div>

  				  <div class="form-group">
  				    <label for="staff_nama"> ID Card :</label>
  				    <input type="file" class="form-control" id="nama" name="foto" required="">
  				  </div>

				  <button type="submit" class="btn btn-block btn-primary">
            Request Access Card 
          </button>
				</form>
        	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

 
<script type="text/javascript">
  $(document).ready(function(){
    $("#vendor").hide();
      $("#staff_main").css("border-bottom","3px solid #e1a435");
      $("#vendor_main").css("border-bottom","1px solid #979797")

      $("#staff_main").click(function(){
          $("#staff").show();
          $("#vendor").hide();
          $("#staff_main").css("border-bottom","3px solid #e1a435");
          $("#vendor_main").css("border-bottom","1px solid #979797");
      });

      $("#vendor_main").click(function(){
          $("#staff").hide();
          $("#vendor").show();
          $("#vendor_main").css("border-bottom","3px solid #e1a435");
          $("#staff_main").css("border-bottom","1px solid #979797");
      });

    $('#head_access_sub_level_authority').hide();
    $('#head_pic_sub_level_authority').hide();
  });

  function conditional_show() {
      var select_value = $('#select_level_authority').val();

      if(select_value == 2) {
        $('#head_pic_sub_level_authority').show();
        $('#head_access_sub_level_authority').hide();
      } else if(select_value == 3) {
        $('#head_pic_sub_level_authority').hide();
        $('#head_access_sub_level_authority').show();
      }
  }
</script>