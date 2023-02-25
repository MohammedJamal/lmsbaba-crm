<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
   <?php $this->load->view('admin/includes/head'); ?>
   <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/jquery_modalLink_1_0_0.css" />
   <script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.modalLink-1.0.0.js"></script>
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
              <div class="row m-b-1">
                 <div class="col-sm-12">
                    <div class="bg_white back_line full-bt-style">
                       <h4>
                           <div class="row">
                                 <div class="col-md-4">My Performance</div>                                                              
                           </div>
                       </h4>
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
                  <?php
                  $logo=$company['logo'];
                  if($logo!='')
                  {
                   $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/thumb/".$logo;
                  }
                  else
                  {
                   $profile_img_path = assets_url().'images/user_img_icon.png';
                  }
                  ?>                                
                  <div class="card-body p-0">
                    <div class="tab_gorup side-by-side custom-style-tab">

                      <div class="tab tab-group-sec">   
                        <!-- <div class="setting-title">General Settings</div> -->
                        <button class="tablinks" onClick="openDiv(event, 'tab_1')" id="defaultOpen" type="button"> 
                           <svg style="vertical-align: middle;" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                               <!-- Uploaded to SVGRepo https://www.svgrepo.com -->
                               <g id="Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                   <g id="ic_fluent_activity_24_filled" fill="#212121" fill-rule="nonzero">
                                       <path d="M8.47096081,7.23691331 L11.5265159,20.2289405 C11.7600677,21.221988 13.1560977,21.268924 13.4558214,20.2938058 L16.4005221,10.7135452 L16.7855356,12.2410553 C16.897511,12.6853085 17.2970602,12.9966465 17.7552079,12.9966465 L21,12.9966465 C21.5522847,12.9966465 22,12.5489312 22,11.9966465 C22,11.4443617 21.5522847,10.9966465 21,10.9966465 L18.5344311,10.9966465 L17.4654602,6.75559114 C17.2181786,5.77452142 15.837182,5.73909386 15.5399228,6.70619415 L12.6284436,16.1783724 L9.4752188,2.77105953 C9.23522942,1.75063984 7.78657863,1.74010295 7.53177121,2.75692369 L5.46696027,10.9966465 L3,10.9966465 C2.44771525,10.9966465 2,11.4443617 2,11.9966465 C2,12.5489312 2.44771525,12.9966465 3,12.9966465 L6.2472882,12.9966465 C6.70594806,12.9966465 7.10580602,12.6846261 7.21729537,12.2397228 L8.47096081,7.23691331 Z" id="🎨-Color"></path>
                                   </g>
                               </g>
                           </svg>
                           Activity Log
                        </button>

                        <button class="tablinks" onClick="openDiv(event, 'tab_2')" type="button">
                          <svg style="vertical-align: middle;" width="24px" height="24px" version="1.1" id="Capa_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 515.458 515.458" style="enable-background:new 0 0 515.458 515.458;"
                            xml:space="preserve">
                              <g>
                                <path d="M298.794,386.711c27.805,9.522,52.357,15.587,87.633,26.427C372.875,584.374,210.952,516.371,298.794,386.711z
                                    M443.366,229.409c-1.826-51.415-10.882-118.86-83.017-108.292c-33.815,8.825-58.8,45.962-70.551,110.035
                                    c-6.454,35.229-2.701,84.678,4.912,114.32c6.951,20.889,4.587,19.605,12.058,23.572c28.916,6.514,57.542,13.725,86.693,21.078
                                    C423.075,369.209,447.397,258.182,443.366,229.409z M220.752,225.463c7.607-29.646,11.36-79.095,4.909-114.32
                                    C213.919,47.067,188.931,9.924,155.11,1.105C82.975-9.463,73.919,57.981,72.093,109.399
                                    c-4.031,28.768,20.294,139.802,49.911,160.711c29.149-7.353,57.771-14.558,86.696-21.078
                                    C216.162,245.069,213.798,246.352,220.752,225.463z M129.029,293.132c13.547,171.234,175.47,103.231,87.63-26.427
                                    C188.854,276.228,164.304,282.292,129.029,293.132z"/>
                              </g>
                          </svg>
                           My Tracker
                        </button>

                        <button class="tablinks" onClick="openDiv(event, 'tab_3')" type="button">
                          <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" stroke="#000" stroke-width="2" d="M9,18 L9,12 M12,18 L12,13 M15,18 L15,10 M17,3 L21,3 L21,23 L3,23 L3,3 L7,3 M7,1 L17,1 L17,5 L7,5 L7,1 Z"/>
                          </svg>
                          
                           My Performance Scorecard
                        </button>
                        <?php if(is_attribute_available('performance_scorecard_approval')){ ?>
                        <button class="tablinks" onClick="openDiv(event, 'tab_4')" type="button">
                          <svg width="24px" height="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M336 64h-53.88C268.9 26.8 233.7 0 192 0S115.1 26.8 101.9 64H48C21.5 64 0 85.48 0 112v352C0 490.5 21.5 512 48 512h288c26.5 0 48-21.48 48-48v-352C384 85.48 362.5 64 336 64zM192 64c17.67 0 32 14.33 32 32s-14.33 32-32 32S160 113.7 160 96S174.3 64 192 64zM282.9 262.8l-88 112c-4.047 5.156-10.02 8.438-16.53 9.062C177.6 383.1 176.8 384 176 384c-5.703 0-11.25-2.031-15.62-5.781l-56-48c-10.06-8.625-11.22-23.78-2.594-33.84c8.609-10.06 23.77-11.22 33.84-2.594l36.98 31.69l72.52-92.28c8.188-10.44 23.3-12.22 33.7-4.062C289.3 237.3 291.1 252.4 282.9 262.8z"/></svg>
                           Performance Scorecard Approval
                        </button>
                        <?php } ?>
                      </div>

                      <div class="tab-section">

                           <!-- TAB 1 - START -->
                           <div id="tab_1" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info">
                                          <svg style="vertical-align: middle;" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                              <!-- Uploaded to SVGRepo https://www.svgrepo.com -->
                                              <g id="Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                  <g id="ic_fluent_activity_24_filled" fill="#212121" fill-rule="nonzero">
                                                      <path d="M8.47096081,7.23691331 L11.5265159,20.2289405 C11.7600677,21.221988 13.1560977,21.268924 13.4558214,20.2938058 L16.4005221,10.7135452 L16.7855356,12.2410553 C16.897511,12.6853085 17.2970602,12.9966465 17.7552079,12.9966465 L21,12.9966465 C21.5522847,12.9966465 22,12.5489312 22,11.9966465 C22,11.4443617 21.5522847,10.9966465 21,10.9966465 L18.5344311,10.9966465 L17.4654602,6.75559114 C17.2181786,5.77452142 15.837182,5.73909386 15.5399228,6.70619415 L12.6284436,16.1783724 L9.4752188,2.77105953 C9.23522942,1.75063984 7.78657863,1.74010295 7.53177121,2.75692369 L5.46696027,10.9966465 L3,10.9966465 C2.44771525,10.9966465 2,11.4443617 2,11.9966465 C2,12.5489312 2.44771525,12.9966465 3,12.9966465 L6.2472882,12.9966465 C6.70594806,12.9966465 7.10580602,12.6846261 7.21729537,12.2397228 L8.47096081,7.23691331 Z" id="Color"></path>
                                                  </g>
                                              </g>
                                          </svg> 
                                          Activity Log
                                       </h3> 
                                    </div>
                                 </div>
                              
                                 <div class="card">
                                    <div class="card-header">
                                      <div class="row align-items-center">
                                        <div class="col-lg-12">
                                          <div class="filter_left_flex">
                                             <ul>
                                                <li>
                                                   <div class="d-inline-flex mr-15">
                                                      <label>Sort By User:</label>
                                                      <div class="label-input">
                                                         <select class="default-select" name="al_user" id="al_user">
                                                            <option value="">Select User</option>
                                                            <?php if(count($user_list)){?>
                                                            <?php foreach($user_list as $user){ ?>
                                                              <option value="<?php echo $user->id; ?>"><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
                                                            <?php } ?>
                                                            <?php } ?>
                                                         </select>
                                                      </div>
                                                   </div>

                                                   <div class="d-inline-flex mr-15">
                                                      <label>Sort By Date:</label>
                                                      <div class="label-input">
                                                         <input type="text" class="default-input al_filter_display_date" name="al_date_from" id="al_date_from" placeholder="Start Date">
                                                      </div>
                                                      <label class="ml-10">To</label>
                                                      <div class="label-input">
                                                         <input type="text" class="default-input al_filter_display_date" name="al_date_to" id="al_date_to" placeholder="End Date">
                                                      </div>
                                                   </div>
                                                </li>
                                                <li>
                                                   <a href="JavaScript:void(0)" id="al_filter_btn" class="btn btn-primary" >Filter</a>
                                                </li>
                                             </ul>
                                          </div>
                                        </div>
                                        
                                        <!-- <div class="col-lg-1">
                                          <div class="dropdown text-right mt-5">
                                            <a href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
                                            </a>
                                            <div class="dropdown-menu download-report" aria-labelledby="dropdownMenuButton">
                                                <ul>                                                        <li><a href="#"><i class="fa fa-download" aria-hidden="true"></i> Download Daily Report</a></li>
                                                   <li><a href="#"><i class="fa fa-download" aria-hidden="true"></i> Download Weekly Report</a></li>
                                                   <li><a href="#"><i class="fa fa-download" aria-hidden="true"></i> Download Monthly Report</a></li>
                                                </ul>
                                            
                                            </div>
                                          </div>                                          
                                        </div> -->
                                      </div>
                                    </div>
                                    <div class="card-body bg-white px-0 pb-2">
                                       <div class="table-responsive">
                                          <table class="table align-items-center mb-0 sp-table-new">
                                             <thead>
                                               <tr>
                                                 <th class="text-uppercase text-secondary text-xxs font-weight-bolder" width="50">User ID</th>
                                                 <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Users Name</th>
                                                 <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Date</th>
                                                 <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Day</th>
                                                 <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Total Active Time</th>
                                                 <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" width="100">Action</th>
                                               </tr>
                                             </thead>
                                             <tbody id="activity_log_tcontent"></tbody>
                                             <input type="hidden" id="filter_al_user" />
                                             <input type="hidden" id="filter_al_date_from" />
                                             <input type="hidden" id="filter_al_date_to" />
                                           </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div>&nbsp;</div>
                           </div>
                           <!-- TAB 1 - END -->

                          <!-- TAB 2 - START -->
                          <div id="tab_2" class="tabcontent">
                              <div class="col-md-12">
                                  <div>&nbsp;</div>
                                  <div class="form-group row">
                                      <div class="col-sm-12">
                                          <h3 class="text-info">
                                            <svg style="vertical-align: middle;" width="24px" height="24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 515.458 515.458" style="enable-background:new 0 0 515.458 515.458;" xml:space="preserve">
                                              <g>
                                                <path d="M298.794,386.711c27.805,9.522,52.357,15.587,87.633,26.427C372.875,584.374,210.952,516.371,298.794,386.711z
                                                    M443.366,229.409c-1.826-51.415-10.882-118.86-83.017-108.292c-33.815,8.825-58.8,45.962-70.551,110.035
                                                    c-6.454,35.229-2.701,84.678,4.912,114.32c6.951,20.889,4.587,19.605,12.058,23.572c28.916,6.514,57.542,13.725,86.693,21.078
                                                    C423.075,369.209,447.397,258.182,443.366,229.409z M220.752,225.463c7.607-29.646,11.36-79.095,4.909-114.32
                                                    C213.919,47.067,188.931,9.924,155.11,1.105C82.975-9.463,73.919,57.981,72.093,109.399
                                                    c-4.031,28.768,20.294,139.802,49.911,160.711c29.149-7.353,57.771-14.558,86.696-21.078
                                                    C216.162,245.069,213.798,246.352,220.752,225.463z M129.029,293.132c13.547,171.234,175.47,103.231,87.63-26.427
                                                    C188.854,276.228,164.304,282.292,129.029,293.132z"/>
                                              </g>
                                            </svg>
                                            My Tracker
                                          </h3> 
                                      </div>
                                  </div>
                                  <div class="card">
                                      <div class="card-header">
                                        <div class="row align-items-center">
                                          <div class="col-lg-12">
                                            <div class="filter_left_flex">
                                              <ul>
                                                  <li>
                                                    <div class="d-inline-flex mr-15">
                                                        <label>Sort By User:</label>
                                                        <div class="label-input">
                                                          <select class="default-select" name="lt_user" id="lt_user">
                                                              <option value="">Select User</option>
                                                              <?php if(count($user_list)){?>
                                                              <?php foreach($user_list as $user){ ?>
                                                                <option value="<?php echo $user->id; ?>"><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
                                                              <?php } ?>
                                                              <?php } ?>
                                                          </select>
                                                        </div>
                                                    </div>

                                                    <div class="d-inline-flex mr-15">
                                                        <label>Sort By Date:</label>
                                                        <div class="label-input">
                                                          <input type="text" class="default-input al_filter_display_date" name="lt_date_from" id="lt_date_from" placeholder="Start Date">
                                                        </div>
                                                        <label class="ml-10">To</label>
                                                        <div class="label-input">
                                                          <input type="text" class="default-input al_filter_display_date" name="lt_date_to" id="lt_date_to" placeholder="End Date">
                                                        </div>
                                                    </div>
                                                  </li>
                                                  <li>
                                                    <a href="JavaScript:void(0)" id="lt_filter_btn" class="btn btn-primary" >Filter</a>
                                                  </li>
                                              </ul>
                                            </div>
                                          </div>                                              
                                        </div>
                                      </div>
                                      <div class="card-body bg-white px-0 pb-2" id="location_tracking_div"></div>
                                      
                                      <input type="hidden" id="filter_lt_user" />
                                      <input type="hidden" id="filter_lt_date_from" />
                                      <input type="hidden" id="filter_lt_date_to" />
                                </div>
                              </div>
                              <div>&nbsp;</div>
                          </div>
                          <!-- TAB 2 - END -->     
                          
                          <!-- TAB 3 - START -->
                          <div id="tab_3" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info">
                                          <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="none" stroke="#000" stroke-width="2" d="M9,18 L9,12 M12,18 L12,13 M15,18 L15,10 M17,3 L21,3 L21,23 L3,23 L3,3 L7,3 M7,1 L17,1 L17,5 L7,5 L7,1 Z"></path>
                                          </svg>
                                          My Performance Scorecard
                                       </h3> 
                                    </div>
                                 </div>
                              
                                 <div class="w-100">
                                    <div class="card-header" style="display:none;">
                                      <div class="row align-items-center">
                                        <div class="col-lg-12">
                                          <div class="filter_left_flex">
                                             <ul>
                                                <li>
                                                   <div class="d-inline-flex mr-15">
                                                      <label>Sort By User:</label>
                                                      <div class="label-input">
                                                         <select class="default-select" name="mps_user" id="mps_user">
                                                            <option value="">Select User</option>
                                                            <?php if(count($mps_kpi_user_list)){?>
                                                            <?php foreach($mps_kpi_user_list as $user){ ?>
                                                              <option value="<?php echo $user['id']; ?>" data-kpi_setting_id="<?php echo $user['kpi_setting_id']; ?>" <?php if($user_id==$user['id']){echo'SELECTED';} ?>><?php echo $user['name'] .'( Emp ID: '.$user['id'].')'; ?></option>
                                                            <?php } ?>
                                                            <?php } ?>
                                                         </select>
                                                      </div>
                                                   </div>
                                                </li>
                                             </ul>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div id="my_performance_scorecard_html" style="text-align:center"></div>                                    
                                 </div>
                              </div>
                              <div>&nbsp;</div>
                           </div>
                           <!-- TAB 3 - END -->

                           <!-- TAB 4 - START -->
                          <div id="tab_4" class="tabcontent">
                              <div class="col-md-12">
                                 <div>&nbsp;</div>
                                 <div class="form-group row">
                                    <div class="col-sm-12">
                                       <h3 class="text-info line-height-40">
                                          <svg width="24px" height="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M336 64h-53.88C268.9 26.8 233.7 0 192 0S115.1 26.8 101.9 64H48C21.5 64 0 85.48 0 112v352C0 490.5 21.5 512 48 512h288c26.5 0 48-21.48 48-48v-352C384 85.48 362.5 64 336 64zM192 64c17.67 0 32 14.33 32 32s-14.33 32-32 32S160 113.7 160 96S174.3 64 192 64zM282.9 262.8l-88 112c-4.047 5.156-10.02 8.438-16.53 9.062C177.6 383.1 176.8 384 176 384c-5.703 0-11.25-2.031-15.62-5.781l-56-48c-10.06-8.625-11.22-23.78-2.594-33.84c8.609-10.06 23.77-11.22 33.84-2.594l36.98 31.69l72.52-92.28c8.188-10.44 23.3-12.22 33.7-4.062C289.3 237.3 291.1 252.4 282.9 262.8z"></path></svg>
                                          Performance Scorecard Approval
                                          <div class="float-right">
                                            <div class="filter_left_flex">
                                               <ul>
                                                  <li>  
                                                    <?php
                                                    $last_month=date('Y-m', strtotime('first day of last month'));
                                                    ?>                                                
                                                      <div class="d-inline-flex ">
                                                        <label>By Month:</label>
                                                        <div class="label-input">
                                                           <select class="default-select" name="ps_month" id="ps_month">
                                                              <option value="">Select Month</option>
                                                              <?php if(count($months)){?>
                                                              <?php foreach($months as $k=>$v){ ?>
                                                                <option value="<?php echo $k; ?>" data-kpi_setting_id="" <?php if($last_month==$k){echo'SELECTED';} ?>><?php echo $v; ?></option>
                                                              <?php } ?>
                                                              <?php } ?>
                                                           </select>
                                                        </div>
                                                     </div>
                                                     <div class="d-inline-flex ml-10">
                                                        <label>Sort By User:</label>
                                                        <div class="label-input">
                                                           <select class="default-select" name="ps_user" id="ps_user">
                                                              <option value="">Select User</option>
                                                              <?php if(count($ps_kpi_user_list)){ $i=0;?>
                                                              <?php foreach($ps_kpi_user_list as $user){ ?>
                                                                <option value="<?php echo $user['id']; ?>" data-kpi_setting_id="<?php echo $user['kpi_setting_id']; ?>"><?php echo $user['name'] .'( Emp ID: '.$user['id'].')'; ?></option>
                                                              <?php $i++;} ?>
                                                              <?php } ?>
                                                           </select>
                                                        </div>
                                                     </div>
                                                  </li>
                                               </ul>
                                            </div>
                                          </div>
                                       </h3> 
                                    </div>
                                 </div>
                              
                                 <div class="card">
                                    
                                    <div id="performance_scorecard_html" style="text-align:center"></div>                                    
                                 </div>
                              </div>
                              <div>&nbsp;</div>
                           </div>
                           <!-- TAB 4 - END -->
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
<script src="<?php echo assets_url();?>js/custom/my_performance/main.js?v=<?php echo rand(0,1000); ?>"></script>
<script type="text/javascript">
  //  $(document).ready(function () {
  //     $('.avatar[data-toggle="tooltip"]').tooltipster({
  //         contentAsHTML: true
  //     });
  //  });   
</script>


