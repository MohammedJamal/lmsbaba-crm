$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
    $('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5'
    });

    $('#lead_follow_up_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: false,
        changeYear: false,
        yearRange: '-100:+5',
        minDate: 0,
    });
});
$(document).ready(function(){
  $(".select2").select2();  
  $("body").on("click",".view_company_detail",function(e){
    var cid=$(this).attr("data-id");
    var base_url=$("#base_url").val();
    var data="cid="+cid;
    $.ajax({
        url: base_url+"customer/view_company_detail_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data);           
          //alert(result.html);
          $("#lead_company_details_body").html(result.html);
          $('#lead_company_details').modal({backdrop: 'static',keyboard: false}); 
        }
      });
    
  });

  $("body").on("change",".tag_company_id",function(e){
      
      var cid=$(this).val();
      var base_url=$("#base_url").val();
      var data="cid="+cid;
      $.ajax({
          url: base_url+"customer/get_company_detail_ajax",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {},
          success: function(data){
            result = $.parseJSON(data);           
            // console.log(result.row);
            // alert(result.row.company_name)
            // =====================================================
            $("#com_company_id").val(cid);
            $("#com_company_name").val(result.row.company_name);
            if(result.row.company_name){
              $("#com_company_name").attr("readonly",true);
            }
            else{
              $("#com_company_name").attr("readonly",false);
            }

            $("#com_contact_person").val(result.row.contact_person);
            if(result.row.contact_person){
              $("#com_contact_person").attr("readonly",true);
            }
            else{
              $("#com_contact_person").attr("readonly",false);
            }

            $("#com_designation").val(result.row.designation);
            if(result.row.designation){
              $("#com_designation").attr("readonly",true);
            }
            else{
              $("#com_designation").attr("readonly",false);
            }

            $("#com_email").val(result.row.email);
            if(result.row.email){
              $("#com_email").attr("readonly",true);
            }
            else{
              $("#com_email").attr("readonly",false);
            }

            $("#com_alternate_email").val(result.row.alt_email);
            if(result.row.alt_email){
              $("#com_alternate_email").attr("readonly",true);
            }
            else{
              $("#com_alternate_email").attr("readonly",false);
            }

            $("#com_mobile_country_code").val(result.row.mobile_country_code);
            if(result.row.mobile_country_code){
              $("#com_mobile_country_code").attr("readonly",true);
            }
            else{
              $("#com_mobile_country_code").attr("readonly",false);
            }

            $("#com_mobile").val(result.row.mobile);
            if(result.row.mobile){
              $("#com_mobile").attr("readonly",true);
            }
            else{
              $("#com_mobile").attr("readonly",false);
            }

            $("#com_alt_mobile_country_code").val(result.row.alt_mobile_country_code);
            if(result.row.alt_mobile_country_code){
              $("#com_alt_mobile_country_code").attr("readonly",true);
            }
            else{
              $("#com_alt_mobile_country_code").attr("readonly",false);
            }

            $("#com_alternate_mobile").val(result.row.alt_mobile);
            if(result.row.alt_mobile){
              $("#com_alternate_mobile").attr("readonly",true);
            }
            else{
              $("#com_alternate_mobile").attr("readonly",false);
            }


            $("#com_landline_country_code").val(result.row.landline_country_code);
            if(result.row.landline_country_code){
              $("#com_landline_country_code").attr("readonly",true);
            }
            else{
              $("#com_landline_country_code").attr("readonly",false);
            }

            $("#com_landline_std_code").val(result.row.landline_std_code);
            if(result.row.landline_std_code){
              $("#com_landline_std_code").attr("readonly",true);
            }
            else{
              $("#com_landline_std_code").attr("readonly",false);
            }

            $("#landline_number").val(result.row.landline_number);
            if(result.row.landline_number){
              $("#landline_number").attr("readonly",true);
            }
            else{
              $("#landline_number").attr("readonly",false);
            }


            $("#com_address").val(result.row.address);
            if(result.row.address){
              $("#com_address").attr("readonly",true);
            }
            else{
              $("#com_address").attr("readonly",true);
            }

            $("#com_country_id").val(result.row.country_id);
            if(result.row.country_id>0)
            {
                 GetStateList(result.row.country_id);
                 $("#com_country_id").attr("disabled",true);
                 $("#com_existing_country").val(result.row.country_id);

                if(result.row.state>0)
                {
                  $("#com_existing_state").val(result.row.state);
                  $("#com_state_id").attr("disabled",true);
                }
                else
                {
                  $("#com_state_id").val($("#com_state_id option:first").val());
                  $("#com_state_id").attr("disabled",false);
                }

                if(result.row.city>0)
                {
                  $("#com_existing_city").val(result.row.city);
                  $("#com_city_id").attr("disabled",true);
                }
                else
                {
                  $("#com_city_id").val($("#com_city_id option:first").val());
                  $("#com_city_id").attr("disabled",false);
                }
            }
            else
            {
              $("#com_country_id").val($("#com_country_id option:first").val());
              $("#com_country_id").attr("disabled",false);

              $("#com_state_id").val($("#com_state_id option:first").val());
              $("#com_state_id").attr("disabled",false);

              $("#com_city_id").val($("#com_city_id option:first").val());
              $("#com_city_id").attr("disabled",false);
            }

            // $("#com_state_id").val();  
            // $("#com_city_id").val();
            $("#com_zip").val((result.row.zip!=0)?result.row.zip:'');
            if(result.row.zip>0){
              $("#com_zip").attr("readonly",true);
            }
            else{
              $("#com_zip").attr("readonly",false);
            }

            $("#com_website").val(result.row.website);
            if(result.row.website){
              $("#com_website").attr("readonly",true);
            }
            else{
              $("#com_website").attr("readonly",false);
            }

            $("#com_source_id").val(result.row.source_id);
            if(result.row.source_id>0)
            {
              $("#com_existing_source").val(result.row.source_id);
              $("#com_source_id").attr("disabled",true);
              $(".add_source_popup").css("display","none");
            }
            else
            {
              $("#com_source_id").val($("#com_source_id option:first").val());
              $("#com_source_id").attr("disabled",false);
              $(".add_source_popup").css("display","block");
            }

            $("#com_short_description").val(result.row.short_description);
            if(result.row.short_description){
              $("#com_short_description").attr("readonly",true);
            }
            else{
              $("#com_short_description").attr("readonly",false);
            }
            // =====================================================
          }
        });
  });

  if($("#com_country_id option:selected").val())
  {
      GetStateList($("#com_country_id option:selected").val());
  }


  $("body").on("click","#search_submit_confirm",function(e){
      var email_obj=$("#email");
      var mobile_obj=$("#mobile");
      var flag=1;

      if(email_obj.val()!='')
      {
          flag=0;          
      }

      if(mobile_obj.val()!='')
      {
         flag=0;
      }

      if(flag==1)
      {        
        swal('Oops! Search by valid E-mail/Mobile.');
        return false;
      }
      else
      {
        if(email_obj.val()!='')
        {
            if(is_email_validate(email_obj.val())==false)
            {
                swal('Oops! Search by valid E-mail.');
                return false;
            }
            else
            {
                return true
            }
        }
        else
        {
            return true;
        }
        
      }

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

      
      if(assigned_user_id_obj.val()=='')
      {
        assigned_user_id_obj.addClass('error_input');
        $("#assigned_user_id_error").html('Please select account manager');
        assigned_user_id_obj.focus();
        return false;
      }
      else
      {
        assigned_user_id_obj.removeClass('error_input');
        $("#assigned_user_id_error").html('');
      }


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
                          window.location.href=base_url+"lead/add";                        
                      });
                }   
              }
            }); 

  });

  $("body").on("click",".add_source_popup",function(e){
    $("#add_source_modal").modal({backdrop: 'static',keyboard: false });
  });

  $("body").on("click","#add_source_submit_confirm",function(e){
    fn_rander_source_add_view();
  });

});

