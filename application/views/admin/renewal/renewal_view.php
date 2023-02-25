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
  <input type="hidden" id="r_mode" value="">
  <div class="content-view"> 
    <div class="row m-b-1">            
      <div class="col-sm-4 pr-0">
        <div class="bg_white back_line">  
          <h4>Manage Renewal </h4> 
        </div>
      </div>
      <div class="col-sm-8 pleft_0">
        <div class="bg_white_filt">
        <ul class="filter_ul">                        
          <li>
            <a href="JavaScript:void(0);" class="new_filter_btn" id="filter_btn">
              <span class="bg_span"><img src="<?php echo assets_url()?>images/filter_new.png"/></span>
              Filters
            </a>
          </li>
          <li>                          
            <a class="new_filter_btn" href="JavaScript:void(0);" id="rander_add_new_lead_view">
              <span class="bg_span"><img src="<?php echo assets_url()?>images/adduesr_new.png"/></span> Add <?php echo $label['menu']['renewal']; ?>
          
            </a> 
          </li>
         <?php  ?>
          <li>
            <?php                            
              if($this->session->userdata['admin_session_data']['user_id']=='1')
              {
              ?>                    
                <!-- <a href="JavaScript:void(0);" class="upload_excel upload_csv new_filter_btn"><span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"></span> Upload </a> -->
              <?php
              }                  
              ?>
          </li>                              
        </ul>
        </div>
      </div>
    </div>   
                   
    <div class="card process-sec">
      <div class="filter_holder new">
        <div class="pull-left">
          <h5 class="lead_board"><?php echo $label['menu']['renewal']; ?> <a href="JavaScript:void(0)" title="Create lead from Renewal" id="create_lead_from_renewal"><i class="fa fa-refresh" aria-hidden="true"></i></a></h5>
          <span id="selected_filter_div" class="lead_filter_div"></span>
        </div>        

        <div id="btnContainer">

            <button class="btn active get_view" data-target="list" id="list_view_btn"><i class="fa fa-bars" title="Transactional view"></i></button>
            <button class="btn get_view" data-target="grid" title="Group view" id="grid_view_btn"><i class="fa fa-th-large"></i></button> 
            
        </div>        
      </div>

      <div class="grey-card-block">
        <div class="full-width">
          <div class="wrapper1"><div class="div1"></div></div>
          <div class="custom-table-responsive table-toggle-holder">
            <div class="table-full-holder">
              <div class="table-one-holder">
                <table class="table custom-table" id="renewal_table">
                  <thead>
                     <tr>                        
                        <th scope="col" class="sort_order" data-field="t1.id" data-orderby="asc">ID</th>
                        <th scope="col" class=""></th>
                        <th scope="col" class="sort_order" data-field="cus.company_name" data-orderby="asc">Company</th>
                        <th scope="col" class="">Product Name</th>
                        <th scope="col" class="sort_order" data-field="renewal_amount" data-orderby="asc">Renewal Amount</th>
                         <th scope="col" class="sort_order" data-field="current_stage_id" data-orderby="asc">Status</th>
                        <th scope="col" class="sort_order desc" data-field="renewal_date" data-orderby="asc">Renewal Date</th> 
                        <th scope="col" class="sort_order" width="110" data-field="next_follow_up_date" data-orderby="asc">Follow-up</th>
                        <th scope="col" class="sort_order" data-field="cus_assigned_user" data-orderby="asc">Assign To</th>
                        <th scope="col" class="sort_order" data-field="lead_id" data-orderby="asc">Lead ID</th>        
                        <th scope="col" class="auto-show  ">Action</th>
                     </tr>
                  </thead>
                  <tbody id="tcontent"></tbody>
                </table>
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

                <input type="hidden" id="view_type" value="list">
                <input type="hidden" id="by_keyword" value="">   
                <input type="hidden" id="renewal_from_date" value="">
                <input type="hidden" id="renewal_to_date" value="">
                <input type="hidden" id="followup_from_date" value="">
                <input type="hidden" id="followup_to_date" value="">
                <input type="hidden" id="lead_type" value="">
                <input type="hidden" id="sort_by" value="">                
                <input type="hidden" id="page_number" value="1">
                <input type="hidden" id="is_scroll_to_top" value="N">                
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
            /*                         
            if($this->session->userdata['admin_session_data']['user_id']=='1')
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
            */               
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
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url();?>js/custom/renewal/get.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.doubleScroll.js"></script>

<link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>

</div>
</div>
</div>
</body>
</html>

<!-- -------------------------------- -->
<!-- ------ADD NEW AMC MODAL ---- -->

<div id="rander_add_new_renewal_view_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Renewal/AMC</h4>
    </div>
    <div class="modal-body" id="rander_add_new_renewal_view_html"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
