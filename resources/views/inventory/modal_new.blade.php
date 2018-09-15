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
          action="{{ route('inventory_insert_data') }}">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Insert inventory AS :
              </label>
              <div class="col-xs-8">
                <select class="form-control" 
                name="inventory_list_id" 
                required="">
                  <option value=""> Select role inventory </option>
                  @foreach($data['inventory_list_id'] as $key=>$val)
                  <option value="{{$val->id}}">
                    {{$val->inventory_name}} ({{$val->inventory_detail_name}})
                  </option>
                  @endforeach
                </select>
              </div>
            </div>


            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tanggal Update :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control datepicker_class" 
                id="" name="tanggal_update_data" value=""
                placeholder="example : 2018-09-15" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kategori :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kategori" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kode Gambar :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kode_gambar" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                DVR :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="dvr" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Lokasi site :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="lokasi_site" value=""
                placeholder="Optional..." >
              </div>
            </div>

            

            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kode Lokasi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kode_lokasi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Jenis Barang :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="jenis_barang" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Merk :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="merk" value=""
                required="" 
                placeholder="Required Field" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tipe :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tipe" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Model :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="model" value=""
                placeholder="Optional..." >
              </div>
            </div>


            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Serial Number :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="serial_number" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                PSU Adaptor :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="psu_adaptor" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tahun Pembuatan :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tahun_pembuatan" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tahun Pengadaan :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tahun_pengadaan" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Kondisi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="kondisi" value=""
                placeholder="Optional..." >
              </div>
            </div>



            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Deskripsi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="deskripsi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Asuransi :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="asuransi" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Lampiran :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="lampiran" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Tanggal Retired :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="tanggal_retired" value=""
                placeholder="Optional..." >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                PO :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="po" value=""
                placeholder="Optional..." >
              </div>
            </div>



            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                QTY :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="qty" value=""
                required="" 
                placeholder="Required Field">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-xs-4" for="email">
                Keterangan :
              </label>
              <div class="col-xs-8">
                <input type="text" class="form-control" 
                id="" name="keterangan" value=""
                placeholder="Optional..." >
              </div>
            </div>




            <div class="form-group"> 
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

