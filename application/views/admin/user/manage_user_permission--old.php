<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('include/header');?>
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
<div class="card">
  <div class="card-header no-bg b-a-0">
      Manage <?php echo $user_data[0]->name; ?>'s Role
      <div class="pull-right"></div>
  </div>
  <div class="card-block">
    <div class="no-more-tables">
      <form action="<?=base_url().$this->session->userdata['admin_session_data']['lms_url']?>/user/manage_permission/<?php echo $user_id; ?>" method="post" class="form-horizontal form-label-left">
      <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
      <input type="hidden" name="package_id" value="<?php echo $this->package_id; ?>">
		  <div class="form-group">
        <div class="form-horizontal form-bordered">
          <div class="col-md-8">                    
            <h5>Menu Permission</h5>
            <!-- <pre><?php //print_r($this->menu);?></pre> -->
            <div class="form-group" id="package_module_function_container">
              <?php if(count($this->menu)) { ?>
              <?php foreach($this->menu as $m){ ?>
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
                <?php if(count($this->package_data['non_menu'])>0){ ?>
                <?php foreach ($this->package_data['non_menu'] as $non_menu) { ?>
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
              </ul>
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
        </form>
      </div>
    </div>
  </div>
</div>
<!-- bottom footer -->
<?php $this->load->view('include/footer');?>
<!-- /bottom footer -->
</div>
</div>      
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
