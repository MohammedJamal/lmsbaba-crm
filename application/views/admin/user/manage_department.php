<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>
    <!-- NESTABLE JS -->
    <script src="<?php echo assets_url(); ?>js/nestable_js/jquery.nestable.js"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo assets_url(); ?>js/nestable_js/tree-menu.css" />
    <style>
    /*.control-label {
        padding-top: 0px !important;
        margin-top: 0px !important;
    }*/
    .tree_action {
        float: right;
        font-size: 18px;
        cursor: pointer;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .catstyle{
        width: 100%;
    }
    .pannel_section {
          max-height: 490px;
          overflow-y: scroll;
    }
    /*h4.text-light{font-family: 'Open Sans', sans-serif; font-size::;px; font-weight: 600; color: #25628F; margin: 20px 0 20px 0; border-bottom: 1px solid #B39732; display: inline-block; padding-bottom:5px;}*/
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
                 <h4><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee">Manage Users</a> </h4>
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
            <div class="card">
              <!-- <div class="card-header no-bg b-a-0">Department List
                <div class="pull-right"></div>
              </div> -->
              <div class="card-block">
                <div class="no-more-tables">
                  <div class="row">
                      <!-- ############################################ --> 
                      <!-- ##### LEFT HAND SECTION :: START ########## --> 
                      <!-- ############################################ -->

                      <div class="col-sm-6">
                          <div id="catListSection" class="panel">
                              <div class="panel-heading">
                                  <h3 class="panel-title text-light"><i class="fa fa-list fa-fw text-sm"></i> Manage <strong> Departments</strong></h3>
                              </div>
                              <div class="pannel_section">
                                  <div class="panel-body">
                                      <form id="cat_frm" name="cat_frm" method="post" action="" class="form-horizontal">
                                          <input type="hidden" name="nestable-output" id="nestable-output" value="" />

                                          <?php
                                          if($tree_html_view!= ""):
                                          ?>
                                              <br clear="all">
                                              <div class="dd" id="nestable3"> <?php echo $tree_html_view; ?> </div>
                                              <br clear="all">
                                          <?php
                                          endif;
                                          ?>
                                          <div id="catTreeView"></div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- ############################################# --> 
                      <!-- #### LEFT HAND SECTION :: END ############### --> 
                      <!-- ############################################# --> 

                      <!-- ############################################# --> 
                      <!-- ### RIGHT HAND SECTION :: START ############# --> 
                      <!-- ############################################# -->
                      <form action="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']."/".$controller."/manage_department"; ?>/<?php echo $edit_id; ?>" method="post" name="Addform" id="Addform" role="form" class="form-horizontal" enctype="multipart/form-data">
                          <input type="hidden" name="controller_name" id="controller_name" value="<?php echo $controller; ?>" />
                          <input type="hidden" name="siteadminurl" id="siteadminurl" value="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']."/"; ?>" />
                          <input type="hidden" name="existing_cat_image" id="existing_cat_image" value="">
                          <input type="hidden" name="edit_parent_cat_id" id="edit_parent_cat_id" value="<?php echo (count($row))?$row['parent_id']:''; ?>">
                          <input type="hidden" name="edit_id" id="edit_id" value="<?php echo $edit_id; ?>">
                          <div class="col-sm-6">
                              <div id="catAddSection" class="panel">
                                  <div class="panel-heading">
                                      <h3 class="panel-title text-light"><i class="fa fa-list fa-fw text-sm"></i> <?php echo ($edit_id)?'Edit':'Add'; ?> <strong> Department</strong></h3>
                                  </div>
                                  <div class="panel-body">
                                      <div id="render_add_html_view"></div>
                                  </div>
                              </div>
                          </div>
                      </form>                      
                      <!--end .box --> 
                      <!-- ############################################# --> 
                      <!-- ### RIGHT HAND SECTION :: END ############### --> 
                      <!-- ############################################# -->
                    </div>
                    <!-- end row -->
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
<div id="preloader" style="display: none"></div>
<style type="text/css">
 div#preloader { position: fixed; left: 0; top: 0; z-index: 999; width: 100%;opacity: 0.8;filter: alpha(opacity=50); /* For IE8 and earlier */; height: 100%; overflow: visible; background: #333 url('http://files.mimoymima.com/images/loading.gif') no-repeat center center; }
</style>

<!-- Sweet-Alert  -->
<!-- <script src="<?php echo assets_url(); ?>plugins/bootstrap-sweetalert/sweet-alert.min.js"></script> -->
<!-- <script src="<?php echo assets_url(); ?>pages/jquery.sweet-alert.init.js"></script> -->
<script src="<?php echo assets_url(); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>plugins/parsleyjs/parsley.min.js"></script>
<script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
 
<script type="text/javascript">    
$(document).ready(function() {
    $('form').parsley();
});
$(function () {
    $('#Addform').parsley().on('field:validated', function () {
        var ok = $('.parsley-error').length === 0;
        $('.alert-info').toggleClass('hidden', !ok);
        $('.alert-warning').toggleClass('hidden', ok);
    })
    .on('form:submit', function () {
        return true; // Don't submit form for this demo
    });
});   
</script>
</body>
</html>
<script>
$(document).ready(function()
{ 
  $('[data-toggle="tooltip"]').tooltip({html: true});
       
    //*********************************************************//
    load_department_add_section();
    //********************************************************//
    
    /**********************************************************/
    /********** SCRIPTING FOR NESTABLE JS :: START ************/
    /**********************************************************/
    var updateOutput = function(e)
    {
        var list = e.length ? e : $(e.target),
                output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            //save_changes();
            $(".dd3-handle").html("&nbsp;");

        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    var saveOutput = function(e)
    {
        var list = e.length ? e : $(e.target),
                output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            save_changes();
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable3').nestable({
        group: 1
    })
            .on('change', saveOutput);
    // output initial serialised data
    updateOutput($('#nestable3').data('output', $('#nestable-output')));

    /**********************************************************/
    /********** SCRIPTING FOR NESTABLE JS :: END **************/
    /**********************************************************/
});
    
    
    /*------------------------------------------------------------*/
    /*--------- Load category add section through ajax -----------*/
    /*------------------------------------------------------------*/
    function load_department_add_section()
    {   
        var adminurl = $('#siteadminurl').val();
        var controllername = $('#controller_name').val();
        var actionUrlPath = adminurl + controllername + '/' + 'ajax_load_add_department';
        //alert(actionUrlPath);
        //$("#preloader").css('display','block');
        $.ajax({
            url: actionUrlPath,
            type: "POST",
            data: $("#Addform").serializeArray(),
            dataType: "HTML",
            beforeSend: function() {
            // setting a timeout
            $("#preloader").css('display','block');
        },
            success: function(data)
            { 

                //show_toastr(4, "Message", "Category updated successfully");
                $('#render_add_html_view').html(data);
            },
            complete: function(){
                $("#preloader").css('display','none');
            } 
        });
        
    }
    /*------------------------------------------------------------*/


    /*------------------------------------------------------------*/
    /*-------------------- CATEGORY CHANGE -----------------------*/
    /*------------------------------------------------------------*/
    function save_changes()
    {
        var adminurl = $('#siteadminurl').val();
        var controllername = $('#controller_name').val();
        var actionUrlPath = adminurl + controllername + '/' + 'ajax_save_category';
        //alert(actionUrlPath); return false;
        $.ajax({
            url: actionUrlPath,
            type: "POST",
            data: $("#cat_frm").serializeArray(),
            dataType: "HTML",
            beforeSend: function() {
            // setting a timeout
            $("#preloader").css('display','block');
        },
            success: function(data)
            {
                load_department_add_section();
                
                //show_toastr(4, "Message", "Category updated successfully");
                //swal('Message','Category updated successfully');
                $("#success_div").css("display","block");
                $("#success_div").html("Category order updated successfully.");
                $("#success_div").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success_div").slideUp(500);
                });
            },
            complete: function(){
                $("#preloader").css('display','none');
            }
        });
    }
    /*------------------------------------------------------------*/

        
    
    /*-------------------------------------------------------*/
    /*------------------ UPDATE CATEGORY --------------------*/
    function update_category(CatId)
    {
        var adminurl = $('#siteadminurl').val();
        var controllername = $('#controller_name').val();
        var actionUrlPath = adminurl + controllername + '/' + 'manage_department/' + CatId;
        
        document.location.href = actionUrlPath;
    }
    /*-------------------------------------------------------*/

    /*------------------ DELETE CATEGORY --------------------*/
    function delete_record(CatId)
    {  
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover the record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {

              var adminurl = $('#siteadminurl').val();
                var controllername = $('#controller_name').val();
                var actionUrlPath = adminurl + controllername + '/' + 'delete_department/' + CatId;
               
                document.location.href = actionUrlPath;

            });
    }
    /*------------------------------------------------------------*/

//jQuery("#form-add").validationEngine('attach', {promptPosition : "bottomLeft", autoPositionUpdate : true});

</script>

