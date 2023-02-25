<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>   
   <link rel="stylesheet" href="<?=assets_url();?>plugins/jquery-ui/jquery-ui.min.css"> 
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


  <div class="row m-b-1">
           <div class="col-sm-3 pr-0">
              <div class="bg_white back_line">
                 <h4><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee">Manage Users</a> </h4>
              </div>
           </div>
           <div class="col-sm-9 pleft_0">
              <div class="bg_white_filt">
              <ul class="filter_ul">
                               
                                
                  
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
<form role="form" class="form-validation" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/add_employee" method="post" name="formUser" id="formUser" enctype="multipart/form-data"> 
  
  <input type="hidden" name="user_type" id="user_type" value="User">
  <input type="hidden" id="emp_id" value=""/>
  <div class="row">
    <div class="col-lg-12">
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
      <?php if($this->session->flashdata('error_msg') || $error_msg) { ?>
      <!--  error message area start  -->
      <div class="alert alert-danger alert-alt" style="display:block;" id="notification-error">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-exclamation-triangle"></i> Error</h4> <span id="error_msg">
        <?php echo ($this->session->flashdata('error_msg'))?$this->session->flashdata('error_msg'):$error_msg; ?></span>
      </div>
      <!--  error message area end  -->
      <?php } ?>

      
