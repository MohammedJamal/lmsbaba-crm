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
                             <h4>Manage <?php echo $menu_label_alias['menu']['user']; ?></h4>
                          </div>
                       </div>
                       <div class="col-sm-9 pleft_0">                          
                          <div class="bg_white_filt">
                              
                              <ul class="filter_ul">
                                  
                                
                                  <?php  ?>
                                  <?php if(is_permission_available('add_users_menu')){ ?>
                                  <li>
                                    <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/add_employee" class="new_filter_btn">
                                        <span class="bg_span lg-w">
                                          <svg baseProfile="tiny" height="24px" id="Layer_1" version="1.2" viewBox="0 0 24 24" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M9,14c1.381,0,2.631-0.56,3.536-1.465C13.44,11.631,14,10.381,14,9s-0.56-2.631-1.464-3.535C11.631,4.56,10.381,4,9,4  S6.369,4.56,5.464,5.465C4.56,6.369,4,7.619,4,9s0.56,2.631,1.464,3.535C6.369,13.44,7.619,14,9,14z"></path><path d="M9,21c3.518,0,6-1,6-2c0-2-2.354-4-6-4c-3.75,0-6,2-6,4C3,20,5.25,21,9,21z"></path><path d="M21,12h-2v-2c0-0.553-0.447-1-1-1s-1,0.447-1,1v2h-2c-0.553,0-1,0.447-1,1s0.447,1,1,1h2v2c0,0.553,0.447,1,1,1s1-0.447,1-1  v-2h2c0.553,0,1-0.447,1-1S21.553,12,21,12z"></path></svg>
                                        </span>
                                        Add <?php echo $menu_label_alias['menu']['user']; ?>
                                      </a>
                                  </li>
                                  <?php } ?>
                                  <?php if(is_permission_available('manage_department_menu')){ ?>
                                    <li>
                                      <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_department" class="new_filter_btn">
                                        <span class="bg_span lg-w">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M335.5 4l288 160c15.4 8.6 21 28.1 12.4 43.5s-28.1 21-43.5 12.4L320 68.6 47.5 220c-15.4 8.6-34.9 3-43.5-12.4s-3-34.9 12.4-43.5L304.5 4c9.7-5.4 21.4-5.4 31.1 0zM320 240c-22.1 0-40-17.9-40-40s17.9-40 40-40s40 17.9 40 40s-17.9 40-40 40zM144 336c-22.1 0-40-17.9-40-40s17.9-40 40-40s40 17.9 40 40s-17.9 40-40 40zm392-40c0 22.1-17.9 40-40 40s-40-17.9-40-40s17.9-40 40-40s40 17.9 40 40zM226.9 491.4L200 441.5V480c0 17.7-14.3 32-32 32H120c-17.7 0-32-14.3-32-32V441.5L61.1 491.4c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l37.9-70.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c16.3 0 31.9 4.5 45.4 12.6l33.6-62.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c32.4 0 62.1 17.8 77.5 46.3l33.6 62.3c13.5-8.1 29.1-12.6 45.4-12.6h19.5c32.4 0 62.1 17.8 77.5 46.3l37.9 70.3c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8L552 441.5V480c0 17.7-14.3 32-32 32H472c-17.7 0-32-14.3-32-32V441.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l36.3-67.5c-1.7-1.7-3.2-3.6-4.3-5.8L376 345.5V400c0 17.7-14.3 32-32 32H296c-17.7 0-32-14.3-32-32V345.5l-26.9 49.9c-1.2 2.2-2.6 4.1-4.3 5.8l36.3 67.5c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8z"/></svg>
                                        </span>
                                      Department
                                      </a>
                                    </li>
                                  <?php } ?>
                                  <?php if(is_permission_available('manage_designation_menu')){ ?>
                                  <li>
                                    <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_designation" class="new_filter_btn">
                                        <span class="bg_span lg-w">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M512 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h448c35.35 0 64-28.65 64-64V96C576 60.65 547.3 32 512 32zM176 128c35.35 0 64 28.65 64 64s-28.65 64-64 64s-64-28.65-64-64S140.7 128 176 128zM272 384h-192C71.16 384 64 376.8 64 368C64 323.8 99.82 288 144 288h64c44.18 0 80 35.82 80 80C288 376.8 280.8 384 272 384zM496 320h-128C359.2 320 352 312.8 352 304S359.2 288 368 288h128C504.8 288 512 295.2 512 304S504.8 320 496 320zM496 256h-128C359.2 256 352 248.8 352 240S359.2 224 368 224h128C504.8 224 512 231.2 512 240S504.8 256 496 256zM496 192h-128C359.2 192 352 184.8 352 176S359.2 160 368 160h128C504.8 160 512 167.2 512 176S504.8 192 496 192z"></path></svg>
                                       </span>
                                      Designation
                                      </a>
                                  </li>
                                  <?php } ?>
                                  <?php if(is_permission_available('manage_functional_area_menu')){ ?>
                                  <li>
                                    <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_functional_area" class="new_filter_btn">
                                        <span class="bg_span lg-w--">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M224 0c70.7 0 128 57.3 128 128s-57.3 128-128 128s-128-57.3-128-128S153.3 0 224 0zM209.1 359.2l-18.6-31c-6.4-10.7 1.3-24.2 13.7-24.2H224h19.7c12.4 0 20.1 13.6 13.7 24.2l-18.6 31 33.4 123.9 39.5-161.2c77.2 12 136.3 78.8 136.3 159.4c0 17-13.8 30.7-30.7 30.7H265.1 182.9 30.7C13.8 512 0 498.2 0 481.3c0-80.6 59.1-147.4 136.3-159.4l39.5 161.2 33.4-123.9z"/></svg>
                                        </span>
                                      Functional Area
                                      </a>
                                  </li>
                                  <?php } ?>
                                  <?php  ?>
                                  <?php if($session_info['user_id']=='1'){ ?>
                                  <li>
                                    <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/tag_service" class="new_filter_btn">
                                        <span class="bg_span lg-w">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c10 0 18.8-4.9 24.2-12.5l-99.2-99.2c-14.9-14.9-23.3-35.1-23.3-56.1v-33c-15.9-4.7-32.8-7.2-50.3-7.2H178.3zM384 224c-17.7 0-32 14.3-32 32v82.7c0 17 6.7 33.3 18.7 45.3L478.1 491.3c18.7 18.7 49.1 18.7 67.9 0l73.4-73.4c18.7-18.7 18.7-49.1 0-67.9L512 242.7c-12-12-28.3-18.7-45.3-18.7H384zm72 80c0 13.3-10.7 24-24 24s-24-10.7-24-24s10.7-24 24-24s24 10.7 24 24z"/></svg>
                                        </span>
                                        Tag Services
                                      </a>
                                  </li>
                                  <?php } ?>
                              </ul>
                              
                          </div>                         
                       </div>
                    </div>
					<div class="card">
					  <div class="card-header no-bg b-a-0">
              <?php echo $menu_label_alias['menu']['user']; ?> List
              <div class="pull-right">
                
                <ul class="d-flex-auto">
                  <li >
                    <label class="check-box-sec">
                      <input type="checkbox" value="N" id="is_show_only_active_user" name="is_show_only_active_user" data-text="PENDING">
                      <span class="checkmark"></span>                        
                    </label> <small>All User(s)</small>
                  </li>
                  <li><a href="javascript:void(0);" class="tfilter-btns tableBt active" id="tableBt"><i class="fa fa-bars" aria-hidden="true"></i></a></li>							
                  <li><a href="javascript:void(0);" class="tfilter-btns treeBt " id="treeBt"><i class="fa fa-sitemap" aria-hidden="true"></i></a></li>
                  <li><a href="#" class="tfilter-btns" id="tExted"><svg aria-hidden="true" role="img" class="octicon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none;"><path fill-rule="evenodd" d="M8.177 14.323l2.896-2.896a.25.25 0 00-.177-.427H8.75V7.764a.75.75 0 10-1.5 0V11H5.104a.25.25 0 00-.177.427l2.896 2.896a.25.25 0 00.354 0zM2.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM6 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM12 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zm2.25.75a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5z"></path></svg></a></li>
                </ul>
              </div>
					  </div>

					  <div class="card-block">
						<div class="no-more-tables cusScroll">
						  
						  <div class="row">
							<div class="col-lg-12 col-md-12">
							  <div class="tHolder show" id="user_table_view">
								<div class="wrapper1">
								  <div class="div1"></div>
								</div>
								<div class="table-toggle-holder">
								  <div class="table-full-holder">
                    <div class="table-one-holder">  
                      <table id="datatable" class="table dataTable table-expand-customer company-table" style="width: 100%">
                      <thead>
                        <tr>
                        <th class="text-left sort_order asc" width="80" data-field="t1.id" data-orderby="desc">User Id</th>
                        <th class="text-left sort_order" data-field="t1.name" data-orderby="desc">Name</th>
                        <th class="text-left sort_order" data-field="t1.designation_id" data-orderby="desc">Designation</th>
                        <th class="text-left sort_order" data-field="t1.department_id" data-orderby="desc">Department</th>
                        <th class="text-left sort_order" data-field="t1.functional_area_id" data-orderby="desc">Functional Area</th>
                        <th class="text-left sort_order" data-field="t1.manager_id" data-orderby="desc">Manager</th>
                        <th class="text-left auto-show hide">Email</th>

                        <th class="text-left auto-show hide">User Type</th>
                        <th class="text-left auto-show hide">Branch</th>
                        <th class="text-left auto-show hide">Login Username</th>
                        <th class="text-center">Action</th>
                        
                        </tr>
                      </thead>
                      <tbody id="tcontent">
                        
                      </tbody>
                      </table>
                      
                    </div>
								  </div>
								</div>
							  </div>
							  <div id="page" style="" class="pull-right"></div>
							  <input type="hidden" id="filter_sort_by" value="">
							  <input type="hidden" id="page_number" value="1">
							  <input type="hidden" id="view_type" value="<?php echo $view_type; ?>">

							  <div class="tHolder " id="user_tree_view">
								<div id="managerial_tree_view"></div>
								<!-- <div id="tcontent"></div>  
								<div id="page" style="" class="pull-right"></div> -->
							  </div>
							</div>
						  </div>                  
						</div>
					  </div>
					  <div class="card-block"> 
							<?php                            
							if($this->session->userdata['admin_session_data']['user_id']=='1')
							{
							?>   
							<div class="row">
								<div class="col-md-12">
								  <a class="new_filter_btn" href="JavaScript:void(0);" id="download_user_csv">
								  <span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"/></span> Download Report  </a>
								</div>
							  </div>
							<?php
							}                  
							?>
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
<script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo assets_url();?>js/common_functions.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/custom/user/get_employee.js?v=<?php echo rand(0,1000); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    
    if($("#view_type").val()=='tree')
    {      
      if (!$("#treeBt").hasClass('active')) 
      {
          $("#view_type").val('tree');
          $("#page").hide();
          $("#treeBt").addClass('active');
          $('.tableBt').removeClass('active');
          $('#user_table_view').removeClass('show');
          $('#user_tree_view').addClass('show');
          $('#tExted').addClass('disabled');
        
          if ($('#tExted').hasClass('active')) 
          {
              //$(this).removeClass('active');
              $('.wrapper1').hide();
              $('#tExted').removeClass('active');
              //$('#tExted').addClass('disabled');
              //$(this).find('.fa').removeClass('fa-long-arrow-left').addClass('fa-long-arrow-right');
              $('.table-toggle-holder').find('.auto-show').addClass('hide');
              $('.table-full-holder').css({'width':'100%'});
              $('.table-toggle-holder').removeClass('scroll');
              $(".wrapper1").scrollLeft(0);          
          }
      }
    }
    else
    {     
      event.preventDefault();
      if (!$("#tableBt").hasClass('active')) {
        $("#view_type").val('list');
        $("#page").show();
        $("#tableBt").addClass('active');
        $('.treeBt').removeClass('active');
        $('#user_tree_view').removeClass('show');
        $('#user_table_view').addClass('show');
        $('#tExted').removeClass('disabled');
      }  
    }

    var getPw = '100%';    
    var parentW = 1650;
    $('.wrapper1 .div1').css({'width':parentW});
    $('.table-full-holder').css({'width':getPw});
    $(document).on("click","#tExted",function(event) {
      event.preventDefault();      
      if ($(this).hasClass('disabled')) {
        return false;
      }
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $('.wrapper1').hide();        
        $('.table-toggle-holder').find('.auto-show').addClass('hide');
          $('.table-full-holder').css({'width':'100%'});
          $('.table-toggle-holder').removeClass('scroll');
        $(".wrapper1").scrollLeft(0);        
      }else{
        $(this).addClass('active');
        $('.wrapper1').show();        
        $('.table-toggle-holder').find('.auto-show').removeClass('hide');
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        $('.table-full-holder').css({'width':parentW});
        $('.table-toggle-holder').addClass('scroll');
        $('.table-toggle-holder, .wrapper1').stop( true, true ).
            animate({
              scrollLeft: parentW
            }, 500, function() {
              //$('.media-grid-child').addClass('scroll-active');
            });
      }  
      //$('.table-toggle-holder').scrollLeft(parentW);;
  });
    ///////
    $(".wrapper1").scroll(function(){
        $(".table-toggle-holder")
            .scrollLeft($(".wrapper1").scrollLeft());
    });
    $(".table-toggle-holder").scroll(function(){
        $(".wrapper1")
            .scrollLeft($(".table-toggle-holder").scrollLeft());
    });
    //treeBt
    $(document).on("click",".treeBt",function(event) {
      event.preventDefault();
      if (!$(this).hasClass('active')) 
      {
          $("#view_type").val('tree');
          $("#page").hide();
          $(this).addClass('active');
          $('.tableBt').removeClass('active');
          $('#user_table_view').removeClass('show');
          $('#user_tree_view').addClass('show');
          $('#tExted').addClass('disabled');
        
          if ($('#tExted').hasClass('active')) 
          {
              //$(this).removeClass('active');
              $('.wrapper1').hide();
              $('#tExted').removeClass('active');
              //$('#tExted').addClass('disabled');
              //$(this).find('.fa').removeClass('fa-long-arrow-left').addClass('fa-long-arrow-right');
              $('.table-toggle-holder').find('.auto-show').addClass('hide');
              $('.table-full-holder').css({'width':'100%'});
              $('.table-toggle-holder').removeClass('scroll');
              $(".wrapper1").scrollLeft(0);          
          }
      }      
    });
    $(document).on("click",".tableBt",function(event) {
      event.preventDefault();
      if (!$(this).hasClass('active')) {
        $("#view_type").val('list');
        $("#page").show();
        $(this).addClass('active');
        $('.treeBt').removeClass('active');
        $('#user_tree_view').removeClass('show');
        $('#user_table_view').addClass('show');
        $('#tExted').removeClass('disabled');
      }      
    });
    ////////////////////////
  });
</script>
</body>
</html>
<!-- ========================== -->
<!-- Add Department Modal Start -->
<div id="ViewEmployeeDetailsModal" class="modal fade" role="dialog" style="z-index: 9999">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title_text">Employee List</h4>
      </div>
      <div class="modal-body" id="body_html"></div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Add Department Modal End -->
<!-- ========================== -->