<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
   <?php $this->load->view('admin/includes/head'); ?>
   <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/jquery_modalLink_1_0_0.css" />
   <script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.modalLink-1.0.0.js"></script>
   <style>
    .sortable_ul{ list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 143px;}
    .sortable_ul li{ margin: 5px; padding: 5px; font-size: 1.2em; width: 120px; }
    div.sortable_cancel {
        background: #999;
        opacity:.5;
    }
  </style>
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
      <form role="form" class="form-validation" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/setting/company/<?php echo $id;?>" method="post" name="form" id="profile_update_form" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" id="edit_id" value="<?=$id;?>"/>
        <input type="hidden" name="existing_profile_image" value="<?php echo $company['logo']; ?>">
        <input type="hidden" name="existing_brochure_file" value="<?php echo $company['brochure_file']; ?>">
        <div class="row">
          <div class="col-lg-12">   
                                   
          <div class="row m-b-1 align-items-center">
                <div class="col-sm-4 pr-0">
                    <div class="bg_white back_line">
                      <h4>Order Management</h4>
                    </div>
                </div>
                <div class="col-sm-8 pleft_0">
                  
                  <div class="bg_white_filt">

                  
                      
                      <ul class="filter_ul">                        
                        <li>
                          <div id="btnContainer">                                          
                            <button class="btn active om_get_view" data-target="om_grid"><i class="fa fa-th-large"></i></button>
                            <button class="btn  om_get_view" data-target="om_list"><i class="fa fa-bars"></i></button>
                          </div>
                        </li>
                        <li class="nav-item" style="display:none">
                          <a class="nav-link active tablinks" href="JavaScript:void(0)" onClick="openDiv(event, 'tab_1')" id="defaultOpen">Order</a>
                        </li>
                        <li class="nav-item" style="display:none">
                        <a class="nav-link tablinks" href="JavaScript:void(0)" onClick="openDiv(event, 'tab_2')" id="settingOpen">Setting</a>
                        </li>  
                      </ul>
                      <?php /* ?>
                      <ul class="nav nav-tabs nav-sm" >                                        
                        <li class="mr-15">
                          <a href="#" class="btn btn-primary btn-sm-new">Add New Order</a>
                        </li>
                        <li class="nav-item" style="display:none">
                          <a class="nav-link active tablinks" href="JavaScript:void(0)" onClick="openDiv(event, 'tab_1')" id="defaultOpen">Order</a>
                        </li>
                        <li class="nav-item" style="display:none">
                        <a class="nav-link tablinks" href="JavaScript:void(0)" onClick="openDiv(event, 'tab_2')" id="settingOpen">Setting</a>
                        </li>                                        
                        <!-- <li class="nav-item" >
                          <div id="btnContainer">                                          
                            <button class="btn active om_get_view" data-target="om_grid"><i class="fa fa-th-large"></i></button>
                            <button class="btn  om_get_view" data-target="om_list"><i class="fa fa-bars"></i></button>
                          </div>
                        </li> -->
                      </ul> 
                      <?php */ ?>                                   
                  </div>                                  
                </div>
              </div>

             
                
                
                
              
              <?php
              if($this->session->flashdata('success_msg')) { ?>
              <!--  success message area start  -->
              <div class="alert alert-success alert-alt" style="display:block;" id="notification-success">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="fa fa-check-circle"></i> Success</h4>
              <span id="success_msg">
              <?php echo $this->session->flashdata('success_msg'); ?></span>
              </div>
              <!--  success message area end  -->
              <?php } ?>
              <?php if($this->session->flashdata('error_msg') || $error_msg) { ?>
              <!--  error message area start  -->
              <div class="alert alert-danger alert-alt" style="display:block;" id="notification-error">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="fa fa-exclamation-triangle"></i> Error</h4>
              <span id="error_msg">
              <?php echo ($this->session->flashdata('error_msg'))?$this->session->flashdata('error_msg'):$error_msg; ?></span>
              </div>
              <!--  error message area end  -->
              <?php } ?>
              
              
              <div class="panel panel-primary card process-sec">  
                            
                <div class="group_from bg_color_white_no">
                  
                  <div class="card-body pall-0 d-block">


                    
                    <!-- TAB 1 - START -->
                    <div id="tab_1" class="tabcontentDiv">
                      <div class="col-md-12">                                                                                
                          <div class="card-" id="order_tcontent"></div>
                          <input type="hidden" id="om_view_type" value="om_grid">                          
                      </div>
                      <div>&nbsp;</div>
                    </div>
                    <!-- TAB 1 - END -->

                    <!-- TAB 2 - START -->
                    <div id="tab_2" class="tabcontentDiv">
                      
                      <div class="col-md-12">                          
                          <div>&nbsp;</div>                                                  
                          <div class="card-" id="settings_tcontent"></div>
                      </div>
                      <div>&nbsp;</div>
                    </div>
                    <!-- TAB 2 - END --> 
                  </div>
                </div>
              </div>
          </div>
        </div>
      </form>
    </div>    
  </div>
  <div class="content-footer">
    <?php $this->load->view('admin/includes/footer'); ?>
  </div>
</div>

</div>
</div>

<div id="activityLogBreakUpViewModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-lg">
     <div class="modal-content">
       <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" data-nlok-ref-guid="96997f9e-3431-4782-829b-d4a0c858150b">×</button>
           <h4 class="modal-title" id="activityLogBreakUpViewModalTitle"></h4>
       </div>
       <div class="modal-body" id="activityLogBreakUpViewModalBody"></div>       
     </div>
  </div>
</div>



<?php $this->load->view('admin/includes/modal-html'); ?>
<?php $this->load->view('admin/includes/app.php'); ?> 
</body>
</html>
<script src="<?php echo assets_url();?>js/custom/order_management/main.js?v=<?php echo rand(0,1000); ?>"></script>
<script type="text/javascript">
 
   $(document).ready(function () {

    

    
    //.sortable_main_ul > li
    updateAfterOrderLoad = function(){
      let cWidth = window.innerWidth;
      if(cWidth > 767){
        var parentWidth = $('#order_tcontent').innerWidth();
        var singleWidth = parentWidth/5;
        //alert(parentWidth);
        $('.sortable_main_ul > li').css({'width': singleWidth});
      }
    }
      // $('.avatar[data-toggle="tooltip"]').tooltipster({
      //     contentAsHTML: true
      // });
   });   
</script>