<div class="group_from bg_color_white-new user-tab-page">
  <div class="user-title">Add User</div>
  <div class="tab_gorup">
    <div class="tab plr-40">
      <button class="tablinks" onClick="openCity(event, 'official_details')" id="defaultOpen" type="button">Official Details</button>
      <button class="tablinks" onClick="openCity(event, 'personal_details');" type="button" id="step_two">Personal Details</button>
      <button class="tablinks" onClick="openCity(event, 'change_pass')" type="button" id="step_three">Login Details</button>
    </div>  

                          
    <div id="official_details" class="tabcontent card-block mt-1">
        <div class="col-md-12 col-sm-11 col-xs-12">
          <div class="row">
            <div class="col-md-7">
              <?php /* ?>
              <div class="form-group row">
                <div class="col-md-12 blue-title">Select Department</div>
                <div class="col-md-12">                  
                  <ul class="auto-width-ul">
                    <li class="lh-46">Compant</li>
                    <li class="lh-46">></li>
                    <li class="lh-46">Management</li>
                    <li class="lh-46">></li>
                    <li>
                      <select class="custom-select form-control get_chield set_name" onchange="get_chield(this)" data-level=0 id="parent_id" name="department_id[]">
                            <option value="">Select</option>
                            <?php foreach($department_0_level_key_value as $k=>$val){ ?>
                            <?php
                            $val_arr=explode("#", $val);
                            $val_tmp = $val_arr[0];
                            $haschild=$val_arr[1];
                            ?>
                            <option value="<?php echo $k; ?>" <?php if($selected_value==$k){echo'SELECTED';} ?> data-haschild="<?php echo $haschild; ?>" ><?php echo $val_tmp; ?></option>
                            <?php } ?>
                      </select>
                      <a href="JavaScript:void(0);" class="add_department_ajax new-right" data-formaction="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']."/".$controller."/add_department_ajax"; ?>" data-pid="0">Add New</a>
                    </li>
                    <li class="lh-46">></li>
                    <li>
                      <select class="custom-select form-control" id="manager_id" name="manager_id">
                          <option value="">Select</option>
                      </select>
                      <input type="hidden" id="manager_selected_value" value="">
                      <input type="hidden" id="existing_department_id" value="">
                      <a href="JavaScript:void(0);" class="add_department_ajax new-right" data-formaction="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']."/".$controller."/add_department_ajax"; ?>" data-pid="0">Add New</a>
                    </li>
                  </ul>
                </div> 
              </div>
              <?php */ ?>
              <div class="form-group row">
                <div class="col-md-12 blue-title">Select Department</div>
                <div class="col-md-12"> 
                    <select class="custom-select form-control get_chield set_name" onchange="get_chield__(this)" data-level=0 id="parent_id" name="department_id[]">
                        <option value="">Select</option>
                        <?php foreach($department_0_level_key_value as $k=>$val){ ?>
                        <?php
                        $val_arr=explode("#", $val);
                        $val_tmp = $val_arr[0];
                        $haschild=$val_arr[1];
                        ?>
                        <option value="<?php echo $k; ?>" <?php if($selected_value==$k){echo'SELECTED';} ?> data-haschild="<?php echo $haschild; ?>" ><?php echo $val_tmp; ?></option>
                        <?php } ?>
                    </select>                
                    <input type="hidden" id="total_level" value="">
                    <a href="JavaScript:void(0);" class="add_department_ajax new-right" data-formaction="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']."/".$controller."/add_department_ajax"; ?>" data-pid="0">Add New</a>
                </div>  
                <span id="child_department_div"></span>                  
              </div>



              <div class="form-group row">
                <div class="col-md-12 blue-title">Select Designation</div>
                <div class="col-md-12">
                  <span id="designation_dropdown_div"></span>
                  <input type="hidden" id="designation_selected_value" value="">
                  <a href="JavaScript:void(0);" id="add_designation_ajax" class="new-right" data-formaction="">Add New</a>
                </div> 
              </div>

              <div class="form-group row">
                <div class="col-md-12 blue-title">Select Manager</div>
                <div class="col-md-12">
                  <span id="manager_dropdown_div">
                    <select class="custom-select form-control" id="manager_id" name="manager_id">
                        <option value="">Select</option>
                    </select>
                  </span>
                  <input type="hidden" id="manager_selected_value" value="">
                  <input type="hidden" id="existing_department_id" value="">

                  <!-- <select class="custom-select form-control" id="manager_id" name="manager_id">
                      <option value="">Select</option>
                  </select> -->
                </div> 
              </div>

              <div class="form-group row">
                <div class="col-md-12 blue-title">Select Functional Area</div>
                <div class="col-md-12">
                  <span id="functional_area_dropdown_div"></span>
                  <input type="hidden" id="functional_area_selected_value" value="">
                  <a href="JavaScript:void(0);" id="add_functional_area_ajax" class="new-right" data-formaction="">Add New</a>
                </div> 
              </div>

            </div>
            <div class="col-md-5">
              <div class="photo-wapper">
                <span class="file">
                  <input type="file" name="image" id="photo" onchange="GetImagePreview(this,'agent_photo_prev')">
                  <label for="file" class="new">Add Photo</label>
                </span>
                <div class="user_image">
                  <a id="agent_photo_prev"><img src="<?=assets_url().'images/user_img_icon.png';?>"/></a>
                    
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group row">                
                <div class="col-md-6">
                  <label for="" class="col-form-label">Name<span class="text-danger">*</span> :</label>
                  <div class="">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $this->input->post('name'); ?>" />
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="" class="col-form-label">Office Email<span class="text-danger">*</span> :</label>
                  <div>
                    <input type="text" class="form-control" name="email" id="email" placeholder="E-mail" value="<?php echo $this->input->post('email'); ?>" />
                  </div>
                </div>

              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group row">
                <div class="col-md-6">
                  <label for="" class="col-form-label">Office Mobile<span class="text-danger">*</span> : </label>
                  <div class="">
                    <input type="text" class="form-control only_natural_number" name="mobile" id="mobile" placeholder="Mobile"  value="<?php echo $this->input->post('mobile'); ?>" maxlength="15" />
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="" class="col-form-label">Joining Date :</label>
                  <div class="">
                    <input type="text" class="form-control display_date" name="joining_date" id="joining_date" placeholder="Joining Date" value="<?php echo $this->input->post('joining_date'); ?>" readonly="true" />
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group row">
                <div class="col-md-6">
                  <label for="" class="col-form-label">Date Of Birth :</label>
                  <div class="">
                    <input type="text" class="form-control display_date" name="date_of_birth" id="date_of_birth" placeholder="Date Of Birth" value="<?php echo $this->input->post('date_of_birth'); ?>" readonly="true" />
                  </div>
                </div>
                <div class="col-md-6">
                 <label for="" class="col-form-label">Salary :</label>
                  <div class="clear"></div>
                   <div class="row">
                     <div class="col-sm-8">
                        <input type="text" class="form-control col-xs-6" name="salary" id="salary" placeholder="Salary" value="<?php echo $this->input->post('salary'); ?>" />
                     </div>
                     <div class="col-sm-4">
                        <select class="custom-select form-control" name="salary_currency_code" id="salary_currency_code">
                           <?php foreach($currency_list as $curr){?>        
                           <option value="<?php echo $curr->code; ?>" <?php if($this->input->post('salary_currency_code')==$curr->code){echo'selected';} ?>>
                              <?php echo $curr->code; ?>
                           </option>
                           <?php } ?>     
                        </select>
                     </div>
                   </div>
                </div>

              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group row">
                <div class="col-md-6">
                   <label for="" class="col-form-label">PAN :</label>
                   <div>
                      <input type="text" class="form-control" name="pan" id="pan" placeholder="PAN No." value="<?php echo $this->input->post('pan'); ?>" />
                   </div>
                </div>
                <div class="col-md-6">
                   <label for="" class="col-form-label">Aadhar Card :</label>
                   <div>
                      <input type="text" class="form-control" name="aadhar" id="aadhar" placeholder="Aadhar Card" value="<?php echo $this->input->post('aadhar'); ?>" />
                   </div>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group row"> 
                <div class="col-md-6">
                  <label for="" class="col-form-label">User Type<span class="text-danger">*</span> :</label>
                  <div class="">
                    <select class="custom-select form-control" name="employee_type_id" id="employee_type_id">
                        <?php foreach($employee_type as $etype){?>        
                        <option value="<?php echo $etype['id']; ?>">
                          <?php echo $etype['name']; ?></option>
                        <?php } ?>     
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="" class="col-form-label">Branch Name<span class="text-danger">*</span> :</label>
                  <div class="">
                    <select class="custom-select form-control" name="branch_id" id="branch_id">
                        <?php foreach($branch_list as $branch){?>        
                        <option value="<?php echo $branch['id']; ?>">
                          <?php echo ($branch['name'])?$branch['name']:$branch['cs_name']; ?></option>
                        <?php } ?>     
                    </select>
                  </div>
                </div>

              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group row">
                <div class="col-sm-12 text-right">
                  <button type="button" class="btn btn-right btn-primary btn-round-shadow border_blue step_one" onclick="$(this).submit();">Next</button>
                <!-- <a href="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee" class="btn btn-default border_blue">Back</a> -->
                </div>                                      
              </div>
            </div>

          </div>
        </div>
        
        
    </div>
                          
    <div id="personal_details" class="tabcontent card-block mt-1">
      <!-- row start -->
      <div class="col-md-12">
        <div class="form-group row">
          <div class="col-md-6">
            <label for="" class="col-form-label">Personal Email<span class="text-danger">*</span> : <!-- <a href="JavaScript:void(0);"  data-toggle="tooltip" title="Personal email should be unique. And login username will be sent to the email address."><i class="fa fa-info-circle" aria-hidden="true"></i></a> --></label>
            <div class="">
              <input type="text" class="form-control" name="personal_email" id="personal_email" placeholder="Personal Email" value="<?php echo $this->input->post('personal_email'); ?>" />
              <span id="personal_email_error" class="error"></span>
            </div> 
          </div>

          <div class="col-md-6">
            <label for="" class="col-form-label">Personal Mobile<span class="text-danger">*</span> : <!-- <a href="JavaScript:void(0);" data-toggle="tooltip" title="Personal mobile should be unique."><i class="fa fa-info-circle" aria-hidden="true"></i></a> --></label>
            <div class="">
              <input type="text" class="form-control only_natural_number" name="personal_mobile" id="personal_mobile" placeholder="Personal Mobile" value="<?php echo $this->input->post('personal_mobile'); ?>" maxlength="15" />
              <span id="personal_mobile_error" class="error"></span>
            </div>  
          </div> 

        </div>
      </div>
      <!-- row end -->

      <!-- row start -->
      <div class="col-md-12">
        <div class="form-group row">
          
          <div class="col-md-6">
             <label for="" class="col-form-label">Gender<span class="text-danger">*</span> :</label>
             <div class="">
                <select id="gender" name="gender" class="custom-select form-control">
                   <option value="">Choose...</option>
                   <option value="M" <?php if($this->input->post('gender')=='M'){echo'selected';} ?>>Male</option>
                   <option value="F" <?php if($this->input->post('gender')=='F'){echo'selected';} ?>>Female</option>
                </select>
             </div>
          </div>
          <div class="col-md-6">
             <label for="" class="col-form-label">Marital Status<span class="text-danger">*</span> :</label>
             <div class="">
                <select class="custom-select form-control" name="marital_status" id="marital_status">
                   <option value="">Choose...</option>
                   <option value="unmarried" <?php if($this->input->post('marital_status')=='unmarried'){echo'selected';} ?>>Unmarried</option>
                   <option value="married" <?php if($this->input->post('marital_status')=='married'){echo'selected';} ?>>Married</option>
                </select>
             </div>
          </div>

        </div>
      </div>
      <!-- row end -->

      <!-- row start -->
      <div class="col-md-12">
        <div class="form-group row">
          
          <div class="col-md-6">
             <label for="" class="col-form-label">Marriage Anniversary :</label>
             <div>
                <input type="text" class="form-control display_date" name="marriage_anniversary" id="marriage_anniversary" placeholder="Marriage Anniversary" value="<?php echo $this->input->post('marriage_anniversary'); ?>" disabled="disabled" />
             </div>
          </div>
          <div class="col-md-6">
             <label for="" class="col-form-label">Spouse Name :</label>
             <div class="">
                <input type="text" class="form-control" name="spouse_name" id="spouse_name" placeholder="Spouse Name" value="<?php echo $this->input->post('spouse_name'); ?>" readonly="true" />
             </div>
          </div>
          
        </div>
      </div>
      <!-- row end -->

      <!-- row start -->
      <div class="col-md-12">
        <div class="form-group row">
          
          <div class="col-md-12">
             <label for="" class="col-form-label">Address :</label>
             <div class="">
                <textarea class="form-control" name="address" id="address"><?php echo $this->input->post('address'); ?></textarea>
             </div>
          </div>
          
        </div>
      </div>
      <!-- row end -->

      <!-- row start -->
      <div class="col-md-12">
        <div class="form-group row">
          
          <div class="col-md-4">
             <label for="" class="col-form-label">Country :</label>
             <div class="">
                <select class="custom-select form-control" name="country_id" id="country_id" onchange="GetStateList(this.value)">
                   <option value="">Select</option>
                   <?php foreach($country_list as $country_data)
                      {
                        ?>
                   <option value="<?php echo $country_data->id;?>" ><?php echo $country_data->name;?></option>
                   <?php
                      }
                      ?>
                </select>
             </div>
          </div> 
          <div class="col-md-4">
             <label for="" class="col-form-label">State :</label>
             <div class="">
                <select class="custom-select form-control" name="state" id="state" onchange="GetCityList(this.value)">
                   <option value="">Select</option>
                </select>
             </div>
          </div>
          <div class="col-md-4">
             <label for="" class="col-form-label">City :</label>
             <div class="">
                <select class="custom-select form-control" name="city" id="city">
                   <option value="">Select</option>
                </select>
             </div>
          </div>         
          
        </div>
      </div>
      <!-- row end -->

      <!-- row start -->
      <div class="col-md-12">
        <div class="form-group row text-right">
           <div class="col-sm-12">
              <a href="JavaScript:void(0);" onClick="openTab('defaultOpen')" class="btn btn-right btn-primary btn-round-shadow border_blue">Back</a>

              <button type="button" class="btn btn-right btn-primary btn-round-shadow border_blue step_two" onclick="$(this).submit();">Next</button>
              
           </div>
        </div>
      </div>
      <!-- row end -->

    </div>
                          
      <div id="change_pass" class="tabcontent card-block mt-1">
          
        <div class="col-md-12">
        <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Username :</label>
              <div class="col-sm-5 text-right">                
                <input type="text" autocomplete="off" class="form-control " name="username" id="username" placeholder="Username" maxlength="20"  />

              </div>

          </div>
          <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Password :</label>
              <div class="col-sm-5 text-right">
                <a href="JavaScript:void(0);" class="toggle_field_type" class="" data-id="password" data-state="hide">Key Show</a>
                <input type="password" autocomplete="off" class="form-control" name="password" id="password" placeholder="Password"  />

              </div>

          </div>

          <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Confirm Password :</label>
              <div class="col-sm-5 text-right">
                <a href="JavaScript:void(0);" class="toggle_field_type" class="" data-id="password_confirm" data-state="hide">Key Show</a>
                <input type="password" autocomplete="off" class="form-control" name="password_confirm" id="password_confirm" placeholder="Password"  />
              </div>
          </div>
          <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">&nbsp;</label>
              <div class="col-sm-5 ">
                <small class="text-danger text-bold">Note: Your login details will be provided to your personal email.</small>
              </div>
          </div>

          <div class="form-group row">
                  <label for="" class="col-sm-2 col-form-label">&nbsp;</label>
                  <div class="col-sm-5">
                    <a href="JavaScript:void(0)" onClick="openTab('step_two')" class="btn btn-right btn-primary btn-round-shadow border_blue">Back</a>
                    <button type="submit" class="btn btn-right btn-primary btn-round-shadow border_blue">Submit</button>
                  
                  </div>                                      
          </div>
          </div>
      </div>
          </div>
      </div>
    </div>             
  </div> 

