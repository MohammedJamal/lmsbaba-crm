<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
<head>
<?php $this->load->view('adminmaster/includes/head'); ?>  
<style>
.select2-container .select2-selection {
    height: auto; !important;
    max-height: initial; !important;
}
</style>  
</head>
<body>
	<form name="updateQueryFrm" id="updateQueryFrm">
    <div class="app full-width">
        <div class="main-panel">            
			<div class="main-content">              
				<div class="content-view"> 
					<div class="card process-sec">
							<div class="filter_holder new">
						  <div class="pull-left">
							<h5 class="lead_board"> All Database Update </h5>
						  </div>
					  </div>
						<div class="form-group">
							<div class="col-md-12">
								<label><h4>Select DB</h4></label>
								<select class="form-control select2" name="client_id[]" id="client_id" multiple>		    		
									<?php foreach($get_all_client AS $client){ ?>
									<option value="<?php echo $client->id;?>"><?php echo $client->name;?> - <?php echo $client->id;?></option>
									<?php } ?>
								</select>
							</div>
							<div>&nbsp;</div>
							<div class="col-md-12">
								<button type="button" onclick="selectAll()">Select All DB</button>
								<button type="button" onclick="deselectAll()">Deselect All DB</button>
							</div>
						</div>    
						
						<div class="form-group">
							<div class="col-sm-12">
								<label><h4>Query</h4></label>
								<textarea name="query_script" id="query_script" class="form-control" rows="10"></textarea>
							</div>		    
						</div>
						<div class="form-group">
							<div class="col-sm-12 text-center">
								<input type="button" value="Run" class="btn btn-primary" style="width:100px;" id="run_db_update_confirm">
							</div>
						</div>						
					</div>                	 
				</div>                
			</div>
          <div class="content-footer">
            <?php $this->load->view('adminmaster/includes/footer'); ?>
          </div>
        </div>
    </div>
  </form>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

<script type="text/javascript">
	function selectAll() {
    $("#client_id > option").prop("selected", true);
    $("#client_id").trigger("change");
}

function deselectAll() {
    $("#client_id > option").prop("selected", false);
    $("#client_id").trigger("change");
}
$(document).ready(function(){
	$("#client_id").select2();
	$("body").on("click","#run_db_update_confirm",function(e){

		var base_url=$("#base_url").val();
		var client_id=$("#client_id").val();
		var query_script=$("#query_script").val();
		
		if(client_id=='' || client_id==null)
		{
			alert("Please select client");
			return false;
		}
		if(query_script=='')
		{
			alert("Please enter query");
			return false;
		}
    $.ajax({
        url: base_url + "adminportal/database_update/update_custom_db_ajax",
        data: new FormData($('#updateQueryFrm')[0]),
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        // cache: false,
        processData: false,
        async: false,
        beforeSend: function(xhr) {
        },
        complete: function (){            
        },
        success: function(data) {
          result = $.parseJSON(data); 
          alert(result.msg);
          // if(result.status=='success')
          // {
          // 	alert(result.msg);
          // }
        }
    });
	});
});
</script>
