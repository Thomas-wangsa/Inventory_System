@if(in_array(1,$user_divisi) || in_array(3,$user_setting))
<div class="pull-left" style="margin-right: 20px">
	<div class="btn btn-success" 
	data-toggle="modal" data-target="#modal_new_pic">
		add new pic category 
	</div>
</div>
@endif
@if(in_array(1,$user_divisi) || in_array(8,$user_setting))
<div class="pull-left">
	<div class="btn btn-success" 
	data-toggle="modal" data-target="#modal_new_admin_room">
		add new admin room category 
	</div>
</div>
@endif
<div class="clearfix" style="margin-bottom: 15px"> </div>
@include('accesscard.modal_new_pic')
@include('accesscard.modal_new_admin_room')