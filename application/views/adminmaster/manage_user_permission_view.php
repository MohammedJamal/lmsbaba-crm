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
                    <div class="container">
                        
                        <h2 class="text-left"><?php echo $client_info->name; ?> - <?php echo $client_info->client_id; ?> <a href="<?php echo adminportal_url(); ?>client/manage_user/<?php echo $client_info->client_id; ?>" class="pull-right btn"><font style="font-size: 16px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</font> </a></h2>

                        <div style="clear:both;">&nbsp;</div>
                        <form action="<?=adminportal_url()?>client/manage_user_permission/<?php echo $client_id; ?>/<?php echo $user_id; ?>" method="post" class="form-horizontal form-label-left">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
                            <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group " id="package_module_function_container">
                                        <div class="col-md-12">                                    
                                            <div class="module_wrapper bg_module text-left" style="padding:10px;">
                                                <?php if( $this->session->flashdata('error_msg') ){ ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <h4><i class="fa fa-times-circle"></i> Error</h4> 
                                                    <?php foreach( $this->session->flashdata('error_msg') as $msgArr ){ echo $msgArr."<br />"; } ?>
                                                </div>
                                                <?php } ?>
                                                <?php if( $this->session->flashdata('success_msg') ){ ?>
                                                <!--  success message area start  -->
                                                <div class="alert alert-success alert-alt" style="display:block;" id="notification-success">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <h4><i class="fa fa-check-circle"></i> Success</h4> <span id="success_msg">
                                                    <?php echo $this->session->flashdata('success_msg'); ?></span>
                                                </div>
                                                    <!--  success message area end  -->
                                                <?php } ?>
                                                <!-- <pre><?php //print_r($service_wise_menu); ?></pre> -->
                                                <?php if(count($service_wise_menu)){ $i=0;$sid='';?>
                                                <?php foreach($service_wise_menu AS $row){ ?>
                                                    <?php 
                                                    if($row['menu_list']['service_id']!=$sid){ $i=0; } 
                                                    if($i==0){
                                                    ?>
                                                    <h4 class="sub_menu_margin"><?php echo $row['menu_list']['service_name']; ?></h4>
                                                    <?php } ?>
                                                    <ul class="parent_ul ">
                                                    <li>
                                                        <div class="checkbox checkbox-warning">
                                                            <input type="checkbox" class="styled parent_access" name="user_wise_permission[]" id="parent_<?php echo $row['menu_list']['id']; ?>" value="<?php echo $row['menu_list']['menu_keyword']; ?>"  data-id="<?php echo $row['menu_list']['id']; ?>" <?php if(in_array($row['menu_list']['menu_keyword'],$user_wise_permission_keyword_arr)){echo'checked';} ?> >
                                                            <label for=""><b><?php echo $row['menu_list']['menu_name']; ?></b> </label>
                                                        </div>
                                                        <?php if($row['menu_wise_permission_list']){ ?>
                                                            <ul class="" style="padding-left:18px">
                                                            <?php foreach($row['menu_wise_permission_list'] AS $permission){ ?>
                                                            
                                                            <li>
                                                                <div class="checkbox checkbox-warning">
                                                                <input type="checkbox" class="styled child_access_<?php echo $row['menu_list']['id']; ?>" name="user_wise_permission[]" id="" value="<?php echo $permission['reserve_keyword']; ?>"  disabled="true" <?php if(in_array($permission['reserve_keyword'],$user_wise_permission_keyword_arr)){echo'checked';} ?> >
                                                                <label for=""><?php echo $permission['display_name']; ?> </label>
                                                                </div>
                                                            </li>
                                                            
                                                            <?php } ?>
                                                            </ul>
                                                        <?php } ?>
                                                    </li>
                                                    </ul>
                                                <?php $sid=$row['menu_list']['service_id'];$i++; } ?>
                                                <?php } ?>                                    
                                            </div>
                                            
                                        </div>
                                    
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-12 col-xs-12">                                        
                                            <button style="float: right;" type="submit" class="btn btn-success" onclick="return validate();">Update Permission </button>                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                                                
                        
                    </div>
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
<script type="text/javascript">
    $( document ).ready(function() {
      //$('#site_menu_access input[type="checkbox"]').change(function(e) {
      $('body').on("change", '#package_module_function_container input[type="checkbox"]', function(e) {
        var checked = $(this).prop("checked"),
        container   = $(this).parent(),
        siblings  = container.siblings();
        //console.log(checked+"***"+container+'--First');
        container.find('input[type="checkbox"]').prop({
          indeterminate: false,
          checked: checked
        });
        
        function checkSiblings(el) { //alert('checkSiblings call');
          var parent  = el.parent().parent(),
            all   = true;            
          el.siblings().each(function() { 
            //alert( $(this).children('input[type="checkbox"]').attr('id')+'****'+$(this).children('input[type="checkbox"]').prop("checked")+'----siblings().each---'+all);
            return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
          });
          
          if (all && checked) 
          {
            //alert(all+'----all && checked');
            parent.children('input[type="checkbox"]').prop({
              indeterminate: false,
              checked: checked
            });
            checkSiblings(parent);
          } else if (all && !checked) {
            //alert(all+'----all && !checked');
            parent.children('input[type="checkbox"]').prop("checked", checked);
            parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
            checkSiblings(parent);
          } else {
            //alert( el.parents("li").find('div').children('input[type="checkbox"]').prop('id')+"**"+el.parents("li").find('div').children('input[type="checkbox"]').prop('checked')+'----else');
            el.parents("li").children('input[type="checkbox"]').prop({
              indeterminate: true,
              checked: true
            });
          }
        }
        checkSiblings(container);
      });

      $(".parent_access:checked").each(function() {
        $(".child_access_"+$(this).attr('data-id')).attr("disabled",false);
    });    

    $('.parent_access').click(function(){
    	
	    if($(this).prop("checked") == true){
	        $(".child_access_"+$(this).attr('data-id')).attr("disabled",false);
	        $(".child_access_"+$(this).attr('data-id')).prop("checked",true);
	    }
	    else if($(this).prop("checked") == false){
	        $(".child_access_"+$(this).attr('data-id')).attr("disabled",true);
	        $(".child_access_"+$(this).attr('data-id')).prop("checked",false);
	    }
	});
    });
</script> 
