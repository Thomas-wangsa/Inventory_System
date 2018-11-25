@switch($val->status_akses)
	@case("1")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>

			@if(in_array(1,$user_divisi)
    				||
    				$data['sponsor_access_data'][$key]
    				)
    			<button 
    			class="btn btn-primary"
    			onclick="approve(2,'{{$val->uuid}}')"
    			>
    				Approve Access Card
    			</button>
    			<button 
    			class="btn btn-danger"
    			onclick="remove(10,'{{$val->uuid}}')" 
    			>
    				Reject Access Card
    			</button>
    		@endif
		</div>
	@break
	@case("2")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>

			@if(in_array(1,$user_divisi)
    				||
    				$data['verification'] == true
    				)
    			<button 
    			class="btn btn-primary"
    			onclick="approve(3,'{{$val->uuid}}')"
    			>
    				Approve Access Card
    			</button>
    			<button 
    			class="btn btn-danger"
    			onclick="remove(11,'{{$val->uuid}}')" 
    			>
    				Reject Access Card
    			</button>
    		@endif
		</div>
	@break
	@case("3")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>

			@if(in_array(1,$user_divisi)
    				||
    				$data['approval_verification'] == true
    				)
    			<button 
    			class="btn btn-primary"
    			onclick="approve(4,'{{$val->uuid}}')"
    			>
    				Approve Access Card
    			</button>
    			<button 
    			class="btn btn-danger"
    			onclick="remove(12,'{{$val->uuid}}')" 
    			>
    				Reject Access Card
    			</button>
    		@endif
		</div>
	@break
	@case("4")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>

			@if(in_array(1,$user_divisi)
			||
			$data['card_printing'] == true
			)
    			<button 
    			class="btn btn-warning"
    			onclick="set_photo('{{$val->uuid}}')" 
    			>
    				Set Photo
    			</button>
    			<button 
    			class="btn btn-primary"
    			onclick="set_access_card('{{$val->name}}','{{$val->uuid}}')"
    			>
    				Set Access Card Number
    			</button>
    		@endif
		</div>
	@break
	@case("5")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>

			@if(in_array(1,$user_divisi)
			||
			$data['approval_activation'] == true
			)
    			<button 
    			class="btn btn-primary"
    			onclick="approve(7,'{{$val->uuid}}')"
    			>
    				Approve Access Card
    			</button>
    			<button 
    			class="btn btn-warning"
    			onclick="check_admin_room('{{$val->name}}','{{$val->uuid}}')"
    			>
    				Confirmation to Admin Room
    			</button>
    			<button 
    			class="btn btn-danger"
    			onclick="remove(14,'{{$val->uuid}}')" 
    			>
    				Reject Access Card
    			</button>
    		@endif
		</div>
	@break
@endswitch

@include('accesscard.modal_new_update_access_card')
@include('accesscard.modal_new_set_admin_room')


<script type="text/javascript">

	function set_photo(uuid) {
		alert("waiting for open smtm connection");
	}

	function set_access_card(name,uuid) {
		$('#modal_new_update_access_card_name').val(name);
		$('#modal_new_update_access_card_uuid').val(uuid);
		$('#modal_new_update_access_card').modal('show');
	}

	function check_admin_room(name,uuid) {
		$('#modal_new_set_admin_room_name').val(name);
		$('#modal_new_set_admin_room_uuid').val(uuid);
		$('#modal_new_set_admin_room').modal('show');
	}

	function approve(status,uuid) {
		if (confirm('Approve this request ?')) { 
			var url = "{{URL::to('/')}}"+'/akses_approval?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+status;
		}
	}


	function remove(status,uuid) {
		if (confirm('Reject this request ?')) {
			var url = "{{URL::to('/')}}"+'/akses_reject?uuid=';
			var url_status = "&next_status=";
			window.location = url+uuid+url_status+status;
		}
	}
</script>