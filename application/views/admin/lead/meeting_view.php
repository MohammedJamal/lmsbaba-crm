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
                                <h4><a href="<?php echo base_url() ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/user/manage_employee">Manage Meeting</a> </h4>
                            </div>
                            </div>
                            <div class="col-sm-9 pleft_0">
                                <div class="bg_white_filt">              
                                    <ul class="filter_ul">
                                       <li>
                                          <a href="JavaScript:void(0);" id="" class="new_filter_btn meeting_report" data-leadid="" data-date="" data-date2="" data-user_id="">
                                             <span class="bg_span"><img src="<?=assets_url();?>images/meeting_report.svg"></span> Report
                                          </a>
                                       </li> 
                                       <li>
                                          <a href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/manage" class="new_filter_btn">
                                             <span class="bg_span"><img src="<?=assets_url();?>images/left_black.svg"></span> Back
                                          </a>
                                       </li>                                                
                                    </ul>                  
                                </div>
                            </div>
                        </div>
                        <div class="card process-sec">
                            <div class="card-block">
                                <div class="cal-hold">                    
                                    <div id="calendar" class="default-calendar"></div>   
                                </div>
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
   <div class="modal fade schedule-modal" id="MeetingDetailEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true">
      
   </div>

   
<!-- Jquery datetime picker -->
<script src="<?php echo assets_url(); ?>js/jquery.datetimepicker.full.min.js" ></script>
<link rel="stylesheet" href="<?php echo assets_url(); ?>css/jquery.datetimepicker.css"  />
<!-- Jquery datetime picker -->

   <!-- <script src="<?php echo assets_url(); ?>js/calendar.js" type="text/javascript"></script> -->
   <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>css/timepicki.css">   
   <link href='<?php echo assets_url(); ?>css/calendar.css' rel='stylesheet' />
   <link href='<?php echo assets_url(); ?>css/calendar_main.css' rel='stylesheet' /> 
   <script src='<?php echo assets_url(); ?>js/calendar_main.js'></script>
   <script src="<?php echo assets_url(); ?>js/timepicki.js"></script>
   <script type="text/javascript">
      var eventDetails = <?php echo $meeting_list_obj; ?>;
      //var eventDetails = [];
      //var events = [];
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
         height: '100%',
         expandRows: true,
         slotMinTime: '08:00',
         slotMaxTime: '20:00',
         headerToolbar: {
         left: 'prev,next today',
         center: 'title',
         right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
         },
         initialView: 'dayGridMonth',
         navLinks: true, // can click day/week names to navigate views
         editable: false,
         selectable: true,
         nowIndicator: true,
         dayMaxEvents: true, // allow "more" link when too many events
         //events: eventDetails,
         eventClick: function(calEvent) {  
         //////////
         var base_url=$("#base_url").val();
         var id=calEvent.event.id;
         var mStatus = calEvent.event.extendedProps.status;         
         open_meeting_detail_popup(id,mStatus);        
      },
      events: function(info, successCallback, failureCallback ) {
         successCallback(eventDetails);
      },
      eventDidMount: function (info) {}      
      });

      function ShowCalendar() {
         calendar.render();
      }

      function open_meeting_detail_popup(id,status){
         var base_url=$("#base_url").val();
         
         var data="m_id="+id;
         
         $.ajax({
            url: base_url + "lead/meeting_detail_with_edit_view_popup_rander_ajax",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function(xhr) {
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
            complete: function() {
               $.unblockUI();
            },
            success: function(res) {
               result=$.parseJSON(res);               
               $('#MeetingDetailEditModal').html(result.html); 
               $('#MeetingDetailEditModal').modal('show');  
               $('.time_element').timepicki();        
                           
            },              
            error: function(response) {}
         });
      }

      rander_calendar_view = function(){
            var base_url=$("#base_url").val();
            var data="";            
            $.ajax({
               url: base_url + "lead/rander_calendar_view_ajax",
               data: data,
               cache: false,
               method: 'POST',
               dataType: "html",
               beforeSend: function(xhr) {
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
               complete: function() {
                  $.unblockUI();
               },
               success: function(res) {
                  result=$.parseJSON(res); 

                  var jsonObj = JSON.parse(result.meeting_list_obj);
                  //var jsonPretty = JSON.stringify(jsonObj, null, '\t');
                  // console.log('==============EVENT START================');   
                  // console.log(jsonObj);     
                  // console.log('==============EVENT END================');     
                  ////////////
                  eventDetails = [];
                  eventDetails.length = 0;
                  eventDetails = jsonObj;
                  calendar.refetchEvents();
                  // console.log('meeting updated.....')
                  //calendar.remove()
                  ////////////
               },              
               error: function(response) {}
            });
      }

      
    $(document).ready(function(){  
      

      rander_calendar_view();
      ShowCalendar();
      
      $("body").on("click",".meeting_schedule_view_popup",function(e){
         e.preventDefault();       
         var lead_id=$(this).attr("data-leadid");
         var c_id=$(this).attr("data-cid");
         var m_id=$(this).attr("id");
         // alert(m_id)
         $('#MeetingDetailEditModal').css('display','none');
         meeting_schedule_view_popup(lead_id,c_id,m_id);           
      });
      $('#scheduleMeetingModal').on('hide.bs.modal', function (event) {
         $('#MeetingDetailEditModal').css('display','block');             
      });

      $('#MeetingDetailEditModal').on('hide.bs.modal', function (event) {
         // location.reload(true);
         rander_calendar_view();
      });

      //mCancelledPoints
      $(document).on("click",".mCancelledPoints",function(event) {
        event.preventDefault();
        $('.mCancelledPointsDetails').toggleClass('show');
      });
      //mPoints
      $(document).on("click",".mPoints",function(event) {
        event.preventDefault();
        $('.mPointsDetails').toggleClass('show');
      });
      //update-meeting
      $(document).on("click",".update-meeting",function(event) {
        event.preventDefault();
        $('.cancel-meeting-block').removeClass('show');
        $('.update-meeting-block').toggleClass('show');
      });
      //
      // ===========================================================================
      // Cancelled
      $(document).on("click",".cancel-meeting",function(event) {
        event.preventDefault();
        $('.update-meeting-block').removeClass('show');
        //alert-md
        $('.alert-md').removeClass('alert-danger');
        $('.cancel-meeting-block').toggleClass('show');
      });
      //cancel-meeting-btn
      $(document).on("click","#meeting_cancelled_confirm",function(event) {
         event.preventDefault();
         var id=$("#id").val();              
         var base_url=$("#base_url").val();
         $.ajax({ 
            url: base_url+"lead/meeting_cancelled_ajax/",
            data: new FormData($('#MeetingFrm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(xhr) {
               $('#MeetingDetailEditModal .modal-body').addClass('logo-loader');
            },
            complete: function() {
               $('#MeetingDetailEditModal .modal-body').removeClass('logo-loader');
            },
            success: function(res) {
               result=$.parseJSON(res); 
               // rander_calendar_view();                 
               if(result.status=='success'){
                  swal({
                  title: "Success",
                  text: result.msg,
                  type: "success",
                  confirmButtonText: "ok",
                  allowOutsideClick: "false"
                  }, function() {  
                     //location.reload(true);                                      
                     //open_meeting_detail_popup(id,result.mstatus);
                     $("#MeetingDetailEditModal").modal('hide');
                  });
               }   
               else if(result.status=='error'){
                  swal('Oops!',result.msg,'error');
               }          
            },              
            error: function(response) {}
         });
         //   $('.alert-md').html('Cancelled').removeClass('alert-primary').addClass('alert-danger');
         //   $('.cancel-meeting-block').removeClass('show');
      });
      // Cancelled
      // ===========================================================================
      $(document).on('hidden.bs.modal', '.modal', function () {
         $('.modal:visible').length && $(document.body).addClass('modal-open');
      });
      // ===========================================================================
      // Finished
      $(document).on("click","#meeting_finished_confirm",function(event) {
         event.preventDefault();
         var id=$("#id").val();              
         var base_url=$("#base_url").val();
         var visited_colleagues_arr=[];        
         $('#visited_colleagues option:selected').each(function() {
            visited_colleagues_arr.push($(this).val());            
         });
         // alert(visited_colleagues_arr); return false;
         $('#MeetingFrm').append('<input type="hidden" name="visited_colleagues_selected" value="'+visited_colleagues_arr+'" />');    
         $.ajax({ 
            url: base_url+"lead/meeting_finished_ajax/",
            data: new FormData($('#MeetingFrm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(xhr) {
               $('#MeetingDetailEditModal .modal-body').addClass('logo-loader');
            },
            complete: function() {
               $('#MeetingDetailEditModal .modal-body').removeClass('logo-loader');
            },
            success: function(res) {
               result=$.parseJSON(res);                  
               if(result.status=='success'){
                  swal({
                  title: "Success",
                  text: result.msg,
                  type: "success",
                  confirmButtonText: "ok",
                  allowOutsideClick: "false"
                  }, function() {  
                     // location.reload(true);                                      
                     //open_meeting_detail_popup(id,result.mstatus);
                     $("#MeetingDetailEditModal").modal('hide');
                  });
               }   
               else if(result.status=='error'){
                  swal('Oops!',result.msg,'error');
               }          
            },              
            error: function(response) {}
         });
         //   $('.alert-md').html('Cancelled').removeClass('alert-primary').addClass('alert-danger');
         //   $('.cancel-meeting-block').removeClass('show');
      });
      // Finished
      // ============================================================================

      ///m-edit
      $(document).on("click",".m-edit",function(event) {
         event.preventDefault();          
         
         
         var field_name=$(this).parent().parent().find('input').attr('name');   
         var save_div='<div id="save_html_div"><a href="JavaScript:void(0);" class="update_field" data-field="'+field_name+'"><i class="fa fa-floppy-o" aria-hidden="true"></i></a></div>' ;

         if($(this).parent().parent().find('input').is(':disabled')){   
            
            
            $(".m-edit").parent().parent().find('input').attr('disabled', true);
            $("#save_html_div").remove();
            $(".m-edit").html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');

            $(this).parent().parent().find('input').attr('disabled', false);
            $(this).before(save_div);
            $(this).html('<i class="fa fa-times" aria-hidden="true"></i></a>');
         }else{
            var existing_field_value=$(this).parent().parent().find('input').attr('data-existing_value');
            $("#existing_value").val('');
            $(this).parent().parent().find('input').attr('disabled', true);
            $("#save_html_div").remove();
            $(this).html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
            if(field_name=='meeting_schedule_start_time'){
               var existing_v=$("#meeting_schedule_start_time").attr('data-existing_value');
               var existing_v2=$("#meeting_schedule_end_time").attr('data-existing_value');
               $("#meeting_schedule_start_time").val(existing_v);
               $("#meeting_schedule_end_time").val(existing_v2);
            }
            else{
               $(this).parent().parent().find('input').val(existing_field_value);
            }
         }
      });
      $("body").on("click",".update_field",function(e){
         var id=$("#id").val();
         var field_name=$(this).attr("data-field");     
         var field_value=$("#"+field_name).val();        
         var base_url=$("#base_url").val();
         // var data="id="+id+"&field_name="+field_name+"&field_value="+field_value;  
         // alert(data); return false; 
         $('#MeetingFrm').append('<input type="hidden" name="field_name" value="'+field_name+'" />');      
         $.ajax({ 
            url: base_url+"lead/meeting_single_field_edit_ajax/",
            data: new FormData($('#MeetingFrm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(xhr) {
               
            },
            complete: function() {
               
            },
            success: function(res) {
               result=$.parseJSON(res);  
                
               if(result.status=='success'){
                  swal({
                  title: "Success",
                  text: 'Record successfully updated.',
                  type: "success",
                  confirmButtonText: "ok",
                  allowOutsideClick: "false"
                  }, function() {
                     
                     open_meeting_detail_popup(id,result.mstatus);
                     // $("#"+field_name).attr('data-existing_value',field_value);
                     // $(".m-edit").parent().parent().find('input').attr('disabled', true);
                     // $("#save_html_div").remove();
                     // $(".m-edit").html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
                  });
               }   
               else if(result.status=='error'){
                  swal('Oops!',result.msg,'error');
               }          
            },              
            error: function(response) {}
         });
      });
      //////
      $( ".follow-date" ).datepicker();
      $(".follow-time").timepicki({
         step_size_minutes:5,
         on_change : function(ct){
            //var getval = $(ct).val();      
         }
      });
    });


   //  ==================================================================================
   /*
    $("body").on("click","#meeting_report",function(e){
      var base_url = $("#base_url").val();       
      $.ajax({
         url: base_url + "lead/rander_meeting_report_view_popup_ajax",
         type: "POST",
         data: {},
         async: false,
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
               $('#meetingReport').html(response); 
               $('#meetingReport').modal({
                     backdrop: 'static',
                     keyboard: false
               });
         },
         error: function() {
            
         }
      });


    });
    
    
    $("body").on("click",".sort_order_meeting_report",function(e){
        var tmp_field=$(this).attr('data-field');
        var curr_orderby=$(this).attr('data-orderby');
        var new_orderby=(curr_orderby=='asc')?'desc':'asc';
        $(this).attr('data-orderby',new_orderby);
        $(".sort_order").removeClass('asc');
        $(".sort_order").removeClass('desc');
        $(this).addClass(curr_orderby);
        $("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
        rander_meeting_report();
    });
    $(document).on('click', '.meeting_pagination_class', function (e) { 
		e.preventDefault();
		var str = $(this).attr('href'); 
		var res = str.split("/");
		var cur_page = res[1];
		$("#page_number").val(cur_page);        
		rander_meeting_report();
	});
    function rander_meeting_report()
   {
      var base_URL = $("#base_url").val();
      var page=$("#page_number").val(); 
      var filter_sort_by=$("#filter_sort_by").val();
      var filter_by_keyword=$("#filter_by_keyword").val();
      var filter_by_user_id=$("#filter_by_user_id").val();
      var filter_by_status_id=$("#filter_by_status_id").val();
      var filter_by_meeting_type=$("#filter_by_meeting_type").val();
      var filter_by_meeting_agenda_type_id=$("#filter_by_meeting_agenda_type_id").val();
      var filter_by_self_visited_or_visited_with_colleagues=$("#filter_by_self_visited_or_visited_with_colleagues").val();
      var filter_by_start_date=$("#filter_by_start_date").val();
      var filter_by_end_date=$("#filter_by_end_date").val();
      var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&filter_by_keyword="+filter_by_keyword+"&filter_by_user_id="+filter_by_user_id+"&filter_by_status_id="+filter_by_status_id+"&filter_by_meeting_type="+filter_by_meeting_type+"&filter_by_meeting_agenda_type_id="+filter_by_meeting_agenda_type_id+"&filter_by_self_visited_or_visited_with_colleagues="+filter_by_self_visited_or_visited_with_colleagues+"&filter_by_start_date="+filter_by_start_date+"&filter_by_end_date="+filter_by_end_date;
      // alert(data)
      $.ajax({
         url: base_URL+"lead/rander_meeting_report_list_ajax/"+page,
         data: data,
         cache: false,
         method: 'GET',
         dataType: "html",
         beforeSend: function( xhr ) {                
               // $.blockUI({ 
               //     message: 'Please wait...', 
               //     css: { 
               //             padding: '10px', 
               //             backgroundColor: '#fff', 
               //             border:'0px solid #000',
               //             '-webkit-border-radius': '10px', 
               //             '-moz-border-radius': '10px', 
               //             opacity: .5, 
               //             color: '#000',
               //             width:'450px',
               //             'font-size':'14px'
               //     }
               // });
               addLoader('#tcontent');
         },
         complete: function(){
               // $.unblockUI();
               removeLoader();
         },
         success:function(res){ 
               result = $.parseJSON(res);
               // alert(result.status)
               if(result.status!='success'){
                  swal('Oops!',result.msg,'error');
               }
               $("#tcontent").html(result.table);
               $("#page").html(result.page);
               $("#page_record_count_info").html(result.page_record_count_info);
               
      },           
      error: function(response) {
         //alert('Error'+response.table);
         }
      });
   }

   $(document).on("click","#download_meeting_report_csv",function (e){
        
      var base_URL     = $("#base_url").val(); 
      var filter_sort_by=$("#filter_sort_by").val();
      var filter_by_keyword=$("#filter_by_keyword").val();
      var filter_by_user_id=$("#filter_by_user_id").val();
      var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&filter_by_keyword="+filter_by_keyword+"&filter_by_user_id="+filter_by_user_id;
      document.location.href = base_URL+'lead/download_meeting_report_csv/?'+data;
    });

    $("body").on("click","#meeting_report_search_confirm",function(e){
        var by_keyword=$("#by_keyword").val();
        var by_user_id=$("#by_user_id").val();
        var by_status_id=$("#by_status_id").val();
        var by_meeting_type=$("#by_meeting_type").val();
        var by_meeting_agenda_type_id=$("#by_meeting_agenda_type_id").val();
        var by_self_visited_or_visited_with_colleagues=$("#by_self_visited_or_visited_with_colleagues").val();
        var by_start_date=$("#by_start_date").val();
        var by_end_date=$("#by_end_date").val();

        $("#filter_by_keyword").val(by_keyword);
        $("#filter_by_user_id").val(by_user_id);
        $("#filter_by_status_id").val(by_status_id);
        $("#filter_by_meeting_type").val(by_meeting_type);
        $("#filter_by_meeting_agenda_type_id").val(by_meeting_agenda_type_id);
        $("#filter_by_self_visited_or_visited_with_colleagues").val(by_self_visited_or_visited_with_colleagues);
        $("#filter_by_start_date").val(by_start_date);
        $("#filter_by_end_date").val(by_end_date);
        rander_meeting_report();
    });
    */
    // ===================================================================================
    
    </script>
  </body>
</html>