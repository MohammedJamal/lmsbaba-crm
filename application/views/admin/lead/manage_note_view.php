<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>  
    <style type="text/css">
        .sweet-alert.sweetalert-lg { 
          width: 600px;
          font-size: 9px; 
        }
    </style>
    <link rel="stylesheet" href="<?php echo assets_url(); ?>css/custom_table.css"/>
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
    <div class="row m-b-1">            
      <div class="col-sm-4 pr-0">
        <div class="bg_white back_line">  
          <h4>Manage Notes <img src="<?php echo assets_url()?>images/note.png" width="32"/></h4>           
        </div>        
      </div>
      <div class="col-sm-8 pleft_0">
        <div class="bg_white_filt">
          
          <ul class="filter_ul">  
            <li>
                <label class="check-box-sec">
                  <input type="checkbox" value="U" class="" name="unread_reply" id="unread_reply">
                  <span class="checkmark"></span>
                </label>
                Unread Replies
            </li>                                    
            <li>
              <a href="JavaScript:void(0);" class="new_filter_btn" id="note_filter_btn">
                <span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>
                Filters
              </a>              
            </li>  
            <?php /* ?>        
            <li>
              <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/lead/report_sync_call');?>" class="new_filter_btn" id="">
                <span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>
                Call Log Report
              </a>
            </li>         
                                        
          </ul>
          <?php */ ?>
        </div>
      </div>
    </div>   
    <span id="note_selected_filter_div" class="lead_filter_div" style="padding-bottom:5px;"></span>   
    <div class="card process-sec">
      <?php /* ?>
      <div class="filter_holder new">
        <div class="pull-left">
          <h5 class="lead_board"> Note List</h5>
          <span id="note_selected_filter_div" class="lead_filter_div"></span>
        </div>
        <div class="filter_right filter_sort">          
          
          <div id="btnContainer">     
            <div class="sync_call_bulk_bt_holder">
              <?php if(is_attribute_available('bulk_assignee_change')){ ?>
                <button class="new_filter_btn pull-right ml-10" type="button" id="delete_row_multiple">
                  <span class="bg_span"><img src="<?php echo assets_url(); ?>images/trash-white.png" ></span>
                  Bulk Delete
                </button>
              <?php } ?>            
              <input type="hidden" name="id_bulk" id="id_bulk">
            </div>                  
          </div>
          <div class="float-right ml-10 tble-sc-bt">
            <a href="JavaScript:void(0)" class="ext-table">
            <svg aria-hidden="true" role="img" class="octicon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom;"><path fill-rule="evenodd" d="M8.177 14.323l2.896-2.896a.25.25 0 00-.177-.427H8.75V7.764a.75.75 0 10-1.5 0V11H5.104a.25.25 0 00-.177.427l2.896 2.896a.25.25 0 00.354 0zM2.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM6 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM12 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zm2.25.75a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5z"></path></svg>
            </a> 
          </div>
        </div>
      </div>
      <?php */ ?>
      
      <div class="grey-card-block" id="note_listing_view">
        <div class="full-width">
          <div class="custom-table-responsive table-toggle-holder-no">
            <div class="table-full-holder">
              <div class="table-one-holder">
                <table class="table custom-table note-list-table" id="note_table">
                  <thead>
                    <tr>
                      <tr>
                        <!-- <th scope="col" width="20">
                           <label class="control control--checkbox">
                            <input type="checkbox" value="all" name="sync_call_all" class="sync_call_all_checkbox js-check-all"><div class="control__indicator"></div>
                           </label>
                        </th> -->
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.name" data-orderby="asc" width="5%">ID</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.number" data-orderby="asc" width="20%">Date</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.bound_type" data-orderby="asc" width="5%">Lead ID</th> 
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.call_start" data-orderby="asc" width="10%">Company</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.call_end" data-orderby="asc" width="10%">Assign to</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="call_time_in_second" data-orderby="asc" width="10%">Note Added By</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.agent_mobile" data-orderby="asc" width="40%">Comment</th>
                        <th>Action</th>
                      </tr>
                    </tr>
                  </thead>
                  <tbody id="tcontent_note"></tbody>
                </table>
                <input type="hidden" id="view_type" value="list">                
                <input type="hidden" id="filter_sort_by" value="">
                <input type="hidden" id="page_number" value="1">
                <input type="hidden" id="is_scroll_to_top" value="N">
                <input type="hidden" id="filter_by_keyword" value="">
                <input type="hidden" id="filter_note_added_by" value="">
                <input type="hidden" id="filter_lead_assign_to" value="">
                <input type="hidden" id="filter_note_from_date" value="">
                <input type="hidden" id="filter_note_to_date" value="">  
                <input type="hidden" id="filter_show_all_unread_reply" value="">                
                
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-block" id="page_info_div">  
         <?php /* ?>
            <input type="hidden" id="filter_like_dsc_text" value="">
            <?php */ ?>
                 
            <div class="row">
              <div id="page_record_count_info_note" class="col-md-6 text-left ffff"></div>
              <div id="page_note" style="" class="col-md-6 text-right custom-pagination"></div>
            </div>
            <?php                            
            /*if($this->session->userdata['admin_session_data']['user_id']=='1')
            {
            ?>   
            <div class="row">
                <div class="col-md-12">
                  <a class="new_filter_btn" href="JavaScript:void(0);" id="download_leads_csv">
                  <span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"/></span> Download Report  </a>
                </div>
              </div>
            <?php
            } */                
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

