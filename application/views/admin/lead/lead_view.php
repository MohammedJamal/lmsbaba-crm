<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">
   <head>
      <?php $this->load->view('admin/includes/head'); ?>  
      <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/jquery_modalLink_1_0_0.css" />
      <script type="text/javascript" src="<?php echo assets_url(); ?>js/jquery.modalLink-1.0.0.js"></script>
      <style type="text/css">
         .sweet-alert.sweetalert-lg { width: 600px;
         font-size: 9px; }
      </style>
      <link rel="stylesheet" href="<?php echo assets_url(); ?>css/custom_table.css"/>
      <style type="text/css">
         .copy {cursor: copy;}

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
            <div class="main-content lead_manage_page">
               <div class="content-view">
                  <div class="row m-b-1 align-items-center">
                     <div class="col-sm-4 pr-0">
                        <div class="bg_white back_line">
                           <h4>Manage <?php echo $menu_label_alias['menu']['lead']; ?></h4>
                        </div>
                     </div>
                     <div class="col-sm-8 pleft_0">
                        <div class="bg_white_filt">
                           <ul class="filter_ul">
                              <li>
                                 <a href="JavaScript:void(0);" class="new_filter_btn" id="filter_btn">
                                 <span class="bg_span lg-w">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M3.853 54.87C10.47 40.9 24.54 32 40 32H472C487.5 32 501.5 40.9 508.1 54.87C514.8 68.84 512.7 85.37 502.1 97.33L320 320.9V448C320 460.1 313.2 471.2 302.3 476.6C291.5 482 278.5 480.9 268.8 473.6L204.8 425.6C196.7 419.6 192 410.1 192 400V320.9L9.042 97.33C-.745 85.37-2.765 68.84 3.854 54.87L3.853 54.87z"/></svg>
                                 </span>
                                 Filters
                                 </a>
                              </li>
                              <li>                          
                                 <a class="new_filter_btn" href="JavaScript:void(0);" id="rander_add_new_lead_view">
                                 <span class="bg_span lg-w">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M512 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h448c35.35 0 64-28.65 64-64V96C576 60.65 547.3 32 512 32zM176 128c35.35 0 64 28.65 64 64s-28.65 64-64 64s-64-28.65-64-64S140.7 128 176 128zM272 384h-192C71.16 384 64 376.8 64 368C64 323.8 99.82 288 144 288h64c44.18 0 80 35.82 80 80C288 376.8 280.8 384 272 384zM496 320h-128C359.2 320 352 312.8 352 304S359.2 288 368 288h128C504.8 288 512 295.2 512 304S504.8 320 496 320zM496 256h-128C359.2 256 352 248.8 352 240S359.2 224 368 224h128C504.8 224 512 231.2 512 240S504.8 256 496 256zM496 192h-128C359.2 192 352 184.8 352 176S359.2 160 368 160h128C504.8 160 512 167.2 512 176S504.8 192 496 192z"/></svg>
                                 </span> 
                                    Add  <?php echo $menu_label_alias['menu']['lead']; ?>
                                 </a> 
                              </li>
                              <?php  ?>
                              <li>
                                 <?php                            
                                    if(is_permission_available('bulk_lead_upload_non_menu'))
                                    {
                                    ?>                    
                                    <a href="JavaScript:void(0);" class="upload_excel upload_csv new_filter_btn">
                                    <span class="bg_span lg-w">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M144 480C64.47 480 0 415.5 0 336C0 273.2 40.17 219.8 96.2 200.1C96.07 197.4 96 194.7 96 192C96 103.6 167.6 32 256 32C315.3 32 367 64.25 394.7 112.2C409.9 101.1 428.3 96 448 96C501 96 544 138.1 544 192C544 204.2 541.7 215.8 537.6 226.6C596 238.4 640 290.1 640 352C640 422.7 582.7 480 512 480H144zM223 263C213.7 272.4 213.7 287.6 223 296.1C232.4 306.3 247.6 306.3 256.1 296.1L296 257.9V392C296 405.3 306.7 416 320 416C333.3 416 344 405.3 344 392V257.9L383 296.1C392.4 306.3 407.6 306.3 416.1 296.1C426.3 287.6 426.3 272.4 416.1 263L336.1 183C327.6 173.7 312.4 173.7 303 183L223 263z"/></svg>
                                    </span> 
                                    Bulk Uploads 
                                    </a>
                                 <?php
                                    }                  
                                    ?>
                              </li>
                              <li>
                                 <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage_meeting" class="new_filter_btn">
                                    <span class="bg_span">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M160 32V64H288V32C288 14.33 302.3 0 320 0C337.7 0 352 14.33 352 32V64H400C426.5 64 448 85.49 448 112V160H0V112C0 85.49 21.49 64 48 64H96V32C96 14.33 110.3 0 128 0C145.7 0 160 14.33 160 32zM0 192H448V464C448 490.5 426.5 512 400 512H48C21.49 512 0 490.5 0 464V192zM64 304C64 312.8 71.16 320 80 320H112C120.8 320 128 312.8 128 304V272C128 263.2 120.8 256 112 256H80C71.16 256 64 263.2 64 272V304zM192 304C192 312.8 199.2 320 208 320H240C248.8 320 256 312.8 256 304V272C256 263.2 248.8 256 240 256H208C199.2 256 192 263.2 192 272V304zM336 256C327.2 256 320 263.2 320 272V304C320 312.8 327.2 320 336 320H368C376.8 320 384 312.8 384 304V272C384 263.2 376.8 256 368 256H336zM64 432C64 440.8 71.16 448 80 448H112C120.8 448 128 440.8 128 432V400C128 391.2 120.8 384 112 384H80C71.16 384 64 391.2 64 400V432zM208 384C199.2 384 192 391.2 192 400V432C192 440.8 199.2 448 208 448H240C248.8 448 256 440.8 256 432V400C256 391.2 248.8 384 240 384H208zM320 432C320 440.8 327.2 448 336 448H368C376.8 448 384 440.8 384 432V400C384 391.2 376.8 384 368 384H336C327.2 384 320 391.2 320 400V432z"/></svg>
                                    </span> 
                                    Events
                                 </a>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="card process-sec">
                     <div class="filter_holder new">
                        <div class="pull-left">
                           <h5 class="lead_board"><?php echo $menu_label_alias['menu']['lead']; ?> Board  
                           <a href="JavaScript:void(0)" title="Download lead from Indiamart" id="download_from_indiamart"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                           <?php //if($this->session->userdata('admin_session_data')['client_id']=='1' || $this->session->userdata('admin_session_data')['client_id']=='17' || $this->session->userdata('admin_session_data')['client_id']=='13'){ 
                           if(count($fb_form_count)){    
                           ?>
                           &nbsp;

                           <div class="pull-right" id="fb_outer">
                              <div class="lead-dropdown new-style">
                                 <div class="dropdown">
                                 <button class="btn-dropdown note-icon fb_sync_btn" type="button" data-toggle="tooltip" data-placement="top" title="Download lead from FaceBook">
                                 <?php /* ?> <span id="fb_count" class="" style="background-color:green"><?php //echo count($fb_form_count); ?></span> <?php */ ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                                 </button>
                                 <div class="dropdown-menu left" id="fb_inner_div"></div>
                              </div>
                           </div>
                           <?php /* ?> <a href="JavaScript:void(0)" title="Download lead from FaceBook" id="download_from_fb"></a> <?php */ ?>
                           <?php 
                           }
                           //} 
                           ?>
                        </h5>
                           <span id="selected_filter_div" class="lead_filter_div"></span>
                        </div>
                        <div class="filter_right filter_sort">
                           <div class="filter_block">
                              <div class="filter_item"><strong>Sort by</strong></div>
                              <div class="filter_item">
                                 <select class="sort_dd" id="sort_by">
                                    <option value="">--Select One--</option>
                                    <optgroup label="ID">
                                       <option value="lead.id-asc">ASC</option>
                                       <option value="lead.id-desc">DESC</option>
                                    </optgroup>
                                    <optgroup label="Enquiry Date">
                                       <option value="lead.enquiry_date-asc">ASC</option>
                                       <option value="lead.enquiry_date-desc">DESC</option>
                                    </optgroup>
                                    <optgroup label="Title">
                                       <option value="lead.title-asc">A to Z</option>
                                       <option value="lead.title-desc">Z to A</option>
                                    </optgroup>
                                    <optgroup label="Last Updated">
                                       <option value="lead.modify_date-asc">ASC</option>
                                       <option value="lead.modify_date-desc">DESC</option>
                                    </optgroup>
                                    <optgroup label="Next Follow-up Date">
                                       <option value="lead.followup_date-asc">ASC</option>
                                       <option value="lead.followup_date-desc">DESC</option>
                                    </optgroup>
                                 </select>
                              </div>
                           </div>
                           <div id="btnContainer">
                              <button class="btn active get_view" data-target="grid"><i class="fa fa-th-large"></i></button> 
                              <button class="btn get_view" data-target="list"><i class="fa fa-bars"></i></button>
                           </div>
                           <div class="float-right ml-10 tble-sc-bt">
                              <a href="JavaScript:void(0)" class="ext-table">
                                 <svg aria-hidden="true" role="img" class="octicon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom;">
                                    <path fill-rule="evenodd" d="M8.177 14.323l2.896-2.896a.25.25 0 00-.177-.427H8.75V7.764a.75.75 0 10-1.5 0V11H5.104a.25.25 0 00-.177.427l2.896 2.896a.25.25 0 00.354 0zM2.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM6 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM12 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zm2.25.75a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5z"></path>
                                 </svg>
                              </a>
                           </div>
                        </div>
                        <div class="bulk_bt_holder">
                           <?php if(is_permission_available('bulk_assignee_change_non_menu')){ ?>
                           <button class="new_filter_btn pull-right ml-10" type="button" id="company_assigne_change_multiple">
                           <span class="bg_span"><img src="<?php echo assets_url(); ?>images/bulk_update.png" ></span>
                           Bulk Assignee Change
                           </button>
                           <?php } ?>
                           <?php if(is_permission_available('bulk_quotation_send_non_menu')){ ?>
                           <button class="new_filter_btn pull-right" type="button" id="create_quotation_bulk">
                           <span class="bg_span"><img src="<?php echo assets_url(); ?>images/bulk_update.png" ></span>
                           Bulk Quotation Send
                           </button>
                           <?php } ?>
                           <input type="hidden" name="lid_bulk" id="lid_bulk">
                        </div>
                     </div>
                     <div class="grey-card-block">
                        <div class="top-new-filter-highlight">
                           <div class="filter_left filter_sort">
                              <div class="filter_block" >
                                 <div class="filter_item">
                                    <label class="check-box-sec pl-25 radio">
                                    <input type="radio" value="AL" class="" name="filter_followup" data-text="New <?php echo $menu_label_alias['menu']['lead']; ?>" id="filter_followup_all" checked="checked">
                                    <span class="checkmark"></span>
                                    All Active <?php echo $menu_label_alias['menu']['lead']; ?>
                                    </label>
                                    &nbsp;
                                    <label class="check-box-sec pl-25 radio">
                                    <input type="radio" value="NL" class="" name="filter_followup" data-text="New <?php echo $menu_label_alias['menu']['lead']; ?>" id="filter_followup_new" >
                                    <span class="checkmark"></span>
                                    New <?php echo $menu_label_alias['menu']['lead']; ?>
                                    </label>
                                    &nbsp;
                                    <label class="check-box-sec pl-25 radio">
                                    <input type="radio" value="TL" class="" name="filter_followup" data-text="Today’s follow-up" id="filter_followup_today">
                                    <span class="checkmark"></span>
                                    Today’s Follow-up
                                    </label>
                                    &nbsp;
                                    <label class="check-box-sec pl-25 radio">
                                    <input type="radio" value="PL" class="" name="filter_followup" data-text="Pending follow-up" id="filter_followup_pending">
                                    <span class="checkmark"></span>
                                    Pending Follow-up
                                    </label>
                                    &nbsp;
                                    <label class="check-box-sec pl-25 radio" style="display:none;">
                                    <input type="radio" value="UL" class="" name="filter_followup" data-text="Upcoming follow-up" id="filter_followup_upcoming">
                                    <span class="checkmark"></span>
                                    Upcoming Follow-up
                                    </label>
                                 </div>
                              </div>
                           </div>
                           <div class="filter_right filter_sort">
                              <div class="filter_block text-right" style="<?php echo ($is_observer_available=='Y')?'display:block;':'display:none;'; ?>" >
                                 <div class="filter_item">   
                                    <label class="check-box-sec pl-25">
                                    <input type="checkbox" value="Y" class="" name="lead_observer" data-text="Lead Observer" id="lead_observer">
                                    <span class="checkmark"></span>
                                    Lead Observer
                                    </label>              
                                 </div>
                              </div>
                              <div class="filter_block text-right" style="<?php echo ($is_common_lead_pool_available=='Y')?'display:block;':'display:none;'; ?>" >
                                 <div class="filter_item">
                                    <label class="check-box-sec pl-25">
                                    <input type="checkbox" value="Y" class="" name="common_lead_pool" data-text="Common lead pool" id="common_lead_pool">
                                    <span class="checkmark"></span>
                                    Common Lead Pool
                                    </label>              
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="full-width">
                           <!-- <div class="wrapper1">
                              <div class="div1"></div>
                           </div> -->
                           <div class="custom-table-responsive table-toggle-holder--">
                              <div class="table-full-holder--" id="table_content">
                                 <div class="table-one-holder--" >
                                    <div id="tcontent" class="grid-content-block" style="min-height: 300px;">
                                      
                                    </div>
                                    <?php
                                       $selected_user_ids='';
                                        $filter_like_dsc_tmp=isset($_GET['filter_like_dsc'])?$_GET['filter_like_dsc']:'';
                                        if($filter_like_dsc_tmp)
                                        {
                                         $filter_like_dsc_str=base64_decode($filter_like_dsc_tmp);
                                         $filter_like_dsc_arr=explode("#",$filter_like_dsc_str);
                                         
                                         $selected_user_ids=$filter_like_dsc_arr[0];
                                        }                           
                                       ?>
                                    <input type="hidden" id="view_type" value="grid">
                                    <input type="hidden" id="filter_search_str" value="<?php echo isset($_REQUEST['search_keyword'])?$_REQUEST['search_keyword']:''; ?>">   
                                    <input type="hidden" id="filter_lead_from_date" value="">
                                    <input type="hidden" id="filter_lead_to_date" value="">
                                    <input type="hidden" id="filter_date_filter_by" value="">   
                                    <input type="hidden" id="filter_assigned_user" value="">  
                                    <input type="hidden" id="filter_lead_applicable_for" value="">
                                    <input type="hidden" id="filter_lead_type" value=""> 
                                    
                                    <input type="hidden" id="filter_opportunity_stage_filter_type" value="">

                                    <input type="hidden" id="filter_opportunity_stage" value="">
                                    <input type="hidden" id="filter_opportunity_status" value="">
                                    <input type="hidden" id="filter_by_source" value="">
                                    <input type="hidden" id="filter_is_hotstar" value="">
                                    <input type="hidden" id="filter_pending_followup" value="">
                                    <input type="hidden" id="filter_pending_followup_for" value="">
                                    <input type="hidden" id="filter_sort_by" value="">
                                    <input type="hidden" id="page_number" value="1">
                                    <input type="hidden" id="is_scroll_to_top" value="N">
                                    <input type="hidden" id="search_by_id" value="<?php echo isset($_GET['search_by_id'])?$_GET['search_by_id']:''; ?>">
                                    <input type="hidden" id="filter_like_dsc" value="<?php echo isset($_GET['filter_like_dsc'])?$_GET['filter_like_dsc']:''; ?>">
                                    <input type="hidden" id="filter_like_dsc_selected_user" value="<?php echo $selected_user_ids; ?>">
                                    <input type="hidden" id="filter_common_lead_pool" value="N">
                                    <input type="hidden" id="filter_followup" value="">
                                    <input type="hidden" id="filter_lead_observer" value="">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card-block">
                        
                        <div class="row">
                           <div id="page_record_count_info" class="col-md-6 text-left ffff"></div>
                           <div id="page" style="" class="col-md-6 text-right custom-pagination"></div>
                        </div>
                        <?php                            
                           if(is_permission_available('lead_download_report_non_menu'))
                           {
                           ?>   
                        <div class="row">
                           <div class="col-md-12">
                              <a class="new_filter_btn" href="JavaScript:void(0);" id="download_leads_csv">
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
      <?php $this->load->view('admin/includes/app'); ?> 

      <!--  ============ CHANGE CLOSER DATE AND DEAL VALUE:START ================= -->
      <div id="lead_closer_date_deal_value_modal" class="modal fade" role="dialog">
         <div class="modal-dialog modal-md modal_margin_top">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Expected Closure Date / Deal Value</h4>
               </div>
               <div class="modal-body" id="">
                  <form name="changeCloserDateDealValueForm" id="changeCloserDateDealValueForm" method="post">
                     <div class="add_form_new">
                        <div class="form-group row">
                           <label for="" class="col-sm-4 col-form-label">Closure Date:</label>
                           <div class="col-sm-8">
                              <input type="text" class="form-control display_date" readonly="true" name="lead_closer_date" id="lead_closer_date" placeholder="Enter Closure Date" />
                           </div>
                        </div>
                        <div class="form-group row" id="lead_deal_value_html">
                           <label for="" class="col-sm-4 col-form-label">Deal Value</label>
                           <div class="col-sm-8">
                              <div class="row no-gutters">
                                 <div class="col-sm-8 pr-0">
                                    <input type="text" class="form-control border-r-0" name="lead_deal_value" id="lead_deal_value" placeholder="Enter Deal Value" value="" />
                                 </div>
                                 <div class="col-sm-4 pl-0">
                                    <select class="border-l-0" id="lead_currency_code" name="lead_currency_code">
                                       <?php foreach($currency_list as $currency){ ?>
                                       <option value="<?php echo $currency->code; ?>"><?php echo $currency->code; ?></option>
                                       <?php } ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form-group row">
                           <div class="col-sm-4">
                           </div>
                           <div class="col-sm-8">
                              <input type="button" value="Save" class="btn btn-primary" id="lead_closer_date_deal_value_submit_confirm" style="width:100px">
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
      <!--  ================ CHANGE CLOSER DATE AND DEAL VALUE:END ================= -->
      <div id="NextFollowupDateUpdateForm" class="hide">
         <form id="nfdUpdateFrm">
            <div id="popover-content">
               <div class="form-group">
                  <div class="input-group date">
                     <input type="text" class="form-control datetimepicker_nfd" placeholder="Select Next Followup Date" name="" value=""  /> 
                  </div>
               </div>
               <div class="form-group">
                  <button class="btn btn-primary btn-block" id="next_followup_update_confirm">Save</button>
               </div>
            </div>
            <input type="hidden" id="nfd_lead_id" name="nfd_lead_id" value="">
            <input type="hidden" id="nfd_date" name="nfd_date" value="">
         </form>
      </div>
      <!-- LEAD FILTER Modal: START -->
      <div class="modal fade" id="leadFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h2>Filter <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
               </div>
               <div class="modal-body">
                  <div class="f_holder">
                     <div class="form-group">
                        <ul>
                           <li>
                              <div class="title_f">By Keyword</div>
                           </li>
                           <li class="full-without-title">
                              <div class="">
                                 <input type="text" class="form-control" name="" id="filter_by_keyword" placeholder="Search by keyword...." value="" data-text="By Keyword"/>
                              </div>
                           </li>
                        </ul>
                     </div>
                     <div class="form-group">
                        <ul>
                           <li>
                              <div class="title_f">By Date</div>
                           </li>
                           <li>
                              <div class="input-prepend input-group">
                                 <span class="add-on input-group-addon">
                                 <img src="<?php echo assets_url(); ?>images/calendar.png"/>
                                 </span>
                                 <input type="text" class="form-control drp search_inp display_date ssss" name="lead_from_date" id="datepicker3" placeholder="Enquiry Date" value="" />
                              </div>
                           </li>
                           <li>
                              <div class="title_f">To</div>
                           </li>
                           <li>
                              <div class="input-prepend input-group">
                                 <span class="add-on input-group-addon">
                                 <img src="<?php echo assets_url(); ?>images/calendar.png" />
                                 </span>
                                 <input type="text" class="form-control drp search_inp display_date" name="lead_to_date" id="datepicker4" placeholder="Enquiry Date" value="" />
                              </div>
                           </li>
                           <li>
                              <select id="date_filter_by" name="date_filter_by" class="demo-default form-control select_user search_inp"  >
                                 <option value="" data-text="">-- Select --</option>
                                 <option value="added_on" data-text="Added On">Added On</option>
                                 <option value="updated_on" data-text="Last Updated">Last Updated</option>
                                 <option value="follow_up_date" data-text="Follow-Up">Follow-Up</option>
                                 
                              </select>
                           </li>
                        </ul>
                     </div>
                     <div class="form-group">
                        <ul>
                           <li>
                              <div class="title_f">By User</div>
                           </li>
                           <li style="width: 160px;">
                              <select id="assigned_user" name="assigned_user" class="demo-default search_inp" placeholder="Select a User..." multiple>
                                 <?php
                                    foreach($user_list as $user_data)
                                    {
                                      ?>
                                 <option value="<?php echo $user_data['id'];?>"<?php /*if($assigned_user==$user_data['id']){?> selected="selected" <?php }*/ ?> data-text="<?php echo $user_data['name']?>"><?php echo $user_data['name']?></option>
                                 <?php
                                    }
                                    ?>
                              </select>
                           </li>
                           <li>
                              <label class="check-box-sec">
                              <input type="checkbox" value="E" class="" name="lead_applicable_for" data-text="Global " id="lead_applicable_for_e" >
                              <span class="checkmark"></span>
                              </label>
                              Global<!-- Export  -->
                           </li>
                           <li>
                              <label class="check-box-sec">
                              <input type="checkbox" value="D" class="" name="lead_applicable_for" data-text="India " id="lead_applicable_for_d">
                              <span class="checkmark"></span>
                              </label>
                              India<!-- Domestic  -->
                           </li>
                        </ul>
                     </div>
                     <div class="form-group">
                        <ul>
                           <li>
                              <div class="title_f">By Type</div>
                           </li>
                           <li>
                              <label class="check-box-sec radio">
                              <input type="radio" value="AL" class="" name="lead_type" data-text="Active <?php echo $menu_label_alias['menu']['lead']; ?>" id="lead_type_al">
                              <span class="checkmark"></span>
                              </label>
                              Active <?php echo $menu_label_alias['menu']['lead']; ?>
                           </li>
                           <!-- <li>
                              <label class="check-box-sec radio">
                                <input type="radio" value="LL" class="" name="lead_type" data-text="Lost Leads" id="lead_type_ll">
                                <span class="checkmark"></span>
                              </label>
                              Lost Leads
                              </li> -->
                           <!-- <li>
                              <label class="check-box-sec radio">
                                <input type="radio" value="WL" class="" name="lead_type" data-text="Won Leads" id="lead_type_wl">
                                <span class="checkmark"></span>
                              </label>
                              Won Leads
                              </li> -->
                           <li>
                              <label class="check-box-sec radio">
                              <input type="radio" value="ALL" class="" name="lead_type" data-text="All <?php echo $menu_label_alias['menu']['lead']; ?>" id="lead_type_all">
                              <span class="checkmark"></span>
                              </label>
                              All <?php echo $menu_label_alias['menu']['lead']; ?>
                           </li>
                        </ul>
                     </div>
                     <div class="form-group">
                        <div class="sss_title">
                           By Stage
                        </div>
                        <div class="sss_con">
                           <ul>
                              <li>
                                 <select id="opportunity_stage_filter_type" name="opportunity_stage_filter_type" class="demo-default form-control select_user search_inp"  >
                                    <option value="all_stage">All Stage</option>
                                    <option value="current_stage">Current Stage</option>                                 
                                 </select>
                              </li>
                           </ul>

                           <ul class="repeart_ul">
                              <?php
                                 foreach($opportunity_stage_list as $opportunity_stage_data){
                                   $is_checked='';
                                   if($opportunity_stage_data->id==1 || $opportunity_stage_data->id==2 || $opportunity_stage_data->id==8 || $opportunity_stage_data->id==10 || $opportunity_stage_data->id==11 || $opportunity_stage_data->id==9)
                                   {
                                     //$is_checked='checked="checked"';
                                   }
                                 ?>
                              <li id="by_stage_li_<?php echo $opportunity_stage_data->id; ?>" >
                                 <label class="check-box-sec">
                                 <input type="checkbox" value="<?php echo $opportunity_stage_data->id; ?>" name="opportunity_stage" id="opportunity_stage_<?php echo $opportunity_stage_data->id; ?>" data-text="<?php echo $opportunity_stage_data->name?>" <?php echo $is_checked; ?>>
                                 <span class="checkmark"></span>
                                 </label>
                                 <?php 
                                    if($opportunity_stage_data->id==1)
                                    {
                                      echo'New '.$menu_label_alias['menu']['lead'];
                                    }
                                    else
                                    {
                                      echo ucwords(strtolower($opportunity_stage_data->name)); 
                                    }
                                    ?>
                              </li>
                              <?php
                                 }
                                 ?> 
                           </ul>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="sss_title">
                           By Status
                        </div>
                        <div class="sss_con">
                           <ul class="repeart_ul">
                              <?php
                                 foreach($opportunity_status_list as $opportunity_status_data){
                                 ?>
                              <li <?php echo ($opportunity_status_data->id==4)?'style="display:none !important;"':''; ?>>
                                 <label class="check-box-sec">
                                 <input type="checkbox" value="<?php echo $opportunity_status_data->id; ?>" name="opportunity_status" id="opportunity_status" data-text="<?php echo $opportunity_status_data->name?>">
                                 <span class="checkmark"></span>
                                 </label>
                                 <?php echo ucfirst(strtolower($opportunity_status_data->name));?>
                              </li>
                              <?php
                                 }
                                 ?> 
                              <li>
                                 <label class="check-box-sec">
                                 <input type="checkbox" value="Y" name="is_hotstar" data-text="Star <?php echo $menu_label_alias['menu']['lead']; ?>" id="is_hotstar">
                                 <span class="checkmark"></span>
                                 </label>
                                 Star <?php echo $menu_label_alias['menu']['lead']; ?>
                              </li>
                              <li>
                                 <label class="check-box-sec">
                                 <input type="checkbox" value="Y" name="pending_followup" data-text="Pending Followup" id="pending_followup">
                                 <span class="checkmark"></span>
                                 </label>
                                 Pending Followup
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="pull-left tree-holder">
                           <div class="tree_clickable fa tree-down-arrow">
                              <span>By Source </span>
                           </div>
                           <div id="select_div" class="default-scoller">
                              <div class="user_header">
                                 <div class="dropdown_new">
                                    <a href="#" class="all-secondary">
                                    <label class="check-box-sec">
                                    <input type="checkbox" value="all" name="user_all" class="user_all_checkbox" >
                                    <span class="checkmark"></span>
                                    </label>
                                    </a>
                                    <div class="dropdown">
                                       <button class="btn-all dropdown-toggle" type="button" id="dropdownMenuUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       None
                                       </button>
                                       <div class="dropdown-menu left" aria-labelledby="dropdownMenuUser">
                                          <a class="dropdown-item cAll" href="#">All</a>
                                          <a class="dropdown-item uAll" href="#">None</a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div id="select_scroller">
                                 <ul>
                                    <?php if(count($source_list)){ ?>
                                    <?php foreach($source_list as $source){ ?>
                                    <li>
                                       <label class="check-box-sec">
                                       <input type="checkbox" value="<?php echo $source->id; ?>" name="by_source" class="user_checkbox"  data-text="<?php echo ($source->alias_name)?$source->alias_name:$source->name;?>">
                                       <span class="checkmark"></span>
                                       </label>
                                       <span class="cname"><?php echo ($source->alias_name)?$source->alias_name:$source->name;?></span>
                                    </li>
                                    <?php } ?>
                                    <?php } ?>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="filter_aaction">
                        <button type="button" class="custom_blu btn btn-primary" id="lead_filter">Search</button>
                        <button type="button" class="custom_blu btn btn-primary lead_filter_reset" id="">Reset</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- LEAD FILTER Modal: END -->
      <!-- ----------------- -->
      <!-- upload csv (fb/ig) -->
      <div id="upload_csv_fb_ig_modal" class="modal fade" role="dialog">
         <div class="modal-dialog modal-width-generate modal_margin_top">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Facebook/Instagram Lead CSV Upload System</h4>
               </div>
               <div class="modal-body">
                  <div class="alert alert-danger" id="fb_ig_error_log_div" style="display: none;">
                     <strong>Error!</strong> Error on csv data, please <a href="JavaScript:void(0)" class="get_fb_ig_error_log"><b><u>Click Here</u></b></a> to see the error logs..
                  </div>
                  <form id="form_upload_fb_ig_csv">
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="background_grey">
                              <a href="JavaScript:void(0)">
                              <span class="file">             
                              <input type="file" name="fb_ig_csv_file" id="fb_ig_csv_file" onchange="fb_ig_csv_upload_and_import()">
                              <label for="file"><b>Click here to Upload Facebook/Instagram CSV file</b><br>OR drag and drop <br>the CSV file</label>
                              </span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <b class="text-danger">Note:</b> 
                           <ol>
                              <il>Please <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_fb_ig_sample" target="_blank"><b class="text-primary"><u>Click Here</u></b></a> to see the sample of csv.</il>
                              </ul>
                              <ul>
                                 <il>Please do not use any comma (,) seperator at the CSV fields</il>
                                 <li>Please do not edit or delete any columns of heading (e.g. A1 row)</li>
                           </ol>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <div id="fb_ig_csv_error_log_modal" class="modal fade modal-fullscreen" role="dialog">
        <div class="modal-dialog modal-width-generate modal_margin_top modal-lg">
          <!-- Modal content-->
          <div class="modal-content" id="fb_ig_csv_error_log_content">
          </div>
          <input type="hidden" id="uploaded_csv_file_name" value="">
        </div>
      </div>
      <!-- upload csv (fb/ig) -->
      <!-- ------------------ -->
      <!-- ----------------- -->
      <!-- upload csv -->
      <div id="upload_csv_modal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-width-generate modal_margin_top">
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">CSV Upload System</h4>
      </div>
      <div class="modal-body">
      <div class="alert alert-danger" id="error_log_div" style="display: none;">
      <strong>Error!</strong> Error on csv data, please <a href="JavaScript:void(0)" class="get_error_log"><b><u>Click Here</u></b></a> to see the error logs..
      </div>
      <form id="form_upload_csv">                    
      <div class="row">                            
      <div class="col-sm-12">
      <div class="background_grey">
      <a href="JavaScript:void(0)">
      <span class="file">             
      <input type="file" name="csv_file" id="csv_file" onchange="csv_upload_and_import()">
      <label for="file"><b>Click here to Upload CSV file</b><br>OR drag and drop <br>the CSV file</label>
      </span>
      </a>
      </div>
      </div>                            
      </div>
      <div class="row">
      <div class="col-md-12">
      <b class="text-danger">Note:</b> 
      <ol>
      <il>Please <a href="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/lead/download_lead_upload_sample" target="_blank"><b class="text-primary"><u>Click Here</u></b></a> to see the sample of csv.</il>
      </ul>
      <ul>
      <?php 
         $source_arr=[];
         $get_source=get_source();
         $source_str ='';
         $source_str .='<ol>';
         if(count($get_source))
         {
           foreach($get_source AS $rource)
           {
             $source_str .='<li class="copy">'.$rource['name'].'</li>';
           }
         } 
         $source_str .='</ol>';
         ?>
      <li>Date Format in all over CSV is dd-mm-yyyy <b>(Ex. 01-01-2021)</b></li>
      <il>Please do not use any <b>comma (,)</b> seperator at the CSV fields</il>
      <li>Please do not edit or delete any columns of heading (e.g. A1 row)</li>
      <li>
      <b>Required Fields<span class="text-danger">*</span>:</b> 
      <ul>
      <li>(*) Lead_Title</li>
      <li>(*) Lead_Describe_Requirement</li>
      <li>(*) Lead_Source: (<b class="text-primary">Click the below Lead Source text to copy</b>)
      <?php echo $source_str; ?>
      </li>
      <li>(*) Assigned_User_Employee_Id (For Ex.,1)</li>
      <!-- <li>(*) Company_Name</li> -->
      <li>(*) Company_Contact_Person</li>
      <li>(*) Company_Email</li>
      <li>(*) Company_Mobile</li>
      <li>(*) company_country</li>
      </ul>
      </li>
      </ol>
      </div>
      </div>
      </form>
      </div>
      </div>
      </div>
      </div> 

      <div id="csv_error_log_modal" class="modal fade modal-fullscreen" role="dialog">
         <div class="modal-dialog modal-width-generate modal_margin_top modal-lg">
            <!-- Modal content-->
            <div class="modal-content" id="csv_error_log_content">
            </div>
            <input type="hidden" id="uploaded_csv_file_name2" value="">
         </div>
      </div>
      <!-- upload csv -->
      <!-- ------------------ -->
      <!-- -------------------- -->
      <!-- DOWNLOAD FROM GMAIL -->
      <div id="download_from_gmail_modal" class="modal fade modal-fullscreen" role="dialog">
         <div class="modal-dialog modal-width-generate modal_margin_top modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Download From Gmail</h4>
               </div>
               <div class="modal-body" id="download_from_gmail_content"></div>
            </div>
         </div>
      </div>
      <!-- DOWNLOAD FROM GMAIL -->
      <!-- -------------------- -->
      <!-- ---------------------------- -->
      <!-- UPDATE LEAD MODAL -->
      <form class="" name="lead_update_frm" id="lead_update_frm" method="post">
         <div class="modal fade mail-modal" id="CommentUpdateLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
      </form>
      <!-- UPDATE LEAD MODAL -->
      <!-- ---------------------------- -->
      <!-- ---------------------------- -->
      <!-- UPDATE LEAD MODAL -->
      <?php /* ?>
      <form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
         <div class="modal fade mail-modal modal-fullscreen" id="PoUploadLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
         <input type="hidden" id="is_back_show" value="Y">
      </form>
      <?php */ ?>
      <!-- UPDATE LEAD MODAL -->
      <!-- ---------------------------- -->
      <!-- ---------------------------- -->
      <!-- UPDATE LEAD MODAL -->
      <div class="modal fade mail-modal" id="QuotationListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
      <!-- UPDATE LEAD MODAL -->
      <!-- ---------------------------- -->
      <!-- -------------------- -->
      <!-- -------------------------------- -->
      <!-- ------ADD LEAD WISE PRODUCT/SERVICES TAGGED---- -->
      <div id="rander_add_tagged_ps_view_modal" class="modal fade" role="dialog">
         <div class="modal-dialog modal-md modal_margin_top modal-md">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Add Product/Service</h4>
               </div>
               <div class="modal-body" id="rander_add_tagged_ps_view_html"></div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
      <!-- ------ADD LEAD WISE PRODUCT/SERVICES TAGGED ---- -->
      <!-- -------------------------------- -->
      <!-- ---------------------------- -->
      <!-- Modal -->
      <div class="like_pop" id="like_pop_block">
         <div class="pop-header">
            <h5>Great Going!</h5>
            <a href="#" class="close-pop">
               <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                  <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"></path>
               </svg>
            </a>
         </div>
         <div class="pop-body">
            <input type="hidden" name="like_id_field" id="like_id_field">
            <div class="like_scroller" id="like_stage_view"></div>
            <!-- <div class="com-holder">
               <textarea class="mail-input bg-input" placeholder="Mention your reason"></textarea>
               </div> -->
         </div>
         <div class="pop-footer">
            <button type="button" class="btn btn-primary fix-w-80" id="like_btn_confirm">Submit</button>
         </div>
      </div>
      <div class="like_pop" id="dislike_icon_block">
         <div class="pop-header">
            <h5>Ohh, is it! Tell us why?</h5>
            <a href="#" class="close-pop">
               <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                  <path id="Icon_ionic-ios-close-circle" data-name="Icon ionic-ios-close-circle" d="M18,3.375A14.625,14.625,0,1,0,32.625,18,14.623,14.623,0,0,0,18,3.375Zm3.705,19.92L18,19.589l-3.705,3.705a1.124,1.124,0,1,1-1.589-1.589L16.411,18l-3.705-3.705a1.124,1.124,0,0,1,1.589-1.589L18,16.411l3.705-3.705a1.124,1.124,0,1,1,1.589,1.589L19.589,18l3.705,3.705a1.129,1.129,0,0,1,0,1.589A1.116,1.116,0,0,1,21.705,23.295Z" transform="translate(-3.375 -3.375)"></path>
               </svg>
            </a>
         </div>
         <div class="pop-body">
            <input type="hidden" name="dislike_id_field" id="dislike_id_field">
            <div class="like_scroller" id="dislike_stage_view"></div>
            <!-- <div class="com-holder">
               <textarea class="mail-input bg-input" placeholder="Mention your reason"></textarea>
               </div> -->
         </div>
         <div class="pop-footer">
            <button type="button" class="btn btn-primary fix-w-80" id="dislike_btn_confirm">Submit</button>
         </div>
      </div>
      <div class="like_overlay"></div>

    <script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.doubleScroll.js"></script>
    <link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
    <script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>
    <script src="<?php echo assets_url(); ?>js/jquery.datetimepicker.full.js" ></script>
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.datetimepicker.css"  />
    
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/timepicki.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/calendar.css"> -->
    <script type="text/javascript" src="<?php echo assets_url(); ?>js/fecha.min.js"></script>
    <script src="<?php echo assets_url(); ?>js/hotel-datepicker.js"></script>
    <script src="<?php echo assets_url(); ?>js/datePicker.js"></script>
    <script src="<?php echo assets_url(); ?>js/timepicki.js"></script>
    <!-- <script src="<?php echo assets_url(); ?>js/calendar.js" type="text/javascript"></script> -->
    <script src="<?php echo assets_url();?>js/custom/lead/get.js?v=<?php echo rand(0,1000); ?>"></script>
    <script src="<?php echo assets_url();?>js/custom/lead/note.js?v=<?php echo rand(0,1000); ?>"></script>


    <script type="text/javascript">

      
      function showAlert(getmsg, getTitle, getType) {
        swal({
            title: getTitle,
            text: getmsg,
            type: getType,
            showCancelButton: false
        }, function() {
            window.location.reload();
        });
      }
       $(window).load(function() {
         
         $('.order-status-container').each(function( index ) {
           var getChild = $(this).find('.status-item').size();
           var newW = getChild*20;
           $(this).css({'width':newW+'%'})
           // console.log( index + ": " + newW );
         });
       });
       $(document).ready(function() {
        
         ////
         $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal:visible').length && $(document.body).addClass('modal-open');
         });
         //
         $(document).on("click",".close-edit-customer-view-modal",function(event) {
            event.preventDefault();
            //alert(1);
            $('#edit_customer_view_modal').modal('hide');
            //$('#scheduleMeetingModal').css('display','block');
         });
         ///////////////////////////////////
       
         var getPw = $('.table-responsive-holder').innerWidth();
                     
         var parentW = 2400;
         $('.wrapper1 .div1').css({'width':parentW});
         $('.table-full-holder').css({'width':getPw});
         $(document).on("click",".ext-table",function(event) {
           event.preventDefault();
           if ($(this).hasClass('active')) {
             $(this).removeClass('active');
             //$('.wrapper1').hide();
             $('.grey-card-block.list_view').removeClass('show_hide');
             $('.table-full-holder').css({'width':'100%'});
             $('.table-toggle-holder').removeClass('scroll');
             $(".wrapper1").scrollLeft(0);
             
             $('.show-on-hover').css({'right': '0px','left':'auto'});
             $('.wrapper1').hide();
           }else{
             $(this).addClass('active');
             $('.wrapper1').show();
             $('.grey-card-block.list_view').addClass('show_hide');
             /////////////////////////////////////////
             $('.table-full-holder').css({'width':parentW});
             $('.table-toggle-holder').addClass('scroll');
             $('.table-toggle-holder, .wrapper1').stop( true, true ).
                 animate({
                   scrollLeft: parentW
                 }, 500, function() {
                   
                 });
           }
           
       });
         ///////
         $(".wrapper1").scroll(function(){
             var getS = $(".table-toggle-holder").scrollLeft();
             $(".table-toggle-holder").scrollLeft($(".wrapper1").scrollLeft());
             actionPos(getS);
         });
         $(".table-toggle-holder").scroll(function(){
           var getS = $(".table-toggle-holder").scrollLeft();
           $(".wrapper1").scrollLeft($(".table-toggle-holder").scrollLeft());
             actionPos(getS);
         });
         function actionPos(getS){
           
           var tabW = $('.custom-table-responsive').width();
           var finalPos = (getS+tabW)-311;
           // console.log('getS: '+getS+', finalPos'+finalPos);
           $('.show-on-hover').css({'right': 'auto','left':finalPos+'px'});
         }
         ///////
         ///////
       
         //$('#assigned_user').select2();
         $('#assigned_user').multiselect({
           buttonClass:'custom-multiselect',
           includeSelectAllOption:true
         });
         
       
         $("body").on("click",".get_alert",function(e){
           var txt=$(this).attr('data-text');
           swal("Oops!", txt, "error");
         });    
         $('.modal').on("hidden.bs.modal", function (e) { 
           
           setTimeout(function(){ 
             // console.log('1 closeeeeeeeeee')
             if ($('.modal:visible').length) { 
               // console.log('2 closeeeeeeeeee')
               $('body').addClass('modal-open');
             }
           }, 500);
           
         });
         //////////
       updateLeadView = function(){
       $('.custom-tooltip').tooltipster();
       $('.tble-sc-bt').show();
       $('.top-new-filter-highlight').addClass('list-filter-highlight');
       $(".lead_all_checkbox").change(function() {
           $('.cousto_check .check-box-sec').removeClass('same-checked');
           $('input:checkbox[name="checked_to_customer"]').prop('checked', $(this).prop("checked"));
       
           //console.log($(this).prop("checked"));
           if($(this).prop("checked") == true){
             showUpdateBt();
           }else{
             hideUpdateBt();
           }
       });
       var $checkboxes = $('#tcontent input[name="checked_to_customer"]');
       var totalcheck = $checkboxes.length;
       $checkboxes.change(function(){
           var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
           //console.log(totalcheck+', '+countCheckedCheckboxes);
           $('.cousto_check .check-box-sec').removeClass('same-checked');
           if(countCheckedCheckboxes > 0 && countCheckedCheckboxes < totalcheck){
             $('.cousto_check .check-box-sec').addClass('same-checked');
             $('.lead_all_checkbox').prop('checked', false);
             showUpdateBt();
           }else if(countCheckedCheckboxes == totalcheck){
             $('.lead_all_checkbox').prop('checked', true);
             showUpdateBt();
             hideUpdateBt();
           }else{
             $('.lead_all_checkbox').prop('checked', false);
             hideUpdateBt();
           }
       }); 
       function showUpdateBt(){
         //onsole.log('showUpdateBt')
         $('.bulk_bt_holder').css({'display':'inline-block'});
       }
       function hideUpdateBt(){
         //console.log('hideUpdateBt')
         $('.bulk_bt_holder').hide();
       }  
       }  
         /////////////rander_add_new_lead_view_modal
         $('#lead_company_details').on('show.bs.modal', function () {
             $('#rander_add_new_lead_view_modal').addClass('goback');
         });
          
         $('#lead_company_details').on('hidden.bs.modal', function () {
             $('#rander_add_new_lead_view_modal').removeClass('goback');
         });
         $('#add_source_modal').on('show.bs.modal', function () {
             $('#rander_add_new_lead_view_modal').addClass('goback');
         });
          
         $('#add_source_modal').on('hidden.bs.modal', function () {
             $('#rander_add_new_lead_view_modal').removeClass('goback');
         });
         
         function trimText(selector, limit) {    
             var text = selector.text(),
                 trim;
       
             selector.each(function() {
                 if ($(this).text().length > limit) {
                     trim = $(this).text().substr(0, limit);
                     var txt = trim+'... <span class="expand">Read More <i class="fa fa-plus-circle">'
                     $('<div class="expend-link show-more">'+txt+'</div>').insertBefore(this);
                     $(this).hide();
                     //$(this).append(trim+'...');
                     $(this).append('<div class="expend-link show-less mt-10"><span class="collapse">Read Less <i class="fa fa-chevron-circle-up"></div>');
                 };
             });
       
             $('body').on("click",".expend-link .expand", function() { //future element
               // console.log('readmore');
               $(this).parent().hide();
               $(this).parent().parent().find('.lead-details').show();
               
             });
       
             $('body').on("click", ".expend-link .collapse",function() { //future element
               $(this).parent().parent().hide();
               $(this).parent().parent().parent().find('.show-more').show();
             });
       
         };
         updateGrid = function(){

           
           $('.bulk_bt_holder').hide();
           $('.tble-sc-bt').hide();
           //////
           $('.top-new-filter-highlight').removeClass('list-filter-highlight');
           ///////
           if ($('.ext-table').hasClass('active')) {
             $('.ext-table').removeClass('active');
             //$('.wrapper1').hide();
             $('.grey-card-block.list_view').removeClass('show_hide');
             $('.table-full-holder').css({'width':'100%'});
             $('.table-toggle-holder').removeClass('scroll');
             $(".wrapper1").scrollLeft(0);
             
             $('.show-on-hover').css({'right': '0px','left':'auto'});
             $('.wrapper1').hide();
           }
           //////
           $('.lead_all_checkbox').prop('checked', false);
           $('input:checkbox[name="lead_id"]').prop('checked', false);
           $( ".grid-view-ul > li" ).each(function( index ) {
             var gTxt = $(this).find('.lead-details')
             // console.log( '================' );
             // console.log( index + ": \n" + gTxt.text() );
             // console.log( '================' );
             trimText(gTxt,   94);
           });
           $('.lead-crown').tooltipster();
           $(document).on("click","a.show_lead_quote",function(event) {
             event.preventDefault();
             $(this).parent().find('.footer-shadow').toggleClass('show');
           });
         }


         $('.modal').on('hidden.bs.modal', function (event) {
           $('.modal:visible').length && $(document.body).addClass('modal-open');
         }); 
       
         var copy = document.querySelectorAll(".copy"); 
         for (const copied of copy) { 
           copied.onclick = function() { 
             document.execCommand("copy"); 
           };  
           copied.addEventListener("copy", function(event) { 
             event.preventDefault(); 
             if (event.clipboardData) { 
               event.clipboardData.setData("text/plain", copied.textContent);
               //console.log(event.clipboardData.getData("text"));
               
               swal("Lead_Source: '"+event.clipboardData.getData("text")+"' copied!"); 
             };
           });
         };
       });  
       
       // ======================================================
       // UPDATE COMMENT OF LEAD 
       function fn_regret_lead_view(lid)
       {
          var base_url = $("#base_url").val();   
          $.ajax({
              url: base_url + "lead/rander_deal_lost_lead_view_popup_ajax",
              type: "POST",
              data: {
                  'lid': lid
              },
              async: true,
              success: function(response) {
                  // $('#CommentUpdateLeadModal').modal('hide')
                  $('#CommentUpdateLeadModal').html(response);
                  $(".buyer-scroller").mCustomScrollbar({
                    scrollButtons:{enable:true},
                    theme:"rounded-dark"
                    });
                  //////
                  $('.select2').select2();
                  simpleEditer();
                  ////////////////////////
                  $('#CommentUpdateLeadModal').modal({
                      backdrop: 'static',
                      keyboard: false
                  });
              },
              error: function() {
                  
              }
          });
       }
       function fn_get_opp_id_view(lid)
       {
          var base_url = $("#base_url").val();  
          var is_back_show=$("#is_back_show").val();      
          $.ajax({
              url: base_url + "lead/rander_quotation_list_view_popup_ajax",
              type: "POST",
              data: {
                  'lid': lid,
                  'is_back_show': is_back_show,
              },
              async: true,
              success: function(response) {
                  $('#CommentUpdateLeadModal').modal('hide')
                  $('#QuotationListModal').html(response);
                  $(".buyer-scroller").mCustomScrollbar({
                    scrollButtons:{enable:true},
                    theme:"rounded-dark"
                    });
                  //////
                  $('.select2').select2();
                  simpleEditer();
                  ////////////////////////
                  $('#QuotationListModal').modal({
                      backdrop: 'static',
                      keyboard: false
                  });
              },
              error: function() {
                  
              }
          });
       }
       function fn_get_po_upload_view(lid,l_opp_id)
       {
          var base_url = $("#base_url").val(); 
          var is_back_show=$("#is_back_show").val(); 
            
          $.ajax({
              url: base_url + "lead/rander_po_upload_view_popup_ajax",
              type: "POST",
              data: {
                  'lid': lid,
                  'l_opp_id':l_opp_id,
                  'is_back_show': is_back_show,
              },
              async: true,
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
             complete: function (){
                       $.unblockUI();
               },
              success: function(response) {
                  $('#CommentUpdateLeadModal').modal('hide')
                  $('#PoUploadLeadModal').html(response);
                  $(".buyer-scroller").mCustomScrollbar({
                    scrollButtons:{enable:true},
                    theme:"rounded-dark"
                    });
                  //////
                  $('.select2').select2();
                  simpleEditer();
                  ////////////////////////
                  $('#PoUploadLeadModal').modal({
                      backdrop: 'static',
                      keyboard: false
                  });
              },
              error: function() {
                  
              }
          });
       }
       function open_lead_update_lead_view(lid)
       {
          var base_url = $("#base_url").val();     
          $.ajax({
              url: base_url + "lead/rander_lead_update_view_popup_ajax",
              type: "POST",
              data: {
                  'lid': lid
              },
              async: true,
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
             complete: function (){
                     $.unblockUI();
               },
              success: function(response) {
                  // $('#updateLeadModal').modal('show')
                  $('#CommentUpdateLeadModal').html(response);
                  $(".buyer-scroller").mCustomScrollbar({
                    scrollButtons:{enable:true},
                    theme:"rounded-dark"
                    });
                  //////
                  $('.select2').select2();
                  simpleEditer();
                  ///////
                  $('.btn-side .item').each(function( index ) {
                     var gItemw = $(this).find('.auto-txt-item').outerWidth();
                     // console.log( index + ": " + gItemw );
                     $(this).css({'width':gItemw});
                  });
                  $('.btn-side').addClass('owl-carousel owl-theme')
                  $('#CommentUpdateLeadModal [data-toggle="tooltip"]').tooltipster();
                  $('#txt-carousel').owlCarousel({
                      margin:10,
                      loop:false,
                      autoWidth:true,
                      nav:true,
                      items:4,
                      dots:false,
                      navText: ['<i class="fa fa-chevron-circle-left" aria-hidden="true"></i>','<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>']
                  })
                  ////////////////////////
                  $('#CommentUpdateLeadModal').modal({
                      backdrop: 'static',
                      keyboard: false
                  });
                  
                  $(".basic-editor").each(function(){
                     tinymce.init({
                         force_br_newlines : true,
                         force_p_newlines : false,
                         forced_root_block : '',
                         menubar: false,
                         statusbar: false,
                         toolbar: false,
                         setup: function(editor) {
                             editor.on('focusout', function(e) {                   
                                 var updated_field_name=editor.id;
                                 var updated_content=editor.getContent();
                             })
                         }
                     });
                     tinymce.execCommand('mceRemoveEditor', true, this.id); 
                     tinymce.execCommand('mceAddEditor', true, this.id); 
                 }); 
                 
       
                 
       
       
                 $( "#next_followup_date" ).datetimepicker({       
                     format:'d-M-Y g:i A',
                     formatTime: 'g:i A',                 
                     step: 15, 
                     theme:'default',
                     inline:false,
                     lang:'en',
                     minDate: '0',
                     closeOnDateTimeSelect:true,
                     onSelectTime : function (current_time,$input) {                       
                        // console.log($input.attr('id')+'/'+$input.val())
                        //update_next_followup($input.attr('id'),$input.val());
                     },
                     
                 });                 
              },
              error: function() {
                  
              }
          });
       }
       
       /*
       // =====================================================
       */
       function fn_get_po_upload_view_without_quotation(lid,l_opp_id)
       {
          var base_url = $("#base_url").val();  
          var is_back_show=$("#is_back_show").val(); 
          
          $.ajax({
              url: base_url + "lead/rander_po_upload_view_popup_without_quotation_ajax",
              type: "POST",
              data: {
                  'lid': lid,
                  'l_opp_id':'',
                  'is_back_show': is_back_show,
              },
              async: true,
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
             complete: function (){
                       $.unblockUI();
               },
              success: function(response) {
                  $('#CommentUpdateLeadModal').modal('hide')
                  $('#PoUploadLeadModal').html(response);
                  $(".buyer-scroller").mCustomScrollbar({
                    scrollButtons:{enable:true},
                    theme:"rounded-dark"
                    });
                  //////
                  $('.select2').select2();
                  simpleEditer();
                  ////////////////////////
                  $('#PoUploadLeadModal').modal({
                      backdrop: 'static',
                      keyboard: false
                  });
              },
              error: function() {
                  
              }
          });
       }
       
       // STYLE
       var edit = function(cmd) {
         var val = false;
         switch (cmd) {
          case 'formatBlock': val = 'blockquote'; break;
          case 'createLink': val = prompt('Enter the URL to hyperlink to from the selected text:'); break;
          case 'insertImage': val = prompt('Enter the image URL:'); break;
         }
         document.execCommand(cmd, false, val);
         box.focus();
       }
    </script>
   </body>
</html>



