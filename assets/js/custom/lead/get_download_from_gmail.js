$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
   $('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+0'
    });
});
$(document).ready(function(){	
	load(1);
	/* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH ################# */
    /* ######################################################################## */
    function validate()
    {        
        return true;
    }
    
    // AJAX SEARCH START
    $(document).on('click', '#submit', function (e) {
			e.preventDefault();
			var base_URL      = $("#base_url").val();			
            /* Validation Code */
            var r = validate();
            if(r === false) {
                    return false;
            }
            else {
                    load(1);
                    return false;
            }
            /* Validation code end */
            
    });
    
    $(document).on('click', '.myclass', function (e) { 
       e.preventDefault();  
       
       var str = $(this).attr('href'); 
       var res = str.split("/");
       var cur_page = res[1];  
       $("#current_page_no").val(cur_page); 
       load();    
       // if(cur_page) {
       //      load(cur_page);
       //  }
       //  else {
       //      load(1);
       //  }

	});
    // AJAX SEARCH END
    
    
    
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH END ############# */
    /* ######################################################################## */
	
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
                          $('#gmail_add_lead_view_modal').modal('hide');
                          load();                       
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
                        $('#add_customer_view_modal').modal('hide');
                        load();                        
                    });
              }   
            }
          });
  });
  

  $("body").on("click",".delete",function(e){
      var id=$(this).attr("data-id");  
      var status=$(this).attr('data-status');    
      delete_gmail(id,status);      
  });

  $(document).on('click', '.seen_status_change', function (e) {        
        var curr_status = $(this).attr('data-curstatus');     
        var id=$(this).attr("data-id"); 
        change_current_read_status(id,curr_status);                 
                  
    });

    $("body").on("click",".detail_page",function(e){
      var id=$(this).attr('data-id');
      var base_URL = $("#base_url").val();
      window.location.href=base_URL+"lead/download_from_gmail_detail/"+id;
    });

    $("body").on("click",".refresh",function(e){
        $("#sync_from_gmail").val('Y');
        load(1);
    });

    $("body").on("click",".selected_delete",function(e){
        var gmail_id_arr=[];
        $("input:checkbox[name=gmail_overview_id]:checked").each(function(){
            gmail_id_arr.push($(this).val());            
        });
        // delete_gmail(id); 
        if(gmail_id_arr.length>0)
        {
          var status=$(this).attr('data-status');
          var id_str=gmail_id_arr.join();         
          delete_gmail(id_str,status); 
        }
    });

    $("body").on("click","#selected_seen_status_change",function(e){        
        var curr_status = $(this).attr('data-curstatus');
        var gmail_id_arr=[];        
        $("input:checkbox[name=gmail_overview_id]:checked").each(function(){
            gmail_id_arr.push($(this).val());                  
        });            
        // delete_gmail(id); 
        if(gmail_id_arr.length>0)
        {
          var id_str=gmail_id_arr.join(); 
          change_current_read_status(id_str,curr_status);  
        }
    });

    $("body").on("click","#sync_logout_account",function(e){
          var base_URL = $("#base_url").val();
          var data="";           
          $.ajax({
                  url: base_URL+"lead/sync_logout_account_ajax",
                  data: data,
                  cache: false,
                  method: 'POST',
                  dataType: "html",
                  beforeSend: function( xhr ) { 
                    //$("#preloader").css('display','block');                           
                  },
                  success: function(data){
                      result = $.parseJSON(data);
                      //load();
                      $("#sync_another_div").hide();
                      $("#sync_logout_div").hide();

                      $("#sync_another_div").show();
                      $("#sync_logout_div").hide();
                  },
                  complete: function(){
                  
                 },
          });
    });

    $("body").on("click",'#sync_other_account',function(e){
        var base_URL = $("#base_url").val();
        var data="";         

        $.ajax({
                url: base_URL+"lead/sync_other_account_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                     
                    if(result.token_link)
                    {        
                        let params = 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=0,height=0,left=-1000,top=-1000';
                        popup = window.open(result.token_link, "gmail_auth_popup_box", params);
                        popup.focus();
                    }   
                    else
                    {

                    }                
                    
                },
                complete: function(){
                
               },
        });
    });

    $("body").on("change","#is_lead_responses",function(e){
      var tmp_val=($(this).is(':checked'))?'Y':'N';      
      $("#filter_is_lead_responses").val(tmp_val);
      load();
    });


    $("body").on("click",".search_confirm",function(e){

        var filter_arr=[];
        var base_url = $("#base_url").val();
        var search_str=$("#search_str").val();
        var search_from=$("#search_from").val();
        var search_to=$("#search_to").val();
        var search_subject=$("#search_subject").val();
        var search_date=$("#search_date").val();
        var search_date_to=$("#search_date_to").val();
        $("#filter_by_str").val(search_str);
        $("#filter_by_from").val(search_from);
        $("#filter_by_to").val(search_to);
        $("#filter_by_subject").val(search_subject);
        $("#filter_by_date").val(search_date);
        $("#filter_by_date_to").val(search_date_to);
        $("#current_page_no").val('1');

        // ----------------------------------- 
        if ($('.advance-search').hasClass("on")) 
        {
          $('.advance-search').removeClass('on');
          if ($('.advance-search').find('i').hasClass("fa-caret-down")) 
          {
            $('.advance-search').find('i').removeClass("fa-caret-down").addClass("fa-caret-up");
          }
          else
          {
            $('.advance-search').find('i').removeClass("fa-caret-up").addClass("fa-caret-down")
          }
          $('.advance-search-holder').slideToggle('fast');
        }
        
        // -------------------------------------

        if(search_str!='' )
        {
          label_text='By String: ';        
          (search_str)?filter_arr.push(label_text+search_str):'';
        }

        if(search_from!='' )
        {
          label_text='By From Email: ';        
          (search_from)?filter_arr.push(label_text+search_from):'';
        }
        if(search_to!='' )
        {
          label_text='By To Email: ';        
          (search_to)?filter_arr.push(label_text+search_to):'';
        }
        if(search_subject!='' )
        {
          label_text='By Subject: ';        
          (search_subject)?filter_arr.push(label_text+search_subject):'';
        }
        if(search_date!='' && search_date_to!='')
        {         
          var date_range_text='By Date: '+' between "'+search_date+'" - "'+search_date_to+'"';
          (date_range_text)?filter_arr.push(date_range_text):'';
        }

        

        if(filter_arr.join())
        {
            $("#selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="text-danger gmail_filter_reset" id=""><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>');
        }
        else
        {
            $("#selected_filter_div").css({'display':'none'}).html('');
        }

        load();
    });

    $("body").on("click",".gmail_filter_reset",function(e){
        
        $(".close-search").parent().parent().removeClass('open');
        $(".close-search").parent().parent().find('input').val('');
        if ($('.advance-search i').hasClass("fa-caret-up")) {
          $('.advance-search i').removeClass("fa-caret-up").addClass("fa-caret-down");
          $('.advance-search-holder').slideUp(10);
        }

        //location.reload(true);
        $("#selected_filter_div").css({'display':'none'}).html('');
        // ------------------------------------------------------
        // FILTER RE-SET    
        $("#search_str").val('');
        $("#search_from").val('');
        $("#search_to").val('');
        $("#search_subject").val('');
        $("#search_date").val('');
        $("#search_date_to").val('');

        // SET VAL
        $("#filter_by_str").val('');
        $("#filter_by_from").val('');
        $("#filter_by_to").val('');
        $("#filter_by_subject").val('');
        $("#filter_by_date").val('');
        $("#filter_by_date_to").val('');
        $("#current_page_no").val('1');


        // FILTER RE-SET
        // ------------------------------------------------------ 
        $("#selected_filter_div").html('');
        // $("#leadFilterModal").modal('hide');        
        load();
    });
});

