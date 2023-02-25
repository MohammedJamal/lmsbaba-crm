<title>lmsbaba.com</title>
<link rel="icon" href="<?=assets_url();?>images/favicon_8.ico" type="image/ico" sizes="18x18">
<link rel="icon" href="<?=assets_url();?>images/favicon_8.ico" type="image/ico" sizes="18x18">
<!-- <link rel="stylesheet" href="<?=assets_url();?>css/bootstrap.min.css"> -->
<link rel="stylesheet" href="<?=assets_url();?>vendor/bootstrap/dist/css/bootstrap.css"/>
<link rel="stylesheet" href="<?=assets_url();?>vendor/font-awesome/css/font-awesome.css"/>
<link rel="stylesheet" href="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.css"/>
<link rel="stylesheet" href="<?=assets_url();?>css/app.css?v=<?php echo rand(0,1000); ?>" id="loadtsf_styles_before"/>
<!-- <script src="<?php echo assets_url();?>js/jquery.blockUI.js"></script> -->
<link rel="stylesheet" href="<?=assets_url();?>css/responsive.css"/>	
<!-- <script src="<?=assets_url();?>vendor/jquery/dist/jquery.js"></script> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<input type="hidden" id="base_url" value="<?php echo adminportal_url();?>">
<input type="hidden" id="assets_base_url" value="<?php echo assets_url();?>">
<input type="hidden" id="clientportal_url_root" value="<?php echo base_url();?>">
<script>
function menu_toggle() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}
</script>
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
}

.mobile-container {
  max-width: 480px;
  margin: auto;
  background-color: #555;
  height: 500px;
  color: white;
  border-radius: 10px;
}

.topnav {
  overflow: hidden;
  background-color: #333;
  position: relative;
}

.topnav #myLinks {
  display: none;
}

.topnav a {
  float: left;
  color: white;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a.icon {
  float: right;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.active {
  background-color: #04AA6D;
  color: white;
}
</style>  
