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
        <div class="topnav">
            <?php $this->load->view('adminmaster/includes/top_menu'); ?>            
        </div>
    	<div class="card process-sec">
    		<div class="filter_holder new">
              <div class="pull-left">
                <h5 class="lead_board">  </h5>
              </div>
          	</div>

			<div class="form-group">
		    	<div class="col-sm-12 text-center">
                    <h4 class="text-primary">Welcome to LMSBaba Admin Panel</h4>
                    <p>Please contact to administrator for permission to access feature.</p>
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
</body>
</html>