function delete_gmail(ids,status)
{
  if(status=='A'){
    var btn_txt='Yes, archive it!';
    var txt='Do you want to archive the mail?';
  }
  else if(status=='D'){
    var btn_txt='Yes, delete it!';
    var txt='Do you want to delete the mail?';
  }
  var base_URL = $("#base_url").val();
  swal({
        title: "Confirmation",
        text: txt,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-warning',
        confirmButtonText: btn_txt,
        closeOnConfirm: true
    }, function () { 

          var data="ids="+ids+"&status="+status; 
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
                      // alert(result.msg);
                      load();
                      /*
                      swal({
                          title: "Updated!",
                          text: "The mail has been deleted",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () { 
                          //location.reload(true); 
                          load();                               
                      });
                      */
                     
                  },
                  complete: function(){
                  //$("#preloader").css('display','none');
                 },
          });
          
      }); 
}

function change_current_read_status(ids,curr_status)
{
  var base_URL = $("#base_url").val();
  var data="ids="+ids+"&curr_status="+curr_status;
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
              load();
             
          },
          complete: function(){
            //$("#preloader").css('display','none');
         },
  });
}
// AJAX LOAD START
function load(p_no='') 
{ 
    var base_URL = $("#base_url").val();
    var page = (p_no=='')?$("#current_page_no").val():p_no;
    var sync_from_gmail=$("#sync_from_gmail").val();
    var filter_is_lead_responses=$("#filter_is_lead_responses").val();

    var filter_by_str=$("#filter_by_str").val();
    var filter_by_from=$("#filter_by_from").val();
    var filter_by_to=$("#filter_by_to").val();
    var filter_by_subject=$("#filter_by_subject").val();
    var filter_by_date=$("#filter_by_date").val();
    var filter_by_date_to=$("#filter_by_date_to").val();
    var data = "page="+page+'&sync_from_gmail='+sync_from_gmail+'&filter_is_lead_responses='+filter_is_lead_responses+"&filter_by_str="+filter_by_str+"&filter_by_from="+filter_by_from+"&filter_by_to="+filter_by_to+"&filter_by_date="+filter_by_date+"&filter_by_date_to="+filter_by_date_to+"&filter_by_subject="+filter_by_subject;
    // alert(data); //return false;
    $.ajax({
      url: base_URL+"lead/get_list_from_gmail_ajax/"+page,
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {                
      addLoader('.table-responsive');
      },
      success:function(res){ 
        result = $.parseJSON(res);  
        
        $("#tcontent").html(result.table);
        $("#page").html(result.page);
        $("#response_count_div").html(result.get_response_count);
        $("#page_record_count_info").html(result.page_record_count_info);
        if($("input:checkbox[name=gmail_overview_id]:checked").length==0)
        {
          $('.dropdown_new .check-box-sec').removeClass('same-checked');
          $('input:checkbox[name=gmail_overview_id], .user_all_checkbox').prop('checked',false);

          $('table.dataTable.new-table-style .float-left.refresh-holder').show();
          $('table.dataTable.new-table-style .float-left.other-holder').hide();
        }
          
        /*
        $("#sync_another_div").hide();
        $("#sync_logout_div").hide();
        if(result.token_link=='')
        {
            $("#sync_logout_div").show();
        }
        else
        {
          $("#sync_another_div").show();
          // addLoader('.table-responsive');            
          // let params = 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=0,height=0,left=-1000,top=-1000';
          // popup = window.open(result.token_link, "gmail_auth_popup", params);
          // popup.focus();
        } 
        */
             
      },
      complete: function(){
        removeLoader();
        $("#sync_from_gmail").val('N');
      },
      error: function(response) {
      //alert('Error'+response.table);
      }
    })
}

// AJAX LOAD END
function addLoader(getele)
{	
	var gets = 100;
	if ($(window).scrollTop() > 200) {
		gets = $(window).scrollTop();
	}
	var loaderhtml = '<div class="loader" style="background-position: 50% '+gets+'px"></div>';
	$(getele).css({'position':'relative', 'min-height': '300px'}).prepend(loaderhtml);
	$('.loader').fadeIn('fast', function() {
		// Animation complete.
		//$(getele).css({'min-height': 'inherit'})
	});
}
function removeLoader()
{
	$('.loader').fadeOut('fast', function() {
		// Animation complete.
		$('.loader').remove()
	});
}