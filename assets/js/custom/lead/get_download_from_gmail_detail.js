$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
    var base_url_root = $("#base_url_root").val();
   $('.display_date').datepicker({
        showOn: "both",
        dateFormat: "dd-M-yy",
        buttonImage: base_url_root+"images/cal-icon.png",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+0',
        buttonImageOnly: true,
        buttonText: "Select date"
    });
});
$(document).ready(function(){	
  

  $("body").on("click",".delete",function(e){
      var id=$(this).attr("data-id");
      var base_URL = $("#base_url").val();

      swal({
              title: "Confirmation",
              text: "Do you want to delete the mail?",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-warning',
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
          }, function () { 

                var data="ids="+id;
                // alert(data); return false;
                $.ajax({
                        url: base_URL+"lead/delete_gmail_lead",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'POST',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
                        beforeSend: function( xhr ) { 
                          //$("#preloader").css('display','block');                           
                        },
                        success: function(data){
                            result = $.parseJSON(data);
                            //$(".preloader").hide();
                            //alert(result.status);
                            window.location.href=base_URL+"lead/download_from_gmail";
                            /*
                            swal({
                                title: "Updated!",
                                text: "The mail has been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                window.location.href=base_URL+"lead/download_from_gmail";                               
                            });
                            */
                           
                        },
                        complete: function(){
                        //$("#preloader").css('display','none');
                       },
                });
                
            }); 
  });

  $(document).on('click', '.seen_status_change', function (e) {
        
        var base_URL = $("#base_url").val();
        var curr_status = $(this).attr('data-curstatus');     
        var id=$(this).attr("data-id"); 
        var data="ids="+id+"&curr_status="+curr_status;
        // alert(data); return false;
        $.ajax({
                url: base_URL+"lead/change_gmail_read_status",
                data: data,
                //data: new FormData($('#frmAccount')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                //mimeType: "multipart/form-data",
                //contentType: false,
                //cache: false,
                //processData:false,
                beforeSend: function( xhr ) { 
                //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);
                    //$(".preloader").hide();
                    //alert(result.status);
                    //load();
                   // location.reload(true);
                   window.location.href=base_URL+"lead/download_from_gmail"; 
                },
                complete: function(){
                  //$("#preloader").css('display','none');
               },
        });            
                  
    });

  $("body").on("click",".attachment_download",function(e){
      //e.preventDefault();
      var file_path=$(this).attr('data-content');
      var base_url=$("#base_url").val();        
      window.location.href=base_url+"lead/download_gmail_attachment/"+file_path;
      // var getit = $(this).attr('data-content');
      // $('#attachmentModal #attach-holder').html('<img src="'+getit+'">');
      // $('#attachmentModal').modal('show');
    });

  $("body").on("click",".add_as_lead",function(e){
    var base_url = $("#base_url").val();
    var id = $(this).attr('data-id');

    $.ajax({
        url: base_url + "lead/gmail_lead_add_view_rander_ajax",
        type: "POST",
        data: {
            'gmail_inbox_overview_id': id
        },
        async: true,
        success: function(response) {
            $('#gmail_add_lead_view_rander').html(response);
            $('#gmail_add_lead_view_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        error: function() {
            swal({
                    title: 'Something went wrong there!',
                    text: '',
                    type: 'danger',
                    showCancelButton: false
                }, function() {

            });
        }
    });
  });

  $("body").on("click","#add_to_lead_submit_confirm",function(e){

      e.preventDefault();
      var base_url = $("#base_url").val();
      var lead_title_obj=$("#lead_title");
      //var lead_requirement_obj=$("#lead_requirement");
      var lead_requirement = tinyMCE.activeEditor.getContent();
      var lead_enq_date_obj=$("#lead_enq_date");
      var lead_follow_up_date_obj=$("#lead_follow_up_date");
      var assigned_user_id_obj=$("#assigned_user_id");
      var com_company_name_obj=$("#com_company_name");
      var com_contact_person_obj=$("#com_contact_person");
      var com_designation_obj=$("#com_designation");
      var com_alternate_email_obj=$("#com_alternate_email");

      var com_country_id_obj=$("#com_country_id");
      var com_state_id_obj=$("#com_state_id");
      var com_source_id_obj=$("#com_source_id");

      if(lead_title_obj.val()=='')
      {
        lead_title_obj.addClass('error_input');
        $("#lead_title_error").html('Please enter lead title');
        lead_title_obj.focus();
        return false;
      }
      else
      {
        lead_title_obj.removeClass('error_input');
        $("#lead_title_error").html('');
      }

      $('#lead_requirement').val(lead_requirement);
      if(lead_requirement=='')
      {
        //lead_requirement.addClass('error_input');
        $("#lead_requirement_error").html('Please describe requirements');
        //lead_requirement.focus();
        return false;
      }
      else
      {
        //lead_requirement.removeClass('error_input');
        $("#lead_requirement_error").html('');
      }

      if(lead_enq_date_obj.val()=='')
      {
        lead_enq_date_obj.addClass('error_input');
        $("#lead_enq_date_error").html('Please select enquiry date');
        lead_enq_date_obj.focus();
        return false;
      }
      else
      {
        lead_enq_date_obj.removeClass('error_input');
        $("#lead_enq_date_error").html('');
      }

      if(lead_follow_up_date_obj.val()=='')
      {
        lead_follow_up_date_obj.addClass('error_input');
        $("#lead_follow_up_date_error").html('Please select follow up date');
        lead_follow_up_date_obj.focus();
        return false;
      }
      else
      {
        lead_follow_up_date_obj.removeClass('error_input');
        $("#lead_follow_up_date_error").html('');
      }

      
      // if(assigned_user_id_obj.val()=='')
      // {
      //   assigned_user_id_obj.addClass('error_input');
      //   $("#assigned_user_id_error").html('Please select account manager');
      //   assigned_user_id_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   assigned_user_id_obj.removeClass('error_input');
      //   $("#assigned_user_id_error").html('');
      // }


      if(com_company_name_obj.val()=='')
      {
        com_company_name_obj.addClass('error_input');
        $("#com_company_name_error").html('Please enter company name');
        com_company_name_obj.focus();
        return false;
      }
      else
      {
        com_company_name_obj.removeClass('error_input');
        $("#com_company_name_error").html('');
      }

      if(com_contact_person_obj.val()=='')
      {
        com_contact_person_obj.addClass('error_input');
        $("#com_contact_person_error").html('Please enter contact person');
        com_contact_person_obj.focus();
        return false;
      }
      else
      {
        com_contact_person_obj.removeClass('error_input');
        $("#com_contact_person_error").html('');
      }

      // if(com_designation_obj.val()=='')
      // {
      //   com_designation_obj.addClass('error_input');
      //   $("#com_designation_error").html('Please enter designation');
      //   com_designation_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_designation_obj.removeClass('error_input');
      //   $("#com_designation_error").html('');
      // }

      if(com_alternate_email_obj.val()!='')
      {
        if(is_email_validate(com_alternate_email_obj.val())==false)
        {
            com_alternate_email_obj.addClass('error_input');
            $("#com_alternate_email_error").html("Please enter valid email.");
            com_alternate_email_obj.focus();
            return false;
        }
        else
        {
            com_alternate_email_obj.removeClass('error_input');
            $("#com_alternate_email_error").html('');
        }
      }
      else
      {
            com_alternate_email_obj.removeClass('error_input');
            $("#com_alternate_email_error").html('');
      }
      



      if(com_country_id_obj.val()=='')
      {
        com_country_id_obj.addClass('error_input');
        $("#com_country_id_error").html('Please select country');
        com_country_id_obj.focus();
        return false;
      }
      else
      {
        com_country_id_obj.removeClass('error_input');
        $("#com_country_id_error").html('');
      }

      // if(com_state_id_obj.val()=='')
      // {
      //   com_state_id_obj.addClass('error_input');
      //   $("#com_state_id_error").html('Please select state');
      //   com_state_id_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_state_id_obj.removeClass('error_input');
      //   $("#com_state_id_error").html('');
      // }

      if(com_source_id_obj.val()=='')
      {
        com_source_id_obj.addClass('error_input');
        $("#com_source_id_error").html('Please select source');
        com_source_id_obj.focus();
        return false;
      }
      else
      {
        com_source_id_obj.removeClass('error_input');
        $("#com_source_id_error").html('');
      }

       $.ajax({
              url: base_url+"lead/add_lead_ajax",
              data: new FormData($('#frmLeadAdd')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function( xhr ) {
                $('.btn_enabled').addClass("btn_disabled");
                $(".btn_disabled").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                $("#add_to_lead_submit_confirm").attr("disabled",true);
              },
              success: function(data){
                result = $.parseJSON(data);
                // console.log(result.msg);
                // alert(result.status);
                $('.btn_enabled').removeClass("btn_disabled");
                $(".btn_enabled").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                $("#add_to_lead_submit_confirm").attr("disabled",false);
                if(result.status=='success')
                {
                    swal({
                          title: "Success!",
                          text: "A new lead successfully added.",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () { 
                          window.location.href=base_URL+"lead/download_from_gmail";                        
                      });
                }   
              }
            }); 

  });

  $("body").on("click",".add_as_company",function(e){
    var base_url = $("#base_url").val();
    var id = $(this).attr('data-id');

    $.ajax({
        url: base_url + "customer/customer_add_view_rander_ajax",
        type: "POST",
        data: {
            'gmail_inbox_overview_id': id
        },
        async: true,
        success: function(response) {
            $('#add_customer_view_rander').html(response);
            $('#add_customer_view_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        },
        error: function() {
            swal({
                    title: 'Something went wrong there!',
                    text: '',
                    type: 'danger',
                    showCancelButton: false
                }, function() {

            });
        }
    });
  });
  $("body").on("click","#add_to_company_submit_confirm",function(e){

      e.preventDefault();
      var base_url = $("#base_url").val();      
      
      var com_company_name_obj=$("#com_company_name");
      var com_contact_person_obj=$("#com_contact_person");
      var com_designation_obj=$("#com_designation");
      var com_alternate_email_obj=$("#com_alternate_email");

      var com_country_id_obj=$("#com_country_id");
      var com_state_id_obj=$("#com_state_id");
      var com_source_id_obj=$("#com_source_id");  



      if(com_company_name_obj.val()=='')
      {
        com_company_name_obj.addClass('error_input');
        $("#com_company_name_error").html('Please enter company name');
        com_company_name_obj.focus();
        return false;
      }
      else
      {
        com_company_name_obj.removeClass('error_input');
        $("#com_company_name_error").html('');
      }

      if(com_contact_person_obj.val()=='')
      {
        com_contact_person_obj.addClass('error_input');
        $("#com_contact_person_error").html('Please enter contact person');
        com_contact_person_obj.focus();
        return false;
      }
      else
      {
        com_contact_person_obj.removeClass('error_input');
        $("#com_contact_person_error").html('');
      }

      // if(com_designation_obj.val()=='')
      // {
      //   com_designation_obj.addClass('error_input');
      //   $("#com_designation_error").html('Please enter designation');
      //   com_designation_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_designation_obj.removeClass('error_input');
      //   $("#com_designation_error").html('');
      // }

      if(com_alternate_email_obj.val()!='')
      {
        if(is_email_validate(com_alternate_email_obj.val())==false)
        {
            com_alternate_email_obj.addClass('error_input');
            $("#com_alternate_email_error").html("Please enter valid email.");
            com_alternate_email_obj.focus();
            return false;
        }
        else
        {
            com_alternate_email_obj.removeClass('error_input');
            $("#com_alternate_email_error").html('');
        }
      }
      else
      {
            com_alternate_email_obj.removeClass('error_input');
            $("#com_alternate_email_error").html('');
      }
      



      if(com_country_id_obj.val()=='')
      {
        com_country_id_obj.addClass('error_input');
        $("#com_country_id_error").html('Please select country');
        com_country_id_obj.focus();
        return false;
      }
      else
      {
        com_country_id_obj.removeClass('error_input');
        $("#com_country_id_error").html('');
      }

      // if(com_state_id_obj.val()=='')
      // {
      //   com_state_id_obj.addClass('error_input');
      //   $("#com_state_id_error").html('Please select state');
      //   com_state_id_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_state_id_obj.removeClass('error_input');
      //   $("#com_state_id_error").html('');
      // }

      if(com_source_id_obj.val()=='')
      {
        com_source_id_obj.addClass('error_input');
        $("#com_source_id_error").html('Please select source');
        com_source_id_obj.focus();
        return false;
      }
      else
      {
        com_source_id_obj.removeClass('error_input');
        $("#com_source_id_error").html('');
      }

       $.ajax({
            url: base_url+"customer/add_ajax",
            data: new FormData($('#frmCompanyAdd')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
              $('.btn_enabled').addClass("btn_disabled");
              $(".btn_disabled").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
              $("#add_to_lead_submit_confirm").attr("disabled",true);
            },
            success: function(data){
              result = $.parseJSON(data);
              // console.log(result.msg);
              // alert(result.status);
              $('.btn_enabled').removeClass("btn_disabled");
              $(".btn_enabled").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
              $("#add_to_lead_submit_confirm").attr("disabled",false);
              if(result.status=='success')
              {
                  swal({
                        title: "Success!",
                        text: "A company successfully added.",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                        window.location.href=base_URL+"lead/download_from_gmail";                         
                    });
              }   
            }
          });
  });

  // ---------------------------------------------------------
  // MAIL COMPOSE BOX
  /*
  $(document).on("click",".open_reply_compose_box",function(event) {
        event.preventDefault();
        var to_mail=$(this).attr('data-tomail');
        $("#email_to_email").val(to_mail);
        $('body').addClass('sp-noscroll'); 
        $('#bulk_mail_pop').addClass('sp-show');      
  });
  $(document).on("click",".bulk-close",function(event) {
        event.preventDefault();
        $('body').removeClass('sp-noscroll')
        $('#bulk_mail_pop').removeClass('sp-show');
        $('#bulk_mail_pop').removeClass('sp-mini');
        $('#bulk_mail_pop').removeClass('sp-full');
        $('.bulk_email_to_email_show').removeClass("active");
        $("#show_selected_to_email").removeClass('active').html('');
        set_all_none_checked_status();
        set_all_unchecked();
    });
    
    $(document).on("click",".bulk-mini",function(event) {
        event.preventDefault();

        if ($(this).parent().parent().parent().parent().parent().parent().hasClass('sp-mini')) {
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-mini');
        }else{
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-full');
            $(this).parent().parent().parent().parent().parent().parent().addClass('sp-mini');
        }
    });
    $(document).on("click",".bulk-header a.bulk-full",function(event) {
        event.preventDefault();

        if ($(this).parent().parent().parent().parent().parent().parent().hasClass('sp-full')) {
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-full');
        }else{
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-mini');
            $(this).parent().parent().parent().parent().parent().parent().addClass('sp-full');
        }
    });
    */
    // MAIL COMPOSE BOX
    // ---------------------------------------------------------
    /*
    $("body").on("click","#reply_submit_confirm",function(e){
      var base_URL = $("#base_url").val();
      var email_from_email=$("#email_from_email").val();
      var email_subject=$("#email_subject").val();
      var email_body = tinymce.get("email_body").getContent();
      if(email_from_email=='')
      {
          swal("Oops", "Please enter from email", "error");
          return false;
      }

      if(email_subject=='')
      {
          swal("Oops", "Please enter mail subject", "error");
          return false;
      }

      if(email_body=='')
      {
          swal("Oops", "Please enter mail body", "error");
          return false;
      }

      $.ajax({                
          url: base_URL+"lead/gmail_reply",
          data: new FormData($('#reply_mail_frm')[0]),
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
                     'z-index':'999999999',
                     'font-size':'14px'
                    }
              });
          },            
          success:function(res){ 
             result = $.parseJSON(res);
             
             //alert(result.bulk_mail_id); 
             if(result.status=='success' && result.bulk_mail_id!='')
             {
                  $('#bulk_mail_pop').removeClass('sp-show');
                  $('#bulk_mail_pop').removeClass('sp-mini');
                  $('#bulk_mail_pop').removeClass('sp-full');  
                  $("#bulk_email_to_email").val('');
                  $("#bulk_email_subject").val('');
                  tinymce.get("bulk_email_body").setContent('');
                  set_all_unchecked();              
                  swal('Success!', 'Bulk mails successfully sent', 'success');
                  swal({
                      title: "Success!",
                      text: "Bulk mails successfully sent",
                      type: "success",
                      showCancelButton: false,
                      confirmButtonClass: 'btn-warning',
                      confirmButtonText: "Yes, send it!",
                      closeOnConfirm: true
                  }, function () {
                      window.location.reload();
                  });  
                  
             }
             else
             {
                  swal('Fail!', 'Bulk mails fail to send.', 'error');
             }
                                
         },
         complete: function(){$.unblockUI();},
         error: function(response) {}
     }); 
    });
    */
  
});