</form>       
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   
<script type="text/javascript">
window.paceOptions = {
          document: true,
          eventLag: true,
          restartOnPushState: true,
          restartOnRequestAfter: true,
          ajax: {
            trackMethods: [ 'POST','GET']
          }
};
</script>

    <script src="<?=assets_url();?>vendor/jquery/dist/jquery.js"></script>
    <script src="<?=assets_url();?>vendor/pace/pace.js"></script>
    <script src="<?=assets_url();?>vendor/tether/dist/js/tether.js"></script>
    <script src="<?=assets_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script>
    <script src="<?=assets_url();?>vendor/fastclick/lib/fastclick.js"></script>
    <script src="<?=assets_url();?>scripts/constants.js"></script>
    <script src="<?=assets_url();?>scripts/main.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script> -->
    <script src="<?php echo assets_url(); ?>plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload.css"/>
    <link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload-ui.css"/>
    <!-- endbuild -->
    <!-- page scripts -->
    <script src="<?php echo assets_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
    <script src="<?php echo assets_url(); ?>vendor/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript" src="<?=assets_url();?>js/custom/user/add_employee.js?v=<?php echo rand(0,1000); ?>"></script>
    <!-- initialize page scripts -->
    <script>
  $( function() {
    // $( "#date_of_birth" ).datepicker();
    // $( "#joining_date" ).datepicker();    
    // $( "#marriage_anniversary" ).datepicker();
    //$( "#next_appraisal_date" ).datepicker();
    $('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+0'
    });
  } );
  </script> 
