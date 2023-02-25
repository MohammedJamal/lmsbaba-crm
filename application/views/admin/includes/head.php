<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?php echo $page_description; ?>">
<meta name="keywords" content="<?php echo $page_keyword; ?>">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1"/>
<meta name="msapplication-tap-highlight" content="no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="Milestone">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Milestone">
<meta name="theme-color" content="#4C7FF0">    
<title>LMS for <?php echo $page_title; ?> | Powered by LMSBABA.COM</title>
<link rel="icon" href="<?=assets_url();?>images/favicon_8.ico" type="image/ico" sizes="18x18">
<link rel="stylesheet" href="<?=assets_url();?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?=assets_url();?>vendor/bootstrap/dist/css/bootstrap.css"/>
<link rel="stylesheet" href="<?=assets_url();?>vendor/font-awesome/css/font-awesome.css"/>
<link rel="stylesheet" href="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.css"/>
<?php if($controller=='lead' && $method=='manage'){?>
    <link rel="stylesheet" href="<?=assets_url();?>css/lead.css?v=<?php echo rand(0,1000); ?>" id="loadtsf_styles_before"/>
<?php }else{ ?>
    <?php if($controller!='dashboard_v2'){ ?>
    <link rel="stylesheet" href="<?=assets_url();?>css/app.css?v=<?php echo rand(0,1000); ?>" id="loadtsf_styles_before"/>
    <?php } ?>
<?php } ?>
<link rel="stylesheet" href="<?=assets_url();?>css/responsive.css"/>
<style type="text/css" media="screen">
.d-none{display: none;}
.d-block{display: block;}
</style>
<script src="<?=assets_url();?>vendor/jquery/dist/jquery.js"></script>
<script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script>
<input type="hidden" id="base_url" value="<?php echo base_url().$lms_url;?>">
<input type="hidden" id="assets_base_url" value="<?php echo assets_url();?>">
<input type="hidden" id="base_url_root" value="<?php echo base_url();?>">
<input type="hidden" id="is_mobile" value="<?php echo $is_mobile; ?>">