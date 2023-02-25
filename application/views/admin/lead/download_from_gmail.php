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
        <div class="row m-b-1">            
          <div class="col-sm-4 pr-0">
            <div class="bg_white back_line">  
              <h4>Manage Gmail Inbox <img src="<?php echo assets_url()?>images/gmail-icon.png"/></h4> 
            </div>
          </div>
          
          <div class="col-sm-8 pleft_0">
            <div class="bg_white_filt">
              <?php if($syncEmail){ ?>
              <ul class="filter_ul d-inline-flex">
                <li>
                  <a href="javascript:void(0)" class="new_filter_btn" id="compose_mail_box">
                    <span class="bg_span"><img src="<?php echo assets_url()?>images/create_plus.png"/></span>
                    Compose
                  </a>
                </li>
                <li id="sync_another_div"  style="<?php if($is_gmail_connected=='N'){ echo'display: block;';}else{echo'display: none;';} ?>">
                  <a href="javascript:void(0)" class="new_filter_btn" id="sync_other_account">
                    <span class="bg_span"><img src="<?php echo assets_url()?>images/add_mail_new.png"/></span>
                    Sync with a gmail Account
                  </a>
                </li>                
                <li id="sync_logout_div" style="<?php if($is_gmail_connected=='N'){ echo'display: none;';}else{echo'display: block;';} ?>">
                  <a href="javascript:void(0)" class="new_filter_btn" id="sync_logout_account">
                    <span class="bg_span"><img src="<?php echo assets_url()?>images/add_mail_new.png"/></span>
                    Logout (<?php echo get_sync_email_account(); ?>)
                  </a>
                </li>                
              </ul>
              <?php } ?>
            </div>
          </div>          
    </div>        
 
