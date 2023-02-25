$(document).ready(function(){ 
  
  var assets_base_url = $("#assets_base_url").val();
  $('.al_filter_display_date').datepicker({
    // showOn: "both",
    dateFormat: "dd-M-yy",
    changeMonth: true,
    changeYear: true,
    yearRange: '-100:+5',
    // buttonImage: assets_base_url+"images/cal-icon.png",
    // buttonImageOnly: true,
    maxDate: 0,
  });

  document.getElementById("defaultOpen").click(); 

  $("#rander_common_view_modal_lg").on('hide.bs.modal', function(){
    
      var listing_from=$("#listing_from").val();
     
      if(listing_from=='ps')
      {
        var month=$("#ps_month").find(":selected").val();
        var uid=$("#ps_user").find(":selected").val();
        var kpi_setting_id=$("#ps_user").find(":selected").attr("data-kpi_setting_id");    
        load_performance_scorecard(month,uid,kpi_setting_id);        
      }
      
      if(listing_from=='mps')
      {
        var uid=$("#mps_user").find(":selected").val();
        var kpi_setting_id=$("#mps_user").find(":selected").attr("data-kpi_setting_id");
        load_my_performance_scorecard(uid,kpi_setting_id);
      }
  });

  $("body").on("change","#ps_user",function(e){    
    // var uid=$(this).val();
    // var kpi_setting_id=$(this).find(":selected").attr("data-kpi_setting_id");
    // load_performance_scorecard(uid,kpi_setting_id);

    var month=$("#ps_month").find(":selected").val();
    var uid=$("#ps_user").find(":selected").val();
    var kpi_setting_id=$("#ps_user").find(":selected").attr("data-kpi_setting_id");    
    load_performance_scorecard(month,uid,kpi_setting_id);

  });

  $("body").on("change","#ps_month",function(e){  
    var month=$("#ps_month").find(":selected").val();
    var uid=$("#ps_user").find(":selected").val();
    var kpi_setting_id=$("#ps_user").find(":selected").attr("data-kpi_setting_id");    
    load_performance_scorecard(month,uid,kpi_setting_id);

  });

  $("body").on("click",".get_google_map",function(e){    
    e.preventDefault();      
    var target_url=$(this).attr("href");
    var lead_title=$(this).attr("data-title");
    $.modalLink.open(target_url, {
        title: (lead_title)?lead_title:"Map"
    });
  });
  $("body").on("click","#al_filter_btn",function(e){
    var al_user=$("#al_user").val();
    var al_date_from=$("#al_date_from").val();
    var al_date_to=$("#al_date_to").val();

    $("#filter_al_user").val(al_user);
    $("#filter_al_date_from").val(al_date_from);
    $("#filter_al_date_to").val(al_date_to);
    load_activity_log();
  });

  $("body").on("click","#lt_filter_btn",function(e){
    var lt_user=$("#lt_user").val();
    var lt_date_from=$("#lt_date_from").val();
    var lt_date_to=$("#lt_date_to").val();

    $("#filter_lt_user").val(lt_user);
    $("#filter_lt_date_from").val(lt_date_from);
    $("#filter_lt_date_to").val(lt_date_to);
    load_location_tracking();
  });

  $("body").on("click",".user_activity_log_breakup_view",function(e){
      var base_URL=$("#base_url").val();
      var user_id=$(this).attr('data-userid');
      var date=$(this).attr('data-date');
      var displaydate=$(this).attr('data-displaydate');
      var day=$(this).attr('data-day');
      var user_name=$(this).attr('data-username');
      var data = "user_id="+user_id+"&date="+date;
      $.ajax({
          url: base_URL+"my_performance/rander_user_wise_activity_log_breakup_list_ajax/",
          data: data,
          cache: false,
          method: 'GET',
          dataType: "html",
          beforeSend: function( xhr ) {                
              //addLoader('.table-responsive');
          },
          success:function(res){ 
              result = $.parseJSON(res);
              //$(".preloader").hide();               
              // $("body, html").animate({ scrollTop: 500 }, "slow");
              $("#activityLogBreakUpViewModalTitle").html('Activity Log Details on '+displaydate+' ('+day+') for '+user_name+'( ID:-'+user_id+' )');   
              $("#activityLogBreakUpViewModalBody").html(result.html);
              $('#activityLogBreakUpViewModal').modal({
                backdrop: 'static',
                keyboard: false
              });              
          },
          complete: function(){
          //removeLoader();
          },
          error: function(response) {
          //alert('Error'+response.table);
          }
      });
  });


  $("body").on("change","#mps_user",function(e){    
    var uid=$(this).val();
    var kpi_setting_id=$(this).find(":selected").attr("data-kpi_setting_id");
    load_my_performance_scorecard(uid,kpi_setting_id);
  });

  $("body").on("click",".target_achieved_edit",function(e){
    var id=$(this).attr("data-id");
    $("#target_achieved_"+id).hide();
    $("#target_achieved_edit_"+id).hide();
    $("#target_achieved_edit_view_"+id).show();
    
  });

  $("body").on("click",".target_achieved_close",function(e){
    var id=$(this).attr("data-id");
    $("#target_achieved_"+id).show();
    $("#target_achieved_edit_"+id).show();
    $("#target_achieved_edit_view_"+id).hide();    
  });

  $("body").on("click",".target_achieved_save",function(e){
    var id=$(this).attr("data-id");
    var target_achieved=$("#target_achieved_value_"+id).val();
    var kpi_setting_id=$(this).attr("data-kpi_setting_id");
    var kpi_user_id=$(this).attr("data-user_id");
    
    var base_URL=$("#base_url").val();    
    var data = "id="+id+"&target_achieved="+target_achieved;
    // alert(data);return false;
    $.ajax({
        url: base_URL+"my_performance/target_achieved_save_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result = $.parseJSON(res);
            //$(".preloader").hide();               
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
            if(result.status=='success')
            {              
              swal({
                title: "Success!",
                text: "The record has been saved",
                  type: "success",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
              }, function () { 

                load_my_performance_scorecard(kpi_user_id,kpi_setting_id)
                // $("#target_achieved_"+id).show();
                // $("#target_achieved_edit_"+id).show();
                // $("#target_achieved_edit_view_"+id).hide();
              });
            }             
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
       
  });

  $("body").on("click",".report_detail_view",function(e){
    
    var id=$(this).attr('data-id');
    var listing_from=$(this).attr('data-listing_from');
    report_detail_view_popup(id,listing_from);
    // var data = "id="+id;
    // $.ajax({
    //     url: base_URL+"my_performance/report_detail_view_ajax/",
    //     data: data,
    //     cache: false,
    //     method: 'GET',
    //     dataType: "html",
    //     beforeSend: function( xhr ) {                
    //         //addLoader('.table-responsive');
    //     },
    //     success:function(res){ 
    //         result = $.parseJSON(res);
    //         //$(".preloader").hide();               
    //         // $("body, html").animate({ scrollTop: 500 }, "slow");
    //         $("#common_view_modal_title_lg").html('');   
    //         $("#rander_common_view_modal_html_lg").html(result.html);
    //         $('#rander_common_view_modal_lg').modal({
    //           backdrop: 'static',
    //           keyboard: false
    //         });              
    //     },
    //     complete: function(){
    //     //removeLoader();
    //     },
    //     error: function(response) {
    //     //alert('Error'+response.table);
    //     }
    // });
});

  $("body").on("change","#grace_score",function(e){
    var base_URL=$("#base_url").val();
    var grace_score=$(this).val();
    var kpi_user_wise_report_log_id=$("#kpi_user_wise_report_log_id").val();
    // alert(grace_score+'/'+kpi_user_wise_report_log_id)
    var data = "kpi_user_wise_report_log_id="+kpi_user_wise_report_log_id+"&grace_score="+grace_score;
    $.ajax({
        url: base_URL+"my_performance/update_grace_score_in_report_log_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result = $.parseJSON(res);
            $("#total_score_obtained_after_grace").val(result.total_score);
            $(".pli_earned").text(result.pli_earned);
                      
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });   
    
  });

  $("body").on("click","#approve_the_report_confirm",function(e){
    var base_URL=$("#base_url").val();
    var listing_from=$("#listing_from").val();
    var kpi_user_wise_report_log_id=$("#kpi_user_wise_report_log_id").val();
    // var comment_on_approved=$("#comment_on_approved").val();
    // var data = "kpi_user_wise_report_log_id="+kpi_user_wise_report_log_id+"&comment_on_approved="+comment_on_approved;
    $.ajax({
        url: base_URL + "my_performance/approve_kpi_user_wise_report_log",
        data: new FormData($('#frm_report_log')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function( xhr ) {                
          $('#rander_common_view_modal_lg .modal-body').addClass('logo-loader');
        },
        complete: function (){
          $('#rander_common_view_modal_lg .modal-body').removeClass('logo-loader');
        },
        success:function(res){ 
            result = $.parseJSON(res);            
            if(result.status=='error')
            {
              swal('Oops!',result.msg,'error');
            }
            else
            {
              swal({
                title: "Success!",
                text: result.msg,
                  type: "success",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
              }, function () { 
                // $(".report_target_achieved_edit").remove();
                // $("#grace_score").attr("disabled","disabled");            
                // $("#approved_html").html(result.approved_html);
                report_detail_view_popup(kpi_user_wise_report_log_id,listing_from);
              });
            }       
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
  });

  $("body").on("click","#self_approve_the_report_confirm",function(e){
    var base_URL=$("#base_url").val();
    var listing_from=$("#listing_from").val();
    var kpi_user_wise_report_log_id=$("#kpi_user_wise_report_log_id").val();
    $.ajax({
      url: base_URL + "my_performance/self_approve_kpi_user_wise_report_log",
      data: new FormData($('#frm_report_log')[0]),
      cache: false,
      method: 'POST',
      dataType: "html",
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function(xhr) {         
          $('#rander_common_view_modal_lg .modal-body').addClass('logo-loader');
      },
      complete: function (){
          $('#rander_common_view_modal_lg .modal-body').removeClass('logo-loader');
      },
      success: function(data) {
          result = $.parseJSON(data);
          if(result.status=='error')
          {
            swal('Oops!',result.msg,'error');
          }
          else
          {
            swal({
              title: "Success!",
              text: result.msg,
                type: "success",
              confirmButtonText: "ok",
              allowOutsideClick: "false"
            }, function () { 

              report_detail_view_popup(kpi_user_wise_report_log_id,listing_from);
            });
          }
      }
    });


    // var kpi_user_wise_report_log_id=$("#kpi_user_wise_report_log_id").val();
    // var data = "kpi_user_wise_report_log_id="+kpi_user_wise_report_log_id;
    // $.ajax({
    //     url: base_URL+"my_performance/approve_kpi_user_wise_report_log",
    //     data: data,
    //     cache: false,
    //     method: 'GET',
    //     dataType: "html",
    //     beforeSend: function( xhr ) {                
    //         //addLoader('.table-responsive');
    //     },
    //     success:function(res){ 
    //         result = $.parseJSON(res);
    //         $(".report_target_achieved_edit").remove();
    //         $("#grace_score").attr("disabled","disabled");            
    //         $("#approved_html").html(result.approved_html);
                      
    //     },
    //     complete: function(){
    //     },
    //     error: function(response) {
    //     }
    // });
  });

  // ------------------------------------------------------------------
    // Report Details
    $("body").on("click",".report_target_achieved_edit",function(e){
      var id=$(this).attr("data-id");
      $("#report_target_achieved_"+id).hide();
      $("#report_target_achieved_edit_"+id).hide();
      $("#report_target_achieved_edit_view_"+id).show();
      
    });
  
    $("body").on("click",".report_target_achieved_close",function(e){
      var id=$(this).attr("data-id");
      $("#report_target_achieved_"+id).show();
      $("#report_target_achieved_edit_"+id).show();
      $("#report_target_achieved_edit_view_"+id).hide();    
    });
  
    $("body").on("click",".report_target_achieved_save",function(e){
      var listing_from=$("#listing_from").val();
      var id=$(this).attr("data-id");
      var kpi_user_wise_report_log_id=$("#kpi_user_wise_report_log_id").val();
      var target_achieved=$("#report_target_achieved_value_"+id).val();
      var kpi_setting_id=$(this).attr("data-kpi_setting_id");
      var kpi_user_id=$(this).attr("data-user_id");
      
      var base_URL=$("#base_url").val();    
      var data = "id="+id+"&target_achieved="+target_achieved+"&kpi_user_wise_report_log_id="+kpi_user_wise_report_log_id;
      // alert(data);return false;
      $.ajax({
          url: base_URL+"my_performance/report_target_achieved_save_ajax/",
          data: data,
          cache: false,
          method: 'GET',
          dataType: "html",
          beforeSend: function( xhr ) {                
              //addLoader('.table-responsive');
          },
          success:function(res){ 
              result = $.parseJSON(res);
              //$(".preloader").hide();               
              // $("body, html").animate({ scrollTop: 500 }, "slow");   
              if(result.status=='success')
              {              
                swal({
                  title: "Success!",
                  text: "The record has been saved",
                    type: "success",
                  confirmButtonText: "ok",
                  allowOutsideClick: "false"
                }, function () { 
  
                  report_detail_view_popup(kpi_user_wise_report_log_id,listing_from);
                  // $("#target_achieved_"+id).show();
                  // $("#target_achieved_edit_"+id).show();
                  // $("#target_achieved_edit_view_"+id).hide();
                });
              }             
          },
          complete: function(){
          //removeLoader();
          },
          error: function(response) {
          //alert('Error'+response.table);
          }
      });
         
    });
    // Report Details
    // ------------------------------------------------------------------
  
});
function openDiv(evt, divName) 
{ 
  $("body, html").animate({ scrollTop: 200 }, "slow");
  var i, tabcontent, tablinks;
  
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  
  document.getElementById(divName).style.display = "block";
  evt.currentTarget.className += " active";
  
  //////////
  if(divName=='tab_1')
  { 
    load_activity_log();
  } 
  if(divName=='tab_2')
  { 
    load_location_tracking();
  }  
  if(divName=='tab_3')
  { 
    var uid=$("#mps_user").find(":selected").val();
    var kpi_setting_id=$("#mps_user").find(":selected").attr("data-kpi_setting_id");
    load_my_performance_scorecard(uid,kpi_setting_id);
  } 

  if(divName=='tab_4')
  { 
    var month=$("#ps_month").find(":selected").val();
    var uid=$("#ps_user").find(":selected").val();
    var kpi_setting_id=$("#ps_user").find(":selected").attr("data-kpi_setting_id");    
    load_performance_scorecard(month,uid,kpi_setting_id);
  } 
}

function load_activity_log()
{
    var base_URL=$("#base_url").val();
    var al_user=$("#filter_al_user").val();
    var al_date_from=$("#filter_al_date_from").val();
    var al_date_to=$("#filter_al_date_to").val();
    var data = "al_user="+al_user+"&al_date_from="+al_date_from+"&al_date_to="+al_date_to;
    // alert(data);// return false;
    $.ajax({
        url: base_URL+"my_performance/rander_activity_log_list_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result = $.parseJSON(res);
            //$(".preloader").hide();               
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
            $("#activity_log_tcontent").html(result.table);
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}

function load_location_tracking()
{
  
    var base_URL=$("#base_url").val();
    var lt_user=$("#filter_lt_user").val();
    var lt_date_from=$("#filter_lt_date_from").val();
    var lt_date_to=$("#filter_lt_date_to").val();
    var data = "lt_user="+lt_user+"&lt_date_from="+lt_date_from+"&lt_date_to="+lt_date_to;
    // alert(data);// return false;
    $.ajax({
        url: base_URL+"my_performance/rander_location_tracking_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
            addLoader('#location_tracking_div');
        },
        success:function(res){ 
            result = $.parseJSON(res);
            //$(".preloader").hide();               
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
            $("#location_tracking_div").html(result.html);
        },
        complete: function(){
          removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}

function load_my_performance_scorecard(uid='',kpi_setting_id='')
{
    var base_URL=$("#base_url").val();    
    var data = "user_id="+uid+"&kpi_setting_id="+kpi_setting_id;
    // alert(data);// return false;
    $.ajax({
        url: base_URL+"my_performance/rander_my_performance_scorecard_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result=$.parseJSON(res);
            // $("body, html").animate({ scrollTop: 500 }, "slow");  
            if(uid)
            {
              $("#my_performance_scorecard_html").html(result.html);
            } 
            else
            {
              $("#my_performance_scorecard_html").html('<br><br>Please select any user.<br><br>');
            }
            
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}

function report_detail_view_popup(id,listing_from='')
{
    var base_URL=$("#base_url").val();   
    var data = "id="+id+"&listing_from="+listing_from;
    $.ajax({
        url: base_URL+"my_performance/report_detail_view_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result = $.parseJSON(res);
            //$(".preloader").hide();               
            // $("body, html").animate({ scrollTop: 500 }, "slow");
            $("#common_view_modal_title_lg").html('');   
            $("#rander_common_view_modal_html_lg").html(result.html);
            $('#rander_common_view_modal_lg').modal({
              backdrop: 'static',
              keyboard: false
            });              
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}

function load_performance_scorecard(month='',uid='',kpi_setting_id='')
{
    var base_URL=$("#base_url").val();    
    var data = "month="+month+"&user_id="+uid+"&kpi_setting_id="+kpi_setting_id;
    // alert(data);// return false;
    $.ajax({
        url: base_URL+"my_performance/rander_performance_scorecard_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {
          //addLoader('.table-responsive');
        },
        success:function(res){
            result=$.parseJSON(res);
            // $("body, html").animate({ scrollTop: 500 }, "slow");  
            $("#performance_scorecard_html").html(result.html);
            // if(uid){
            //   $("#performance_scorecard_html").html(result.html);
            // }
            // else{
            //   $("#performance_scorecard_html").html('<br><br>Please select any user.<br><br>');
            // }
        },
        complete: function(){},
        error: function(response){}
    });
}