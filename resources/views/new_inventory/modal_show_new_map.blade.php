<style type="text/css">
	tbody #info_tbody tr th {background-color: red} 
</style>

<!-- Modal -->
  <div class="modal fade" id="modal_show_new_map" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center" >
          	Detail information map list
          </h4>
        </div>
        <div class="modal-body">
  			
  			@if(count($data['map_data']) > 0)
  				@foreach($data['map_data'] as $key=>$val)
  					<div class="panel panel-primary" id="head_modal_info_map_location">
				      <div class="panel-heading text-center">
				      	Map {{$val->map_name}}
				      </div>
				      	<div class="panel-body">
					      	<img class="img-responsive"
				      		id="modal_info_map_location" 
					      	src="{{$val->map_images}}" 
					      	alt="Chania">

				      	</div> <!--panel body-->
				      	<div class="panel-footer">
				      		note : {{$val->map_notes}}
				      		<br/>
				      		created : {{$val->created_at}}
				      	</div>
				    </div> <!--panel-->
  				@endforeach
  			@endif 
		  

  		</div> <!--modal body-->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      
     </div> <!--modal content-->


    </div>
  </div>