<div class="card process-sec">
    <?php if($syncEmail){ ?>
    <h5 class="lead_board">      
      <div class="pull-left d-flex-auto">
        Download From Gmail
        <div class="serchHolder ml-15">
          
        <div class="search">
          <form action="">
            <input type="text" class="search-box" placeholder="Type to Search" name="search_str" id="search_str" autocomplete="off" data-text="" />
            <a href="#" class="advance-search"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
            <a href="#" class="close-search"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
            <a href="#" class="search-button-over"></a>
            <button class="search-button search_confirm" type="button" id="search_confirm">
              <span class="search-icon"></span>
            </button>
          </form>
          <div class="advance-search-holder">
            <div class="form-group">
              <label>From</label><input type="text" name="search_from" id="search_from" class="default-border-input" autocomplete="off" data-text="">
            </div>
            <div class="form-group">
              <label>To</label><input type="text" name="search_to" id="search_to" class="default-border-input" autocomplete="off" data-text="">
            </div>
            <div class="form-group">
              <label>Subject</label><input type="text" name="search_subject" id="search_subject" class="default-border-input" autocomplete="off" data-text="">
            </div>
            
            <div class="form-group text-center ">
              <h6>Date</h6>            
            </div>

            <div class="form-group">
              <label>From</label>
              <input type="text" name="search_date" id="search_date" class="default-border-input display_date" autocomplete="off" readonly="true" data-text="">
            </div>
            <div class="form-group">
              <label>To</label>
              <input type="text" name="search_date_to" id="search_date_to" class="default-border-input display_date" autocomplete="off" readonly="true" data-text="">
            </div>

            <div class="form-group">
              <button type="button" class="custom_blu btn btn-primary pull-right search_confirm" id="">Search</button>
            </div>
          </div>
        </div>

        </div>
      </div>


      <div class="dashboard-right pull-right">
        <!-- <div class="serchHolder">
          <div class="search-boxs">
          <input class="search-txt" type="text" name="gmail_search" id="gmail_search" placeholder="Type to Search">
          <a href="javascript:void(0);" class="search-btns">
            <i class="fas fa-search"></i>
          </a>
        </div>
        </div> -->
        <div class="left">New Emails</div>
        <label class="switch_dashboard">
          <input type="checkbox" name="is_lead_responses" id="is_lead_responses" <?php echo ($is_sync=='Y')?'checked':''; ?>>
          <span class="slider round"></span>
        </label>
        <div class="right">Lead Responses <span class="badge badge-secondary bg-danger" id="response_count_div">0</span></div>
      </div>
    </h5>

    <span id="selected_filter_div" class="lead_filter_div"></span>

    
    <div class="card-block">                
      <div class="table-full-holder-new table-responsive" style="width: 100%">
        <table id="table" class="table new-table-style dataTable table-expand lead-mail-table" style="width: 100%">
          <thead>
            <tr>
              <th class="text-left" colspan="3">
                <div class="dropdown_new float-left">
                   <a href="#" class="all-secondary">
                   <label class="check-box-sec">
                   <input type="checkbox" value="all" name="user_all" class="user_all_checkbox">
                   <span class="checkmark"></span>
                   </label>
                   </a>
                   <div class="dropdown">
                      <button class="btn-all dropdown-toggle auto-width" type="button" id="dropdownMenuUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      
                      </button>
                      <div class="dropdown-menu left" aria-labelledby="dropdownMenuUser">
                         <a class="dropdown-item cAll" href="#">All</a>
                         <a class="dropdown-item uAll" href="#">None</a>
                         <!-- <a class="dropdown-item cRead" href="#">Read</a>
                         <a class="dropdown-item uRead" href="#">Unread</a> -->
                      </div>
                   </div>
                </div>  
                <div class="refresh-holder float-left">                  
                  <ul class="action-ul">
                    <li>
                      <a href="javascript:void(0);" class="op refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    </li>
                  </ul>
                </div>
                <div class="other-holder float-left">
                  <ul class="action-ul">
                    <li><a href="javascript:void(0);" class="selected_delete" data-status="A"><i class="fa fa-archive" aria-hidden="true" data-toggle="tooltip" title="Archive"></i></a></li>
                    <li><a href="javascript:void(0);" class="selected_delete" data-status="D"><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" title="Delete"></i></a></li>
                    <li><a href="javascript:void(0);" class="" id="selected_seen_status_change" data-toggle="tooltip" data-curstatus=""><img src="<?php echo assets_url(); ?>images/drafts_white.png"></a></li>
                  </ul>
                </div> 
                <?php /* ?>
                <div class="dd-holder float-left">                  
                  <ul class="action-ul dropdown_new no-arrow">
                    <li>
                      <div class="dropdown over">
                        <a href="#" class="dropdown-toggle op" id="dropdownMenuButtonMail" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                        <div class="dropdown-menu left" aria-labelledby="dropdownMenuButtonMail">
                          <a class="dropdown-item" href="#">Mark all as read</a>
                          <div class="dropdown-divider"></div>
                          <div class="menu-info">Select messages to see more actions</div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <?php */ ?>
                <div class="page-holder float-right">
                  <!-- <ul class="action-ul dropdown_new no-arrow ">
                    <li>
                      <div class="dropdown over">
                        <a href="#" class="dropdown-toggle op-full" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="page_record_count_info"></a>
                        <div class="dropdown-menu left" aria-labelledby="">
                          <a class="dropdown-item" href="#">Newest</a>
                          <a class="dropdown-item" href="#">Oldest</a>
                        </div>
                      </div>
                    </li>                    
                  </ul> -->
                  <div class="" id="page"></div>
                </div>
                

              </th>
            </tr>
          </thead>
          <tbody id="tcontent"></tbody>
        </table>
        <input type="hidden" id="current_page_no" name="current_page_no" value="1">
        <input type="hidden" id="sync_from_gmail" name="sync_from_gmail" value="N">
        <input type="hidden" id="filter_is_lead_responses" name="filter_is_lead_responses" value="<?php echo $is_sync; ?>">


        <input type="hidden" id="filter_by_str" value="">
        <input type="hidden" id="filter_by_from" value="">
        <input type="hidden" id="filter_by_to" value="">
        <input type="hidden" id="filter_by_subject" value="">
        <input type="hidden" id="filter_by_date" value="">
        <input type="hidden" id="filter_by_date_to" value="">
      </div>
    </div> 

  <?php }else{ ?>
    <div class="card-block"> Please add a gmail account to sync with gmail.</div>
  <?php } ?>
</div>
  

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>assets/css/tooltipster.bundle.min.css" />

<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>assets/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-shadow.min.css" />
<script type="text/javascript" src="<?php echo assets_url(); ?>js/tooltipster.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url(); ?>js/bs-modal-fullscreen.js"></script>
<script src="<?php echo assets_url();?>js/custom/lead/get_download_from_gmail.js"></script>


<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.mCustomScrollbar.css" />
<script src="<?php echo assets_url(); ?>js/jquery.mCustomScrollbar.concat.min.js"></script>      
<script type="text/javascript" src="<?php echo assets_url(); ?>js/app.js"></script>
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
<!-- Modal -->
<div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div id="attach-holder">ffff</div>
      </div>
      
    </div>
  </div>
