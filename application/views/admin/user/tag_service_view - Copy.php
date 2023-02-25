<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
    <?php $this->load->view('admin/includes/head'); ?>   
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">     -->
    <style>
    /*.sortable-ul { list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 183px;}
    .sortable-ul li { margin: 5px; padding: 5px; font-size: 1.2em; width: 160px; }
    .service-div{ border:1px dotted #000;}*/
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

      $("body").on("change","#service",function(e){
        var s_id=$(this).val();
        rander_service_list_ajax(s_id);        
    });
  });

  function rander_service_list_ajax(s_id)
  {
        var base_URL=$("#base_url").val();    
        var data = "s_id="+s_id;        
        $.ajax({
            url: base_URL+"user/rander_service_list_ajax/",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
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
              success:function(res){ 
              result = $.parseJSON(res);             
              $("#rander_html").html(result.html);
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
                      var base_URL = $("#base_url").val();
                      var data="user_id="+id+"&sod_id_to="+service_order_detail_id_to+"&sod_id_from="+service_order_detail_id_from;           
                      $.ajax({
                          url: base_URL+"/user/tag_user_wise_service",
                          data: data,
                          cache: false,
                          method: 'POST',
                          dataType: "html",
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
                              if(result.status=='success'){                       
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
                                    // location.reload(true);                                    
                                    rander_service_list_ajax(s_id);
                                }); 
                              }
                                              
                          }              
                      });
                  }
                });
          },       
          error: function(response) {}
      });      
  }
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
                  <!-- <pre><?php print_r($service_info['all_available_service']); ?></pre> -->
                    <!-- <pre>
                      <?php //print_r($all_user); ?>
                      <?php //print_r($service_info['all_available_service_order']); ?>  
                      <?php //print_r($all_user_wise_service_order); ?>
                    </pre> -->
                      <div class="no-more-tables"> 
                          <div class="row">
                            <div class="col-sm-6">
                              <label class="col-form-label">Select Service :</label>
                              <select class="custom-select form-control" name="service" id="service">
                                <option value="">Select</option>
                                  <?php if(count($service_info['all_available_service'])){ ?>
                                      <?php foreach($service_info['all_available_service'] AS $service){ ?>
                                        <option value="<?php echo $service['service_id'] ?>"><?php echo $service['service_name'] ?></option>
                                      <?php } ?>
                                  <?php } ?>                                 
                              </select>
                            </div>
                          </div>
                          <br>
                          <div id="rander_html"></div>                          
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