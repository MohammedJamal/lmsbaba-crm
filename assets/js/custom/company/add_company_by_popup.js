$(document).ready(function(){

  $('#lead_company_details').on('hidden.bs.modal', function () { 

      if($(this).hasClass("add_company"))
      {
        $("#lead_company_details").removeClass('add_company');
        $("#rander_add_new_company_view_modal").modal("show");
      }      
  });
  $("body").on("click",".view_company_detail",function(e){
    var cid=$(this).attr("data-id");
    fn_rander_company_details(cid);
    $('#lead_company_details').addClass('add_company');
    $("#rander_add_new_company_view_modal").modal("hide");
  });
  $("body").on("click","#add_new_com_submit_confirm",function(e){

      e.preventDefault();
      var base_url = $("#base_url").val();      
      var com_source_id_obj=$("#com_source_id"); 
      var assigned_user_id_obj=$("#assigned_user_id"); 
      var com_country_id_obj=$("#country");    
      var com_contact_person_obj=$("#contact_person");
      if(com_source_id_obj.val()=='')
      {
        swal('Oops','Please select source.','error');
        return false;        
      }     

      if(assigned_user_id_obj.val()=='')
      {
        swal('Oops','Please select account manager.','error');
        return false;         
      }      

      if(com_country_id_obj.val()=='')
      {
        swal('Oops','Please select country.','error');
        return false; 
      } 

      if(com_contact_person_obj.val()=='')
      {
        swal('Oops','Please enter contact person.','error');
        return false; 
        
      }

      $.ajax({
              url: base_url+"customer/add_customer_ajax",
              data: new FormData($('#frmCompanyAdd')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function( xhr ) {                
                $('#rander_add_new_company_view_modal .modal-body').addClass('logo-loader');
              },
              complete: function (){
                $('#rander_add_new_company_view_modal .modal-body').removeClass('logo-loader');
              },
              success: function(data){
                result = $.parseJSON(data);                
                if(result.status=='success')
                {
                  
                  swal({
                        title: "Success!",
                        text: result.msg,
                        type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false",
                        showCancelButton: false,
                        closeOnConfirm: true
                    }, function () {                           
                        location.reload();                         
                    });
                }   
              }
      }); 
  });

  $("body").on("click","#add_new_customer",function(e){
      e.preventDefault()
      rander_add_new_company_view();
  });

  $("body").on("click","#search_com_submit_confirm",function(e){
      e.preventDefault();
      var base_url = $("#base_url").val();
      var email_obj=$("#email");
      var mobile_obj=$("#mobile");
      var flag=1;
      var status=false;
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
                status=true;
            }
        }
        else
        {
            status=true;
        }
        
      }

      if(status==true)
      {
          $.ajax({
          url: base_url + "customer/add_popup_view_ajax",
          data: new FormData($('#searchCompanyFrm')[0]),
          cache: false,
          method: 'POST',
          dataType: "html",
          mimeType: "multipart/form-data",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function(xhr) {
            $('#rander_add_new_company_view_modal .modal-body').addClass('logo-loader');
          },
          complete: function (){
            $('#rander_add_new_company_view_modal .modal-body').removeClass('logo-loader');
          },
          success: function(response) {
            //result = $.parseJSON(data);
            // alert(response);
            var arr = response.split("#");
            if(arr[1]=='id')
            {
              $("#rander_add_new_company_view_modal").modal('hide'); 
              var customer_id = $("input[type='radio'][name='tag_company_id']:checked").val();
              rander_customer_edit_view(arr[0],'');
              
            }
            else
            {
              $("#rander_add_new_company_view_title").text("Add Details");
              $('#rander_add_new_company_view_html').html(response);
              $('#rander_add_new_company_view_modal').modal({backdrop: 'static',keyboard: false});
            }
            
          }
      });
      }
            
  });

  $("body").on("click","#get_company_add_view",function(e){
    $("#rander_add_new_company_view_modal").modal('hide'); 
    var customer_id = $("input[type='radio'][name='tag_company_id']:checked").val();
    rander_customer_edit_view(customer_id,'');        
  });
});

function rander_add_new_company_view()
{      
    var base_url = $("#base_url").val();
    $.ajax({
            url: base_url + "customer/add_popup_view_ajax",
            type: "POST",
            data: {
                'is_search_box_show': 'Y',
                'is_customer_basic_data_show':'Y',
                'cid':''
            },
            async: true,
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
          complete: function (){
            $.unblockUI();
          },
          success: function(response) {
              $("#rander_add_new_company_view_title").text("Add Details")
              $('#rander_add_new_company_view_html').html(response);
              $('#rander_add_new_company_view_modal').modal({backdrop: 'static',keyboard: false});
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
}

function fn_rander_company_details2(cid)
{    
    var base_URL = $("#base_url").val();
    var data="cid="+cid;
    $.ajax({
        url: base_URL+"customer/view_company_detail_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
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
        success: function(data){
          result = $.parseJSON(data); 
          $('#lead_company_details').addClass('add_company');
          $("#lead_company_details_body").html(result.html); 
          $('#lead_company_details').modal({backdrop: 'static',keyboard: false}); 
        }
   });
}