</div>

<!-- ---------------------------- -->
<!-- UPDATE LEAD MODAL -->
<form id="compose_mail_frm" name="compose_mail_frm">
  <div class="modal fade mail-modal" id="ComposePopupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"></div>
</form>

<!-- UPDATE LEAD MODAL -->
<!-- ---------------------------- -->
<script type="text/javascript">
function fn_open_compose_mail_box_view()
{   
     // alert('To:'+to_mail+'  / From:'+from_mail);
     var base_url = $("#base_url").val();   
     $.ajax({
         url: base_url + "lead/rander_compose_mail_box_view_popup_ajax",
         type: "POST",
         data: {},
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
             $('#ComposePopupModal').html(response);
             $(".buyer-scroller").mCustomScrollbar({
               scrollButtons:{enable:true},
               theme:"rounded-dark"
               });
             //////
             $('.select2').select2();
             simpleEditer();
             ////////////////////////
             $('#ComposePopupModal').modal({
                 backdrop: 'static',
                 keyboard: false
             });
         },
         error: function() {
             
         }
     });
}
function simpleEditer()
{
  // $(".tools").show();
  var box = $('.buying-requirements');
  box.attr('contentEditable', true);

  

  // EDITING LISTENERS
  $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
     edit($(this).data('cmd'));
  });
  //var contents = box.html();
  // TOGGLE SOURCE
  // var view_source = true;
  // $('a').on('click', function(e) {
  //   e.preventDefault();
  //   if (view_source) {
  //     view_source = false;
  //     var contents = box.html();
  //     box.empty();
  //     box.append('<textarea></textarea>');
  //     box.find('textarea').val(contents);
  //   } else {
  //     view_source = true;
  //     var contents = box.find('textarea').val();
  //     box.empty();
  //     box.html(contents);
  //   }
  // });
}

