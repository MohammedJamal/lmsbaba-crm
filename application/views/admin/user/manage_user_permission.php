<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>    
  </head>
  <body>
    <div class="app full-width expanding">    
        <div class="off-canvas-overlay" data-toggle="sidebar"></div>
        <div class="sidebar-panel">       
          <?php $this->load->view('admin/includes/left-sidebar'); ?>
        </div> 
        <div class="app horizontal top_hader_dashboard">
              <?php $this->load->view('admin/includes/header'); ?>
        </div>      

        <div class="main-panel">
            <div class="min_height_dashboard"></div>
            <div class="main-content"> 
              <div class="content-view">
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

                  <div class="row m-b-1">
                    <div class="col-sm-3 pr-0">
                        <div class="bg_white back_line">
                          <h4><a href="JavaScript:void(0);">Manage Permission</a> <i class="fa fa-list" aria-hidden="true"></i></h4>
                        </div>
                    </div>
                    <div class="col-sm-9 pleft_0">
                        <div class="bg_white_filt">
                          <ul class="filter_ul">
                            <li>
                                <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee" class="new_filter_btn">
                                  <span class="bg_span"><img src="<?=assets_url();?>images/left_black.svg"></span> Back
                                </a>
                            </li>
                          </ul>
                        </div>
                    </div>
                  </div>

              <div class="card process-sec">
                <div class="card-header no-bg b-a-0">
                    Manage <?php echo ($user_data)?$user_data[0]->name:''; ?>'s Role
                    <div class="pull-right"></div>
                </div>
                <div class="card-block">
                  <div class="no-more-tables">
                    <form action="<?=base_url().$this->session->userdata['admin_session_data']['lms_url']?>/user/manage_permission/<?php echo $user_id; ?>" method="post" class="form-horizontal form-label-left">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="package_id" value="<?php echo $package_id; ?>">
                      <div class="form-group">
                        <div class="form-horizontal form-bordered">
                          <div class="col-md-12">
                            <div class="form-group" id="package_module_function_container">
                              <div class="col-md-12">
                                  <div class="module_wrapper bg_module">
                                  <!-- <pre><?php //print_r($this->session->userdata('service_wise_menu')); ?></pre> -->
                                    <?php if(count($service_wise_menu)){ $i=0;$sid='';?>
                                      <?php foreach($service_wise_menu AS $row){ ?>
                                        <?php 
                                        if(in_array($row['menu_list']['service_id'],$user_wise_service_info['service_tagged']))
                                        {
                                        
                                          if($row['menu_list']['service_id']!=$sid){ $i=0; } 
                                          if($i==0){
                                        ?>
                                        <h4 class="sub_menu_margin"><?php echo $row['menu_list']['service_name']; ?></h4>
                                        <?php } ?>
                                        <ul class="parent_ul">
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
                                    <?php } } ?>                                    
                                  </div>
                                  
                              </div>
                              
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                              <div class="col-md-12 col-sm-12 col-xs-12">
                              <input type="hidden" value="<?php echo $user_id?>" name="user_id" id="user_id"/>
                              <input type="hidden" value="1" name="command" id="command"/>
                              <button style="float: right;" type="submit" class="btn btn-success" onclick="return validate();">Update Permission </button>
                              <a href="<?=base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee" style="float: right; margin-right:10px;" class="btn btn-primary">Cancel</a>
                              </div>
                            </div>
                          </div>




                          <?php /* ?>

                          <div class="col-md-8">                    
                            <h5>Menu Permission</h5>
                            <!-- <pre><?php //print_r($this->menu);?></pre> -->
                            <div class="form-group" id="package_module_function_container1">
                              <?php if(count($menu)) { ?>
                              <?php foreach($menu as $m){ ?>
                              <div class="col-md-6">
                                <div class="module_wrapper bg_module">
                                  <ul class="parent_ul">
                                    <li>
                                      <div class="checkbox checkbox-warning">
                                          <input type="checkbox" class="styled parent_access" name="chk_menu[]" id="parent_<?php echo $m['menu']['id']; ?>" value="<?php echo $m['menu']['id']; ?>" <?php if(in_array($m['menu']['id'],$available_menu_id_arr)){ ?> checked="checked" <?php } ?> data-id="<?php echo $m['menu']['id']; ?>">

                                          <label for=""><?php echo $m['menu']['menu_name']; ?> </label>
                                      </div>                              
                                      <?php if(count($m['menu']['sub_menu'])){ ?>       
                                      <ul class="child_ul">
                                      <?php foreach($m['menu']['sub_menu'] as $sub_m) { ?>
                                        <?php if($sub_m['is_display_on_user_permission']=='Y'){ ?>
                                        <li>                       
                                          <label class="sub_menu_margin" for="child_<?php echo $sub_m['sub_menu_id']; ?>"><span class="function_name"><?php echo $sub_m['sub_menu_name']; ?></span></label>
                                            <ul class="child_inner_ul row">
                                              <?php foreach($sub_m['sub_menu_wise_method'] as $method){ ?>
                                                  <?php if($method['is_display']=='Y'){ ?>
                                                  <li class="col-md-4">
                                                  <div class="checkbox checkbox-primary">
                                                      <input type="checkbox" class="styled child_access_<?php echo $m['menu']['id']; ?>" name="chk_controller_method[]" id="" value="<?php echo $method['controller_name'].'@'.$method['method_name']; ?>" <?php if(in_array($method['controller_name'].'@'.$method['method_name'],$chk_controller_method2)){echo'checked';}  ?> disabled="true">
                                                      <label>
                                                          <?php echo $method['method_display_name']; ?>
                                                      </label>
                                                  </div>                                  
                                                </li>
                                              <?php } ?>
                                              <?php } ?>
                                            </ul>
                                          </li>
                                          <?php } ?>
                                          <?php } ?>
                                        </ul>
                                        <?php } ?>
                                      </li>                          
                                  </ul>
                                </div>
                              </div>
                                      <?php } ?>
                                      <?php } ?>
                            </div>
                          </div>
                            <div class="col-md-4">
                              <h4>Other Permission</h4>
                              <ul class="other_per">
                                <?php if(count($package_data['non_menu'])>0){ ?>
                                <?php foreach ($package_data['non_menu'] as $non_menu) {?>
                                  <?php if(!in_array($non_menu['id'],$non_menu_disable_id_arr)){ ?>
                                  <?php if(!in_array($non_menu['reserved_keyword'],$admin_level_attribute)){ ?>
                                    <li>
                                      <div class="checkbox checkbox-info">
                                          <input type="checkbox" class="styled" name="chk_non_menu[]" id="" value="<?php echo $non_menu['id']; ?>" <?php if(in_array($non_menu['package_attribute_id'],$available_non_menu_id_arr)){ ?> checked="checked" <?php } ?>>
                                          <label for="">
                                              <?php echo $non_menu['attribute_name']; ?>
                                          </label>
                                      </div>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php } ?>
                              </ul>
                            </div>
                            <?php */ ?>
                            
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
              <?php $this->load->view('admin/includes/footer'); ?>
            </div>
        </div>
    </div>
    <?php $this->load->view('admin/includes/modal-html'); ?>
    <?php $this->load->view('admin/includes/app.php'); ?> 
  </body>
</html>
<script type="text/javascript">
function confirm_delete(id) {
    swal({
      title: 'Are you sure?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: false
    }, function() {
      
      window.location.href="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/delete/"+id;
    });
    return false;
  }
</script>
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
    });
</script>     
</body>
</html>
<script type="text/javascript" src="<?=assets_url();?>js/custom/user/manage_permission.js"></script>
