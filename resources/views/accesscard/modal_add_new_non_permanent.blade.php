<!-- modal add new permanent-->
  <div id="new_non_permanent">
    <form method="POST" enctype="multipart/form-data"
    action="{{ route('post_new_access_card') }}">
      {{ csrf_field() }}
      
      <input type="hidden" name="register_id" value="2">

      <div class="form-group">
        <label for="staff_nama"> Full Name :</label>
        <input type="text" 
        name="new_full_name"  class="form-control" id="nama" 
        value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->name}} @endif"
        placeholder="full name is required" required="">
      </div>

      <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" 
        name="new_email" class="form-control" id="email"
        value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->email}} @endif"
        placeholder="email is required" required="">
      </div>

      <div class="form-group">
        <label for="staff_nama"> NIK : </label>
        <input type="text" 
        name="new_nik"  class="form-control" id="nama" 
        value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->uuid}} @endif"
        placeholder="nik is required" required="">
      </div>

       <div class="form-group">
          <label for="staff_nama"> Start Active Work :</label>
          <input type="text" 
          name="new_date_start" class="form-control datepicker_class" id=""
          value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->date}} @endif" 
          placeholder="example : 2018-09-01" required="">
      </div>

      <div class="form-group">
        <label for="staff_nama"> End Active Work :</label>
        <input type="text" 
        name="new_date_end" class="form-control datepicker_class" id=""
        value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->date}} @endif" 
        placeholder="example : 2018-09-01" required="">
      </div>

      <div class="form-group" id="head_pic_sub_level_authority">
        <label for="staff_nama"> 
          Select PIC-Sponsor :
        </label>
        <select class="form-control" name="new_sponsor">
            @foreach($data['pic_list'] as $key=>$val)
              <option value="{{$val->id}}"> 
                {{$val->vendor_name}} ({{$val->vendor_detail_name}})
              </option>
            @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="staff_nama"> KTP : </label>
        <input type="file" 
        name="new_ktp" class="form-control" id="nama"  
        required="">
      </div>

      <div class="form-group">
        <label for="staff_nama"> Additional Note : </label>
        <input type="text" 
        name="new_additional_note" class="form-control" id="nama"  
        value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->text}} @endif" 
        placeholder="optional for add any information">
      </div>

      <div class="form-group">
        <label for="staff_divisi"> Location Activities :</label>
        <input type="text" 
        name="new_location_activities" class="form-control" id="nama"  
        value="@if(env('ENV_STATUS', 'development') == 'development'){{$data['faker']->address}} @endif"
        placeholder="Location Activities is required" required="">
      </div>

      <div class="form-group">
        <label for="staff_nama"> Company Work Contract / PO : </label>
        <input type="file" 
        name="new_po" class="form-control" id="nama"  
        required="">
      </div>

      <button type="submit" class="btn btn-block btn-primary">
        Submit  
      </button>
    </form>
  </div>
  <!-- modal add new permanent-->