</div>

<!-- ------ADD NEW AMC MODAL ---- -->
<!-- -------------------------------- -->
<!-- -------------------------------- -->
<!-- ------EDIT NEW AMC MODAL ---- -->

<div id="rander_edit_new_renewal_view_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md modal_margin_top modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit New Renewal/AMC</h4>
    </div>
    <div class="modal-body" id="rander_edit_new_renewal_view_html"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
</div>

<!-- ------EDIT NEW AMC MODAL ---- -->
<!-- -------------------------------- -->

<!-- LEAD FILTER Modal: START -->
<div class="modal fade renewal-modal" id="leadFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <h2>Filter Renewal/AMC  <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
    </div>
    <div class="modal-body">
      <div class="f_holder">
        <div class="form-group hide">
          <ul>
            <li><div class="title_f">By Keyword</div></li>
            <li class="full-without-title">
              <div class="">
                <input type="text" class="form-control" name="" id="filter_by_keyword" placeholder="Search by keyword...." value="" data-text="By Keyword"/>
              </div>
            </li>
          </ul>
        </div>
        <div class="form-group">
          <ul>
            <li><div class="title_f">Renewal Date</div></li>
            <li>
            <div class="input-prepend input-group">
              <input type="text" class="form-control drp search_inp display_date" name="" id="filter_renewal_from_date" placeholder="From date" value="" />
            </div>
            </li>
            <li><div class="title_f">To</div></li>
            <li>
            <div class="input-prepend input-group">
              <input type="text" class="form-control drp search_inp display_date" name="" id="filter_renewal_to_date" placeholder="To date" value="" />
            </div>
            </li>            
          </ul>
        </div>
        <div class="form-group">
          <ul>
            <li><div class="title_f">Follow-Up Date</div></li>
            <li>
            <div class="input-prepend input-group">
              <input type="text" class="form-control drp search_inp display_date" name="" id="filter_followup_from_date" placeholder="From date" value="" />
            </div>
            </li>
            <li><div class="title_f">To</div></li>
            <li>
            <div class="input-prepend input-group">
              <input type="text" class="form-control drp search_inp display_date" name="" id="filter_followup_to_date" placeholder="To date" value="" />
            </div>
            </li>            
          </ul>
        </div>
        

        <div class="form-group">
          <ul>
            <li><div class="title_f">Renewal Status<!-- By Lead Type --></div></li>
            <li>
              <label class="check-box-sec checkbox">
                <input type="checkbox" value="P" class="" name="filter_lead_type" data-text="Pending" id="filter_lead_type_p">
                <span class="checkmark"></span>
              </label>
              Pending
            </li>
            <li>
              <label class="check-box-sec checkbox">
                <input type="checkbox" value="AL" class="" name="filter_lead_type" data-text="Active" id="filter_lead_type_al">
                <span class="checkmark"></span>
              </label>
              Active
            </li>
            <li>
              <label class="check-box-sec checkbox">
                <input type="checkbox" value="WL" class="" name="filter_lead_type" data-text="Won Leads" id="filter_lead_type_wl">
                <span class="checkmark"></span>
              </label>
              Won
            </li> 
            <li>
              <label class="check-box-sec checkbox">
                <input type="checkbox" value="LL" class="" name="filter_lead_type" data-text="Lost Leads" id="filter_lead_type_ll">
                <span class="checkmark"></span>
              </label>
              Lost
            </li>
                       
          </ul>
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
                      <li>(*) Lead_Source: (Click the text to copy)
                        <?php echo $source_str; ?>
                      </li>
                      <li>(*) Assigned_User_Employee_Id (For Ex.,1)</li>
                      <!-- <li>(*) Company_Name</li> -->
                      <li>(*) Company_Contact_Person</li>
                      <li>(*) Company_Email</li>
                      <li>(*) Company_Mobile</li>
                      <li>(*) Company_City</li>
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

<!-- <div class="main-floating">
  <form>
    <div class="form-group row">
      <div class="col-md-2">To:</div>
      <div class="col-md-10"><input type="text" name=""></div>
      
    </div>
  </form>
</div>
<a href="#" class="main-icon-floating"><i class="fa fa-envelope" aria-hidden="true"></i></a> -->

<div class="like_overlay"></div>

<script type="text/javascript">  
  $(document).ready(function() {

    //$('#assigned_user').select2();
    $('#assigned_user').multiselect({
      buttonClass:'custom-multiselect',
      includeSelectAllOption:true
    });
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
    
   
    
    $('.modal').on('hidden.bs.modal', function (event) {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    }); 
    
  });  
 
</script>
<style type="text/css">.copy {cursor: copy;}</style>