<script type="text/javascript">
$(document).ready(function () {

  $('#username,#password,#password_confirm').bind('keypress paste', function (event) {
    var regex = /^[a-zA-Z0-9%()#@_& -]+$/;
    var key = String.fromCharCode(event.charCode || event.which);
    if (!regex.test(key)) {
      event.preventDefault();
      return false;
    }
  });


  $('#username,#password,#password_confirm').keypress(function( e ) {
       if(e.which === 32) 
         return false;
  });
  $('#formUser').validate({   
        rules: {
            user_type: {
                required: true
            },
            parent_id: {
                required: true
            },
            manager_id: {
                required: true
            },
            designation_id: {
                required: true
            },
            functional_area_id: {
                required: true
            },
            name: {
                required: true
            },                                       
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                minlength: '10',
                number: true               
            },
            // joining_date: {
            //   required: true,
            // },
            // date_of_birth: {
            //   required: true,
            // },
            personal_email: {
                required: true,
                email: true
            },
            personal_mobile: {
                required: true,
                minlength: '10',
                number: true             
            },
            gender: {
                required: true
            },
            marital_status: {
                required: true
            },
            username: {
                required: true,
                minlength : 6,
                nospace: true
                //alphanumeric: true          
            },
            password: {
                required: true,
                minlength : 6
                //alphanumeric: true          
            },
            password_confirm : {
                    minlength : 6,
                    equalTo : "#password"
            }            
        },
        // Specify validation error messages
        messages: {
          parent_id: "Please select department",
          manager_id: "Please select manager",
          designation_id: "Please select designation",
          functional_area_id: "Please select functional area",
          name: "Please enter name",      
          email: "Please enter a valid email address",          
          mobile: "Please enter mobile no (Length - 10)",
          //joining_date: "Please select joining date",
          //date_of_birth: "Please select date of birth",
          personal_email: "Please enter a valid personal email address",          
          personal_mobile: "Please enter personal mobile no (Length - 10)",
          gender: "Please select gender",
          marital_status: "Please select marital status",
          password: {                      
                      required: "Please enter password",
                      minlength: "Minimum length should be greather than 6"
                      //alphanumeric: "password should be alphanumeric"
                    }
          
        },
        submitHandler: function() {           

          var department=$("#parent_id").val();
          var designation_id=$("#designation_id").val();
          var manager_id=$("#manager_id").val();
          var functional_area_id=$("#functional_area_id").val();
          var name=$("#name").val();
          var email=$("#email").val();
          var mobile=$("#mobile").val();

          var personal_email=$("#personal_email").val();
          var personal_mobile=$("#personal_mobile").val();
          var gender=$("#gender").val();
          var marital_status=$("#marital_status").val();

          var password=$("#password").val();
          var password_confirm=$("#password_confirm").val();


          if(department!='' && designation_id!='' && manager_id!='' && functional_area_id!='' && name!='' && email!='' && mobile!='' && personal_email!='' && personal_mobile!='' && gender!='' && marital_status!='' && password!='' && password_confirm!='')
          {
              return true;
          }
          else
          {

              if(department=='' || designation_id=='' || manager_id=='' || functional_area_id=='' || name=='' || email=='' || mobile=='')
              {                
                go_next("defaultOpen");
                $(".step_one").submit();
              }
              else if(personal_email=='' || personal_mobile=='' || gender=='' || marital_status=='')
              {                
                go_next("step_two");
                $(".step_two").submit();
              }
              else if(password=='' || password_confirm=='')
              {                
                go_next("step_three");
                $(".step_three").submit();
              }
              // if($("#personal_email").val()=='' || $("#personal_mobile").val()=='')
              // {
              //   go_next("step_two");
              // }
              // else
              // {
              //   go_next("step_three");
              // }
          }
        }           
    });
});