<!-- FILTER Modal: START -->
<div id="noteFilterModal" class="modal fade in default_filter" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h2>Filter Note <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
         </div>
         <div class="modal-body">
            <div class="f_holder">
              <div class="form-group row">
                <div class="col-md-3 title_f">Search Note</div>
                <div class="col-md-9"><input type="text" class="form-control" name="note_search_by_keyword" id="note_search_by_keyword" placeholder="Search by Note...." value="" data-text="Search Note By"/></div>
                
              </div>

              <div class="form-group row">
                <div class="col-md-3 title_f">Note Added By</div>
                <div class="col-md-3">
                  <select name="note_added_by" id="note_added_by" class="w-100">
                      <option value="">--Select--</option>
                      <?php
                      foreach($user_list as $user_data)
                      {
                      ?>
                      <option value="<?php echo $user_data['id'];?>"<?php /*if($assigned_user==$user_data['id']){?> selected="selected" <?php }*/ ?> data-text="<?php echo $user_data['name']?>"><?php echo $user_data['name']?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
                  <div class="col-md-3 title_f">Lead Assign To</div>
                  <div class="col-md-3">
                    <select name="lead_assign_to" id="lead_assign_to" class="w-100">
                      <option value="">--Select--</option>
                      <?php
                      foreach($user_list as $user_data)
                      {
                      ?>
                      <option value="<?php echo $user_data['id'];?>"<?php /*if($assigned_user==$user_data['id']){?> selected="selected" <?php }*/ ?> data-text="<?php echo $user_data['name']?>"><?php echo $user_data['name']?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
              </div> 

              <div class="form-group row">
                <div class="col-md-3 title_f">Note Date Form</div>
                <div class="col-md-3">
                  <!-- <span class="add-on input-group-addon">
                    <img src="<?php echo assets_url()?>images/calendar.png"/>
                    </span> -->
                  <input type="text" class="form-control drp search_inp display_date" name="note_from_date" id="note_from_date" placeholder="From" value="" />
                </div>
                <div class="col-md-3 title_f">Note Date To</div>
                <div class="col-md-3">
                  <!-- <span class="add-on input-group-addon">
                     <img src="<?php echo assets_url()?>images/calendar.png" />
                    </span> -->
                    
                    <input type="text" class="form-control drp search_inp display_date" name="note_to_date" id="note_to_date" placeholder="To" value="" />
                </div>
                
              </div>
                
              <div class="filter_aaction">
                <button type="button" class="custom_blu btn btn-primary" id="notel_filter_confirm">Search</button>
                <button type="button" class="custom_blu btn btn-primary" id="note_filter_reset">Reset</button>
              </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- FILTER Modal: END -->
<!-- ----------------- -->

<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>

<!-- <script src="<?php echo assets_url();?>js/custom/lead/get.js?v=<?php echo rand(0,1000); ?>"></script> -->
<script src="<?php echo assets_url();?>js/custom/lead/note.js"></script>
</div>
</div>
</div>
</body>
</html>
<script type="text/javascript">
// $('#add_source_modal').on('show.bs.modal', function () {
//     $('#rander_add_new_lead_view_modal').addClass('goback');
// });
 
// $('#add_source_modal').on('hidden.bs.modal', function () {
//     $('#rander_add_new_lead_view_modal').removeClass('goback');
// });

$('.display_date').datepicker({
    dateFormat: "dd-M-yy",
    changeMonth: true,
    changeYear: true,
    yearRange: '-100:+5'
});
</script>