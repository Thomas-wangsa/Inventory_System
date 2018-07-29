<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<!-- Modal -->
  <div class="modal fade" id="modal_special" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="border-bottom:0px">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
          	Akun Thomas
          </h4>
        </div>
        <div class="modal-body">
        	<table class="table table-bordered">
			    <thead>
			      <tr>
			        <th> Feature </th>
			        <th> Status </th>
			      </tr>
			    </thead>
			    <tbody>
			      <tr>
			        <td> Edit Background </td>
			        <td>
			        	<label class="switch">
						  <input type="checkbox">
						  <span class="slider round"></span>
						</label> 
			        </td>
			      </tr>
			      <tr>
			        <td> Export CSV Inventory </td>
			        <td>
			        	<label class="switch">
						  <input type="checkbox">
						  <span class="slider round"></span>
						</label> 
			        </td>
			      </tr>
			      <tr>
			        <td> Add List Inventory </td>
			        <td>
			        	<label class="switch">
						  <input type="checkbox">
						  <span class="slider round"></span>
						</label>  
			        </td>
			      </tr>
			      <tr>
			        <td> Sharing Role Inventory </td>
			        <td>
			        	<label class="switch">
						  <input type="checkbox">
						  <span class="slider round"></span>
						</label> 
			         </td>
			      </tr>

			      <tr>
			        <td> Report </td>
			        <td>
			        	<label class="switch">
						  <input type="checkbox">
						  <span class="slider round"></span>
						</label> 
			         </td>
			      </tr>
			    </tbody>
			  </table>
        </div>
        <div class="modal-footer">
        	<button type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
      
    </div>
  </div>