function go_next(step_id)
{
  document.getElementById(step_id).click();
}

function GetStateList(cont)
{
  $.ajax({
      url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getstatelist",
      type: "POST",
      data: {'country_id':cont},      
      success: function (response) 
      {
        if(response!='')
        {
        document.getElementById('state').innerHTML=response;
      }
          
      },
      error: function () 
      {
       //$.unblockUI();
       alert('Something went wrong there');
      }
  });
}

function GetCityList(state)
{
  $.ajax({
      url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getcitylist",
      type: "POST",
      data: {'state_id':state},     
      success: function (response) 
      {
        if(response!='')
        {
        document.getElementById('city').innerHTML=response;
      }
          
      },
      error: function () 
      {
       //$.unblockUI();
       alert('Something went wrong there');
      }
     });
}
</script>
<script>
function openCity(evt, cityName) { 
    var i, tabcontent, tablinks;
    
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";

}
  
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
function openTab(id)
{
  document.getElementById(id).click();
}
//====================================================================
// Get Image Preview
function GetImagePreview(input,displayDiv)
{
   if (input.files && input.files[0]) 
   {
      var reader = new FileReader();
      reader.onload = function (e) {
        var strHtml = '<img src="'+e.target.result+'" width="300">'; 
        $('#'+displayDiv).html(strHtml);  
      };
      reader.readAsDataURL(input.files[0]);
   }
}
// Get Image Preview
//====================================================================
</script>
<!-- ============================================== -->
<!-- ADD DEPARTMENT BY POPUP -->


<!-- ========================== -->
<!-- Add Department Modal Start -->
<div id="FormOpenModal" class="modal fade" role="dialog" style="z-index: 9999">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title_text"></h4>
      </div>
      <div class="modal-body">
          <form action="" method="post" name="add_form" id="add_form" role="form" class="form-horizontal" enctype="multipart/form-data">  
          <input type="hidden" id="existing_pid" value="0">
          <input type="hidden" id="existing_l" value="0">        
            <div class="row">                
                <div class="col-sm-12">
                    <div id="catAddSection" class="panel">
                        <div class="panel-body">
                            <div id="render_add_html_view"></div>
                        </div>
                    </div>
                </div>
              </div>
            </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- Add Department Modal End -->
<!-- ========================== -->

<!-- ADD DEPARTMENT BY POPUP -->
<!-- ============================================== -->
