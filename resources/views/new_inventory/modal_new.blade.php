<!-- Modal -->
  <div class="modal fade" id="modal_new" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">
          	Insert new inventory
          </h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" 
          method="POST" enctype="multipart/form-data"
          action="{{ route('new_inventory_draft_data') }}">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Insert inventory AS :
              </label>
              <div class="col-xs-8">
                <select class="form-control"
                id="checking_jabatan" 
                name="new_inventory_role_id" 
                required="">
                  <option value=""> Select role inventory </option>
                  @foreach($data['list_new_inventory_role'] as $key=>$val)
                  <option value="{{$val->jabatan}}">
                    {{$val->nama_jabatan}} 
                  </option>
                  @endforeach
                </select>
              </div>  
            </div>


            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Inventory Name :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="checking_inventory_name" name="inventory_name" value=""
                required="" 
                placeholder="Required Field" >
              </div>
            </div>

            <div class="form-group conditional_checking_reverse"> 
              <div class="col-xs-12">
                <div class="btn btn-success btn-block" onclick="validate_inventory()">
                  Checking Inventory 
                </div>
              </div>
            </div>

            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                QTY :
              </label>
              <div class="col-xs-8">
                <input type="number" class="form-control" 
                id="" name="qty" value=""
                required="" 
                placeholder="Required Field">
              </div>
            </div>

            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Tanggal Update :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control datepicker_class" 
                id="" name="tanggal_update_data" value=""
                required="" 
                placeholder="example : 2018-09-15" >
              </div>
            </div>

            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 1 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kategori" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 2 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kode_gambar" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 3 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="dvr" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 4 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="lokasi_site" value=""
                placeholder="Optional..." >
              </div>
            </div>

            

            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 5 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kode_lokasi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 6 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="jenis_barang" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 7 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="merk" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 8 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tipe" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 9 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="model" value=""
                placeholder="Optional..." >
              </div>
            </div>


            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 10 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="serial_number" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 11 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="psu_adaptor" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 12 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tahun_pembuatan" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 13 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tahun_pengadaan" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 14 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kondisi" value=""
                placeholder="Optional..." >
              </div>
            </div>



            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 15 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="deskripsi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 16 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="asuransi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 17 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="lampiran" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 18 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tanggal_retired" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 19 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="po" value=""
                placeholder="Optional..." >
              </div>
            </div>

            <div class="form-group conditional_checking">
              <label class="control-label col-xs-4" for="email">
                Ket 20 :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="keterangan" value=""
                placeholder="Optional..." >
              </div>
            </div>




            <div class="form-group conditional_checking"> 
              <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block">
                  Insert Inventory Data
                </button>
              </div>
            </div>
          </form>          
        </div> <!--modal-body-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">
            Close
          </button>
        </div>
      </div>
      
    </div>
  </div>

<script type="text/javascript">

  $('.conditional_checking').hide();

  function validate_inventory() {

    jabatan = $('#checking_jabatan').val();
    inventory_name = $('#checking_inventory_name').val();

    if(jabatan == null || jabatan == "") {
      alert("Please select role inventory");
      return
    }

    if(inventory_name == null || inventory_name == "") {
      alert("Please set the inventory name");
      return
    }

    var data = {
        "new_inventory_role_id":jabatan,
        "inventory_name":inventory_name
    };

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });


    $.ajax({
      type : "POST",
      url: " {{ route('new_inventory_checking_data') }}",
      contentType: "application/json",
      data : JSON.stringify(data),
      success: function(result) {
        var response = JSON.parse(result);
        if(response.status) {
          $("#checking_jabatan").prop('readonly', true);
          $("#checking_inventory_name").prop('readonly', true);
          $('.conditional_checking').show();
          $('.conditional_checking_reverse').hide();
        } else {
          alert(response.message);
        }
      },
      error: function( jqXhr, textStatus, errorThrown ){
        console.log( errorThrown );
      }
    })
  }
</script>