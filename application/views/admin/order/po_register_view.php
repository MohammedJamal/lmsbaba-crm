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
          <h4>Manage Purchase Order </h4> 
        </div>
      </div>
      <div class="col-sm-8 pleft_0">
        <div class="bg_white_filt">
        <ul class="filter_ul">
                                   
        </ul>
        </div>
      </div>
    </div>   
                   
    <div class="card process-sec">
      <div class="filter_holder new">
        <div class="pull-left">
          <h5 class="lead_board"> PO Register  </h5>
          <span id="selected_filter_div" class="lead_filter_div"></span>
        </div>
        <div class="filter_right filter_sort">
          
          <div id="btnContainer">

            <!-- <button class="btn active get_view" data-target="grid"><i class="fa fa-th-large"></i></button>  -->
            <!-- <button class="btn get_view" data-target="list"><i class="fa fa-bars"></i></button> -->
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
                        <th>Order ID</th>
                        <th scope="col" class="sort_order" data-field="t1.id" data-orderby="asc">PO No.</th>
                        <th>PO Date</th>
                        <th>Company</th>
                        <th>PO Amount</th>
                        <th>Payment Terms</th>
                        <th>Proforma No.</th>
                        <th>Proforma Date</th>
                        <th>Invoice No.</th>
                        <th>Invoice Date</th>
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
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<!-- <script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script> -->
<link rel="stylesheet" href="<?=assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo assets_url();?>js/custom/order/po_register.js?v=<?php echo rand(0,1000); ?>"></script>
<script src="<?php echo assets_url();?>js/custom/order/order.js?v=<?php echo rand(0,1000); ?>"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.doubleScroll.js"></script>

<link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>

