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
	@case("6")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>

			@if(in_array(1,$user_divisi)
    		||
			$data['admin_room'][$key] 
			)
    			<button 
    			class="btn btn-primary"
    			onclick="approve(7,'{{$val->uuid}}')"
    			>
    				Approve Access Card
    			</button>
    			<button 
    			class="btn btn-danger"
    			onclick="remove(15,'{{$val->uuid}}')" 
    			>
    				Reject Access Card
    			</button>
    		@endif
		</div>
	@break
	@case("7")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>

			@if(in_array(1,$user_divisi)
    		||
			$data['staff_activation'] == true
			)
    			<button 
    			class="btn btn-primary"
    			onclick="approve(8,'{{$val->uuid}}')"
    			>
    				Approve Access Card
    			</button>
    			<button 
    			class="btn btn-danger"
    			onclick="remove(16,'{{$val->uuid}}')" 
    			>
    				Reject Access Card
    			</button>
    		@endif
		</div>
	@break
	@case("8")
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
    			class="btn btn-primary"
    			onclick="approve(9,'{{$val->uuid}}')"
    			>
    				Approve Access Card
    			</button>
    			<button 
    			class="btn btn-warning"
    			onclick="set_photo('{{$val->uuid}}')" 
    			>
    				Set Pick Up Access Card
    			</button>
    		@endif
		</div>
	@break
	@case("9")
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
    			class="btn btn-danger"
    			onclick="deactive_access_card('{{$val->no_access_card}}','{{$val->uuid}}')"
    			>
    				Deactive Access Card
    			</button>
    		@endif
		</div>
	@break
	@case("10")
	@case("11")
	@case("12")
	@case("13")
	@case("14")
	@case("15")
	@case("16")
	@case("17")
	@case("18")
		<div class="btn-group-vertical">
			<button 
			class="btn btn-info"
			onclick="info('{{$val->status_akses}}','{{$val->uuid}}')"
			>
				Info Access Card
			</button>
		</div>
		<br/> <br/>
		{{$val->comment}}
	@break;

@endswitch



