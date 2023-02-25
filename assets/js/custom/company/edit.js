$( function() {
    
});
$(document).ready(function(){

  if($("#com_country_id option:selected").val())
  {
      GetStateList($("#com_country_id option:selected").val());
  }
  

  $("body").on("click","#add_company_submit_confirm",function(e){
	
      e.preventDefault();
      var base_url = $("#base_url").val();    
	    var customer_id=$("#cust_id").val();
      var com_company_name_obj=$("#com_company_name");
      var com_contact_person_obj=$("#com_contact_person");
      var com_designation_obj=$("#com_designation");
      var com_email_obj=$("#com_email");
      var com_alternate_email_obj=$("#com_alternate_email");
      var com_mobile_obj=$("#com_mobile");
      var com_country_id_obj=$("#com_country_id");
      var com_state_id_obj=$("#com_state_id");
      var com_source_id_obj=$("#com_source_id");
      var com_short_description_obj=$("#com_short_description");

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

      if(com_email_obj.val()!='')
      {
        if(is_email_validate(com_email_obj.val())==false)
        {
            com_email_obj.addClass('error_input');
            $("#com_email_error").html("Please enter valid email.");
            com_email_obj.focus();
            return false;
        }
        else
        {
            com_email_obj.removeClass('error_input');
            $("#com_email_error").html('');
        }
      }
      else
      {
        com_email_obj.addClass('error_input');
        $("#com_email_error").html('Please enter email');
        com_email_obj.focus();
        return false;
      }

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
      

      if(com_mobile_obj.val()=='')
      {
        com_mobile_obj.addClass('error_input');
        $("#com_mobile_error").html('Please enter mobile');
        com_mobile_obj.focus();
        return false;
      }
      else
      {
        com_mobile_obj.removeClass('error_input');
        $("#com_mobile_error").html('');
        // if(com_mobile_obj.val().length>=10)
        // {
        //   com_mobile_obj.removeClass('error_input');
        //   $("#com_mobile_error").html('');
        // }
        // else
        // {
        //   com_mobile_obj.addClass('error_input');
        //   $("#com_mobile_error").html('Mobile should be 10 digits');
        //   com_mobile_obj.focus();
        //   return false;
        // }
        
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

      
      if(com_state_id_obj.val()=='' || com_state_id_obj.val()==null)
      {
        com_state_id_obj.addClass('error_input');
        $("#com_state_id_error").html('Please select state');
        com_state_id_obj.focus();
        return false;
      }
      else
      {
        com_state_id_obj.removeClass('error_input');
        $("#com_state_id_error").html('');
      }

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
      
      if(com_short_description_obj.val()=='')
      {
        com_short_description_obj.addClass('error_input');
        $("#com_short_description_error").html('Please enter short description.');
        com_short_description_obj.focus();
        return false;
      }
      else
      {
        com_short_description_obj.removeClass('error_input');
        $("#com_short_description_error").html('');
      }

       $.ajax({
              url: base_url+"customer/edit_ajax",
              data: new FormData($('#frmCompanyEdit')[0]),
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
                console.log(result);
                
                if(result.status=='success')
                {
                    swal({
                          title: "Success!",
                          text: "A new lead successfully added.",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () { 
                          window.location.href=base_url+"customer/edit/"+customer_id;                        
                      });
                }   
              }
            }); 

  });

});

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





