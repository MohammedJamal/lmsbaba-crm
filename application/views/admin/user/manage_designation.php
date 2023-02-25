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
                    <?php /* ?>
                    <?php if(is_method_available('user','add_employee')==TRUE){ ?>
                    <li>
                       <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/add_employee" class="new_filter_btn">
                          <span class="bg_span"><img src="<?=assets_url();?>images/adduesr_new.png"></span>
                         Add User
                        </a>
                    </li>
                    <?php } ?>
                    <?php if(is_method_available('user','manage_department')==TRUE){ ?>
                      <li>
                        <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_department" class="new_filter_btn">
                          <span class="bg_span"><img src="<?=assets_url();?>images/filter_new_department.png"></span>
                         Manage Department
                        </a>
                      </li>
                    <?php } ?>
                    <?php if(is_method_available('user','manage_designation')==TRUE){ ?>
                    <li>
                       <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_designation" class="new_filter_btn">
                          <span class="bg_span"><img src="<?=assets_url();?>images/filter_new_deg.png"></span>
                         Manage Designation
                        </a>
                    </li>
                    <?php } ?>
                    <?php if(is_method_available('user','manage_functional_area')==TRUE){ ?>
                    <li>
                       <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_functional_area" class="new_filter_btn">
                          <span class="bg_span"><img src="<?=assets_url();?>images/filter_new_deg2.png"></span>
                         Manage Functional Area
                        </a>
                    </li>
                    <?php } ?>
                    <?php */ ?>
                 </ul>
                 
              </div>
           </div>
        </div>
    <div class="card process-sec">
      <?php /* ?>
      <div class="">          
        <?php  if(is_method_available('user','add_designation')==TRUE){ ?>
        <div class="col-md-12">
            <a class="pull-right" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/add_designation"><button type="button" class="btn btn-primary btn-round-shadow m-r-xs m-b-xs mb-0 mt-14">Add Designation</button></a>
        </div>
        <?php } ?>
      </div>
      <?php */ ?>
        <div class="clear">&nbsp;</div>

      <div class="col-md-6">
        <div class="card-block">
          <div class="no-more-tables">
            <h3 class="panel-title text-light"><i class="fa fa-list fa-fw text-sm"></i> Manage <strong>Designations</strong></h3><br>
            <?php
            if($rows)
            {       
            ?>            
            <table class="table table-bordered table-striped m-b-0 th_color" id="userList">
              <thead>
                <tr>
                  <th width="80%">Name</th>
                  <th width="20%" class="no-sort text-center" >Action</th>
                </tr>
              </thead>
              <?php
              $key = 0;
              foreach($rows as $row)
              {
              ?>
                <tr>
                  <td><strong><?php print stripslashes($row->name); ?></strong> <span class="badge badge-secondary bg-info" id=""><?php echo $row->emp_count; ?></span></td>
                  <td class="text-center">
                  <?php  if(is_method_available('user','edit_designation')==TRUE){ ?>
                  <a href="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_designation/<?php echo $row->id?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                  <?php }else{ ?>
                    <i class="fa fa-pencil" aria-hidden="true" style="text-decoration: line-through;" data-toggle="tooltip" data-placement="left" title="" data-original-title="Not Applicable"></i>&nbsp;&nbsp;&nbsp;
                  <?php } ?>

                  <?php  if(is_method_available('user','delete_designation')==TRUE){ ?>
                    <a href="#" onclick="confirm_delete('<?php echo $row->id?>')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                  <?php }else{ ?>
                    <i class="fa fa-trash" aria-hidden="true" style="text-decoration: line-through;" data-toggle="tooltip" data-placement="left" title="" data-original-title="Not Applicable"></i>
                  <?php } ?>
                  </td>
                </tr>
              <?php 
              }
              ?>
            </table>
            <nav class="pull-right"><?php  echo $this->pagination->create_links(); ?></nav>
            <?php         
            }
            else
            {
              echo'No Records Found';
            }
            ?>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card-block">
          <h3 class="panel-title text-light"><i class="fa fa-list fa-fw text-sm"></i> <?php echo $label; ?> <strong>Designation</strong></h3><br>
          <form class="tsf-content" action="<?php echo $action; ?>" method="post" name="validate" id="validate">
            <input type="hidden" name="edit_id" id="edit_id" value="<?php echo $id; ?>">
            <div class="group_from">
              <div class="form-group">
                <label><b>Name</b></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="<?php echo $name; ?>" />
              </div>
              <div class="tsf-controls ">  
                  <button class="btn btn-right btn-primary btn-round-shadow border_blue pull-right"  onclick="form_submit()" type="button">SUBMIT</button>
              </div>
            </div>  
          </form>
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
<!-- <script src="<?=base_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>vendor/sweetalert/dist/sweetalert.min.js"></script> -->
<script type="text/javascript">
$(document).ready(function () {
    $('#validate').validate({ // initialize the plugin    
        rules: {
            name: {
                required: true,
            }            
        },
        // Specify validation error messages
    messages: {
      name: "Please enter name"      
    },     
    });
});
    
function form_submit()
{

  var name=$("#name").val();
  if(name=='')
  {
    swal('Oops','Name should not be blank','error');
    return false;
  }
  $('#validate').submit();
} 
</script>

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
      
      window.location.href="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/delete_designation/"+id;
    });
    return false;
  }

$(document).ready(function() {
    $('#userList').DataTable({
          "pageLength": 10,
          "columnDefs": [ {
          "targets": 'no-sort',
          "orderable": false,
                      } ]
    });
} );
</script>