</div>
</div>
</div>
</body>
</html>
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
<!-- UPDATE LEAD MODAL -->
<!-- LEAD FILTER Modal: START -->
<div class="modal fade" id="leadFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
            <h2>Filter Leads <a class="filter_close pull-right" href="#" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h2>
        </div>
    <div class="modal-body">
      <div class="f_holder">
        <div class="form-group">
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
          <li><div class="title_f">By Date</div></li>
          <li>
          <div class="input-prepend input-group">
            <span class="add-on input-group-addon">
            <img src="<?php echo assets_url(); ?>images/calendar.png"/>
            </span>
            
            <input type="text" class="form-control drp search_inp display_date" name="lead_from_date" id="datepicker3" placeholder="Enquiry Date" value="" />
          </div>
          </li>
          <li><div class="title_f">To</div></li>
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
            <option value="added_on" data-text="Added On">Added On</option>
            <option value="updated_on" data-text="Last Updated">Last Updated</option>
            <option value="follow_up_on" data-text="Follow-up">Follow-up </option>
            <option value="quoted_on" data-text="Quoted">Quoted</option>
            <option value="regretted_on" data-text="Regretted">Regretted</option>
            <option value="deal_losted_on" data-text="Deal Lost">Deal Lost</option>
            <option value="deal_won_on" data-text="Deal Won">Deal Won</option>
          </select>
          </li>
        </ul>
        </div>
        <div class="form-group">
        <ul>
          <li><div class="title_f">By User</div></li>
          <li style="width: 160px;">
          <select id="assigned_user" name="assigned_user" class="demo-default search_inp" placeholder="Select a User..." multiple>
            
            <?php
            foreach($user_list as $user_data)
            {
              ?>
              <option value="<?php echo $user_data['id'];?>"<?php if($assigned_user==$user_data['id']){?> selected="selected" <?php } ?> data-text="<?php echo $user_data['name']?>"><?php echo $user_data['name']?></option>
            <?php
            }
            ?>
          </select>
          </li>
          <li>
          <label class="check-box-sec">
            <input type="checkbox" value="E" class="" name="lead_applicable_for" data-text="Export Leads" id="lead_applicable_for_e" >
            <span class="checkmark"></span>
          </label>
          Export Leads
          </li>
          <li>
          <label class="check-box-sec">
            <input type="checkbox" value="D" class="" name="lead_applicable_for" data-text="Domestic Leads" id="lead_applicable_for_d">
            <span class="checkmark"></span>
          </label>
          Domestic Leads
          </li>
        </ul>
        </div>

        <div class="form-group">
          <ul>
            <li><div class="title_f">By Type</div></li>          
            <li>
              <label class="check-box-sec radio">
                <input type="radio" value="AL" class="" name="lead_type" data-text="Active Leads" checked="checked" id="lead_type_al">
                <span class="checkmark"></span>
              </label>
              Active Leads
            </li>
            <li>
              <label class="check-box-sec radio">
                <input type="radio" value="LL" class="" name="lead_type" data-text="Lost Leads" id="lead_type_ll">
                <span class="checkmark"></span>
              </label>
              Lost Leads
            </li>
            <li>
              <label class="check-box-sec radio">
                <input type="radio" value="WL" class="" name="lead_type" data-text="Won Leads" id="lead_type_wl">
                <span class="checkmark"></span>
              </label>
              Won Leads
            </li>
            <li>
              <label class="check-box-sec radio">
                <input type="radio" value="ALL" class="" name="lead_type" data-text="All Leads" id="lead_type_all">
                <span class="checkmark"></span>
              </label>
              All Leads
            </li>
          </ul>
        </div>

        <div class="form-group">
        <div class="sss_title">
          By Stage
        </div>
        <div class="sss_con">
          <ul class="repeart_ul">
          <?php
          foreach($opportunity_stage_list as $opportunity_stage_data){
            $is_checked='';
            if($opportunity_stage_data->id==1 || $opportunity_stage_data->id==2 || $opportunity_stage_data->id==8 || $opportunity_stage_data->id==10 || $opportunity_stage_data->id==11 || $opportunity_stage_data->id==9)
            {
              //$is_checked='checked="checked"';
            }
          ?>
          <li>
            <label class="check-box-sec">
            <input type="checkbox" value="<?php echo $opportunity_stage_data->id; ?>" name="opportunity_stage" id="opportunity_stage_<?php echo $opportunity_stage_data->id; ?>" data-text="<?php echo $opportunity_stage_data->name?>" <?php echo $is_checked; ?>>
            <span class="checkmark"></span>
            </label>
            <?php 
            if($opportunity_stage_data->id==1)
            {
              echo'New Leads';
            }
            else
            {
              echo ucfirst(strtolower($opportunity_stage_data->name)); 
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
          <li>
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
            <input type="checkbox" value="Y" name="is_hotstar" data-text="Star Leads" id="is_hotstar">
            <span class="checkmark"></span>
            </label>
            Star Leads
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
                            <input type="checkbox" value="<?php echo $source->id; ?>" name="by_source" class="user_checkbox"  data-text="<?php echo $source->name?>">
                            <span class="checkmark"></span>
                           </label>
                           <span class="cname"><?php echo $source->name; ?></span>
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
<!-- owl -->
<link rel="stylesheet" href="<?=assets_url();?>css/owl.carousel.min.css">
<link rel="stylesheet" href="<?=assets_url();?>css/owl.theme.default.min.css">
<script src="<?=assets_url();?>js/owl.carousel.js"></script>
<!-- owl -->
<!-- <script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script> -->

<!-- tooltip -->
<?php /* ?>
<link rel="stylesheet" type="text/css" href="<?=assets_url();?>css/tooltipster.css" />
<script type="text/javascript" src="<?=assets_url();?>js/jquery.tooltipster.js"></script>
<?php */ ?>
<!-- tooltip -->
<script type="text/javascript">
$(document).ready(function(){
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
        console.log('cccccc');
        
        $('.show-on-hover').css({'right': '0px','left':'auto'});
        $('.wrapper1').hide();
      }else{
        $(this).addClass('active');
        $('.wrapper1').show();
        //$(this).find('.fa').removeClass('fa-long-arrow-right').addClass('fa-long-arrow-left');
        //$('.table-toggle-holder').find('.auto-show').removeClass('hide');
        $('.grey-card-block.list_view').addClass('show_hide');
        /////////////////////////////////////////
        $('.table-full-holder').css({'width':parentW});
        $('.table-toggle-holder').addClass('scroll');
        $('.table-toggle-holder, .wrapper1').stop( true, true ).
            animate({
              scrollLeft: parentW
            }, 500, function() {
              //1262
              console.log('scccccc: '+parentW)
              //actionPos(parentW);
              //$('.media-grid-child').addClass('scroll-active');
            });
      }
      
      
      //$('.table-toggle-holder').scrollLeft(parentW);;
  });
    ///////

  updateGrid = function(){      
      $('.bulk_bt_holder').hide();
      $('.tble-sc-bt').hide();
      
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
  }
  updateLeadView = function(){
    $('.custom-tooltip').tooltipster();
    $('.tble-sc-bt').show();
  } 

  // =====================================================
  // Quick View

  $("body").on("click",".quick_view_item",function(e){
    var txt=$(this).attr('data-comment');
    // var existing_txt=$(".buying-requirements").html();
    $(".buying-requirements .default-com").text(txt);
  });
  $("body").on("click",".del-comm",function(e){
        var base_URL = $("#base_url").val();
        var click_btn=$(this);
        var id=$(this).attr("data-id");
          var indexToRemove = $(this).parent().parent().parent().index();
        
        swal({
            title: 'Warning',
            text: 'Are you sure? Do you want to delete the record?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
          }, 
          function(isConfirm) {
                if (isConfirm) {
                    var data = "id="+id;  
                    $.ajax({
                        url: base_URL+"lead/delete_lead_update_pre_define_comment",
                        data: data,
                        cache: false,
                        method: 'POST',
                        dataType: "html",
                        beforeSend: function( xhr ) {},
                        success:function(res){ 
                            result = $.parseJSON(res);
                            if(result.status=='success')
                            {
                                swal('Success!', result.msg, 'success'); 
                                $("#item_"+id).parent().html(''); 


                                $(this).parent().parent().parent().index();
      
                                $("#txt-carousel").trigger('remove.owl.carousel', [indexToRemove]).trigger('refresh.owl.carousel'); 
                                var c = $('#txt-carousel .owl-stage .owl-item').size();
                                

                                $('.quick_reply_count').html('('+c+')');                                       
                            }
                        },
                        complete: function(){},
                        error: function(response) {
                        //alert('Error'+response.table);
                        }
                    })
              }
              return false;
          });        
    });
  /*
  $(document).on('click', 'a.del-item', function (e) {
      e.preventDefault();
      var base_URL = $("#base_url").val();
      var id=$(this).attr("data-id");
      var indexToRemove = $(this).parent().parent().parent().index();
      
      $("#txt-carousel").trigger('remove.owl.carousel', [indexToRemove]).trigger('refresh.owl.carousel');

      var c = $('#txt-carousel .owl-stage .owl-item').size();

        //$('.quick_view_h1_tag').removeClass('hide');
        //$('.quick_reply_count').removeClass('hide');
        $('.quick_reply_count').html('('+c+')');

    });
  */
  $(document).on('click', '.add_quick_view_comment', function (e) {
      e.preventDefault();

      var getTxt = $('#quick_view_title').val();
      var getTxtDesc = $('#quick_view_desc').val();
      var getTarget = $(this).attr('data-target');
      var error='';
      if(getTxt =='')
      {  
        $("#quick_view_title").addClass("field-error");
        error='1';
        e.stopPropagation();
      } 
      else
      {
        $("#quick_view_title").removeClass("field-error");
         error='';
      }

      if(getTxtDesc=='')
      {  
        $("#quick_view_desc").addClass("field-error");
         error='1';
        e.stopPropagation();
      } 
      else
      {
        $("#quick_view_desc").removeClass("field-error");
        error='';
      }
         
      if(error=='')
      {

        var base_URL = $("#base_url").val();        
        var data = "title="+getTxt+"&description="+getTxtDesc;
        // alert(data); return false;
        $.ajax({
            url: base_URL+"lead/add_lead_update_pre_define_comment",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {
                
            },
            complete: function(){
                
            },
            success:function(res){ 
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                  $('[data-toggle="tooltip"]').tooltipster('destroy');
                  var ttt = '<div class="item noshow"><div class="auto-txt-item">'+getTxt+'<a href="#" class="del-item"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
                  $('body').append(ttt);
                  var gdw = $('.noshow .auto-txt-item').innerWidth();
                  $('.noshow').remove();
                  var ttt = '<div class="item" style="width: '+gdw+'px;"><div class="auto-txt-item quick_view_item" data-toggle="tooltip"  title="'+getTxtDesc+'" data-comment="'+getTxtDesc+'">'+getTxt+'<a href="JavaScript:void(0)" data-id="'+result.id+'" class="del-item del-comm"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
                  //alert(gdw)
                  $('#'+getTarget).trigger('add.owl.carousel', [$(ttt), 0]).trigger('refresh.owl.carousel');
                  $('#'+getTarget).trigger('to.owl.carousel', 0);
                  //updateNumber(getTarget);
                  $('.com-holder-new .com-holder-fild input').val('');

                  $('[data-toggle="tooltip"]').tooltipster();
                  ////////
                  var c = $('#'+getTarget+' .owl-stage .owl-item').size();

                  $('.quick_view_h1_tag').removeClass('hide');
                  $('.quick_reply_count').removeClass('hide');
                  $('.quick_reply_count').html('('+c+')');


                  //$('.add-com-count').html('('+c+')');
                  //$('[data-toggle="tooltip"]').tooltip('update')
                   $('#quick_view_title').val('');
                   $('#quick_view_desc').val('');
                }
            },            
            error: function(response) {
            //alert('Error'+response.table);
            }
        });
      }
   });
   $(document).on('click', '.close_quick_view_comment', function (e) {
      e.preventDefault();
      $('#quick_view_title').val('');
      $('#quick_view_desc').val('');
   });
  $('#CommentUpdateLeadModal').on('shown.bs.modal', function (e) {
      // do something...
      //console.log('do something...')
      $('#txt-carousel .owl-stage .owl-item').each(function( index ) {
         var gItemw = $(this).find('.auto-txt-item').outerWidth();
         //console.log( index + ": " + gItemw );
         $(this).find('.item').css({'width':gItemw});
         $('#txt-carousel').trigger('refresh.owl.carousel');

      });
   });
  $('#ReplyPopupModal').on('shown.bs.modal', function (e) {
      // do something...
      //console.log('do something...')
      $('#txt-carousel .owl-stage .owl-item').each(function( index ) {
         var gItemw = $(this).find('.auto-txt-item').outerWidth();
         //console.log( index + ": " + gItemw );
         $(this).find('.item').css({'width':gItemw});
         $('#txt-carousel').trigger('refresh.owl.carousel');

      });
   });
  // Quick View
  // =====================================================

});
function simpleEditer()
{
    // $(".tools").show();
    var box = $('.buying-requirements');
    box.attr('contentEditable', true);

    // EDITING LISTENERS
    $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
       edit($(this).data('cmd'));
    });    
}
</script>
<style type="text/css">.copy {cursor: copy;}</style>
