<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
    <?php $this->load->view('admin/includes/head'); ?>   
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">     -->
    <style>
    .sortable-ul { list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 183px;}
    .sortable-ul li { margin: 5px; padding: 5px; font-size: 1.2em; width: 160px; }
    .service-div{ border:1px dotted #000;}
    </style>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
    <!-- <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script> -->
    <script>
    $( function() {
      // $( "ul.droptrue" ).sortable({
      //   connectWith: "ul"
      // });  
      // $( "ul.dropfalse" ).sortable({
      //   connectWith: "ul",
      //   dropOnEmpty: true,
      //   cursor: "move"
      // });  
      // $( ".sortable-ul" ).disableSelection();

      $(".sortable-ul").sortable({
        connectWith: "ul",
        dropOnEmpty: true,
        cursor: "move",
        start: function(e, ui) {
        },
        change: function( event, ui ) {
        },
        update: function( event, ui ) {
        },
        stop: function( event, ui ) {
        },
        activate: function( event, ui ) {          
        },
        create: function( event, ui ) {          
        },
        deactivate: function( event, ui ) {          
        },
        receive: function( event, ui ) {
            var id = ui.item.attr("id");
            var sourceList = ui.sender;
            var targetList = $(this);
            var service_order_detail_id_to=targetList[0].dataset.service_order_detail_id;
            var service_order_detail_id_from=sourceList[0].dataset.service_order_detail_id;
            // alert('User ID:-'+id+'/SODID TO:-'+service_order_detail_id_to+'/SODID From:-'+service_order_detail_id_from)
            var base_URL = $("#base_url").val();
            var data="user_id="+id+"&sod_id_to="+service_order_detail_id_to+"&sod_id_from="+service_order_detail_id_from;
            //alert(data); //return false;
            $.ajax({
                url: base_URL+"/user/tag_user_wise_service",
                data: data,
                //data: new FormData($('#frmAccount')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                //mimeType: "multipart/form-data",
                //contentType: false,
                //cache: false,
                //processData:false,
                beforeSend: function( xhr ) { 
                  $.blockUI({ 
                      message: 'Please wait...', 
                      css: { 
                        padding: '10px', 
                        backgroundColor: '#fff', 
                        border:'0px solid #000',
                        '-webkit-border-radius': '10px', 
                        '-moz-border-radius': '10px', 
                        opacity: .5, 
                        color: '#000',
                        width:'450px',
                        'font-size':'14px'
                        }
                  });                        
                },
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result=$.parseJSON(data);   
                    // alert();   
                    if(result.status=='success')      
                    {
                      // swal({
                      //     title: "Updated!",
                      //     text: "The status has been changed",
                      //       type: "success",
                      //     confirmButtonText: "ok",
                      //     allowOutsideClick: "false"
                      // }, function () { 
                      //     //location.reload(true); 
                      // }); 
                    }     
                    else
                    {
                      swal({
                          title: "Oops!",
                          text: result.msg,
                          type: "error",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () { 
                          location.reload(true); 
                      }); 
                    }
                                     
                }              
            });
        }
      }); 
    } );
    </script>
  </head>
  <body>
    <div class="app full-width expanding">    
        <div class="off-canvas-overlay" data-toggle="sidebar"></div>
        <div class="sidebar-panel">       
          <?php $this->load->view('admin/includes/left-sidebar'); ?>
        </div> 
        <div class="app horizontal top_hader_dashboard"><?php $this->load->view('admin/includes/header'); ?></div>      

        <div class="main-panel">
            <div class="min_height_dashboard"></div>
            <div class="main-content">              
                <div class="content-view">
            <?php
            if($this->session->flashdata('menu_success_msg')) { ?>
            <!--  success message area start  -->
            <div class="alert alert-success alert-alt" style="display:block;" id="notification-success">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="fa fa-check-circle"></i> Success</h4> <span id="success_msg">
              <?php echo $this->session->flashdata('menu_success_msg'); ?></span>
            </div>
            <!--  success message area end  -->
            <?php } ?>
            <?php
            if($this->session->flashdata('success_msg')) { ?>
            <!--  success message area start  -->
            <div class="alert alert-success alert-alt" style="display:block;" id="notification-success">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="fa fa-check-circle"></i> Success</h4> <span id="success_msg">
              <?php echo $this->session->flashdata('success_msg'); ?></span>
            </div>
            <!--  success message area end  -->
            <?php } ?>
            <?php if($this->session->flashdata('error_msg')) { ?>
            <!--  error message area start  -->
            <div class="alert alert-danger alert-alt" style="display:block;" id="notification-error">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="fa fa-exclamation-triangle"></i> Error</h4> <span id="error_msg">
              <?php echo $this->session->flashdata('error_msg'); ?></span>
            </div>
            <!--  error message area end  -->
            <?php } ?>
            <div class="row m-b-1">
              <div class="col-sm-3 pr-0">
                  <div class="bg_white back_line">
                    <h4><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee">Tag Service</a> <img src="<?=assets_url();?>images/user.png"></h4>
                  </div>
              </div>
              <div class="col-sm-9 pleft_0">
                  <div class="bg_white_filt">
                    <ul class="filter_ul">
                      <li>
                          <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee" class="new_filter_btn">
                            <span class="bg_span"><img src="<?=assets_url();?>images/left_black.png"></span> Back
                          </a>
                      </li>
                    </ul>
                  </div>
              </div>
            </div>
            <div class="card process-sec">      
              <div class="clear">&nbsp;</div>
                <div class="col-md-12">
                  <div class="card-block">
                    <!-- <pre>
                      <?php //print_r($service_info['all_available_service_order']); ?>  
                      <?php //print_r($all_user_wise_service_order); ?>
                    </pre> -->
                      <div class="no-more-tables"> 
                        <div class="row" style="float:left">
                        <div class="col-md-12"> 
                        <div><h5>User List</h5></div>
                          <ul id="sortable0" class="dropfalse sortable-ul " data-service_order_detail_id="">
                          <?php 
                          if(count($all_user))
                          {
                            foreach($all_user AS $user)
                            {
                              ?>                            
                                <li id="<?php echo $user['id']; ?>" class="ui-state-default <?php if($user['id']==1){ ?>ui-state-disabled<?php } ?>"><?php echo $user['name'].' (User ID:- '.$user['id'].')'; ?></li>
                              <?php
                            }
                          }
                          ?>
                          </ul>
                        </div>
                        <?php
                        if(count($service_info['all_available_service_order']))
                        {
                            $i=1;
                            foreach($service_info['all_available_service_order'] AS $service_order)
                            {
                              ?> 
                              <div class="col-md-3 service-div" style="padding-bottom:10px;">
                                <div>
                                    <h5><?php echo $service_order['service_name'].'-'. $service_order['display_name'].'-'.$service_order['id']; ?></h5>
                                    <small class="text-info">Available User: <?php echo $service_order['no_of_user']; ?> (Admin + <?php echo ($service_order['no_of_user']-1); ?>)</small><br>
                                    <small class="text-info">Start Date: <?php echo date_db_format_to_display_format($service_order['start_date']); ?></small><br>
                                    <small class="text-info">End Date: <?php echo date_db_format_to_display_format($service_order['end_date']); ?></small><br>
                                    <small class="text-info">Expiry Date: <?php echo date_db_format_to_display_format($service_order['expiry_date']); ?></small>
                                </div>
                                <ul id="sortable<?php echo $i; ?>" class="dropfalse sortable-ul " data-service_order_detail_id="<?php echo $service_order['id']; ?>">
                                    <?php if(count($all_user_wise_service_order)){ ?>
                                        <?php foreach($all_user_wise_service_order AS $tagged_sod){ ?>
                                            <?php if($tagged_sod['service_order_detail_id']==$service_order['id']){ ?>
                                              <li id="<?php echo $tagged_sod['user_id']; ?>" class="ui-state-default"><?php echo $tagged_sod['name'].' (User ID:- '.$tagged_sod['user_id'].')'; ?></li>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                              </div>
                              <?php
                              $i++;
                            }
                        }
                        ?>
                        </div>
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
$(document).ready(function () {
   
});
</script>