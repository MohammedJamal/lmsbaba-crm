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
      delete_gmail(id);      
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
          var id_str=gmail_id_arr.join();         
          delete_gmail(id_str); 
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
});

function delete_gmail(ids)
{
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

          var data="ids="+ids; 

          $.ajax({
                  url: base_URL+"/lead/delete_gmail_lead",
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
          url: base_URL+"/lead/change_gmail_read_status",
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
    var data = "page="+page;
    // alert(data); return false;
    $.ajax({
      url: base_URL+"lead/get_list_from_gmail_response_ajax/"+page,
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {                
      addLoader('.table-responsive');
      },
      success:function(res){ 
      result = $.parseJSON(res);
      //$(".preloader").hide();
      //alert(result.table);
      //alert(3);        
      //alert(result.table);
      $("#tcontent").html(result.table);
      $("#page").html(result.page);
      $("#page_record_count_info").html(result.page_record_count_info);
      if($("input:checkbox[name=gmail_overview_id]:checked").length==0)
      {
        $('table.dataTable.new-table-style .float-left.refresh-holder').show();
      $('table.dataTable.new-table-style .float-left.other-holder').hide();
      }
      },
      complete: function(){
      removeLoader();
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