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
          <h4>Call Log Report <img src="<?php echo assets_url()?>images/message.png" width="32"/></h4> 
        </div>
      </div>
      <div class="col-sm-8 pleft_0">
        <div class="bg_white_filt">
        <ul class="filter_ul">
          <!-- <li>
            <a href="JavaScript:void(0);" class="new_filter_btn" id="sync_call_report_filter_btn">
              <span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>
              Filters
            </a>
          </li> --> 
          <li>
            <a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/lead/manage_sync_call/');?>" class="new_filter_btn" id="">
              <span class="bg_span"><img src="<?php echo assets_url()?>images/left_black.png"/></span>
              Back
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
          <h5 class="lead_board"> Call Log Leads </h5>
          <span id="call_sync_report_selected_filter_div" class="lead_filter_div" style=""></span>
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
                              
          </div>
          <!-- <div class="float-right ml-10 tble-sc-bt">
            <a href="JavaScript:void(0)" class="ext-table">
            <svg aria-hidden="true" role="img" class="octicon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display: inline-block; user-select: none; vertical-align: text-bottom;"><path fill-rule="evenodd" d="M8.177 14.323l2.896-2.896a.25.25 0 00-.177-.427H8.75V7.764a.75.75 0 10-1.5 0V11H5.104a.25.25 0 00-.177.427l2.896 2.896a.25.25 0 00.354 0zM2.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM6 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 5a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5zM12 4.25a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zm2.25.75a.75.75 0 000-1.5h-.5a.75.75 0 000 1.5h.5z"></path></svg>
            </a>
          </div> -->
        </div>
      </div>

      <div class="grey-card-block list_view" id="listing_view">
        <div class="full-width">
          <div class="wrapper1"><div class="div1"></div></div>
          <div class="custom-table-responsive table-toggle-holder">
            <div class="table-full-holder">
              <div class="table-one-holder">
                <table class="table custom-table" id="renewal_table-">
                  <thead>
                    <tr>
                      <tr>                       
                        <th scope="col" style="text-align:center;">Date</th>
                        <th scope="col" style="text-align:center;">Total Talk<br><small>(H:m:s)</small></th>
                        <th scope="col" style="text-align:center;">Total Calls</th>
                        <th scope="col" style="text-align:center;">Talked</th>
                        <th scope="col" style="text-align:center;">Not Talked</th>
                        <th scope="col" style="text-align:center;">Unique</th>
                        <th scope="col" style="text-align:center;">Outgoing</th>
                        <th scope="col" style="text-align:center;">Incoming</th>
                        <th scope="col"  style="text-align:center;">Missing<br> Opportunities</th>
                        <th scope="col" class="auto-show-- hide--" style="text-align:center;">New Leads<br> Created</th>
                        <th scope="col" class="auto-show-- hide--" style="text-align:center;">Sales/ Service</th>
                        <th scope="col" class="auto-show-- hide--" style="text-align:center;">Business<br> Call</th>
                      </tr>
                    </tr>
                  </thead>
                  <tbody id="tcontent"></tbody>
                </table>                              
                <input type="hidden" id="filter_sort_by" value="">
                <input type="hidden" id="filter_scr_assigned_user" value="">
                <input type="hidden" id="filter_scr_year" value="">
                <input type="hidden" id="filter_scr_month" value="">

                <input type="hidden" id="filter_scr_from_date" value="">
                <input type="hidden" id="filter_scr_to_date" value="">
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
            if($this->session->userdata['admin_session_data']['user_id']=='1')
            {
            ?>   
            <div class="row">
                <div class="col-md-12">
                  <a class="new_filter_btn" href="JavaScript:void(0);" id="download_call_log_csv">
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
<!-- FILTER Modal: START -->
<div id="syncCallFilterModal" class="modal fade in default_filter" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h2>Filter Call Log Report <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
         </div>
         <input type="hidden" id="scr_assigned_user_default" value="<?php echo $user_id; ?>">
         <input type="hidden" id="scr_year_default" value="<?php echo $Startyear; ?>">
         <input type="hidden" id="scr_month_default" value="<?php echo $CurrMonth; ?>">
         <input type="hidden" id="scr_date_default" value="<?php echo $CurrDate; ?>">
         <div class="modal-body">
            <div class="f_holder">
              <div class="form-group row">
                  <div class="col-md-1 title_f">User</div>
                    <div class="col-md-3">
                        <!-- <select id="scr_assigned_user" name="scr_assigned_user" class="w-100" placeholder="Select a User..." > -->
                        <select id="scr_assigned_user" name="scr_assigned_user" class="demo-default search_inp" placeholder="Select a User..." multiple>
                  
                          <?php
                          foreach($user_list as $user_data)
                          {
                            ?>
                            <option value="<?php echo $user_data['id'];?>" selected data-text="<?php echo $user_data['name']?>"><?php echo $user_data['name']?></option>
                          <?php
                          }
                          ?>
                        </select>
                    </div>
                  
                    <div class="col-md-1 title_f hide">Year</div>
                      <div class="col-md-3 hide">
                        <select name="scr_year" id="scr_year" class="w-100">
                            <?php
                            foreach ($yearArray as $year) {
                                $selected = ($year == $Startyear) ? 'selected="selected"' : '';
                                echo '<option '.$selected.' value="'.$year.'" data-text="<b><u>By Year:</u></b> '.$year.'">'.$year.'</option>';
                            }
                            ?>
                        </select>
                      </div>
                      <?php $all_months=get_all_month(); ?>
                      <div class="col-md-1 title_f hide">Month</div>
                      <div class="col-md-3 hide">
                        <select name="scr_month" id="scr_month" class="w-100">
                            <?php		$k=1;
                            for ($i = 0; $i < 12; $i++) {                         
                              $label=$all_months[($i+1)];
                              $value=($k<=9)?'0'.$k:$k;
                                          $selected = ($value == $CurrMonth) ? 'selected="selected"' : '';						  
                                          echo "<option $selected value='$value' data-text='<b><u>By Month:</b></u> $label'>$label</option>";
                              $k++;
                            }
                            ?>
                        </select>
                      </div>
                    </div> 

                    <div class="form-group row">
                      <div class="col-md-3 title_f">By Date Form</div>
                      <div class="col-md-3">
                        <!-- <span class="add-on input-group-addon">
                          <img src="<?php echo assets_url()?>images/calendar.png"/>
                          </span> -->
                        <input type="text" class="form-control drp search_inp display_date" name="scr_from_date" id="scr_from_date" placeholder="From" value="" data-text="" />
                      </div>
                      <div class="col-md-3 title_f">To</div>
                      <div class="col-md-3">
                        <!-- <span class="add-on input-group-addon">
                          <img src="<?php echo assets_url()?>images/calendar.png" />
                          </span> -->
                          
                          <input type="text" class="form-control drp search_inp display_date" name="scr_to_date" id="scr_to_date" placeholder="To" value="" data-text="" />
                      </div>
                      
                    </div>            
                
                    <div class="filter_aaction">
                      <button type="button" class="custom_blu btn btn-primary" id="sync_call_filter">Search</button>
                      <button type="button" class="custom_blu btn btn-primary" id="sync_call_report_filter_reset">Reset</button>
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
<script src="<?php echo assets_url();?>js/custom/lead/sync_call_report.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>
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
  $('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5'
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
    //////////////////////////////////////////

    $('#scr_assigned_user').multiselect({
      buttonClass:'custom-multiselect',
      includeSelectAllOption:true,
      numberDisplayed:1
    });


});
</script>