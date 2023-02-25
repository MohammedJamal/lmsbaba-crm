<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('adminmaster/includes/head'); ?>    
  </head>
  <body>
    <div class="app full-width">
        <div class="main-panel">            
            <div class="main-content">              
                <div class="content-view"> 
                	<div class="card process-sec">
                		<div class="filter_holder new">
	                      <div class="pull-left">
	                        <h5 class="lead_board">Add Client </h5>
	                      </div>
	                  	</div>
	                  	<form class="form-horizontal" id="FrmClientAdd" name="FrmClientAdd">
							<div class="form-group">
							    <label class="control-label col-sm-2" for="email">Company Name:</label>
							    <div class="col-sm-10">
							      <input type="text" class="form-control" id="name" name="name" placeholder="Enter Company Name">
							    </div>
							</div>
							<div class="form-group">
							    <div class="col-sm-offset-2 col-sm-10">
							      <button type="button" class="btn btn-primary" id="client_add_confirm">Submit</button>
							    </div>
							</div>
						</form> 
                	</div>                	 
                </div>                
            </div>
            <div class="content-footer">
              <?php $this->load->view('adminmaster/includes/footer'); ?>
            </div>
        </div>
    </div>
  </body>
</html>
<script type="text/javascript">
	$(document).ready(function(){
		$("body").on("click","#client_add_confirm",function(e){
			e.preventDefault();
	        var base_url = $("#base_url").val();	        
	        $.ajax({
	            url: base_url + "adminmaster/client_add_ajax",
	            data: new FormData($('#FrmClientAdd')[0]),
	            cache: false,
	            method: 'POST',
	            dataType: "html",
	            mimeType: "multipart/form-data",
	            contentType: false,
	            cache: false,
	            processData: false,
	            beforeSend: function(xhr) {},
	            success: function(data) {
	                result = $.parseJSON(data); 
	                alert(result.msg)
	                if (result.status == 'success') {
	                    swal({
	                        title: 'Success',
	                        text: '',
	                        type: 'success',
	                        showCancelButton: false
	                    }, function() {                           
	                        // location.reload();
	                    });
	                }
	                else
	                {

	                  
	                }
	            }
	        });
		});
	});
</script>