function fn_rander_source_add_view()
{
    var base_url=$("#base_url").val();        
    $.ajax({
        url: base_url+"lead/add_source_ajax",
        data: new FormData($('#AddSourceForm')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data);           
          if(result.status=='success')
          {
            //alert(result.msg);
            swal({
                title: "Success",
                text: result.msg,
                type: "warning",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
            $("#com_source_id").html(result.options);
            $("#source").val('');
            $("#source").focus();
              
          }   
          else if(result.status=='error')
          {
            //alert(result.msg);
            $("#source").focus();
            swal(result.msg);
          }
        }
    });
}

function GetStateList(cont)
{
  var base_url=$("#base_url").val();
  $.ajax({
      url: base_url+"lead/getstatelist",
      type: "POST",
      data: {'country_id':cont},      
      success: function (response) 
      {
        if(response!='')
        {
          document.getElementById('com_state_id').innerHTML=response;
          $("#com_state_id").val($("#com_existing_state").val());
          GetCityList($("#com_state_id option:selected").val());
        }
          
      },
      error: function () 
      {
       //$.unblockUI();
       //alert('Something went wrong there');
       swal({
                title: "Danger!",
                text: "Something went wrong there",
                type: "danger",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
      }
  });
}

function GetCityList(state)
{
  var base_url=$("#base_url").val();
  $.ajax({
      url: base_url+"lead/getcitylist",
      type: "POST",
      data: {'state_id':state},     
      success: function (response) 
      {
        if(response!='')
        {
          document.getElementById('com_city_id').innerHTML=response;
          $("#com_city_id").val($("#com_existing_city").val())
        }
          
      },
      error: function () 
      {
       //$.unblockUI();
       //alert('Something went wrong there');
       swal({
                title: "Danger!",
                text: "Something went wrong there",
                type: "danger",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
      }
     });
}