function inactiveSimpleEditer()
{
  $(".tools").hide();
  var box = $('.buying-requirements');
  box.attr('contentEditable', false);
}
  $(document).ready(function() {


    // ================================================
    // compose mail
    $("body").on("click","#compose_mail_box",function(e){
      fn_open_compose_mail_box_view();
    });

    $("body").on("click","#compose_submit_confirm",function(e){   

        var base_URL = $("#base_url").val();  
        var mail_to=$("#mail_to").val();  
        var mail_to_cc=$("#mail_to_cc").val(); 
        var mail_subject=$("#mail_subject").val();
        var box = $('.buying-requirements');
        var email_body = box.html();       
       $('#compose_email_body').val(email_body);      

       if(mail_to=='')
       {
           swal("Oops", "Please specify a recipient.", "error");
           return false;
       }
       else
       {
          if(is_email_validate(mail_to)==false)
          {       
            swal("Oops", "Please enter a valid email.", "error");
            return false;
          } 
       }

       if(mail_subject=='')
       {
           swal("Oops", "Please enter a subject.", "error");
           return false;
       }
       

       if(email_body=='')
       {
           swal("Oops", "Please enter mail body", "error");
           return false;
       }
       
       $.ajax({                
           url: base_URL+"lead/gmail_compose_mail",
           data: new FormData($('#compose_mail_frm')[0]),
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
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
           success:function(res){ 
              result = $.parseJSON(res);          
              
              if(result.status=='success')
              {
                swal({
                    title: "Success!",
                    text: "Mail successfully sent",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, send it!",
                    closeOnConfirm: true
                }, function () {
                   $('#ComposePopupModal').modal('hide');
                });                    
              }
              else
              {
                   //swal('Fail!', 'Bulk mails fail to send.', 'error');
              }
                                 
          },         
          error: function(response) {}
      }); 
    });
    // compose mail
    // ================================================


    //////////////////////////
    $('.search-button-over').click(function(event){
        event.preventDefault();
        //$(this).parent().toggleClass('open');
        console.log('ssss');
        $(this).parent().parent().addClass('open');
        $(this).parent().parent().find('input').focus();
    });
    $('.close-search').click(function(event){
        event.preventDefault();
        // console.log('ssss');
        $(this).parent().parent().removeClass('open');
        $(this).parent().parent().find('input').val('');
        if ($('.advance-search i').hasClass("fa-caret-up")) {
          $('.advance-search i').removeClass("fa-caret-up").addClass("fa-caret-down");
          $('.advance-search-holder').slideUp(10);
        }

        if($("#filter_by_str").val()!='' || $("#filter_by_from").val()!='' || $("#filter_by_to").val()!='' || $("#filter_by_date").val()!='' || $("#filter_by_date_to").val()!='' || $("#filter_by_subject").val()!='')
        {
          $("#filter_by_str").val('');
          $("#filter_by_from").val('');
          $("#filter_by_to").val('');
          $("#filter_by_subject").val('');
          $("#filter_by_date").val('');
          $("#filter_by_date_to").val('');
          $("#current_page_no").val('1');
          load();
        }
        

    });
    // $('.search-button').click(function(event){
    //     event.preventDefault();
    //     var getval = $(this).parent().parent().find('input').val();
    //     alert(getval);
    // });
    $('.advance-search').click(function(event){
        event.preventDefault();
        $(this).toggleClass('on');
        if ($(this).find('i').hasClass("fa-caret-down")) {
          $(this).find('i').removeClass("fa-caret-down").addClass("fa-caret-up");
        }else{
          $(this).find('i').removeClass("fa-caret-up").addClass("fa-caret-down")
        }
        $('.advance-search-holder').slideToggle('fast');
    });
    /////////////////////////
    
      //$('.tooltip').tooltipster();
      //attachmentModal
      $("body").on("click",".attachment_download",function(e){
        //e.preventDefault();
        var file_path=$(this).attr('data-content');
        var base_url=$("#base_url").val();        
        window.location.href=base_url+"lead/download_gmail_attachment/"+file_path;
        // var getit = $(this).attr('data-content');
        // $('#attachmentModal #attach-holder').html('<img src="'+getit+'">');
        // $('#attachmentModal').modal('show');
      });
      //tooltip-new
      $('.tooltip-new').tooltipster({
        theme: 'tooltipster-shadow'
      });
      $('.tooltip').tooltipster({
        content: 'Loading...',
        updateAnimation: false,
        theme: 'tooltipster-shadow',
        //trigger: 'click',
        functionBefore: function(instance, helper) {
          
          var $origin = $(helper.origin);
          
          if ($origin.data('ajax') !== 'cached') {
            var hh = `<div class="pop-outer">
                      <div class="pop-top">
                        <figure></figure>
                        <figcaption>
                          <h2>Flipkart</h2>
                          <p>info@flipkart.com</p>
                        </figcaption>
                      </div>
                    </div>`;
            instance.content($(hh));
            
            $origin.data('ajax', 'cached');
          }
        },
        functionAfter: function(instance) {
          //alert('The tooltip has closed!');
        }
    });
    /////////////////////////////////////////////////////
    $(".user_all_checkbox").change(function () {
      $('.dropdown_new .check-box-sec').removeClass('same-checked');
      //alert(1);
      if($(this).prop("checked") == true){
        //$('#dropdownMenuUser').html('All');
        showOption();
        checkAll();
      }else{
        //$('#dropdownMenuUser').html('None');
        hideOption()
      }
      $('input:checkbox[name=gmail_overview_id]').prop('checked', $(this).prop("checked"));
          var base_url_root=$("#base_url_root").val();
          var flag_read=0;
          $("input:checkbox[name=gmail_overview_id]:checked").each(function(){
              
              if($(this).attr('data-currstatus')=='N'){
                flag_read++;
              }     
          });
          
          if(flag_read>0)
          {
            var title='Mark as read';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/drafts_white.png">');
             $("#selected_seen_status_change").attr('data-curstatus','N');
          }
          else
          {
            var title='Mark as unread';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/mark_as_unread_white.png">');
            $("#selected_seen_status_change").attr('data-curstatus','Y');
          }
          $("#selected_seen_status_change").attr('title',title);
    });
    

    $("body").on("click",".cAll",function(e){
      e.preventDefault();
      showOption();

      $('input:checkbox[name=gmail_overview_id], .user_all_checkbox').prop('checked',true);
          var base_url_root=$("#base_url_root").val();
          var flag_read=0;
          $("input:checkbox[name=gmail_overview_id]:checked").each(function(){
              
              if($(this).attr('data-currstatus')=='N'){
                flag_read++;
              }     
          });
          
          if(flag_read>0)
          {
            var title='Mark as read';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/drafts_white.png">');
             $("#selected_seen_status_change").attr('data-curstatus','N');
          }
          else
          {
            var title='Mark as unread';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/mark_as_unread_white.png">');
            $("#selected_seen_status_change").attr('data-curstatus','Y');
          }
          $("#selected_seen_status_change").attr('title',title);
          checkSelect();
    });
    $("body").on("click",".uAll",function(e){
      e.preventDefault();
      hideOption();      
      $('.dropdown_new .check-box-sec').removeClass('same-checked');
      $('input:checkbox[name=gmail_overview_id], .user_all_checkbox').prop('checked',false);
    });
    //////////////////////////////
    //cRead
    $("body").on("click",".cRead",function(e){
      e.preventDefault();
      //hideOption();
      $('input:checkbox[name=gmail_overview_id]').prop('checked',false);
      var cc = 0;
      $('table.dataTable.new-table-style tbody > tr').each(function( index ) {
        console.log( index + ": " + $( this ).text() );

        if ($(this).hasClass('unread')) {
          cc++;
          $(this).find('input:checkbox[name=gmail_overview_id]').prop('checked',true);
        }
        
      });
      if(cc > 0){
        showOption();
        $('.user_all_checkbox').prop('checked',false);
        $('.dropdown_new .check-box-sec').addClass('same-checked');
      }
    });
    $("body").on("click",".uRead",function(e){
      e.preventDefault();
      //hideOption();
      $('input:checkbox[name=gmail_overview_id]').prop('checked',false);
      var cc = 0;
      $('table.dataTable.new-table-style tbody > tr').each(function( index ) {
        console.log( index + ": " + $( this ).text() );

        if ($(this).hasClass('read')) {
          cc++;
          $(this).find('input:checkbox[name=gmail_overview_id]').prop('checked',true);
        }
        
      });
      if(cc > 0){
        showOption();
        $('.user_all_checkbox').prop('checked',false);
        $('.dropdown_new .check-box-sec').addClass('same-checked');
      }

    });
    /////////////////////////////
    
    //$("input:checkbox[name=gmail_overview_id]").change(function () {
    $("body").on("click","input:checkbox[name=gmail_overview_id]",function(e){
        var base_url_root=$("#base_url_root").val();
        var ddc = $('input:checkbox[name=gmail_overview_id]:checked').length;
        var dd = $('input:checkbox[name=gmail_overview_id]').length;
        
        

        if (ddc > 0) 
        {
          //$('#dropdownMenuUser').html('None');
          showOption();
          if (ddc == dd){
            $('.user_all_checkbox').prop('checked',true);
            $('.dropdown_new .check-box-sec').removeClass('same-checked');
          }else{
            $('.user_all_checkbox').prop('checked',false);
            $('.dropdown_new .check-box-sec').addClass('same-checked');
          }
          
          var flag_read=0;
          $("input:checkbox[name=gmail_overview_id]:checked").each(function(){
              
              if($(this).attr('data-currstatus')=='N'){
                flag_read++;
              }     
          });
          
          if(flag_read>0)
          {
            var title='Mark as read';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/drafts_white.png">');
             $("#selected_seen_status_change").attr('data-curstatus','N');
          }
          else
          {
            var title='Mark as unread';
            $("#selected_seen_status_change").html('<img src="'+base_url_root+'images/mark_as_unread_white.png">');
            $("#selected_seen_status_change").attr('data-curstatus','Y');
          }
          $("#selected_seen_status_change").attr('title',title);
          
        }
        else 
        {
          hideOption()
          $('.user_all_checkbox').prop('checked',false);
          $('.dropdown_new .check-box-sec').removeClass('same-checked');
          
        }
    });
    function showOption(){
      
      
      $('table.dataTable.new-table-style .float-left.refresh-holder').hide();
      $('table.dataTable.new-table-style .float-left.other-holder').show();
      checkSelect();
    }
    function hideOption(){
      console.log('hideOption');
      $('table.dataTable.new-table-style .float-left.refresh-holder').show();
      $('table.dataTable.new-table-style .float-left.other-holder').hide();
      $('table.dataTable.new-table-style tbody > tr').removeClass('td_selected');
      //checkSelect();
    }
    function checkAll(){
      $('table.dataTable.new-table-style tbody > tr').addClass('td_selected')
    }
    function checkSelect(){
      console.log('check......')
      $('table.dataTable.new-table-style tbody > tr').each(function( index ) {
        if ($(this).find('input:checkbox[name=gmail_overview_id]').prop('checked')) {
          //blah blah
          $(this).addClass('td_selected');
        }else{
          $(this).removeClass('td_selected')
        }
        
      });
    }
    /////////////////////////////////////////////////////
    var posHeader = new Array();
var head, tableOffset, tableHeight, posHeaderLen;

function appendHead_bbb() {
  if ($(".lead-mail-table").hasClass("table-fixed")) {
      return;
    } else {
      $(".lead-mail-table").addClass("table-fixed");
      $(".lead-mail-table thead").addClass('header-copy header-fixed');
    }
}
function appendHead() {
  //return;
  $("#table").each(function(index, element) {
    //alert(element.id)

    head = "#" + element.id + " thead";
    tableHeight = $(element).height();
    tableOffset = $(element).offset();

    posHeader[3 * index] = tableOffset.top;
    posHeader[3 * index + 1] = element.id;
    posHeader[3 * index + 2] = tableHeight + posHeader[3 * index];
    var tw =$(element).width();
    /* Add a class to the table to identify the processed table */
    if ($(element).hasClass("table-fixed")) {
      return;
    } else {
      $("#" + element.id).addClass("table-fixed");
    }
    $("#" + element.id + " thead").addClass('header-copy header-fixed');
    //return;
    var headerCopy = $(".header-copy");
    //$("#" + element.id + " thead").clone().addClass('header-copy header-fixed').stop().appendTo("#" + element.id);

    var attributes = $("#" + element.id + " thead").prop("attributes");

    $.each(attributes, function() {
      headerCopy.attr(this.name, this.value);
    });
    var style = [];
    $(element).find('thead > tr:first > th').each(function(i, h) {
      return style.push($(h).width());
    });
    $.each(style, function(i, w) {
      return $(element).find('thead > tr > th:eq(' + i + '), thead.header-copy > tr > th:eq(' + i + ')').css({
        width: w
      });
    });
    //alert(tw);
    $(element).find('thead.header-copy').css({
      margin: '0 auto',
      width: $(element).width(),
      top: tableOffset
    });
    $(element).find('thead.header-copy th').css({
      width: $(element).width()
    });

    posHeaderLen = parseInt(posHeader.length / 3);

  });

}
var xpos = 0;
$(window).scroll(function() {
  scrollAmount = $(window).scrollTop()+56;
  
  for (j = 0; j <= posHeaderLen; j++) {
    posizione = j * 3;

    if (posHeader[posizione] < scrollAmount) {
      //siamo all'interno della tabella
      flag = true;
      //console.log(posHeader[2 + posizione]);
      if (posHeader[2 + posizione] > scrollAmount) {
        //siamo ancora all'interno della tabella
        
        xpos = $("#" + posHeader[1 + posizione]).offset().left;
        console.log(1+': '+xpos);
        $("#" + posHeader[1 + posizione]).find("thead").css('left', xpos);
        $("#" + posHeader[1 + posizione]).addClass("visible");

      } else {
        // siamo arrivati alla fine della tabella
        flag = false;
        //xpos = 0;
        console.log(2+': '+xpos);
        $("#" + posHeader[1 + posizione]).find("thead").css('left', 0);
        $("#" + posHeader[1 + posizione]).removeClass("visible");
      }
    } else {

      flag = false;
      //xpos = 0;
      console.log(3+': '+xpos);
      $("#" + posHeader[1 + posizione]).find("thead").css('left', 0);
      $("#" + posHeader[1 + posizione]).removeClass("visible");
    }
  }
  //var x = $(".table-full-holder-new").offset();
  console.log("xpos: " + xpos);
  // if(flag == true){
  //   xpos = $("#" + posHeader[1 + posizione]).offset().left;
  // }else{
  //   xpos = 0;
  // }
  orizScroll = (-1) * $(window).scrollLeft();

  //$(".header-copy").css('left', xpos);

});

  $(window).resize(function() {
    for (k = 0; k < posHeaderLen; k++) {
      posizione = k * 3;
      tableId = "#" + posHeader[1 + posizione];

      var headerCopy = $(tableId + " .header-copy");
      var attributes = $(tableId + " thead").prop("attributes");

      $.each(attributes, function() {
        headerCopy.attr(this.name, this.value);
      });
      var style = [];
      $(tableId).find('thead > tr:first > th').each(function(i, h) {
        return style.push($(h).width());
      });
      $.each(style, function(i, w) {
        return $(tableId).find('thead > tr > th:eq(' + i + '), thead.header-copy > tr > th:eq(' + i + ')').css({
          width: w
        });
      });
      $(tableId).find('thead.header-copy').css({
        margin: '0 auto',
        width: $(tableId).width(),
        top: tableOffset
      });
    }
  });
    /*
    $(document).bind("DOMSubtreeModified", function(){
    appendHead();
    });*/

    appendHead();
    /////////////////////////////////////////////////////
  });
</script>
