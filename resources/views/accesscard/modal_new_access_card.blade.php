<!-- modal -->
<div class="modal fade" id="modal_new_access_card" role="dialog">
  <div class="modal-dialog">
    <!-- modal content-->
    <div class="modal-content">

      
      <!-- modal header-->
      <div class="modal-header" style="border-bottom:0px">
          <button type="button" 
          class="close" data-dismiss="modal">
            &times;
          </button>
          <h4 class="modal-title text-center">
            Add New Access Card
            <div style="width: 100%">
              <div class="col-sm-6" id="new_permanent_main"  
              style="cursor: pointer">
                Permanent
              </div>
              <div class="col-sm-6" id="new_non_permanent_main"
              style="cursor: pointer">
                Non Permanent
              </div>
              <div class="clearfix"> </div>
            </div> 
          </h4>
      </div>
      <!-- modal header-->


      <!-- modal body-->
      <div class="modal-body">

        <!-- modal note-->
        <div id="new_note" style="margin-bottom: 20px">
          <div> 
            <strong> Note : </strong>
          </div>
          <div>
            1. Full Name,Email,NIK,Start & End Work,Sponsor,KTP are mandatory
            <br/>
            2. Additional Note is optional
            <br/>
            3. <strong> Permanent : </strong> Division & Position are mandatory
            <br/>
            4. <strong> Non Permanent : </strong> Location Activities,Contract PO are mandatory 
            <br/>
            5. Only for <b> new worker </b> or for rejected,deactivated or expired access card. 
          </div>
        </div>
        <!-- modal note-->

        @include('accesscard.modal_add_new_permanent')
        @include('accesscard.modal_add_new_non_permanent')

        
      </div>
      <!-- modal body-->

      <!-- modal footer-->
      <div class="modal-footer">
        <button type="button" 
        class="btn btn-danger" data-dismiss="modal">
          Close
      </button>
      </div>
      <!-- modal footer-->


    </div>
    <!-- modal content-->
  </div>
</div>
<!-- modal -->


<script type="text/javascript">
  $(document).ready(function(){
    $("#new_non_permanent").hide();
      $("#new_permanent_main").css("border-bottom","3px solid #e1a435");
      $("#new_non_permanent_main").css("border-bottom","1px solid #979797")

      $("#new_permanent_main").click(function(){
          $("#new_permanent").show();
          $("#new_non_permanent").hide();
          $("#new_permanent_main").css("border-bottom","3px solid #e1a435");
          $("#new_non_permanent_main").css("border-bottom","1px solid #979797");
      });

      $("#new_non_permanent_main").click(function(){
          $("#new_permanent").hide();
          $("#new_non_permanent").show();
          $("#new_non_permanent_main").css("border-bottom","3px solid #e1a435");
          $("#new_permanent_main").css("border-bottom","1px solid #979797");
      });

  });

</script>