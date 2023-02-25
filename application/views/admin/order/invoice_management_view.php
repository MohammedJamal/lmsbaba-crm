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
          <h4>Manage Invoice </h4> 
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
          <h5 class="lead_board"> Invoice Management </h5>
          <span id="selected_filter_div" class="lead_filter_div"></span>
        </div>
        <div class="filter_right filter_sort">
        <div class="filter_block">
            <div class="filter_item"><strong>Sort by FY</strong></div>
            <div class="filter_item">              
              <select class="sort_dd" id="sort_by_fy">
                  <option value="">--Select One--</option>
                    <?php if(count($get_fy)){ ?>
                      <?php $i=0;foreach($get_fy AS $fy){ ?>
                        <option value="<?php echo $fy['fy']; ?>" <?php if($i==0){echo"selected";} ?> data-fy_start_date="<?php echo $fy['start_date']; ?>" data-fy_end_date="<?php echo $fy['end_date']; ?>"><?php echo $fy['fy']; ?></option>
                      <?php $i++;} ?>                    
                    <?php } ?>
              </select>
            </div>
          </div>

          <div class="filter_block">
            <div class="filter_item"><strong>Search by</strong></div>
            <div class="filter_item">
              <input type="text" class="sort_dd" id="filter_string_search" />              
            </div>
          </div>
          <div id="btnContainer">
            <button class="btn active" id="string_search_btn">Go</button>
            <!-- <button class="btn active get_view" data-target="grid"><i class="fa fa-th-large"></i></button>  -->
            <!-- <button class="btn get_view" data-target="list"><i class="fa fa-bars"></i></button> -->
          </div>
          <div class="float-right ml-10 tble-sc-bt">
            <button class="btn active" id="string_search_btn_reset">Reset</button>
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
                        <th scope="col" class="sort_order" data-field="t1.invoice_no" data-orderby="asc">Invoice No.</th>
                        <th>Invoice Date</th>
                        <th>Company</th>
                        <th>Amount</th>
                        <th>Payment Terms</th>
                        <th>Payment Received</th>
                        <th>Balance Payment</th>
                        <th>TDS Deduction</th>
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
            //if($this->session->userdata['admin_session_data']['user_id']=='1')
            //{
            ?>   
            <div class="row">
                <div class="col-md-12">
                  <a class="new_filter_btn" href="JavaScript:void(0);" id="download_invoice_management_csv">
                  <span class="bg_span"><img src="<?php echo assets_url()?>images/dwonload_new.png"/></span> Download Report  </a>
                </div>
              </div>
            <?php
            //}                 
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
<script src="<?php echo assets_url();?>js/custom/order/invoice_management.js?v=<?php echo rand(0,1000); ?>"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.doubleScroll.js"></script>

<link rel="stylesheet" href="<?=assets_url();?>plugins/bootstrap-multiselect/bootstrap-multiselect.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-multiselect/bootstrap-multiselect.js" type="text/javascript"></script>

</div>
</div>
</div>
</body>
</html>
<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form id="frmPoUpload" name="frmPoUpload" onsubmit="return false;">
  <div class="modal fade mail-modal modal-fullscreen" id="PoUploadLeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
  <input type="hidden" id="is_back_show" value="Y">
</form>
<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->
<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<!-- owl -->
<link rel="stylesheet" href="<?=assets_url();?>css/owl.carousel.min.css">
<link rel="stylesheet" href="<?=assets_url();?>css/owl.theme.default.min.css">
<script src="<?=assets_url();?>js/owl.carousel.js"></script>
<!-- owl -->
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>

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
