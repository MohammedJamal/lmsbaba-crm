<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>  
    <style type="text/css">
      .sweet-alert.sweetalert-lg { width: 600px;
        font-size: 9px; }
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
          <h4>Manage Call Log <img src="<?php echo assets_url()?>images/message.png" width="32"/></h4> 
        </div>
      </div>
      <div class="col-sm-8 pleft_0">
        <div class="bg_white_filt">
        <ul class="filter_ul">
                                  
          <li>
            <a href="JavaScript:void(0);" class="new_filter_btn" id="sync_call_filter_btn">
              <span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>
              Filters
            </a>
          </li>          
          <li>
            <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/lead/report_sync_call');?>" class="new_filter_btn" id="">
              <span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>
              Call Log Report
            </a>
          </li>          
          <?php /* ?>
          <li>                          
            <a class="new_filter_btn" href="JavaScript:void(0);" id="rander_add_new_lead_view">
              <span class="bg_span"><img src="<?php echo assets_url()?>images/adduesr_new.png"/></span> Add New Lead 
          
            </a> 
          </li>
         
          <li>
            <?php                            
              if($this->session->userdata['admin_session_data']['user_id']=='1')
              {
              ?>                    
                <a href="JavaScript:void(0);" class="upload_excel upload_csv new_filter_btn"><span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"></span> Upload Leads </a>
              <?php
              }                  
              ?>
          </li> 
          <?php */ ?>                             
        </ul>
        </div>
      </div>
    </div>   
                   
    <div class="card process-sec">
      <div class="filter_holder new">
        <div class="pull-left">
          <h5 class="lead_board"> Call Log Leads 
            <a href="JavaScript:void(0)" title="Reload Call Log Leads" id="load_sync_call">
              <i class="fa fa-refresh" aria-hidden="true"></i>
            </a>
            &nbsp;
            <a href="JavaScript:void(0)" title="Auto Sync Call Log Leads" id="auto_sync_call_log_to_lead">
              <i class="fa fa-tags text-success" aria-hidden="true"></i>
            </a>
            </h5>
          <span id="call_sync_selected_filter_div" class="lead_filter_div"></span>
        </div>
        <div class="filter_right filter_sort">
          <!-- <div class="filter_block">
            <div class="filter_item"><strong>Sort by</strong></div>
            <div class="filter_item">
              <select class="sort_dd" id="sort_by">
                  <option value="">--Select One--</option>
                  <optgroup label="Lead ID">
                    <option value="lead.id-asc">ASC</option>
                    <option value="lead.id-desc">DESC</option>
                  </optgroup>
                  <optgroup label="Enquiry Date">
                    <option value="lead.enquiry_date-asc">ASC</option>
                    <option value="lead.enquiry_date-desc">DESC</option>
                  </optgroup>
                  <optgroup label="Lead Title">
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
          </div> -->
          <div id="btnContainer">
            <!-- <button class="btn get_view active" data-target="list" title="Transactional view"><i class="fa fa-bars"></i></button>

            <button class="btn get_view " data-target="grid" title="Group View"><i class="fa fa-th-large"></i></button> -->      
            <div class="sync_call_bulk_bt_holder">
            <?php //if(is_attribute_available('bulk_assignee_change')){ ?>
            <button class="new_filter_btn pull-right ml-10" type="button" id="delete_row_multiple">
              <span class="bg_span"><img src="<?php echo assets_url(); ?>images/trash-white.png" ></span>
              Bulk Delete
            </button>
            <?php //} ?>            
            <input type="hidden" name="id_bulk" id="id_bulk">
          </div>                  
          </div>
          <div class="float-right ml-10 tble-sc-bt">
            <!-- <a href="JavaScript:void(0)" class="ext-table">
            <svg aria-hidden="true" role="img" class="octicon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom;"><path fill-rule="evenodd" d="M8.177 14.323l2.896-2.896a.25.25 0 00-.177-.427H8.75V7.764a.75.75 0 10-1.5 0V11H5.104a.25.25 0 00-.177.427l2.896 2.896a.25.25 0 00.354 0zM2.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM6 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM12 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zm2.25.75a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5z"></path></svg>
            </a> -->
          </div>
        </div>
      </div>

      <div class="grey-card-block" id="listing_view">
        <div class="full-width">
          <div class="wrapper1"><div class="div1"></div></div>
          <div class="custom-table-responsive table-toggle-holder">
            <div class="table-full-holder">
              <div class="table-one-holder">
                <table class="table custom-table" id="renewal_table">
                  <thead>
                    <tr>
                      <tr>
                        <th scope="col" width="20">
                           <label class="control control--checkbox">
                            <input type="checkbox" value="all" name="sync_call_all" class="sync_call_all_checkbox js-check-all"><div class="control__indicator"></div>
                           </label>
                        </th>
                        <th></th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.number" data-orderby="asc">Number</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.name" data-orderby="asc">Name</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="cust.company_name" data-orderby="asc">Company</th>
                        <th scope="col" class="sort_order_sync_call " data-field="" data-orderby="">Lead Id</th>
                        <th scope="col" class="sort_order_sync_call " data-field="" data-orderby="">Stage</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.bound_type" data-orderby="asc">Call Type</th> 
                        <th scope="col" class="sort_order_sync_call " data-field="" data-orderby="">Call Date</th> 
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.call_start" data-orderby="asc">Call Start</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.call_end" data-orderby="asc">Call End</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="call_time_in_second" data-orderby="asc" align="center">Talk Time (H:m:s)</th>
                        <th scope="col" class="sort_order_sync_call sort_order_css" data-field="t1.agent_mobile" data-orderby="asc">Assigned to</th>
                        <th>Action</th>
                      </tr>
                    </tr>
                  </thead>
                  <tbody id="tcontent"></tbody>
                </table>
                <input type="hidden" id="view_type" value="list">                
                <input type="hidden" id="filter_sort_by" value="">
                <input type="hidden" id="page_number" value="1">
                <input type="hidden" id="is_scroll_to_top" value="N">

                <input type="hidden" id="filter_sync_call_filter_by_keyword" value="">
                <input type="hidden" id="filter_sync_call_from_date" value="">
                <input type="hidden" id="filter_sync_call_to_date" value="">
                <input type="hidden" id="filter_sync_call_call_type" value="">
                <input type="hidden" id="filter_sync_call_buyer_type" value="">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-block">  
         <?php /* ?>
            <input type="hidden" id="filter_like_dsc_text" value="">
            <?php */ ?>
                 
            <div class="row">
              <div id="page_record_count_info" class="col-md-6 text-left ffff"></div>
              <div id="page" style="" class="col-md-6 text-right custom-pagination"></div>
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
<div id="syncCallFilterModal" class="modal fade in default_filter" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h2>Filter Call Log <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
         </div>
         <div class="modal-body">
            <div class="f_holder">
              <div class="form-group row">
                <div class="col-md-3 title_f">By Name</div>
                <div class="col-md-9"><input type="text" class="form-control" name="sync_call_filter_by_keyword" id="sync_call_filter_by_keyword" placeholder="Search by Name...." value="" data-text="By Name"/></div>
                
              </div>
              <div class="form-group row">
                <div class="col-md-3 title_f">Call Date Form</div>
                <div class="col-md-3">
                  <!-- <span class="add-on input-group-addon">
                    <img src="<?php echo assets_url()?>images/calendar.png"/>
                    </span> -->
                  <input type="text" class="form-control drp search_inp display_date" name="sync_call_from_date" id="sync_call_from_date" placeholder="From" value="" />
                </div>
                <div class="col-md-3 title_f">Call Date To</div>
                <div class="col-md-3">
                  <!-- <span class="add-on input-group-addon">
                     <img src="<?php echo assets_url()?>images/calendar.png" />
                    </span> -->
                    
                    <input type="text" class="form-control drp search_inp display_date" name="sync_call_to_date" id="sync_call_to_date" placeholder="To" value="" />
                </div>
                
              </div>

              <div class="form-group row">
                <div class="col-md-3 title_f">Call Type</div>
                <div class="col-md-3">
                  <select name="sync_call_call_type" id="sync_call_call_type" class="w-100">
                        <option value="">--Select--</option>
                        <option value="inconing" data-text="Call Type: Incoming">Incoming</option>
                        <option value="outgoing" data-text="Call Type: Outgoing">Outgoing</option>
                      </select>
                </div>
                  <div class="col-md-3 title_f">Buyer Type</div>
                  <div class="col-md-3">
                    <select name="sync_call_buyer_type" id="sync_call_buyer_type" class="w-100">
                      <option value="">--Select--</option>
                      <option value="existing_buyer" data-text="Buyer Type: Old Buyer">Old Buyer</option>
                      <option value="new_buyer" data-text="Buyer Type: New Buyer">New Buyer</option>
                    </select>
                  </div>
              </div>             
              

               
                
              <div class="filter_aaction">
                <button type="button" class="custom_blu btn btn-primary" id="sync_call_filter">Search</button>
                <button type="button" class="custom_blu btn btn-primary" id="sync_call_filter_reset">Reset</button>
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
<script src="<?php echo assets_url();?>js/custom/lead/sync_call.js"></script>
<script src="<?php echo assets_url();?>js/custom/lead/get.js"></script>
</div>
</div>
</div>
</body>
</html>
<script type="text/javascript">

$('#add_source_modal').on('show.bs.modal', function () {
    $('#rander_add_new_lead_view_modal').addClass('goback');
});
 
$('#add_source_modal').on('hidden.bs.modal', function () {
    $('#rander_add_new_lead_view_modal').removeClass('goback');
});

$(document).ready(function(){
  
  $("body").on("click","#auto_sync_call_log_to_lead",function(e){
    var base_URL     = $("#base_url").val(); 
    var data = "";     
    $.ajax({
        url: base_URL+"lead/update_auto_tagged_call_to_lead_ajax",
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
            if(result.status=='success' && result.is_call_log_updated=='Y'){
              load_sync_call();
            }
            else{
              if(result.msg){
                swal('Oops',result.msg,'error');
              }              
            }            
          },           
          error: function(response) {
            //alert('Error'+response.table);
          }
   })
  });
});
</script>