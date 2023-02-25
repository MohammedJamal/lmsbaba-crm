//const { result } = require("lodash");

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();     
  $(".select2").select2();


  // Auto Session Expire
  if ($("#is_session_expire_for_idle").is(":checked")) {
    $("#idle_time").attr("disabled", false);
    //$("#auto_session_expire_save_submit").show();
  }
  else {
      $("#idle_time").attr("disabled", true);
      //$("#auto_session_expire_save_submit").hide();
  }
  $("body").on("change", "#is_session_expire_for_idle", function (e) {
      if ($(this).is(":checked")) {
          $("#idle_time").attr("disabled", false);
          //$("#auto_session_expire_save_submit").show();
      }
      else {
          $("#idle_time").attr("disabled", true);   
          //$("#auto_session_expire_save_submit").hide();
      }
  });
  $("body").on("click","#auto_session_expire_save_submit",function(e){
        
    var base_url=$("#base_url").val();
    var edit_id=$("#edit_id").val();
    if ($("#is_session_expire_for_idle").is(":checked")) {
        var is_session_expire_for_idle='Y';
    }
    else {
      var is_session_expire_for_idle='N';
    }
    //var is_cronjobs_auto_regretted_on=$("#is_cronjobs_auto_regretted_on").val();
    var idle_time=$("#idle_time").find("option:selected").val();        
    var data='edit_id='+edit_id+'&is_session_expire_for_idle='+is_session_expire_for_idle+'&idle_time='+idle_time;
    // alert(data); return false;
    
    if (is_session_expire_for_idle=='') 
    {
      swal("Oops!", "Auto Session should not be null",'error');        
      return false;
    } 

    if (idle_time=='') 
    {
      swal("Oops!", "Expire time should not be null",'error');        
      return false;
    } 
     
    
    $.ajax({
            url: base_url+"setting/auto_session_expire_save_submit",
            data: data,                    
            cache: false,
            method: 'GET',
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
            complete: function(){
              $.unblockUI();
            },
            success: function(data){
                result = $.parseJSON(data); 
                                     
                if(result.status=='success')
                {
                  swal({
                      title: "Success",
                      text: "The changes Saved Successfully.",
                      type: "success",
                      confirmButtonText: "ok",
                      allowOutsideClick: "false"
                  }, function () {  
                  });
                }
            }, 
            error: function(response) {
              swal("Oops!", response,'error');      
            }                   
    });
  });
  // Auto Session Expire
  // ------------------------------------------------


 
  // ------------------------------------------------
   // Google Map api key
  $("body").on("click","#google_map_api_key_submit_btn",function(e){
        
    
    var base_url=$("#base_url").val();
    var edit_id=$("#edit_id").val();
    var google_map_api_key=$("#google_map_api_key").val();
           
    var data='edit_id='+edit_id+'&google_map_api_key='+google_map_api_key;
    // alert(data); return false;
    
    // if (google_map_api_key=='') 
    // {
    //   swal("Oops!", "API KEY should not be blank",'error');        
    //   return false;
    // } 
     
    
    $.ajax({
            url: base_url+"setting/google_map_api_key_submit",
            data: data,                    
            cache: false,
            method: 'GET',
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
            complete: function(){
              $.unblockUI();
            },
            success: function(data){
                result = $.parseJSON(data); 
                                     
                if(result.status=='success')
                {
                  swal({
                      title: "Success",
                      text: "The changes Saved Successfully.",
                      type: "success",
                      confirmButtonText: "ok",
                      allowOutsideClick: "false"
                  }, function () {  
                  });
                }
            }, 
            error: function(response) {
              swal("Oops!", response,'error');      
            }                   
    });
  });
  // Google Map api key
  // ------------------------------------------------


    if ($("#is_cronjobs_auto_regretted_on").is(":checked")) {
        $("#auto_regretted_day_interval").attr("disabled", false);
        //$("#auto_regretted_save_submit").show();
    }
    else {
        $("#auto_regretted_day_interval").attr("disabled", true);
        //$("#auto_regretted_save_submit").hide();
    }
    $("body").on("change", "#is_cronjobs_auto_regretted_on", function (e) {
        if ($(this).is(":checked")) {
            $("#auto_regretted_day_interval").attr("disabled", false);
            //$("#auto_regretted_save_submit").show();
        }
        else {
            $("#auto_regretted_day_interval").attr("disabled", true);   
            //$("#auto_regretted_save_submit").hide();
        }
    });

    $("body").on("click",".setting_submit_confirm",function(e){
          
          var name=$("#name").val();
          var address=$("#address").val();
          var country=$("#country").val();
          var state=$("#state").val();
          var city=$("#city").val();
          var pin=$("#pin").val();
          var about_company=$("#about_company").val();
          var gst_number=$("#gst_number").val();
          var pan_number=$("#pan_number").val();
          var default_currency=$("#default_currency").val();
          var ceo_name=$("#ceo_name").val();
          var contact_person=$("#contact_person").val();
          var email1=$("#email1").val();
          var mobile1=$("#mobile1").val();
          var mobile2=$("#mobile2").val();
          var phone1=$("#phone1").val();
          var phone2=$("#phone2").val();
          var website=$("#website").val();
          // var quotation_cover_letter_body_text=$("#quotation_cover_letter_body_text").val();
          var quotation_cover_letter_body_text=tinyMCE.get('quotation_cover_letter_body_text').getContent();
          var quotation_terms_and_conditions=$("#quotation_terms_and_conditions").val();
          // var quotation_cover_letter_footer_text=$("#quotation_cover_letter_footer_text").val();
          var quotation_cover_letter_footer_text=tinyMCE.get('quotation_cover_letter_footer_text').getContent();
          
          var quotation_bank_details1 = tinyMCE.get('quotation_bank_details1').getContent();          
          var quotation_bank_details2 = tinyMCE.get('quotation_bank_details2').getContent();
          
          if(name=='')
          {
            swal("Oops!", "Company Name should not be blank."); 
            return false;
          }
          if(address=='')
          {
            swal("Oops!", "Company Address should not be blank."); 
            return false;
          }
          if(country=='')
          {
            swal("Oops!", "Please select Country."); 
            return false;
          }
          if(state=='')
          {
            swal("Oops!", "Please select State."); 
            return false;
          }
          if(city=='')
          {
            swal("Oops!", "Please select City."); 
            return false;
          }
          if(pin=='')
          {
            swal("Oops!", "Pin Number should not be blank."); 
            return false;
          }

          if(about_company=='')
          {
            swal("Oops!", "About Company should not be blank."); 
            return false;
          }

          if(gst_number=='')
          {
            swal("Oops!", "GST should not be blank."); 
            return false;
          }

          if(pan_number=='')
          {
            swal("Oops!", "PAN should not be blank."); 
            return false;
          }

          if(default_currency=='')
          {
            swal("Oops!", "Default Currency should not be blank."); 
            return false;
          }
          if(ceo_name=='')
          {
            swal("Oops!", "CEO Name should not be blank."); 
            return false;
          }

          if(contact_person=='')
          {
            swal("Oops!", "Contact Person should not be blank."); 
            return false;
          }

          if(email1=='')
          {
            swal("Oops!", "Email 1 should not be blank."); 
            return false;
          }

          if(mobile1=='')
          {
            swal("Oops!", "Mobile 1 should not be blank."); 
            return false;
          }

          if(mobile1=='')
          {
            swal("Oops!", "Mobile 1 should not be blank."); 
            return false;
          }

          if(quotation_bank_details1=='')
          {
            if(quotation_bank_details2!='')
            {
              swal("Oops!", "With out provide Banker's Detail 1 system should not be allowed to fill up Banker's Detail 2."); 
              return false;
            }            
          }

          return true;
    });

    $("body").on("click",".im_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_im_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data); 

                        // $("#im_assign_rule_div").addClass('disabled');
                        $("#im_add_div_toggle").hide(); 
                        $("#im_add_div").slideDown( "slow" );
                        // $("body, html").animate({ scrollTop: 250 }, "slow");               
                        //alert(result.assign_to);    
                        $("#indiamart_setting_id").val(id);
                        $('input[name=is_old_version][value='+result.is_old_version+']').attr('checked', true); 
                        $("#indiamart_account_name").val(result.account_name);
                        $("#indiamart_glusr_mobile").val(result.glusr_mobile);
                        $("#indiamart_glusr_mobile_key").val(result.glusr_mobile_key);
                       
                        
                        //$('#assign_rule').prop('selectedIndex', result.assign_rule);
                        $('#assign_rule').val(result.assign_rule);
                        // $('#assign_rule').attr('disabled',true);
                        fn_rander_im_rule_wise_view(result.assign_rule,id);
                        
                        
                        /*
                        $("#im_submit_confirm").html('Save'); 
                        $('.indiamart_assign_to:input:checkbox').each(function() { 
                          this.checked=false;
                        });
                        $('.indiamart_assign_to:input:checkbox').each(function() { 
                          // if(this.checked == true)
                          // {
                          //   indiamart_assign_to_checked_flag++;
                          // } 
                          //alert(this.value)
                          if(result.assign_to.indexOf(this.value)>-1){
                            this.checked=true;
                          }
                        });
                        */                        
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
    });
    $("body").on("click",".im_delete",function(e){
        var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();
    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_im",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
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
                                $(".preloader").hide();
                                //alert(result.status);
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                    //location.reload(true); 
                                    load_im_credentials(); 
                                });
                               
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      $("body").on("click","#im_submit_confirm",function(e){
    
    
            var base_url=$("#base_url").val();   
            var is_old_version=$('input[name="is_old_version"]:checked').val();         
            var indiamart_account_name_obj=$("#indiamart_account_name");
            var indiamart_glusr_mobile_obj=$("#indiamart_glusr_mobile");
            var indiamart_glusr_mobile_key_obj=$("#indiamart_glusr_mobile_key");
            var assign_rule=$("#assign_rule");
            var data = "";
    
            if(indiamart_account_name_obj.val()=='')
            {
              swal("Oops!", "Account Name should not be blank."); 
              indiamart_account_name_obj.focus();
              return false;
            }
    
            if(indiamart_glusr_mobile_obj.val()=='')
            {
              swal("Oops!", "Mobile should not be blank."); 
              indiamart_glusr_mobile_obj.focus();
              return false;
            }
    
            if(indiamart_glusr_mobile_key_obj.val()=='')
            {
              swal("Oops!", "Key should not be blank."); 
              indiamart_glusr_mobile_key_obj.focus();
              return false;
            }
            
            if(assign_rule.val()=='1') // round robin
            {
               var indiamart_assign_to_checked_flag=0;
               $('#indiamart_assign_to > option:selected').each(function() {  
                     indiamart_assign_to_checked_flag++;              
                });
               // $('.indiamart_assign_to:input:checkbox').each(function() { 
               //   if(this.checked == true)
               //   {
               //     indiamart_assign_to_checked_flag++;
               //   } 
               // });

               if (indiamart_assign_to_checked_flag==0) {
                 swal("Oops!", "Please check atleast one user for assign.");          
                 return false;
               }
            }
            else if(assign_rule.val()=='2') // country
            {
              var rule_count=$("#rule_count").val();
              var rule_activity_count=$("#rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var indiamart_find_to_checked_flag=0;                
                var country_id='country_'+assign_rule.val()+'_'+j;                
                $('#'+country_id+' > option:selected').each(function() {  
                     indiamart_find_to_checked_flag++;
                });

                
                if(indiamart_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
               

              for(let i=1;i<=rule_activity_count;i++)
              {
                var indiamart_assign_to_checked_flag=0;
                var assigned_user_id='indiamart_assign_to_'+assign_rule.val()+'_'+i; 
                $('#'+assigned_user_id+' > option:selected').each(function() {  
                     indiamart_assign_to_checked_flag++;                              
                });

                if(indiamart_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");          
                 return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");        
                 return false;
              }    

              var indiamart_assign_to_2_other=0;
              $('#indiamart_assign_to_2_other > option:selected').each(function() {  
                   indiamart_assign_to_2_other++;              
              });

              if (indiamart_assign_to_2_other==0) {
               swal("Oops!", "Please check atleast one user for all other leads.");          
               return false;
              }          
            }
            else if(assign_rule.val()=='3') // State
            {
              var rule_count=$("#rule_count").val();
              var rule_activity_count=$("#rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var indiamart_find_to_checked_flag=0;                
                var state_id='state_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                     indiamart_find_to_checked_flag++;
                });

                
                if(indiamart_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
               

              for(let i=1;i<=rule_activity_count;i++)
              {
                var indiamart_assign_to_checked_flag=0;                
                //var class_name='indiamart_assign_to_'+assign_rule.val()+'_'+i+':input:checkbox';
                var assigned_user_id='indiamart_assign_to_'+assign_rule.val()+'_'+i;
                // $('.'+class_name).each(function() { 
                //  if(this.checked == true)
                //  {                 
                //    indiamart_assign_to_checked_flag++;
                //  }
                // });

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                     indiamart_assign_to_checked_flag++;                              
                });

                if(indiamart_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");          
                 return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");        
                 return false;
              }    

              var indiamart_assign_to_3_other=0;
              $('#indiamart_assign_to_3_other > option:selected').each(function() {  
                   indiamart_assign_to_3_other++;              
              });

              if (indiamart_assign_to_3_other==0) {
               swal("Oops!", "Please check atleast one user for all other leads.");          
               return false;
              }          
            }
            else if(assign_rule.val()=='4') // State
            {
              var rule_count=$("#rule_count").val();
              var rule_activity_count=$("#rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var indiamart_find_to_checked_flag=0;                
                var city_id='city_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+city_id+' > option:selected').each(function() {  
                     indiamart_find_to_checked_flag++;
                });

                
                if(indiamart_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
               

              for(let i=1;i<=rule_activity_count;i++)
              {
                var indiamart_assign_to_checked_flag=0;                
                //var class_name='indiamart_assign_to_'+assign_rule.val()+'_'+i+':input:checkbox';
                var assigned_user_id='indiamart_assign_to_'+assign_rule.val()+'_'+i;
                // $('.'+class_name).each(function() { 
                //  if(this.checked == true)
                //  {                 
                //    indiamart_assign_to_checked_flag++;
                //  }
                // });

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                     indiamart_assign_to_checked_flag++;                              
                });

                if(indiamart_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");          
                 return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");        
                 return false;
              }    

              var indiamart_assign_to_4_other=0;
              $('#indiamart_assign_to_4_other > option:selected').each(function() {  
                   indiamart_assign_to_4_other++;              
              });

              if (indiamart_assign_to_4_other==0) {
               swal("Oops!", "Please check atleast one user for all other leads.");          
               return false;
              }          
            }
            else if(assign_rule.val()=='5') // Keyword
            {
              var rule_count=$("#rule_count").val();
              var rule_activity_count=$("#rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;
              for(let j=1;j<=rule_activity_count;j++)
              {
                var indiamart_find_to_checked_flag=0;                
                var k_id='keyword_'+assign_rule.val()+'_'+j;                
                if($("#"+k_id).val()){
                  indiamart_find_to_checked_flag++;
                }
                
                if(indiamart_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }               

              for(let i=1;i<=rule_activity_count;i++)
              {
                var indiamart_assign_to_checked_flag=0;
                var assigned_user_id='indiamart_assign_to_'+assign_rule.val()+'_'+i;                

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                     indiamart_assign_to_checked_flag++;                              
                });

                if(indiamart_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");          
                 return false;
              }  

              
              if (chk_assign_to_count!=rule_count) {
                 swal("Oops!", "Required all fields.");        
                 return false;
              }    

              var indiamart_assign_to_5_other=0;
              $('#indiamart_assign_to_5_other > option:selected').each(function() {  
                   indiamart_assign_to_5_other++;              
              });

              if (indiamart_assign_to_5_other==0) {
               swal("Oops!", "Please check atleast one user for all other leads.");          
               return false;
              }          
            }
            
            
            $.ajax({
                url: base_url + "setting/add_edit_indiamart_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    //alert(result.status)
                    if(result.status='success'){

                      swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                        location.reload(true); 

                        // $("#im_add_div_toggle").show(); 
                        // $("#im_add_div").slideToggle();
                        // indiamart_account_name_obj.val('');
                        // indiamart_glusr_mobile_obj.val('');
                        // indiamart_glusr_mobile_key_obj.val('');
                        // $('.indiamart_assign_to:input:checkbox').each(function() { 
                        //     this.checked = false
                        // });
                        // $("#indiamart_setting_id").val('');
                        // $("#im_submit_confirm").html('Save');
                        // load_im_credentials(); 
                    });

                        
                    }
                       
                    
                }
            });
      });

      $("body").on("click","#im_add_div_toggle",function(e){
        $("#im_add_div").slideToggle();  
        //$('#assign_rule').attr('disabled',false);
        $('#assign_rule').prop('selectedIndex', 0);
        $("#im_rule_wise_view").html('');
        $(this).hide();
      });

      
      
      $("body").on("click",".add_more_im",function(e){
          e.preventDefault();
          var ruleid=$("#rule_id").val();
          var existing_rule_count=parseInt($("#rule_count").val());
          var new_rule_count=(existing_rule_count+1);
          $("#rule_count").val(new_rule_count);
          $("#rule_activity_count").val(new_rule_count);
          // alert(new_rule_count+'/'+ruleid+'/new')   
          fn_rander_outer_div(new_rule_count,ruleid,'new');
      });

      $(document).on("click",".del_div",function(e){
        var ruleid=$(this).attr('data-ruleid');
        var cnt=$(this).attr('data-cnt');
        var existing_rule_count=parseInt($("#rule_count").val());      
        var new_rule_count=(existing_rule_count-1);        
        $("#rule_count").val(new_rule_count);
        $("#inner_div_"+ruleid+"_"+cnt).remove();
      });

      $("body").on("click","#im_submit_close",function(e){
        $("#im_add_div").slideToggle();  
        $("#im_add_div_toggle").show();        
        $("#indiamart_account_name").val('');
        $("#indiamart_glusr_mobile").val('');
        $("#indiamart_glusr_mobile_key").val('');

        $('#assign_rule').prop('selectedIndex', 0);
        //$("#im_rule_wise_view").hide();

        // $('.indiamart_assign_to:input:checkbox').each(function() { 
        //     this.checked = false
        // });
        $("#im_rule_wise_view").html('');
        // $("#im_assign_rule_div").removeClass('disabled');
        $("#rule_count").val('0');
        $("#rule_activity_count").val('0');
        $("#indiamart_setting_id").val('');
        $("#im_submit_confirm").html('Save');


      });

      

      $("#fileupload_pdf").on("change", function()
      {
          var files = !!this.files ? this.files : [];
          if (!files.length || !window.FileReader) return; 
          if ( files[0].type=='application/pdf')
          {   
              var base_url=$("#base_url").val();
              var ReaderObj = new FileReader(); // Create instance of the FileReader
              ReaderObj.readAsDataURL(files[0]); // read the file uploaded
              ReaderObj.onloadend = function()
              { 
                var result_obj=this.result;
                  $.ajax({
                      url: base_url+"setting/update_brochure",
                      data: new FormData($('#profile_update_form')[0]),
                      cache: false,
                      method: 'POST',
                      dataType: "html",
                      mimeType: "multipart/form-data",
                      contentType: false,
                      cache: false,
                      processData:false,  
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
                      success: function (data) 
                      {
                        result = $.parseJSON(data);                   
                        
                        $("#PreviewPdf").html(files[0].name+'<span class="del_pdf"><i class="fa fa-trash" area-hidden="true" onclick="remove_pdf(\''+result.edit_id+'\')"></i></span>');
                        $("#PreviewPdf").show();
                        //swal('Success!', 'The PDF has been updated successfully!', 'success');
                      },
                      error: function () 
                      {
    
                      }
                  });
                  // $("#PreviewPdf").html(files[0].name+'<span class="del_pdf"><i class="fa fa-trash" area-hidden="true" onclick="remove_pdf()"></i></span>');
                  // $("#PreviewPdf").show();
              }
          }
          else
          {
            swal('Upload a PDF');
          }
      });

      $("body").on("click","#show_indiamart_glusr_mobile_key",function(e){
        $("#indiamart_glusr_mobile_key").attr("type","text");
        $(this).text("Key Hide");
        $(this).attr("id","hide_indiamart_glusr_mobile_key");
      });

      $("body").on("click","#hide_indiamart_glusr_mobile_key",function(e){
        $("#indiamart_glusr_mobile_key").attr("type","password");
        $(this).text("Key Show");
        $(this).attr("id","show_indiamart_glusr_mobile_key");
      });


      // -------------------------------------------------------
      $("body").on("click","#c2c_add_div_toggle",function(e){
        $("#c2c_add_div").slideToggle();  
        $(this).hide();
      });
      $("body").on("click","input[type='radio'][name='user_c2c_assign_to']:checked",function(e){
          var user_id=$(this).val();
          var base_url=$("#base_url").val();  
          var data="user_id="+user_id;  
          $.ajax({
                  url: base_url+"setting/get_user_info_ajax",
                  data: data,
                  cache: false,
                  method: 'GET',
                  dataType: "html",
                  beforeSend: function( xhr ) {},
                  success: function(data){
                      result = $.parseJSON(data);
                      $("#c2c_caller_name").val(result.name);
                      $("#c2c_mobile").val(result.mobile);
                  },
                  complete: function(){},
          });
      });

      $("body").on("click","#c2c_submit_close",function(e){
            $("#c2c_add_div").slideToggle();  
            $("#c2c_add_div_toggle").show(); 

            $("#c2c_setting_id").val('');
            $('.user_c2c_assign_to:input:radio').each(function() { 
              this.checked = false
            });
            $("#c2c_caller_name").val('');
            $("#c2c_mobile").val('');
            $("#c2c_office_no").val('');
            $("#c2c_submit_confirm").html('Save');
      });
      $("body").on("click","#c2c_credential_submit_confirm",function(e){
    
            var base_url=$("#base_url").val();
            var c2c_api_dial_url_obj=$("#c2c_api_dial_url");
            var c2c_api_userid_obj=$("#c2c_api_userid");
            var c2c_api_password_obj=$("#c2c_api_password");
            var c2c_api_client_obj=$("#c2c_api_client_name");
            var data = "";
    
            if(c2c_api_dial_url_obj.val()=='')
            {
              swal("Oops!", "URL should not be blank."); 
              c2c_api_dial_url_obj.focus();
              return false;
            }    
            
            
            //alert(data); //return false;
            $.ajax({
                url: base_url + "setting/edit_c2c_credentials_setting",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    // alert(result.status)
                    if(result.status=='success')
                    {
                        swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                        }, function () { 
                            //location.reload(true); 
                             
                        });
                    }
                    else
                    {
                      swal('Oops!',"Unknown error",'error');
                    }
                }
            });
      });
      $("body").on("click","#c2c_submit_confirm",function(e){
    
            var base_url=$("#base_url").val();
            var c2c_caller_name_obj=$("#c2c_caller_name");
            var c2c_mobile_obj=$("#c2c_mobile");
            var c2c_office_no_obj=$("#c2c_office_no");
            var data = "";
            
            if(c2c_caller_name_obj.val()=='')
            {
              swal("Oops!", "Name should not be blank."); 
              c2c_caller_name_obj.focus();
              return false;
            }  

            if(c2c_mobile_obj.val()=='')
            {
              swal("Oops!", "Personal Mobile should not be blank."); 
              c2c_mobile_obj.focus();
              return false;
            } 

            if(c2c_office_no_obj.val()=='')
            {
              swal("Oops!", "Office No.( C2C No.) should not be blank."); 
              c2c_office_no_obj.focus();
              return false;
            }    
            
            var user_c2c_assign_to_flag=0;
            $('.user_c2c_assign_to:input:radio').each(function() { 
              if(this.checked == true)
              {
                user_c2c_assign_to_flag++;
              } 
            });
            
            if (user_c2c_assign_to_flag==0) {
              swal("Oops!", "Please check atleast one user for assign.");          
              return false;
            }
            //alert(data); //return false;
            $.ajax({
                url: base_url + "setting/add_edit_c2c_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    
                    if(result.status=='success')
                    {
                        swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                        }, function () { 
                            //location.reload(true); 
                            $("#c2c_add_div_toggle").show(); 
                            $("#c2c_add_div").slideToggle();
                            
                            $('.user_c2c_assign_to:input:radio').each(function() { 
                                this.checked = false
                            });     
                            c2c_caller_name_obj.val(''); 
                            c2c_mobile_obj.val(''); 
                            c2c_office_no_obj.val('');   
                            $("#c2c_setting_id").val('');                
                            $("#c2c_submit_confirm").html('Save');
                            load_c2c_credentials(); 
                        });
                    }
                    else if(result.status='exist')
                    {
                        swal('Oops!',"The user already exist for the C2C",'error');
                    }
                    else
                    {
                      swal('Oops!',"Unknown error",'error');
                    }
                }
            });
      });

      $("body").on("click",".c2c_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_c2c_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);     
                        $("#c2c_add_div_toggle").hide(); 
                        $("#c2c_add_div").slideDown( "slow" );
                        // $("body, html").animate({ scrollTop: 250 }, "slow");               
                        //alert(result.assign_to);    
                        
                        $("#c2c_setting_id").val(result.id);
                        $('#c2c_service_provider_id').val(result.c2c_service_provider_id);
                        $("#c2c_caller_name").val(result.caller_name);
                        $("#c2c_mobile").val(result.mobile);
                        $("#c2c_office_no").val(result.office_no);
                        $("#c2c_submit_confirm").html('Save');
                        $('.user_c2c_assign_to:input:radio').each(function() { 
                          this.checked=false;
                        });

                        $('.user_c2c_assign_to:input:radio').each(function() { 
                          // if(this.checked == true)
                          // {
                          //   indiamart_assign_to_checked_flag++;
                          // } 
                          //alert(this.value)
                          if(result.user_id.indexOf(this.value)>-1){
                            this.checked=true;
                          }
                        });
                        
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
      });
      $("body").on("click",".c2c_delete",function(e){
            var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();
    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_c2c",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
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
                                $(".preloader").hide();
                                //alert(result.status);
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                    //location.reload(true); 
                                    load_c2c_credentials(); 
                                });
                               
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      // --------------------------------------------------------

      // ---------------------------------------------
      $("body").on("click",".lead_stage_edit",function(e){
        var id = $(this).attr('data-id');
        $("#output_div_"+id).hide();
        $("#input_div_inner_"+id).show();
      });
      $("body").on("click",".input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#output_div_"+id).show();
        $("#input_div_inner_"+id).hide();
      });

      $("body").on("click",".lead_stage_edit_submit",function(e){
            var base_url=$("#base_url").val();
            var id=$(this).attr('data-id');
            var stage=$("#stage_"+id).val();
            var data='edit_id='+id+'&stage='+stage;           

            if (stage=='') 
            {
              swal("Oops!", "Name should not be null",'error');        
              return false;
            }  

            $.ajax({
                    url: base_url+"setting/edit_lead_stage_setting",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);                        
                        if(result.status=='success')
                        {
                          $("#output_div_"+id).html(stage);
                          $("#output_div_"+id).show();
                          $("#input_div_inner_"+id).hide();
                        }
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
      });

      $("body").on("click",".lead_stage_delete",function(e){
            var id = $(this).attr('data-id');

            if(id!='')
            {
                var base_url=$("#base_url").val();
    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: true
                }, function () {
                    var data = 'id='+id;
                    $.ajax({
                            url: base_url+"setting/delete_lead_stage",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
                            dataType: "html",
                            //mimeType: "multipart/form-data",
                            //contentType: false,
                            //cache: false,
                            //processData:false,
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
                            complete: function(){
                              $.unblockUI();
                            },
                            success: function(data){
                                result = $.parseJSON(data);

                                if(result.status=='success'){
                                  load_lead_stage_list();
                                }
                                
                               
                            },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });

      $("body").on("click","#lead_stage_add_submit",function(e){
            var base_url=$("#base_url").val();
            var lead_stage_name=$("#lead_stage_name").val();
            var lead_stage_position=$("#lead_stage_position").find("option:selected").val();
            var lead_stage_id_as_per_position=$("#lead_stage_id").find("option:selected").val();
            var data='lead_stage_name='+lead_stage_name+'&lead_stage_position='+lead_stage_position+'&lead_stage_id_as_per_position='+lead_stage_id_as_per_position;
            

            if (lead_stage_name=='') 
            {
              swal("Oops!", "Name should not be null",'error');        
              return false;
            } 

            if (lead_stage_position=='') 
            {
              swal("Oops!", "Stage add position should not be null",'error');        
              return false;
            } 

            if (lead_stage_id_as_per_position=='') 
            {
              swal("Oops!", "Stage as per position should not be null",'error');        
              return false;
            }  
            
            $.ajax({
                    url: base_url+"setting/add_lead_stage_setting",
                    data: data,                    
                    cache: false,
                    method: 'GET',
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
                    complete: function(){
                      $.unblockUI();
                    },
                    success: function(data){
                        result = $.parseJSON(data);                        
                        if(result.status=='success')
                        {
                          load_lead_stage_list();
                        }
                    },                    
            });
      });

      $("body").on("click","#my_document_add_submit",function(e){
            var base_url=$("#base_url").val();
            var md_title=$("#md_title").val();
            
            if (md_title=='') 
            {
              swal("Oops!", "Title should not be blank",'error');        
              return false;
            } 
              
            $.ajax({
                url: base_url + "setting/add_my_document",
                data: new FormData($('#md_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
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
                complete: function(){
                    $.unblockUI();
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    
                    if(result.status=='success'){
                      $("#md_title").val('');
                      $("#md_file").val('');
                      load_my_document();                                            
                    }
                    else{
                      swal("Oops!",result.msg,"error");            
                    }
                }
          });            
      });

      $("body").on("click",".my_document_delete",function(e){
            var id = $(this).attr('data-id');
            if(id!='')
            {
              var base_url=$("#base_url").val();    
              //Warning Message            
              swal({
                  title: "Are you sure?",
                  text: "You will not be able to recover this document!",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonClass: 'btn-warning',
                  confirmButtonText: "Yes, delete it!",
                  closeOnConfirm: true
              }, function () {
                  var data = 'id='+id;                  
                  $.ajax({
                      url: base_url+"setting/delete_document",
                      data: data,
                      //data: new FormData($('#frmAccount')[0]),
                      cache: false,
                      method: 'GET',
                      dataType: "html",
                      //mimeType: "multipart/form-data",
                      //contentType: false,
                      //cache: false,
                      //processData:false,
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
                      complete: function(){
                        $.unblockUI();
                      },
                      success: function(data){
                          result = $.parseJSON(data);
                          if(result.status=='success'){
                            load_my_document();   
                          }
                      },
                  });
                  
              });
             
          }
          else
          { 
              swal("Oops!", "Check the record to delete.");            
          }
      });

      $("body").on("click","#auto_regretted_save_submit",function(e){
        
        var base_url=$("#base_url").val();
        var edit_id=$("#edit_id").val();
        if ($("#is_cronjobs_auto_regretted_on").is(":checked")) {
            var is_cronjobs_auto_regretted_on='Y';
        }
        else {
          var is_cronjobs_auto_regretted_on='N';
        }
        //var is_cronjobs_auto_regretted_on=$("#is_cronjobs_auto_regretted_on").val();
        var auto_regretted_day_interval=$("#auto_regretted_day_interval").find("option:selected").val();        
        var data='edit_id='+edit_id+'&is_cronjobs_auto_regretted_on='+is_cronjobs_auto_regretted_on+'&auto_regretted_day_interval='+auto_regretted_day_interval;
        
        
        if (is_cronjobs_auto_regretted_on=='') 
        {
          swal("Oops!", "Auto Regretted should not be null",'error');        
          return false;
        } 

        if (auto_regretted_day_interval=='') 
        {
          swal("Oops!", "Days position should not be null",'error');        
          return false;
        } 
         
        
        $.ajax({
                url: base_url+"setting/auto_regretted_save_submit",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data); 
                                         
                    if(result.status=='success')
                    {
                      swal({
                          title: "Success",
                          text: "The changes Saved Successfully.",
                          type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () {  
                      });
                    }
                }, 
                error: function(response) {
                  swal("Oops!", response,'error');      
                }                   
        });
      });

      // =========================================================
      // product group
      $("body").on("click",".product_group_edit",function(e){
        var id = $(this).attr('data-id');        
        $("#pg_output_div_"+id).hide();
        $("#pg_input_div_inner_"+id).show();
      });
      $("body").on("click",".pg_input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#pg_output_div_"+id).show();
        $("#pg_input_div_inner_"+id).hide();
      });
      $("body").on("click",".product_group_edit_submit",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        var name=$("#pg_name_"+id).val();
        var data='edit_id='+id+'&name='+name;           

        if (name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        }  

        $.ajax({
                url: base_url+"setting/edit_product_group_setting",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      $("#pg_output_div_"+id).html(name);
                      $("#pg_output_div_"+id).show();
                      $("#pg_input_div_inner_"+id).hide();
                    }
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
      });

      $("body").on("click",".product_group_delete",function(e){
        var id = $(this).attr('data-id');

        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+id;
                $.ajax({
                        url: base_url+"setting/delete_product_group",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
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
                        complete: function(){
                          $.unblockUI();
                        },
                        success: function(data){
                            result = $.parseJSON(data);

                            if(result.status=='success'){
                              load_product_group();
                            }
                            
                           
                        },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });

      $("body").on("click","#product_group_add_submit",function(e){
        var base_url=$("#base_url").val();
        var pg_name=$("#pg_name").val();
        var data='name='+encodeURIComponent(pg_name);
        

        if (pg_name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        } 
        
        $.ajax({
                url: base_url+"setting/add_product_group_setting",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      load_product_group();
                    }
                },                    
        });
      });
      // product group
      // =========================================================

      // =========================================================
      // product category
      $("body").on("click",".product_category_edit",function(e){
        var id = $(this).attr('data-id');        
        $("#pc_output_div_"+id).hide();
        $("#pc_input_div_inner_"+id).show();
      });
      $("body").on("click",".pc_input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#pc_output_div_"+id).show();
        $("#pc_input_div_inner_"+id).hide();
      });
      $("body").on("click",".product_category_edit_submit",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        var name=$("#pc_name_"+id).val();
        var data='edit_id='+id+'&name='+name;           

        if (name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        }  

        $.ajax({
                url: base_url+"setting/edit_product_category_setting",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      $("#pc_output_div_"+id).html(name);
                      $("#pc_output_div_"+id).show();
                      $("#pc_input_div_inner_"+id).hide();
                    }
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
      });

      $("body").on("click",".product_category_delete",function(e){
        var id = $(this).attr('data-id');

        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+id;
                $.ajax({
                        url: base_url+"setting/delete_product_group",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
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
                        complete: function(){
                          $.unblockUI();
                        },
                        success: function(data){
                            result = $.parseJSON(data);

                            if(result.status=='success'){
                              load_product_category();
                            }
                            
                           
                        },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });

      $("body").on("click","#product_category_add_submit",function(e){
        var base_url=$("#base_url").val();
        var pc_group_id=$("#pc_group_id").find("option:selected").val();
        var pc_name=$("#pc_name").val();
        var data='name='+pc_name+"&group_id="+pc_group_id;
        
        if (pc_group_id=='') 
        {
          swal("Oops!", "Group should not be null",'error');        
          return false;
        }

        if (pc_name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        } 
        // alert(data); return false;
        $.ajax({
                url: base_url+"setting/add_product_category_setting",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      load_product_category();
                    }
                },                    
        });
      });
      // product category
      // =========================================================
      
      // =========================================================
      // product unit type
      $("body").on("click",".product_unit_type_edit",function(e){
        var id = $(this).attr('data-id');        
        $("#put_output_div_"+id).hide();
        $("#put_input_div_inner_"+id).show();
      });
      $("body").on("click",".put_input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#put_output_div_"+id).show();
        $("#put_input_div_inner_"+id).hide();
      });
      $("body").on("click",".product_unit_type_edit_submit",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        var name=$("#put_name_"+id).val();
        var data='edit_id='+id+'&name='+name;           

        if (name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        }  

        $.ajax({
                url: base_url+"setting/edit_product_unit_type_setting",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      $("#put_output_div_"+id).html(name);
                      $("#put_output_div_"+id).show();
                      $("#put_input_div_inner_"+id).hide();
                    }
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
      });

      $("body").on("click",".product_unit_type_delete",function(e){
        var id = $(this).attr('data-id');

        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+id;
                $.ajax({
                        url: base_url+"setting/delete_product_unit_type",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
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
                        complete: function(){
                          $.unblockUI();
                        },
                        success: function(data){
                            result = $.parseJSON(data);

                            if(result.status=='success'){
                              load_product_unit_type();
                            }
                            
                           
                        },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });

      $("body").on("click","#product_unit_type_add_submit",function(e){
        var base_url=$("#base_url").val();
        var put_name=$("#put_name").val();
        var data='name='+put_name;
        

        if (put_name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        } 
        
        $.ajax({
                url: base_url+"setting/add_product_unit_type_setting",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      load_product_unit_type();
                    }
                },                    
        });
      });
      // product unit type
      // =========================================================
      


      
      // =========================================================
      // Business type
      $("body").on("click",".business_type_edit",function(e){
        var id = $(this).attr('data-id');        
        $("#bt_output_div_"+id).hide();
        $("#bt_input_div_inner_"+id).show();
      });
      $("body").on("click",".bt_input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#bt_output_div_"+id).show();
        $("#bt_input_div_inner_"+id).hide();
      });
      $("body").on("click",".business_type_edit_submit",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        var name=$("#bt_name_"+id).val();
        var data='edit_id='+id+'&name='+name;           

        if (name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        }  

        $.ajax({
                url: base_url+"setting/edit_business_type_setting",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      $("#bt_output_div_"+id).html(name);
                      $("#bt_output_div_"+id).show();
                      $("#bt_input_div_inner_"+id).hide();
                    }
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
      });
      $("body").on("click","#business_type_add_submit",function(e){
        var base_url=$("#base_url").val();
        var bt_name=$("#bt_name").val();
        var data='name='+bt_name;
        

        if (bt_name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        } 
        
        $.ajax({
                url: base_url+"setting/add_business_type_setting",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      load_business_type();
                    }
                },                    
        });
      });

      $("body").on("click",".business_type_delete",function(e){
        var id = $(this).attr('data-id');

        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+id;
                $.ajax({
                        url: base_url+"setting/delete_business_type",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
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
                        complete: function(){
                          $.unblockUI();
                        },
                        success: function(data){
                            result = $.parseJSON(data);

                            if(result.status=='success'){
                              load_business_type();
                            }
                            
                           
                        },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });
      // Business type
      // =========================================================

      // =========================================================
      // Employee type
      $("body").on("click",".employee_type_edit",function(e){
        var id = $(this).attr('data-id');        
        $("#bt_output_div_"+id).hide();
        $("#bt_input_div_inner_"+id).show();
      });
      $("body").on("click",".bt_input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#bt_output_div_"+id).show();
        $("#bt_input_div_inner_"+id).hide();
      });
      $("body").on("click",".employee_type_edit_submit",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        var name=$("#bt_name_"+id).val();
        var data='edit_id='+id+'&name='+name;           

        if (name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        }  

        $.ajax({
                url: base_url+"setting/edit_employee_type_setting",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      $("#bt_output_div_"+id).html(name);
                      $("#bt_output_div_"+id).show();
                      $("#bt_input_div_inner_"+id).hide();
                    }
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
      });
      $("body").on("click","#employee_type_add_submit",function(e){
        var base_url=$("#base_url").val();
        var et_name=$("#et_name").val();
        var data='name='+et_name;
        

        if (et_name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        } 
        
        $.ajax({
                url: base_url+"setting/add_employee_type_setting",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      load_employee_type();
                    }
                },                    
        });
      });

      $("body").on("click",".employee_type_delete",function(e){
        var id = $(this).attr('data-id');

        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+id;
                $.ajax({
                        url: base_url+"setting/delete_employee_type",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
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
                        complete: function(){
                          $.unblockUI();
                        },
                        success: function(data){
                            result = $.parseJSON(data);

                            if(result.status=='success'){
                              load_employee_type();
                            }
                            
                           
                        },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });
      // Employee type
      // =========================================================

      // =========================================================
      // lead source

      $("body").on("click",".lead_source_edit",function(e){
        var id = $(this).attr('data-id');        
        $("#ls_output_div_"+id).hide();
        $("#ls_input_div_inner_"+id).show();
      });

      $("body").on("click",".ls_input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#ls_output_div_"+id).show();
        $("#ls_input_div_inner_"+id).hide();
      });

      $("body").on("click",".lead_source_edit_submit",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        var name=$("#ls_name_"+id).val();
        var data='edit_id='+id+'&name='+name;           

        if (name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        }  
        
        $.ajax({
                url: base_url+"setting/edit_lead_source_setting",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      $("#ls_output_div_"+id).html(name);
                      $("#ls_output_div_"+id).show();
                      $("#ls_input_div_inner_"+id).hide();
                    }
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
      });

      $("body").on("click",".lead_source_delete",function(e){
        var id = $(this).attr('data-id');

        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+id;
                $.ajax({
                        url: base_url+"setting/delete_lead_source",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
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
                        complete: function(){
                          $.unblockUI();
                        },
                        success: function(data){
                            result = $.parseJSON(data);

                            if(result.status=='success'){
                              load_lead_source();
                            }
                            
                           
                        },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });

      $("body").on("click","#lead_source_add_submit",function(e){
        var base_url=$("#base_url").val();
        var ls_name=$("#ls_name").val();
        var data='name='+ls_name;
        

        if (ls_name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        } 
        
        $.ajax({
                url: base_url+"setting/add_lead_source_setting",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      load_lead_source();
                    }
                },                    
        });
      });
      // lead source
      // =========================================================

      // =========================================================
      // Lead Regret Reasons

      $("body").on("click",".lead_regret_reason_edit",function(e){
        var id = $(this).attr('data-id');        
        $("#lrr_output_div_"+id).hide();
        $("#lrr_input_div_inner_"+id).show();
      });

      $("body").on("click",".lrr_input_div_close",function(e){
        var id = $(this).attr('data-id');
        $("#lrr_output_div_"+id).show();
        $("#lrr_input_div_inner_"+id).hide();
      });

      $("body").on("click",".lead_regret_reason_edit_submit",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        var name=$("#lrr_name_"+id).val();
        var data='edit_id='+id+'&name='+name;           

        if (name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        }  
        
        $.ajax({
                url: base_url+"setting/edit_lead_regret_reason_setting",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      $("#lrr_output_div_"+id).html(name);
                      $("#lrr_output_div_"+id).show();
                      $("#lrr_input_div_inner_"+id).hide();
                    }
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
      });

      $("body").on("click",".lead_regret_reason_delete",function(e){
        var id = $(this).attr('data-id');

        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function () {
                var data = 'id='+id;
                $.ajax({
                        url: base_url+"setting/delete_lead_regret_reason",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
                        dataType: "html",
                        //mimeType: "multipart/form-data",
                        //contentType: false,
                        //cache: false,
                        //processData:false,
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
                        complete: function(){
                          $.unblockUI();
                        },
                        success: function(data){
                            result = $.parseJSON(data);

                            if(result.status=='success'){
                              load_lead_regret_reason();
                            }
                            
                           
                        },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });

      $("body").on("click","#lead_regret_reason_add_submit",function(e){
        var base_url=$("#base_url").val();
        var lrr_name=$("#lrr_name").val();
        var data='name='+lrr_name;
        

        if (lrr_name=='') 
        {
          swal("Oops!", "Name should not be null",'error');        
          return false;
        } 
        
        $.ajax({
                url: base_url+"setting/add_lead_regret_reason_setting",
                data: data,                    
                cache: false,
                method: 'GET',
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
                complete: function(){
                  $.unblockUI();
                },
                success: function(data){
                    result = $.parseJSON(data);                        
                    if(result.status=='success')
                    {
                      load_lead_regret_reason();
                    }
                },                    
        });
      });
      // Lead Regret Reasons
      // =========================================================

      // ---------------------------------------------

      // =================================================================
      // DOMESTIC TERMS AND CONDITIONS
      $("body").on("click",".dterms_copy_to_iterms",function(e){
            var id = $(this).attr('data-id');
            var name=$(this).attr('data-name');
            if(id!='')
            {
                var base_url=$("#base_url").val();    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "Do you want to copy '"+name+"' to Export Lead's T&C ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, do it!",
                    closeOnConfirm: false
                }, function () {
                    var data = 'id='+id;     
                           
                    $.ajax({
                            url: base_url+"setting/copy_dterms_to_iterms",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
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
                                $(".preloader").hide();
                                //alert(result.status);
                                if(result.status=='success')
                                {
                                  swal({
                                        title: "Copied!",
                                        text: "The T&C has been copied to T&C of Export Lead",
                                        type: "success",
                                        confirmButtonText: "ok",
                                        allowOutsideClick: "false"
                                    }, function () { 
                                        //location.reload(true); 
                                        load_international_terms(); 
                                    });
                                }
                                else
                                {
                                  swal({
                                        title: "Oops!",
                                        text: "The T&C already exist in Export Lead's T&C",
                                        type: "error",
                                        confirmButtonText: "ok",
                                        allowOutsideClick: "false"
                                    });
                                }  
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });

      $("body").on("click",".dterms_delete",function(e){
        var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: true
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_domestic_terms",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
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
                                $(".preloader").hide();
                                load_domestic_terms(); 
                                /*
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                    //location.reload(true); 
                                    load_domestic_terms(); 
                                });
                                */
                               
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      $("body").on("click",".dterms_edit",function(e){
              var id = $(this).attr('data-id');
              var data = 'id='+id;   
              var base_url=$("#base_url").val();            
              $.ajax({
                      url: base_url+"setting/get_domestic_terms",
                      data: data,                    
                      cache: false,
                      method: 'GET',
                      dataType: "html",                   
                      beforeSend: function( xhr ) { 
                        //$("#preloader").css('display','block');                           
                      },
                      success: function(data){
                          result = $.parseJSON(data);     
                          $("#dterms_add_div_toggle").hide(); 
                          $("#dterms_add_div").slideDown( "slow" );
                          // $("body, html").animate({ scrollTop: 250 }, "slow");               
                          //alert(result.assign_to);    
                          $("#dterms_name").val(result.name);
                          //$("#dterms_value").val(result.value);
                          tinyMCE.get('dterms_value').setContent(result.value);
                          $("#dterms_id").val(result.id);                          
                      },
                      complete: function(){
                      //$("#preloader").css('display','none');
                      },
              });
      });
      $("body").on("click","#dterns_submit_confirm",function(e){   
    
            var base_url=$("#base_url").val();  
            var dterms_id=$("#dterms_id").val();
            var dterms_name=$("#dterms_name").val();
            var dterms_value=tinyMCE.get('dterms_value').getContent();

            if(dterms_name=='')
            {
              swal("Oops!", "Name should not be blank."); 
              $("#dterms_name").focus();
              return false;
            }

            if(dterms_value=='')
            {
              swal("Oops!", "Value should not be blank.");
              return false;
            }
            
            var data = "dterms_id="+dterms_id+"&dterms_name="+dterms_name+"&dterms_value="+dterms_value;
            
            $.ajax({
                url: base_url + "setting/add_edit_domestic_terms",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {
                    //$("#dterns_submit_confirm").attr("disabled","disabled");
                },
                complete: function(){
                  //$("#dterns_submit_confirm").attr("disabled",false);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status='success'){

                      $("#dterms_add_div_toggle").show(); 
                      $("#dterms_add_div").slideToggle();
                      $("#dterms_name").val('');
                      //$("#dterms_value").val('');
                      tinyMCE.get('dterms_value').setContent('');
                      $("#dterms_id").val('');
                      $("#dterns_submit_confirm").html('Save');
                      load_domestic_terms(); 
                      /*
                      swal({
                            title: "Success!",
                            text: "The record(s) have been saved",
                            type: "success",
                            confirmButtonText: "ok",
                            allowOutsideClick: "false"
                          }, function () { 
                            //location.reload(true); 
                            $("#dterms_add_div_toggle").show(); 
                            $("#dterms_add_div").slideToggle();
                            $("#dterms_name").val('');
                            $("#dterms_value").val('');
                            $("#dterms_id").val('');
                            $("#dterns_submit_confirm").html('Save');
                            load_domestic_terms(); 
                        });
                        */                       
                    }
                }
            });
      });
      $("body").on("click","#dterms_add_div_toggle",function(e){
        $("#dterms_add_div").slideToggle();  
        $(this).hide();
      });

      $("body").on("click","#dterms_submit_close",function(e){
        $("#dterms_add_div").slideToggle();  
        $("#dterms_add_div_toggle").show();        
        $("#dterms_name").val('');
        //$("#dterms_value").val('');
        tinyMCE.get('dterms_value').setContent('');
        $("#dterms_id").val('');
        $("#dterns_submit_confirm").html('Save');
      });
      // DOMESTIC TERMS AND CONDITIONS
      // =================================================================

      // =================================================================
      // INTERNATIONAL TERMS AND CONDITIONS
      $("body").on("click",".iterms_copy_to_dterms",function(e){
          var id = $(this).attr('data-id');
          var name=$(this).attr('data-name');
          if(id!='')
          {
              var base_url=$("#base_url").val();    
              //Warning Message            
              swal({
                  title: "Are you sure?",
                  text: "Do you want to copy '"+name+"' to Domestic Lead's T&C ?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonClass: 'btn-warning',
                  confirmButtonText: "Yes, do it!",
                  closeOnConfirm: false
              }, function () {
                  var data = 'id='+id;     
                        
                  $.ajax({
                          url: base_url+"setting/copy_iterms_to_dterms",
                          data: data,
                          //data: new FormData($('#frmAccount')[0]),
                          cache: false,
                          method: 'GET',
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
                              $(".preloader").hide();
                              //alert(result.status);
                              if(result.status=='success')
                              {
                                swal({
                                      title: "Copied!",
                                      text: "The T&C has been copied to T&C of Domestic Lead",
                                      type: "success",
                                      confirmButtonText: "ok",
                                      allowOutsideClick: "false"
                                  }, function () { 
                                      //location.reload(true); 
                                      load_domestic_terms(); 
                                  });
                              }
                              else
                              {
                                swal({
                                      title: "Oops!",
                                      text: "The T&C already exist in Domestic Lead's T&C",
                                      type: "error",
                                      confirmButtonText: "ok",
                                      allowOutsideClick: "false"
                                  });
                              }  
                          },
                          complete: function(){
                          //$("#preloader").css('display','none');
                        },
                  });
                  
              });
            
          }
          else
          { 
              swal("Oops!", "Check the record to delete.");            
          }
    });

      $("body").on("click",".iterms_delete",function(e){
        var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: true
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_international_terms",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
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
                                $(".preloader").hide();
                                load_international_terms();
                                /*
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                    //location.reload(true); 
                                    load_international_terms(); 
                                });
                                */
                               
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      $("body").on("click",".iterms_edit",function(e){
              var id = $(this).attr('data-id');
              var data = 'id='+id;   
              var base_url=$("#base_url").val();            
              $.ajax({
                      url: base_url+"setting/get_international_terms",
                      data: data,                    
                      cache: false,
                      method: 'GET',
                      dataType: "html",                   
                      beforeSend: function( xhr ) { 
                        //$("#preloader").css('display','block');                           
                      },
                      success: function(data){
                          result = $.parseJSON(data);     
                          $("#iterms_add_div_toggle").hide(); 
                          $("#iterms_add_div").slideDown( "slow" );
                          // $("body, html").animate({ scrollTop: 250 }, "slow");               
                          //alert(result.assign_to);    
                          $("#iterms_name").val(result.name);
                          //$("#iterms_value").val(result.value);
                          tinyMCE.get('iterms_value').setContent(result.value);
                          $("#iterms_id").val(result.id);                          
                      },
                      complete: function(){
                      //$("#preloader").css('display','none');
                      },
              });
      });
      $("body").on("click","#iterns_submit_confirm",function(e){   
    


            var base_url=$("#base_url").val();
            var iterms_id=$("#iterms_id").val();
            var iterms_name=$("#iterms_name").val();
            var iterms_value=tinyMCE.get('iterms_value').getContent();

            if(iterms_name=='')
            {
              swal("Oops!", "Name should not be blank."); 
              $("#iterms_name").focus();
              return false;
            }

            if(iterms_value=='')
            {
              swal("Oops!", "Value should not be blank.");
              return false;
            }
            
            var data = "iterms_id="+iterms_id+"&iterms_name="+iterms_name+"&iterms_value="+iterms_value;
            
            $.ajax({
                url: base_url + "setting/add_edit_international_terms",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status='success'){


                      $("#iterms_add_div_toggle").show(); 
                      $("#iterms_add_div").slideToggle();
                      $("#iterms_name").val('');
                      //$("#iterms_value").val('');
                      tinyMCE.get('iterms_value').setContent('');
                      $("#iterms_id").val('');
                      $("#iterns_submit_confirm").html('Save');
                      load_international_terms();
                      /*
                      swal({
                            title: "Success!",
                            text: "The record(s) have been saved",
                            type: "success",
                            confirmButtonText: "ok",
                            allowOutsideClick: "false"
                        }, function () { 
                            //location.reload(true); 
                            $("#iterms_add_div_toggle").show(); 
                            $("#iterms_add_div").slideToggle();
                            $("#iterms_name").val('');
                            $("#iterms_value").val('');
                            $("#iterms_id").val('');
                            $("#iterns_submit_confirm").html('Save');
                            load_international_terms(); 
                        });
                        */                        
                    }
                }
            });
      });
      $("body").on("click","#iterms_add_div_toggle",function(e){
        $("#iterms_add_div").slideToggle();  
        $(this).hide();
      });

      $("body").on("click","#iterms_submit_close",function(e){
        $("#iterms_add_div").slideToggle();  
        $("#iterms_add_div_toggle").show();        
        $("#iterms_name").val('');
        //$("#iterms_value").val('');
        tinyMCE.get('iterms_value').setContent('');
        $("#iterms_id").val('');
        $("#iterns_submit_confirm").html('Save');
      });
      // INTERNATIONAL TERMS AND CONDITIONS
      // =================================================================
      // =================================================
      // AAJJO API
      $("body").on("click","#aj_add_div_toggle",function(e){
        $("#aj_add_div").slideToggle();  
        $(this).hide();
      });

      $("body").on("click","#aj_submit_close",function(e){
        $("#aj_add_div").slideToggle();  
        $("#aj_add_div_toggle").show();        
        $("#aajjo_account_name").val('');
        $("#aajjo_username").val('');
        $("#aajjo_key").val('');

        $('#aj_assign_rule').prop('selectedIndex', 0);
        $("#aj_rule_wise_view").html('');
        $("#aj_rule_count").val('0');
        $("#aj_rule_activity_count").val('0');
        $("#aajjo_setting_id").val('');
        $("#aj_submit_confirm").html('Save');
      });

      $("body").on("click",".aj_edit",function(e){
          var id = $(this).attr('data-id');
          var data = 'id='+id;   
          var base_url=$("#base_url").val();            
          $.ajax({
                  url: base_url+"setting/get_aj_credentials",
                  data: data,                    
                  cache: false,
                  method: 'GET',
                  dataType: "html",                   
                  beforeSend: function( xhr ) { 
                    //$("#preloader").css('display','block');                           
                  },
                  success: function(data){
                      result = $.parseJSON(data);  

                      // $("#im_assign_rule_div").addClass('disabled');
                      $("#aj_add_div_toggle").hide(); 
                      $("#aj_add_div").slideDown( "slow" );
                      // $("body, html").animate({ scrollTop: 250 }, "slow");               
                      //alert(result.assign_to);    
                      $("#aajjo_setting_id").val(result.id);
                      $("#aajjo_account_name").val(result.account_name);
                      $("#aajjo_username").val(result.username);
                      $("#aajjo_key").val(result.aj_key);                        
                      
                      $('#aj_assign_rule').val(result.assign_rule);
                      fn_rander_aj_rule_wise_view(result.assign_rule,id);
                  },
                  complete: function(){
                  //$("#preloader").css('display','none');
                  },
          });
        });
        $("body").on("click",".aj_delete",function(e){
          var id = $(this).attr('data-id');
              if(id!='')
              {
                  var base_url=$("#base_url").val();

                  //Warning Message            
                  swal({
                      title: "Are you sure?",
                      text: "You will not be able to recover this record!",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonClass: 'btn-warning',
                      confirmButtonText: "Yes, delete it!",
                      closeOnConfirm: false
                  }, function () {
                      var data = 'id='+id;               
                      $.ajax({
                              url: base_url+"setting/delete_aj",
                              data: data,
                              //data: new FormData($('#frmAccount')[0]),
                              cache: false,
                              method: 'GET',
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
                                  $(".preloader").hide();
                                  //alert(result.status);
                                  swal({
                                      title: "Deleted!",
                                      text: "The record(s) have been deleted",
                                      type: "success",
                                      confirmButtonText: "ok",
                                      allowOutsideClick: "false"
                                  }, function () { 
                                      //location.reload(true); 
                                      load_aj_credentials(); 
                                  });
                                
                              },
                              complete: function(){
                              //$("#preloader").css('display','none');
                            },
                      });
                      
                  });
                
              }
              else
              { 
                  swal("Oops!", "Check the record to delete.");            
              }
        });
        $("body").on("click","#aj_submit_confirm",function(e){    
    
          var base_url=$("#base_url").val(); 
          var aajjo_account_name_obj=$("#aajjo_account_name");           
          var aajjo_username_obj=$("#aajjo_username");
          var aajjo_key_obj=$("#aajjo_key");
          var assign_rule=$("#aj_assign_rule");
          var data = "";
          
          if(aajjo_account_name_obj.val()=='')
          {
            swal("Oops!", "Account Name should not be blank."); 
            aajjo_account_name_obj.focus();
            return false;
          }

          if(aajjo_username_obj.val()=='')
          {
            swal("Oops!", "Username should not be blank."); 
            aajjo_username_obj.focus();
            return false;
          }
  
          
  
          if(aajjo_key_obj.val()=='')
          {
            swal("Oops!", "Key should not be blank."); 
            aajjo_key_obj.focus();
            return false;
          }

         

          if(assign_rule.val()=='1') // round-robin
          {
            var aajjo_assign_to_checked_flag=0;
            $('#aajjo_assign_to > option:selected').each(function() { 
              aajjo_assign_to_checked_flag++;              
            });

            if (aajjo_assign_to_checked_flag==0) {
              swal("Oops!", "Please check atleast one user for assign.");          
              return false;
            }
          }
          else if(assign_rule.val()=='2') // country
          {
            var rule_count=$("#aj_rule_count").val();
            var rule_activity_count=$("#aj_rule_activity_count").val();
            var chk_find_to_count=0;
            var chk_assign_to_count=0;

            for(let j=1;j<=rule_activity_count;j++)
            {
              var aajjo_find_to_checked_flag=0;                
              var state_id='country_'+assign_rule.val()+'_'+j;
              //alert($('#'+state_id+' option').filter(":selected").val())
              $('#'+state_id+' > option:selected').each(function() {  
                aajjo_find_to_checked_flag++;
              });

              
              if(aajjo_find_to_checked_flag>0){
                chk_find_to_count++;
              }
            }
            

            for(let i=1;i<=rule_activity_count;i++)
            {
              var aajjo_assign_to_checked_flag=0;                
              var assigned_user_id='aajjo_assign_to_'+assign_rule.val()+'_'+i;

              $('#'+assigned_user_id+' > option:selected').each(function() {  
                aajjo_assign_to_checked_flag++;                              
              });

              if(aajjo_assign_to_checked_flag>0){
                chk_assign_to_count++;
              }
            }
            
            if (chk_find_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");          
              return false;
            }  


            if (chk_assign_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");        
              return false;
            }    

            var aajjo_assign_to_2_other=0;
            $('#aajjo_assign_to_2_other > option:selected').each(function() {  
              aajjo_assign_to_2_other++;              
            });

            if (aajjo_assign_to_2_other==0) {
            swal("Oops!", "Please check atleast one user for all other leads.");          
            return false;
            }          
          }
          else if(assign_rule.val()=='3') // state
          {
            var rule_count=$("#aj_rule_count").val();
            var rule_activity_count=$("#aj_rule_activity_count").val();
            var chk_find_to_count=0;
            var chk_assign_to_count=0;

            for(let j=1;j<=rule_activity_count;j++)
            {
              var aajjo_find_to_checked_flag=0;                
              var state_id='aj_state_'+assign_rule.val()+'_'+j;
              //alert($('#'+state_id+' option').filter(":selected").val())
              $('#'+state_id+' > option:selected').each(function() {  
                aajjo_find_to_checked_flag++;
              });

              
              if(aajjo_find_to_checked_flag>0){
                chk_find_to_count++;
              }
            }
            

            for(let i=1;i<=rule_activity_count;i++)
            {
              var aajjo_assign_to_checked_flag=0;                
              var assigned_user_id='aajjo_assign_to_'+assign_rule.val()+'_'+i;

              $('#'+assigned_user_id+' > option:selected').each(function() {  
                aajjo_assign_to_checked_flag++;                              
              });

              if(aajjo_assign_to_checked_flag>0){
                chk_assign_to_count++;
              }
            }
            
            if (chk_find_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");          
              return false;
            }  


            if (chk_assign_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");        
              return false;
            }    

            var aajjo_assign_to_3_other=0;
            $('#aajjo_assign_to_3_other > option:selected').each(function() {  
              aajjo_assign_to_3_other++;              
            });

            if (aajjo_assign_to_3_other==0) {
            swal("Oops!", "Please check atleast one user for all other leads.");          
            return false;
            }          
          }
          else if(assign_rule.val()=='4') // city
          {
            var rule_count=$("#aj_rule_count").val();
            var rule_activity_count=$("#aj_rule_activity_count").val();
            var chk_find_to_count=0;
            var chk_assign_to_count=0;

            for(let j=1;j<=rule_activity_count;j++)
            {
              var aajjo_find_to_checked_flag=0;                
              var state_id='city_'+assign_rule.val()+'_'+j;
              //alert($('#'+state_id+' option').filter(":selected").val())
              $('#'+state_id+' > option:selected').each(function() {  
                aajjo_find_to_checked_flag++;
              });

              
              if(aajjo_find_to_checked_flag>0){
                chk_find_to_count++;
              }
            }
            

            for(let i=1;i<=rule_activity_count;i++)
            {
              var aajjo_assign_to_checked_flag=0;                
              var assigned_user_id='aajjo_assign_to_'+assign_rule.val()+'_'+i;

              $('#'+assigned_user_id+' > option:selected').each(function() {  
                aajjo_assign_to_checked_flag++;                              
              });

              if(aajjo_assign_to_checked_flag>0){
                chk_assign_to_count++;
              }
            }
            
            if (chk_find_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");          
              return false;
            }  


            if (chk_assign_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");        
              return false;
            }    

            var aajjo_assign_to_4_other=0;
            $('#aajjo_assign_to_4_other > option:selected').each(function() {  
              aajjo_assign_to_4_other++;              
            });

            if (aajjo_assign_to_4_other==0) {
            swal("Oops!", "Please check atleast one user for all other leads.");          
            return false;
            }          
          }
          else if(assign_rule.val()=='5') // Keyword
          {
            var rule_count=$("#aj_rule_count").val();
            var rule_activity_count=$("#aj_rule_activity_count").val();
            var chk_find_to_count=0;
            var chk_assign_to_count=0;

            for(let j=1;j<=rule_activity_count;j++)
            {
              var aajjo_find_to_checked_flag=0;                
              var k_id='keyword_'+assign_rule.val()+'_'+j;                
              if($("#"+k_id).val()){
                aajjo_find_to_checked_flag++;
              }
              
              if(aajjo_find_to_checked_flag>0){
                chk_find_to_count++;
              }
            }
            

            for(let i=1;i<=rule_activity_count;i++)
            {
              var aajjo_assign_to_checked_flag=0;                
              var assigned_user_id='aajjo_assign_to_'+assign_rule.val()+'_'+i;

              $('#'+assigned_user_id+' > option:selected').each(function() {  
                aajjo_assign_to_checked_flag++;                              
              });

              if(aajjo_assign_to_checked_flag>0){
                chk_assign_to_count++;
              }
            }
            
            if (chk_find_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");          
              return false;
            }  


            if (chk_assign_to_count!=rule_count) {
              swal("Oops!", "Required all fields.");        
              return false;
            }    

            var aajjo_assign_to_5_other=0;
            $('#aajjo_assign_to_5_other > option:selected').each(function() {  
              aajjo_assign_to_5_other++;              
            });

            if (aajjo_assign_to_5_other==0) {
            swal("Oops!", "Please check atleast one user for all other leads.");          
            return false;
            }          
          }
          // alert(data); return false;
          $.ajax({
              url: base_url + "setting/add_edit_aajjo_credentials",
              data: new FormData($('#profile_update_form')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData: false,
              beforeSend: function(xhr) {
                  //$("#company_assigne_change_submit").attr("disabled",true);
              },
              success: function(data) {
                  result = $.parseJSON(data);
                  if(result.status='success'){
                      swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                        type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                      }, function () { 
                        //location.reload(true); 
                        $("#aj_add_div").slideToggle();  
                        $("#aj_add_div_toggle").show();        
                        $("#aajjo_account_name").val('');
                        $("#aajjo_username").val('');
                        $("#aajjo_key").val('');

                        $('#aj_assign_rule').prop('selectedIndex', 0);
                        $("#aj_rule_wise_view").html('');
                        $("#aj_rule_count").val('0');
                        $("#aj_rule_activity_count").val('0');
                        $("#aajjo_setting_id").val('');
                        $("#aj_submit_confirm").html('Save');
                        
                        load_aj_credentials(); 
                      });                        
                  } 
              }
          });
      });
      $("body").on("click",".aj_add_more",function(e){
        e.preventDefault();
        var ruleid=$("#aj_rule_id").val();
        var existing_rule_count=parseInt($("#aj_rule_count").val());
        var new_rule_count=(existing_rule_count+1);
        $("#aj_rule_count").val(new_rule_count);
        $("#aj_rule_activity_count").val(new_rule_count);          
        fn_rander_outer_div_aj(new_rule_count,ruleid,'new');
      });
      // AAJJO API
      // =================================================
      // =================================================
      // EXPORTERINDIA API
      $("body").on("click","#ei_add_div_toggle",function(e){
        $("#ei_add_div").slideToggle();  
        $(this).hide();
      });
      $("body").on("change","#ei_assign_rule",function(e){
        var r_id=$(this).val(); 
        fn_rander_ei_rule_wise_view(r_id);    
      });
      $("body").on("click",".ei_add_more",function(e){
          e.preventDefault();
          var ruleid=$("#ei_rule_id").val();
          var existing_rule_count=parseInt($("#ei_rule_count").val());
          var new_rule_count=(existing_rule_count+1);
          $("#ei_rule_count").val(new_rule_count);
          $("#ei_rule_activity_count").val(new_rule_count);          
          fn_rander_outer_div_ti(new_rule_count,ruleid,'new');
      });
      $("body").on("click","#ei_submit_close",function(e){
        $("#ei_add_div").slideToggle();  
        $("#ei_add_div_toggle").show();        
        $("#exporterindia_account_name").val('');
        $("#exporterindia_userid").val('');
        $("#exporterindia_profile_id").val('');
        $("#exporterindia_key").val('');

        $('#ei_assign_rule').prop('selectedIndex', 0);
        $("#ei_rule_wise_view").html('');
        $("#ei_rule_count").val('0');
        $("#ei_rule_activity_count").val('0');
        $("#exporterindia_setting_id").val('');
        $("#ei_submit_confirm").html('Save');
      });
      $("body").on("click","#ei_submit_confirm",function(e){    
    
            var base_url=$("#base_url").val(); 
            var tradeindia_account_name_obj=$("#exporterindia_account_name");           
            var tradeindia_userid_obj=$("#exporterindia_userid");
            var tradeindia_profile_id_obj=$("#exporterindia_profile_id");
            var tradeindia_key_obj=$("#exporterindia_key");
            var assign_rule=$("#ei_assign_rule");
            var data = "";
            
            if(tradeindia_account_name_obj.val()=='')
            {
              swal("Oops!", "Account Name should not be blank."); 
              tradeindia_account_name_obj.focus();
              return false;
            }

            if(tradeindia_userid_obj.val()=='')
            {
              swal("Oops!", "User ID should not be blank."); 
              tradeindia_userid_obj.focus();
              return false;
            }

            if(tradeindia_profile_id_obj.val()=='')
            {
              swal("Oops!", "Profile ID should not be blank."); 
              tradeindia_profile_id_obj.focus();
              return false;
            }

            if(tradeindia_key_obj.val()=='')
            {
              swal("Oops!", "Key should not be blank."); 
              tradeindia_key_obj.focus();
              return false;
            }

            // var tradeindia_assign_to_checked_flag=0;
            // $('.tradeindia_assign_to:input:checkbox').each(function() { 
            //   if(this.checked == true)
            //   {
            //     tradeindia_assign_to_checked_flag++;
            //   } 
            // });
            
            // if (tradeindia_assign_to_checked_flag==0) {
            //   swal("Oops!", "Please check atleast one user for assign.");          
            //   return false;
            // }

            if(assign_rule.val()=='1') // round-robin
            {
              var tradeindia_assign_to_checked_flag=0;
              $('#exporterindia_assign_to > option:selected').each(function() { 
                  tradeindia_assign_to_checked_flag++;              
              });

              if (tradeindia_assign_to_checked_flag==0) {
                swal("Oops!", "Please check atleast one user for assign.");          
                return false;
              }
            }
            else if(assign_rule.val()=='2') // country
            {
              var rule_count=$("#ei_rule_count").val();
              var rule_activity_count=$("#ei_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var state_id='country_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  tradeindia_find_to_checked_flag++;
                });

                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='exporterindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_2_other=0;
              $('#exporterindia_assign_to_2_other > option:selected').each(function() {  
                  tradeindia_assign_to_2_other++;              
              });

              if (tradeindia_assign_to_2_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='3') // state
            {
              var rule_count=$("#ei_rule_count").val();
              var rule_activity_count=$("#ei_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var state_id='ei_state_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  tradeindia_find_to_checked_flag++;
                });

                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='exporterindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_3_other=0;
              $('#exporterindia_assign_to_3_other > option:selected').each(function() {  
                  tradeindia_assign_to_3_other++;              
              });

              if (tradeindia_assign_to_3_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='4') // city
            {
              var rule_count=$("#ei_rule_count").val();
              var rule_activity_count=$("#ei_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var state_id='city_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  tradeindia_find_to_checked_flag++;
                });

                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='exporterindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_4_other=0;
              $('#exporterindia_assign_to_4_other > option:selected').each(function() {  
                  tradeindia_assign_to_4_other++;              
              });

              if (tradeindia_assign_to_4_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='5') // Keyword
            {
              var rule_count=$("#ei_rule_count").val();
              var rule_activity_count=$("#ei_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var k_id='keyword_'+assign_rule.val()+'_'+j;                
                if($("#"+k_id).val()){
                  tradeindia_find_to_checked_flag++;
                }
                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='exporterindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_5_other=0;
              $('#exporterindia_assign_to_5_other > option:selected').each(function() {  
                  tradeindia_assign_to_5_other++;              
              });

              if (tradeindia_assign_to_5_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            // alert(data); return false;
            $.ajax({
                url: base_url + "setting/add_edit_exporterindia_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status=='success'){
                        swal({
                          title: "Success!",
                          text: "The record(s) have been saved",
                          type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                        }, function () { 
                          //location.reload(true); 
                          $("#ei_add_div").slideToggle();  
                          $("#ei_add_div_toggle").show();        
                          $("#exporterindia_account_name").val('');
                          $("#exporterindia_userid").val('');
                          $("#exporterindia_profile_id").val('');
                          $("#exporterindia_key").val('');

                          $('#ei_assign_rule').prop('selectedIndex', 0);
                          $("#ei_rule_wise_view").html('');
                          $("#ei_rule_count").val('0');
                          $("#ei_rule_activity_count").val('0');
                          $("#exporterindia_setting_id").val('');
                          $("#ei_submit_confirm").html('Save');
                          load_ei_credentials(); 
                        });                        
                    }
                    else{
                      swal("Oops!", "Unknown error! Try again later",'error');
                    } 
                }
            });
      });
      $("body").on("click",".ei_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_ei_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);  

                        // $("#im_assign_rule_div").addClass('disabled');
                        $("#ei_add_div_toggle").hide(); 
                        $("#ei_add_div").slideDown( "slow" );
                        // $("body, html").animate({ scrollTop: 250 }, "slow");               
                        //alert(result.assign_to);    
                        $("#exporterindia_setting_id").val(result.id);
                        $("#exporterindia_account_name").val(result.account_name);
                        $("#exporterindia_userid").val(result.userid);
                        $("#exporterindia_profile_id").val(result.profileid);
                        $("#exporterindia_key").val(result.ti_key);                        
                        
                        $('#ei_assign_rule').val(result.assign_rule);
                        fn_rander_ei_rule_wise_view(result.assign_rule,id);                        
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
          });
          $("body").on("click",".ei_delete",function(e){
            var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();

                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_ei",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
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
                                $(".preloader").hide();
                                //alert(result.status);
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                    type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                    //location.reload(true); 
                                    load_ei_credentials(); 
                                });
                              
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                          },
                    });
                    
                });
              
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      // EXPORTERINDIA API
      // =================================================
      


      // =================================================
      // TRADEINDIA API
      $("body").on("click","#ti_add_div_toggle",function(e){
        $("#ti_add_div").slideToggle();  
        $(this).hide();
      });

      // $("body").on("click","#ti_submit_close",function(e){
      //   $("#ti_add_div").slideToggle();  
      //   $("#ti_add_div_toggle").show();   
      //   $("#tradeindia_account_name").val('');     
      //   $("#tradeindia_userid").val('');
      //   $("#tradeindia_profile_id").val('');
      //   $("#tradeindia_key").val('');       

      //   $('.tradeindia_assign_to:input:checkbox').each(function() { 
      //       this.checked = false
      //   });
      //   $("#tradeindia_setting_id").val('');
      //   $("#ti_submit_confirm").html('Save');

      // });

      

      $("body").on("click",".ti_add_more",function(e){
          e.preventDefault();
          var ruleid=$("#ti_rule_id").val();
          var existing_rule_count=parseInt($("#ti_rule_count").val());
          var new_rule_count=(existing_rule_count+1);
          $("#ti_rule_count").val(new_rule_count);
          $("#ti_rule_activity_count").val(new_rule_count);          
          fn_rander_outer_div_ti(new_rule_count,ruleid,'new');
      });
      

      $(document).on("click",".ti_del_div",function(e){
        var ruleid=$(this).attr('data-ruleid');
        var cnt=$(this).attr('data-cnt');
        var existing_rule_count=parseInt($("#ti_rule_count").val());       
        var new_rule_count=(existing_rule_count-1);        
        $("#ti_rule_count").val(new_rule_count);
        $("#ti_inner_div_"+ruleid+"_"+cnt).remove();
      });

      $("body").on("click","#ti_submit_close",function(e){
        $("#ti_add_div").slideToggle();  
        $("#ti_add_div_toggle").show();        
        $("#tradeindia_account_name").val('');
        $("#tradeindia_userid").val('');
        $("#tradeindia_profile_id").val('');
        $("#tradeindia_key").val('');

        $('#ti_assign_rule').prop('selectedIndex', 0);
        $("#ti_rule_wise_view").html('');
        $("#ti_rule_count").val('0');
        $("#ti_rule_activity_count").val('0');
        $("#tradeindia_setting_id").val('');
        $("#ti_submit_confirm").html('Save');
      });
      
      $("body").on("click","#ti_submit_confirm",function(e){    
    
            var base_url=$("#base_url").val(); 
            var tradeindia_account_name_obj=$("#tradeindia_account_name");           
            var tradeindia_userid_obj=$("#tradeindia_userid");
            var tradeindia_profile_id_obj=$("#tradeindia_profile_id");
            var tradeindia_key_obj=$("#tradeindia_key");
            var assign_rule=$("#ti_assign_rule");
            var data = "";
            
            if(tradeindia_account_name_obj.val()=='')
            {
              swal("Oops!", "Account Name should not be blank."); 
              tradeindia_account_name_obj.focus();
              return false;
            }

            if(tradeindia_userid_obj.val()=='')
            {
              swal("Oops!", "User ID should not be blank."); 
              tradeindia_userid_obj.focus();
              return false;
            }
    
            if(tradeindia_profile_id_obj.val()=='')
            {
              swal("Oops!", "Profile ID should not be blank."); 
              tradeindia_profile_id_obj.focus();
              return false;
            }
    
            if(tradeindia_key_obj.val()=='')
            {
              swal("Oops!", "Key should not be blank."); 
              tradeindia_key_obj.focus();
              return false;
            }

            // var tradeindia_assign_to_checked_flag=0;
            // $('.tradeindia_assign_to:input:checkbox').each(function() { 
            //   if(this.checked == true)
            //   {
            //     tradeindia_assign_to_checked_flag++;
            //   } 
            // });
            
            // if (tradeindia_assign_to_checked_flag==0) {
            //   swal("Oops!", "Please check atleast one user for assign.");          
            //   return false;
            // }

            if(assign_rule.val()=='1') // round-robin
            {
              var tradeindia_assign_to_checked_flag=0;
              $('#tradeindia_assign_to > option:selected').each(function() { 
                  tradeindia_assign_to_checked_flag++;              
              });

              if (tradeindia_assign_to_checked_flag==0) {
                swal("Oops!", "Please check atleast one user for assign.");          
                return false;
              }
            }
            else if(assign_rule.val()=='2') // country
            {
              var rule_count=$("#ti_rule_count").val();
              var rule_activity_count=$("#ti_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var state_id='country_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  tradeindia_find_to_checked_flag++;
                });

                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='tradeindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_2_other=0;
              $('#tradeindia_assign_to_2_other > option:selected').each(function() {  
                  tradeindia_assign_to_2_other++;              
              });

              if (tradeindia_assign_to_2_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='3') // state
            {
              var rule_count=$("#ti_rule_count").val();
              var rule_activity_count=$("#ti_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var state_id='ti_state_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  tradeindia_find_to_checked_flag++;
                });

                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='tradeindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_3_other=0;
              $('#tradeindia_assign_to_3_other > option:selected').each(function() {  
                  tradeindia_assign_to_3_other++;              
              });

              if (tradeindia_assign_to_3_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='4') // city
            {
              var rule_count=$("#ti_rule_count").val();
              var rule_activity_count=$("#ti_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var state_id='city_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  tradeindia_find_to_checked_flag++;
                });

                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='tradeindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_4_other=0;
              $('#tradeindia_assign_to_4_other > option:selected').each(function() {  
                  tradeindia_assign_to_4_other++;              
              });

              if (tradeindia_assign_to_4_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='5') // Keyword
            {
              var rule_count=$("#ti_rule_count").val();
              var rule_activity_count=$("#ti_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var tradeindia_find_to_checked_flag=0;                
                var k_id='keyword_'+assign_rule.val()+'_'+j;                
                if($("#"+k_id).val()){
                  tradeindia_find_to_checked_flag++;
                }
                
                if(tradeindia_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var tradeindia_assign_to_checked_flag=0;                
                var assigned_user_id='tradeindia_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  tradeindia_assign_to_checked_flag++;                              
                });

                if(tradeindia_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var tradeindia_assign_to_5_other=0;
              $('#tradeindia_assign_to_5_other > option:selected').each(function() {  
                  tradeindia_assign_to_5_other++;              
              });

              if (tradeindia_assign_to_5_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            // alert(data); return false;
            $.ajax({
                url: base_url + "setting/add_edit_tradeindia_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status='success'){
                        swal({
                          title: "Success!",
                          text: "The record(s) have been saved",
                          type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                        }, function () { 
                          //location.reload(true); 
                          $("#ti_add_div").slideToggle();  
                          $("#ti_add_div_toggle").show();        
                          $("#tradeindia_account_name").val('');
                          $("#tradeindia_userid").val('');
                          $("#tradeindia_profile_id").val('');
                          $("#tradeindia_key").val('');

                          $('#ti_assign_rule').prop('selectedIndex', 0);
                          $("#ti_rule_wise_view").html('');
                          $("#ti_rule_count").val('0');
                          $("#ti_rule_activity_count").val('0');
                          $("#tradeindia_setting_id").val('');
                          $("#ti_submit_confirm").html('Save');


                          // $("#ti_add_div_toggle").show(); 
                          // $("#ti_add_div").slideToggle();
                          // tradeindia_account_name_obj.val('')
                          // tradeindia_userid_obj.val('');
                          // tradeindia_profile_id_obj.val('');
                          // tradeindia_key_obj.val('');                        
                          // $('.tradeindia_assign_to:input:checkbox').each(function() { 
                          //     this.checked = false
                          // });
                          // $("#indiamart_setting_id").val('');
                          // $("#im_submit_confirm").html('Save');
                          load_ti_credentials(); 
                        });                        
                    } 
                }
            });
      });
      
      $("body").on("click",".ti_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_ti_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);  

                        // $("#im_assign_rule_div").addClass('disabled');
                        $("#ti_add_div_toggle").hide(); 
                        $("#ti_add_div").slideDown( "slow" );
                        // $("body, html").animate({ scrollTop: 250 }, "slow");               
                        //alert(result.assign_to);    
                        $("#tradeindia_setting_id").val(result.id);
                        $("#tradeindia_account_name").val(result.account_name);
                        $("#tradeindia_userid").val(result.userid);
                        $("#tradeindia_profile_id").val(result.profileid);
                        $("#tradeindia_key").val(result.ti_key);                        
                         
                        $('#ti_assign_rule').val(result.assign_rule);
                        fn_rander_ti_rule_wise_view(result.assign_rule,id);
                         
                         
                         


                        // console.log(result)   
                        // $("#ti_add_div_toggle").hide(); 
                        // $("#ti_add_div").slideDown( "slow" );    
                        // $("#tradeindia_setting_id").val(result.id);
                        // $("#tradeindia_account_name").val(result.account_name);
                        // $("#tradeindia_userid").val(result.userid);
                        // $("#tradeindia_profile_id").val(result.profileid);
                        // $("#tradeindia_key").val(result.ti_key);
                        // $("#ti_submit_confirm").html('Save');
                        // $('.tradeindia_assign_to:input:checkbox').each(function() { 
                        //   this.checked=false;
                        // });

                        // $('.tradeindia_assign_to:input:checkbox').each(function() { 
                        //   if(result.assign_to.indexOf(this.value)>-1){
                        //     this.checked=true;
                        //   }
                        // });
                        
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
      });
      $("body").on("click",".ti_delete",function(e){
        var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();
    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_ti",
                            data: data,
                            //data: new FormData($('#frmAccount')[0]),
                            cache: false,
                            method: 'GET',
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
                                $(".preloader").hide();
                                //alert(result.status);
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                    //location.reload(true); 
                                    load_ti_credentials(); 
                                });
                               
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      // TRADEINDIA API
      // =================================================

      // =================================================
      // JUSTDIAL SETTING
      $("body").on("click","#jd_add_div_toggle",function(e){
        $("#jd_add_div").slideToggle();  
        $(this).hide();
      });
      $(document).on("click",".jd_del_div",function(e){
        var ruleid=$(this).attr('data-ruleid');
        var cnt=$(this).attr('data-cnt');
        var existing_rule_count=parseInt($("#jd_rule_count").val());       
        var new_rule_count=(existing_rule_count-1);        
        $("#jd_rule_count").val(new_rule_count);
        $("#jd_inner_div_"+ruleid+"_"+cnt).remove();
      });

      

      $("body").on("click",".jd_add_more",function(e){
        e.preventDefault();
          var ruleid=$("#jd_rule_id").val();
          var existing_rule_count=parseInt($("#jd_rule_count").val());
          var new_rule_count=(existing_rule_count+1);
          $("#jd_rule_count").val(new_rule_count);
          $("#jd_rule_activity_count").val(new_rule_count);          
          fn_rander_outer_div_jd(new_rule_count,ruleid,'new');
      });

      $("body").on("click","#jd_submit_close",function(e){
        $("#jd_add_div").slideToggle(); 
        // $("#jd_add_div_toggle").slideToggle(); 
        if($('#jd_add_div_toggle').css('display') == 'none'){          
        }
        else{
          $("#jd_add_div_toggle").show();
        }       

        // $('.justdial_assign_to:input:checkbox').each(function() { 
        //     this.checked = false
        // });

        $('#jd_assign_rule').prop('selectedIndex', 0);
        $("#jd_rule_wise_view").html('');
        $("#jd_rule_count").val('0');
        $("#jd_rule_activity_count").val('0');
        $("#justdial_setting_id").val('');
        $("#jd_submit_confirm").html('Save');
        load_jd_credentials(); 
      });

     

      $("body").on("click","#jd_submit_confirm",function(e){    
    
            var base_url=$("#base_url").val();  
            var assign_rule=$("#jd_assign_rule");          
            var data = "";
            // var justdial_assign_to_checked_flag=0;
            // $('.justdial_assign_to:input:checkbox').each(function() { 
            //   if(this.checked == true)
            //   {
            //     justdial_assign_to_checked_flag++;
            //   } 
            // });
            
            // if (justdial_assign_to_checked_flag==0) {
            //   swal("Oops!", "Please check atleast one user for assign.");          
            //   return false;
            // }
            
            if(assign_rule.val()=='1')
            {
              var justdial_assign_to_checked_flag=0;
              $('#justdial_assign_to > option:selected').each(function() { 
                justdial_assign_to_checked_flag++;              
              });

              if (justdial_assign_to_checked_flag==0) {
                swal("Oops!", "Please check atleast one user for assign.");          
                return false;
              }
            }
            else if(assign_rule.val()=='2')
            {
              var rule_count=$("#jd_rule_count").val();
              var rule_activity_count=$("#jd_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var justdial_find_to_checked_flag=0;                
                var state_id='country_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  justdial_find_to_checked_flag++;
                });

                
                if(justdial_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var justdial_assign_to_checked_flag=0;                
                var assigned_user_id='justdial_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  justdial_assign_to_checked_flag++;                              
                });

                if(justdial_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var justdial_assign_to_2_other=0;
              $('#justdial_assign_to_2_other > option:selected').each(function() {  
                justdial_assign_to_2_other++;              
              });

              if (justdial_assign_to_2_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='3')
            {
              var rule_count=$("#jd_rule_count").val();
              var rule_activity_count=$("#jd_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var justdial_find_to_checked_flag=0;                
                var state_id='jd_state_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  justdial_find_to_checked_flag++;
                });

                
                if(justdial_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var justdial_assign_to_checked_flag=0;                
                var assigned_user_id='justdial_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  justdial_assign_to_checked_flag++;                              
                });

                if(justdial_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var justdial_assign_to_3_other=0;
              $('#justdial_assign_to_3_other > option:selected').each(function() {  
                justdial_assign_to_3_other++;              
              });

              if (justdial_assign_to_3_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='4')
            {
              var rule_count=$("#jd_rule_count").val();
              var rule_activity_count=$("#jd_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var justdial_find_to_checked_flag=0;                
                var state_id='city_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  justdial_find_to_checked_flag++;
                });                
                if(justdial_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var justdial_assign_to_checked_flag=0;                
                var assigned_user_id='justdial_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  justdial_assign_to_checked_flag++;                              
                });

                if(justdial_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var justdial_assign_to_4_other=0;
              $('#justdial_assign_to_4_other > option:selected').each(function() {  
                justdial_assign_to_4_other++;              
              });

              if (justdial_assign_to_4_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='5')
            {
              var rule_count=$("#jd_rule_count").val();
              var rule_activity_count=$("#jd_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var justdial_find_to_checked_flag=0;                
                var k_id='keyword_'+assign_rule.val()+'_'+j;                
                if($("#"+k_id).val()){
                  justdial_find_to_checked_flag++;
                }
                
                if(justdial_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var justdial_assign_to_checked_flag=0;                
                var assigned_user_id='justdial_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  justdial_assign_to_checked_flag++;                              
                });

                if(justdial_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var justdial_assign_to_5_other=0;
              $('#justdial_assign_to_5_other > option:selected').each(function() {  
                justdial_assign_to_5_other++;              
              });

              if (justdial_assign_to_5_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            // alert(data); return false;
            $.ajax({
                url: base_url + "setting/add_edit_justdial_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status='success'){

                      swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                        //location.reload(true); 
                        $("#jd_add_div_toggle").show(); 
                        $("#jd_add_div").slideToggle();
                                               
                        // $('.justdial_assign_to:input:checkbox').each(function() { 
                        //     this.checked = false
                        // });

                        $('#jd_assign_rule').prop('selectedIndex', 0);
                        $("#jd_rule_wise_view").html('');
                        $("#jd_rule_count").val('0');
                        $("#jd_rule_activity_count").val('0');
                        $("#justdial_setting_id").val('');
                        $("#jd_submit_confirm").html('Save');                        
                        load_jd_credentials(); 
                    });

                        
                    }
                       
                    
                }
            });
      });

      $("body").on("click",".jd_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_jd_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);  
                        // console.log(result)   
                        $("#jd_add_div_toggle").hide(); 
                        $("#jd_add_div").slideDown( "slow" );
                        // $("body, html").animate({ scrollTop: 250 }, "slow");               
                        //alert(result.assign_to);    
                        $("#justdial_setting_id").val(result.id);
                        // $("#justdial_account_name").val(result.account_name);
                        $("#jd_submit_confirm").html('Save');
                        // $('.justdial_assign_to:input:checkbox').each(function() { 
                        //   this.checked=false;
                        // });

                        // $('.justdial_assign_to:input:checkbox').each(function() { 
                          
                        //   if(result.assign_to.indexOf(this.value)>-1){
                        //     this.checked=true;
                        //   }
                        // });

                        $('#jd_assign_rule').val(result.assign_rule);
                        fn_rander_jd_rule_wise_view(result.assign_rule,id);
                        
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
      });
      $("body").on("click",".jd_delete",function(e){
        var id = $(this).attr('data-id');
        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                var data = 'id='+id;               
                $.ajax({
                        url: base_url+"setting/delete_jd",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
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
                            $(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Deleted!",
                                text: "The record(s) have been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                load_jd_credentials(); 
                            });
                           
                        },
                        complete: function(){
                        //$("#preloader").css('display','none');
                       },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });
      // JUSTDIAL SETTING
      // =================================================

      // =================================================
      // WEBSITE SETTING
      $("body").on("click","#web_add_div_toggle",function(e){
        $("#web_add_div").slideToggle();  
        $(this).hide();
      });

      $(document).on("click",".web_del_div",function(e){
        var ruleid=$(this).attr('data-ruleid');
        var cnt=$(this).attr('data-cnt');
        var existing_rule_count=parseInt($("#web_rule_count").val());       
        var new_rule_count=(existing_rule_count-1);     
        $("#web_rule_count").val(new_rule_count);
        $("#web_inner_div_"+ruleid+"_"+cnt).remove();
      });

      $("body").on("click",".web_add_more",function(e){
          e.preventDefault();
          var ruleid=$("#web_rule_id").val();
          var existing_rule_count=parseInt($("#web_rule_count").val());
          var new_rule_count=(existing_rule_count+1);
          $("#web_rule_count").val(new_rule_count);
          $("#web_rule_activity_count").val(new_rule_count);          
          fn_rander_outer_div_web(new_rule_count,ruleid,'new');
      });

      $("body").on("click","#web_submit_close",function(e){
        $("#web_add_div").slideToggle(); 
        // $("#jd_add_div_toggle").slideToggle(); 
        if($('#web_add_div_toggle').css('display') == 'none'){          
        }
        else{
          $("#web_add_div_toggle").show();
        }       

        // $('.justdial_assign_to:input:checkbox').each(function() { 
        //     this.checked = false
        // });

        $('#web_assign_rule').prop('selectedIndex', 0);
        $("#web_rule_wise_view").html('');
        $("#web_rule_count").val('0');
        $("#web_rule_activity_count").val('0');
        $("#website_setting_id").val('');
        $("#web_submit_confirm").html('Save');
        load_web_credentials(); 
      });

      $("body").on("click","#web_submit_confirm",function(e){    
    
            var base_url=$("#base_url").val();  
            var assign_rule=$("#web_assign_rule");          
            var data = "";
            // var justdial_assign_to_checked_flag=0;
            // $('.justdial_assign_to:input:checkbox').each(function() { 
            //   if(this.checked == true)
            //   {
            //     justdial_assign_to_checked_flag++;
            //   } 
            // });
            
            // if (justdial_assign_to_checked_flag==0) {
            //   swal("Oops!", "Please check atleast one user for assign.");          
            //   return false;
            // }

            if(assign_rule.val()=='1')
            {
              var website_assign_to_checked_flag=0;
              $('#website_assign_to > option:selected').each(function() { 
                website_assign_to_checked_flag++;              
              });

              if(website_assign_to_checked_flag==0) {
                swal("Oops!", "Please check atleast one user for assign.");          
                return false;
              }
            }
            else if(assign_rule.val()=='2')
            {
              var rule_count=$("#web_rule_count").val();
              var rule_activity_count=$("#web_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var website_find_to_checked_flag=0;                
                var state_id='country_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  website_find_to_checked_flag++;
                });

                
                if(website_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var website_assign_to_checked_flag=0;                
                var assigned_user_id='website_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  website_assign_to_checked_flag++;                              
                });

                if(website_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var website_assign_to_2_other=0;
              $('#website_assign_to_2_other > option:selected').each(function() {  
                website_assign_to_2_other++;              
              });

              if (website_assign_to_2_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='3')
            {
              var rule_count=$("#web_rule_count").val();
              var rule_activity_count=$("#web_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var website_find_to_checked_flag=0;                
                var state_id='web_state_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  website_find_to_checked_flag++;
                });

                
                if(website_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var website_assign_to_checked_flag=0;                
                var assigned_user_id='website_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  website_assign_to_checked_flag++;                              
                });

                if(website_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
              
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var website_assign_to_3_other=0;
              $('#website_assign_to_3_other > option:selected').each(function() {  
                website_assign_to_3_other++;              
              });

              if (website_assign_to_3_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='4')
            {
              var rule_count=$("#web_rule_count").val();
              var rule_activity_count=$("#web_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var website_find_to_checked_flag=0;                
                var state_id='city_'+assign_rule.val()+'_'+j;
                //alert($('#'+state_id+' option').filter(":selected").val())
                $('#'+state_id+' > option:selected').each(function() {  
                  website_find_to_checked_flag++;
                });

                
                if(website_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var website_assign_to_checked_flag=0;                
                var assigned_user_id='website_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  website_assign_to_checked_flag++;                              
                });

                if(website_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
             
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var website_assign_to_4_other=0;
              $('#website_assign_to_4_other > option:selected').each(function() {  
                website_assign_to_4_other++;              
              });

              if (website_assign_to_4_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            else if(assign_rule.val()=='5')
            {
              var rule_count=$("#web_rule_count").val();
              var rule_activity_count=$("#web_rule_activity_count").val();
              var chk_find_to_count=0;
              var chk_assign_to_count=0;

              for(let j=1;j<=rule_activity_count;j++)
              {
                var website_find_to_checked_flag=0;                
                var k_id='keyword_'+assign_rule.val()+'_'+j;                
                if($("#"+k_id).val()){
                  website_find_to_checked_flag++;
                }
                
                if(website_find_to_checked_flag>0){
                  chk_find_to_count++;
                }
              }
              

              for(let i=1;i<=rule_activity_count;i++)
              {
                var website_assign_to_checked_flag=0;                
                var assigned_user_id='website_assign_to_'+assign_rule.val()+'_'+i;

                $('#'+assigned_user_id+' > option:selected').each(function() {  
                  website_assign_to_checked_flag++;                              
                });

                if(website_assign_to_checked_flag>0){
                  chk_assign_to_count++;
                }
              }
             
              if (chk_find_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");          
                return false;
              }  


              if (chk_assign_to_count!=rule_count) {
                swal("Oops!", "Required all fields.");        
                return false;
              }    

              var website_assign_to_5_other=0;
              $('#website_assign_to_5_other > option:selected').each(function() {  
                website_assign_to_5_other++;              
              });

              if (website_assign_to_5_other==0) {
              swal("Oops!", "Please check atleast one user for all other leads.");          
              return false;
              }          
            }
            // alert(data); return false;
            $.ajax({
                url: base_url + "setting/add_edit_website_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status='success'){

                      swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                        type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                        //location.reload(true); 
                        $("#web_add_div_toggle").show(); 
                        $("#web_add_div").slideToggle();
                                              
                        // $('.justdial_assign_to:input:checkbox').each(function() { 
                        //     this.checked = false
                        // });

                        $('#web_assign_rule').prop('selectedIndex', 0);
                        $("#web_rule_wise_view").html('');
                        $("#web_rule_count").val('0');
                        $("#web_rule_activity_count").val('0');
                        $("#website_setting_id").val('');
                        $("#web_submit_confirm").html('Save');                        
                        load_web_credentials(); 
                    });

                        
                    }
                      
                    
                }
            });
      });

      $("body").on("click",".web_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_web_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);  
                        // console.log(result)   
                        $("#web_add_div_toggle").hide(); 
                        $("#web_add_div").slideDown( "slow" );
                        // $("body, html").animate({ scrollTop: 250 }, "slow");       
                        $("#website_setting_id").val(result.id);
                        $("#web_submit_confirm").html('Save');
                        $('#web_assign_rule').val(result.assign_rule);
                        fn_rander_web_rule_wise_view(result.assign_rule,id);
                        
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
      });

      $("body").on("click",".web_delete",function(e){
        var id = $(this).attr('data-id');
        if(id!='')
        {
            var base_url=$("#base_url").val();

            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                var data = 'id='+id;               
                $.ajax({
                        url: base_url+"setting/delete_web",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
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
                            $(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Deleted!",
                                text: "The record(s) have been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                load_web_credentials(); 
                            });
                           
                        },
                        complete: function(){
                        //$("#preloader").css('display','none');
                       },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });
      // WEBSITE SETTING
      // =================================================

      
      // =================================================
      // FACEBOOK ASSIGNMENT RULES SETTING
      $("body").on("change","#fb_assign_rule",function(e){
        var r_id=$(this).val(); 
        fn_rander_fb_rule_wise_view(r_id);    
      });
      $("body").on("click","#fb_add_div_toggle",function(e){
        $("#fb_add_div").slideToggle();  
        $(this).hide();
      });
      $("body").on("click",".fb_edit",function(e){
        var id = $(this).attr('data-id');
        var data = 'id='+id;   
        var base_url=$("#base_url").val();            
        $.ajax({
                url: base_url+"setting/get_fb_credentials",
                data: data,                    
                cache: false,
                method: 'GET',
                dataType: "html",                   
                beforeSend: function( xhr ) { 
                  //$("#preloader").css('display','block');                           
                },
                success: function(data){
                    result = $.parseJSON(data); 
                    $("#fb_add_div_toggle").hide(); 
                    $("#fb_add_div").slideDown( "slow" );
                    // $("body, html").animate({ scrollTop: 250 }, "slow");       
                    $("#fb_setting_id").val(result.id);
                    $("#fb_submit_confirm").html('Save');
                    $('#fb_assign_rule').val(result.assign_rule);
                    fn_rander_fb_rule_wise_view(result.assign_rule,id);
                    
                },
                complete: function(){
                //$("#preloader").css('display','none');
                },
        });
     });
     $("body").on("click","#fb_submit_confirm",function(e){    
    
          var base_url=$("#base_url").val();  
          var assign_rule=$("#fb_assign_rule");          
          var data = "";        

          if(assign_rule.val()=='1')
          {
            var facebook_assign_to_checked_flag=0;
            $('#facebook_assign_to > option:selected').each(function() { 
              facebook_assign_to_checked_flag++;              
            });

            if(facebook_assign_to_checked_flag==0) {
              swal("Oops!", "Please check atleast one user for assign.");          
              return false;
            }
          }
          $.ajax({
              url: base_url + "setting/add_edit_facebook_credentials",
              data: new FormData($('#profile_update_form')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData: false,
              beforeSend: function(xhr) {
                  //$("#company_assigne_change_submit").attr("disabled",true);
              },
              success: function(data) {
                  result = $.parseJSON(data);
                  if(result.status='success'){

                    swal({
                      title: "Success!",
                      text: "The record(s) have been saved",
                      type: "success",
                      confirmButtonText: "ok",
                      allowOutsideClick: "false"
                  }, function () { 
                      //location.reload(true); 
                      $("#fb_add_div_toggle").show(); 
                      $("#fb_add_div").slideToggle();
                                            
                      // $('.justdial_assign_to:input:checkbox').each(function() { 
                      //     this.checked = false
                      // });

                      $('#fb_assign_rule').prop('selectedIndex', 0);
                      $("#fb_rule_wise_view").html('');
                      $("#fb_rule_count").val('0');
                      $("#fb_rule_activity_count").val('0');
                      $("#fb_setting_id").val('');
                      $("#fb_submit_confirm").html('Save');                        
                      load_fb_lead_assignment_setting(); 
                    });
                  }
              }
          });
      });
      $("body").on("click","#fb_submit_close",function(e){
        $("#fb_add_div").slideToggle(); 
        // $("#jd_add_div_toggle").slideToggle(); 
        if($('#fb_add_div_toggle').css('display') == 'none'){          
        }
        else{
          $("#fb_add_div_toggle").show();
        }       

        // $('.justdial_assign_to:input:checkbox').each(function() { 
        //     this.checked = false
        // });

        $('#fb_assign_rule').prop('selectedIndex', 0);
        $("#fb_rule_wise_view").html('');
        $("#fb_rule_count").val('0');
        $("#fb_rule_activity_count").val('0');
        $("#fb_setting_id").val('');
        $("#fb_submit_confirm").html('Save');
        load_fb_lead_assignment_setting(); 
      });

      $("body").on("click",".fb_setting_delete",function(e){
        var id = $(this).attr('data-id');
        if(id!='')
        {
            var base_url=$("#base_url").val();
            //Warning Message            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                var data = 'id='+id;   
                // alert(data); return false;            
                $.ajax({
                        url: base_url+"setting/delete_fb",
                        data: data,
                        //data: new FormData($('#frmAccount')[0]),
                        cache: false,
                        method: 'GET',
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
                            $(".preloader").hide();
                            //alert(result.status);
                            swal({
                                title: "Deleted!",
                                text: "The record(s) have been deleted",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                load_fb_lead_assignment_setting(); 
                            });
                           
                        },
                        complete: function(){
                        //$("#preloader").css('display','none');
                       },
                });
                
            });
           
        }
        else
        { 
            swal("Oops!", "Check the record to delete.");            
        }
      });
      // FACEBOOK ASSIGNMENT RULES SETTING
      // =================================================
      


      // =================================================
      // SMS API
      $("body").on("click","#sms_add_div_toggle",function(e){
        $("#sms_add_div").slideToggle();  
        $(this).hide();
      });

      $("body").on("click","#sms_submit_close",function(e){
        $("#sms_add_div").slideToggle();  
        $("#sms_add_div_toggle").show();  
        
        $("#sms_service_provider_id").val('');
        $("#sms_service_provider_id option:first").attr('selected','selected');
        $("#sms_account_name").val('');     
        $("#sms_sender").val('');
        $("#sms_apikey").val('');
        $("#sms_entity_id").val('');         
        $("#sms_setting_id").val('');
        $("#sms_submit_confirm").html('Save');
      });

      $("body").on("click","#sms_submit_confirm",function(e){    
    
            var base_url=$("#base_url").val(); 
            var sms_service_provider_id_obj=$("#sms_service_provider_id");
            var sms_account_name_obj=$("#sms_account_name");           
            var sms_sender_obj=$("#sms_sender");
            var sms_apikey_obj=$("#sms_apikey");
            var sms_entity_id_obj=$("#sms_entity_id");
            var data = "";

            if(sms_service_provider_id_obj.val()=='')
            {
              swal("Oops!", "Select any service provider.");               
              return false;
            }

            if(sms_account_name_obj.val()=='')
            {
              swal("Oops!", "Account Name should not be blank."); 
              sms_account_name_obj.focus();
              return false;
            }

            if(sms_sender_obj.val()=='')
            {
              swal("Oops!", "Sender should not be blank."); 
              sms_sender_obj.focus();
              return false;
            }
    
            if(sms_apikey_obj.val()=='')
            {
              swal("Oops!", "API Key should not be blank."); 
              sms_apikey_obj.focus();
              return false;
            }
    
            if(sms_entity_id_obj.val()=='')
            {
              swal("Oops!", "Entity ID should not be blank."); 
              sms_entity_id_obj.focus();
              return false;
            }
           
            // alert(data); return false;
            $.ajax({
                url: base_url + "setting/add_edit_sms_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status='success'){

                      swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                        //location.reload(true); 
                        $("#sms_add_div_toggle").show(); 
                        $("#sms_add_div").slideToggle();
                        sms_account_name_obj.val('')
                        sms_sender_obj.val('');
                        sms_apikey_obj.val('');
                        sms_entity_id_obj.val('');                       
                       
                        $("#sms_setting_id").val('');
                        $("#sms_submit_confirm").html('Save');
                        load_sms_credentials(); 
                    });

                        
                    }
                       
                    
                }
            });
      });

      $("body").on("click",".sms_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_sms_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);  
                        // console.log(result)   
                        $("#sms_add_div_toggle").hide(); 
                        $("#sms_add_div").slideDown( "slow" );
                           
                        
                        $("#sms_setting_id").val(result.id);
                        $("#sms_service_provider_id").val(result.sms_service_provider_id);
                        $("#sms_account_name").val(result.account_name);
                        $("#sms_sender").val(result.sender);
                        $("#sms_apikey").val(result.apikey);
                        $("#sms_entity_id").val(result.entity_id);
                        $("#sms_submit_confirm").html('Save');                        
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
      });
      $("body").on("click",".sms_delete",function(e){
            var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();
    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record! And also all respective template(s) will be deleted accordingly. Do you confirm?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_sms",
                            data: data,                            
                            cache: false,
                            method: 'GET',
                            dataType: "html",                            
                            beforeSend: function( xhr ) { 
                              //$("#preloader").css('display','block');                           
                            },
                            success: function(data){
                                result = $.parseJSON(data);
                                $(".preloader").hide();                                
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                   
                                    load_sms_credentials(); 
                                });
                               
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      // SMS API
      // =================================================

      // =================================================
      // WhatsApp API
      $("body").on("click","#whatsapp_add_div_toggle",function(e){
        $("#whatsapp_add_div").slideToggle();  
        $(this).hide();
      });

      $("body").on("click","#whatsapp_submit_close",function(e){
        $("#whatsapp_add_div").slideToggle();  
        $("#whatsapp_add_div_toggle").show();  
        
        $("#whatsapp_service_provider_id").val('');
        $("#whatsapp_service_provider_id option:first").attr('selected','selected');
        $("#whatsapp_account_name").val('');     
        $("#whatsapp_sender").val('');
        $("#whatsapp_apikey").val('');
        $("#whatsapp_entity_id").val('');         
        $("#whatsapp_setting_id").val('');
        $("#whatsapp_submit_confirm").html('Save');
      });

      $("body").on("click","#whatsapp_submit_confirm",function(e){    
    
            var base_url=$("#base_url").val(); 
            var whatsapp_service_provider_id_obj=$("#whatsapp_service_provider_id");
            var whatsapp_account_name_obj=$("#whatsapp_account_name");           
            var whatsapp_sender_obj=$("#whatsapp_sender");
            var whatsapp_apikey_obj=$("#whatsapp_apikey");           
            var data = "";

            if(whatsapp_service_provider_id_obj.val()=='')
            {
              swal("Oops!", "Select any service provider.");               
              return false;
            }

            if(whatsapp_account_name_obj.val()=='')
            {
              swal("Oops!", "Account Name should not be blank."); 
              whatsapp_account_name_obj.focus();
              return false;
            }

            if(whatsapp_sender_obj.val()=='')
            {
              swal("Oops!", "Sender mobile should not be blank."); 
              whatsapp_sender_obj.focus();
              return false;
            }
    
            if(whatsapp_apikey_obj.val()=='')
            {
              swal("Oops!", "API Key should not be blank."); 
              whatsapp_apikey_obj.focus();
              return false;
            }
    
            
           
            // alert(data); return false;
            $.ajax({
                url: base_url + "setting/add_edit_whatsapp_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    if(result.status='success'){

                      swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                        //location.reload(true); 
                        $("#whatsapp_add_div_toggle").show(); 
                        $("#whatsapp_add_div").slideToggle();
                        whatsapp_account_name_obj.val('')
                        whatsapp_sender_obj.val('');
                        whatsapp_apikey_obj.val('');                       
                       
                        $("#whatsapp_setting_id").val('');
                        $("#whatsapp_submit_confirm").html('Save');
                        load_whatsapp_credentials(); 
                    });

                        
                    }
                       
                    
                }
            });
      });

      $("body").on("click",".whatsapp_edit",function(e){
            var id = $(this).attr('data-id');
            var data = 'id='+id;   
            var base_url=$("#base_url").val();            
            $.ajax({
                    url: base_url+"setting/get_whatsapp_credentials",
                    data: data,                    
                    cache: false,
                    method: 'GET',
                    dataType: "html",                   
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);  
                        // console.log(result)   
                        $("#whatsapp_add_div_toggle").hide(); 
                        $("#whatsapp_add_div").slideDown( "slow" );
                           
                        
                        $("#whatsapp_setting_id").val(result.id);
                        $("#whatsapp_service_provider_id").val(result.whatsapp_service_provider_id);
                        $("#whatsapp_account_name").val(result.account_name);
                        $("#whatsapp_sender").val(result.sender);
                        $("#whatsapp_apikey").val(result.apikey);
                        $("#whatsapp_entity_id").val(result.entity_id);
                        $("#whatsapp_submit_confirm").html('Save');                  
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                    },
            });
      });
      $("body").on("click",".whatsapp_delete",function(e){
            var id = $(this).attr('data-id');
            if(id!='')
            {
                var base_url=$("#base_url").val();
    
                //Warning Message            
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this record! And also all respective template(s) will be deleted accordingly. Do you confirm?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    var data = 'id='+id;               
                    $.ajax({
                            url: base_url+"setting/delete_whatsapp",
                            data: data,                            
                            cache: false,
                            method: 'GET',
                            dataType: "html",                            
                            beforeSend: function( xhr ) { 
                              //$("#preloader").css('display','block');                           
                            },
                            success: function(data){
                                result = $.parseJSON(data);
                                $(".preloader").hide();                                
                                swal({
                                    title: "Deleted!",
                                    text: "The record(s) have been deleted",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                   
                                    load_whatsapp_credentials(); 
                                });
                               
                            },
                            complete: function(){
                            //$("#preloader").css('display','none');
                           },
                    });
                    
                });
               
            }
            else
            { 
                swal("Oops!", "Check the record to delete.");            
            }
      });
      // WhatsApp API
      // =================================================

      // =================================================
      // USER WISE GMAIL
       $("body").on("click","#user_gmail_add_div_toggle",function(e){        
        $("#user_gmail_add_div").slideToggle();  
        $(this).hide();
      });

       $("body").on("click","#user_gmail_submit_close",function(e){
        $("#user_gmail_add_div").slideToggle();  
        $("#user_gmail_add_div_toggle").show();        
        $("#gmail_address").val('');        
        $('.user_gmail_assign_to:input:radio').each(function() { 
            this.checked = false
        });
        $("#user_gmail_id").val('');
        $("#user_gmail_submit_confirm").html('Save');

      });

    $("body").on("click","#user_gmail_submit_confirm",function(e){      
          var base_url=$("#base_url").val();            
          var gmail_address_obj=$("#gmail_address");           
          var data = "";
  
          if(gmail_address_obj.val()=='')
          {
            swal("Oops!", "Gmail should not be blank.",'error'); 
            gmail_address_obj.focus();
            return false;
          } 
          else
          {
            if(is_email_validate(gmail_address_obj.val())==false)
            {
              swal("Oops!", "Enter valid gmail.",'error'); 
              gmail_address_obj.focus();
              return false;
            }
            // else
            // {
            //   var gmail_arr = gmail_address_obj.val().split("@");
            //   if(gmail_arr[1]!='gmail.com')
            //   {
            //     swal("Oops!", "Enter valid gmail.",'error'); 
            //     gmail_address_obj.focus();
            //     return false;
            //   }
            // }


          }

          var user_gmail_checked_flag=0;
          $('.user_gmail_assign_to:input:radio').each(function() {               
            if(this.checked == true)
            {
              user_gmail_checked_flag++;
            } 
          });
          
          if (user_gmail_checked_flag==0) {
            swal("Oops!", "Please check one user for gmail sync.",'error');          
            return false;
          }
          // alert(data); return false;
          $.ajax({
              url: base_url + "setting/add_edit_user_gmail",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    // alert(result.status)
                    if(result.status=='success')
                    {

                        swal({
                          title: "Success!",
                              text: "The record(s) have been saved",
                               type: "success",
                              confirmButtonText: "ok",
                              allowOutsideClick: "false"
                          }, function () { 
                              //location.reload(true); 
                              $("#user_gmail_add_div_toggle").show(); 
                              $("#user_gmail_add_div").slideToggle();
                              gmail_address_obj.val('');                          
                              $('.user_gmail_assign_to:input:radio').each(function() { 
                                  this.checked = false
                              });
                              $("#user_gmail_id").val('');
                              $("#user_gmail_submit_confirm").html('Save');
                              load_user_gmail_for_sync(); 
                          });                          
                    }
                    else if(result.status=='exist')
                    {
                      swal("Oops!", "Oops! User already exist.","error");  
                    }

                       
                    
                }
            });
      });

    $("body").on("click",".user_gmail_edit",function(e){
          var id = $(this).attr('data-id');
          var data = 'id='+id;   
          var base_url=$("#base_url").val();            
          $.ajax({
                  url: base_url+"setting/get_user_gmail",
                  data: data,                    
                  cache: false,
                  method: 'GET',
                  dataType: "html",                   
                  beforeSend: function( xhr ) { 
                    //$("#preloader").css('display','block');                           
                  },
                  success: function(data){
                      result = $.parseJSON(data);  
                      // console.log(result)   
                      $("#user_gmail_add_div_toggle").hide(); 
                      $("#user_gmail_add_div").slideDown( "slow" );
                      // $("body, html").animate({ scrollTop: 250 }, "slow");               
                      // alert(result.assign_to);    

                      $("#user_gmail_id").val(result.id);
                      $("#gmail_address").val(result.gmail_address);                          
                      $("#user_gmail_submit_confirm").html('Edit');
                      $('.user_gmail_assign_to:input:radio').each(function() { 
                        this.checked=false;
                      });

                      $('.user_gmail_assign_to:input:radio').each(function() { 
                        // if(this.checked == true)
                        // {
                        //   indiamart_assign_to_checked_flag++;
                        // } 
                        //alert(this.value)
                        if(result.assign_to.indexOf(this.value)>-1){
                          this.checked=true;
                        }
                      });
                      
                  },
                  complete: function(){
                  //$("#preloader").css('display','none');
                  },
          });
    });
    $("body").on("click",".user_gmail_delete",function(e){
      var id = $(this).attr('data-id');
          if(id!='')
          {
              var base_url=$("#base_url").val();
  
              //Warning Message            
              swal({
                  title: "Are you sure?",
                  text: "You will not be able to recover this record!",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonClass: 'btn-warning',
                  confirmButtonText: "Yes, delete it!",
                  closeOnConfirm: false
              }, function () {
                  var data = 'id='+id;               
                  $.ajax({
                          url: base_url+"setting/delete_user_gmail",
                          data: data,
                          //data: new FormData($('#frmAccount')[0]),
                          cache: false,
                          method: 'GET',
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
                              $(".preloader").hide();
                              //alert(result.status);
                              swal({
                                  title: "Deleted!",
                                  text: "The record(s) have been deleted",
                                   type: "success",
                                  confirmButtonText: "ok",
                                  allowOutsideClick: "false"
                              }, function () { 
                                  //location.reload(true); 
                                  load_user_gmail_for_sync(); 
                              });
                             
                          },
                          complete: function(){
                          //$("#preloader").css('display','none');
                         },
                  });
                  
              });
             
          }
          else
          { 
              swal("Oops!", "Check the record to delete.");            
          }
    });

  // USER WISE GMAIL
  // =================================================

  // =================================================
  // Email Forwarding Settings
  $("body").on("click",".email_setting_update",function(e){
      var base_url=$("#base_url").val(); 
      var ThisObj=$(this);
      var id=ThisObj.attr("data-id");
      var field=ThisObj.attr("data-field");
      var value=ThisObj.val();
      // alert(id+' / '+field+' / ' +value);
      var value_tmp=(ThisObj.is(':checked'))?'Y':'N'; 
      var data = 'id='+id+"&field="+field+"&value="+value_tmp;  
      //alert(data); return false; 
      $.ajax({
            url: base_url+"setting/email_forwarding_settings_update",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);  
                // console.log(result)   
                if(result.status=='success')
                {
                    load_email_forwarding_settings(); 
                    // swal({
                    //       title: "Success!",
                    //       text: "The record successfully updated",
                    //        type: "success",
                    //       confirmButtonText: "ok",
                    //       allowOutsideClick: "false"
                    //   }, function () { 
                    //       //location.reload(true); 
                    //       load_email_forwarding_settings(); 
                    //   });
                }
                
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
    });
  });

  $("body").on("click","#daily_report_email_send_update_confirm",function(e){
      var base_url=$("#base_url").val(); 
      var ThisObj=$("#daily_report_email_send");
      var is_daily_report_send=(ThisObj.is(':checked'))?'Y':'N'; 
      var daily_report_tomail=$("#daily_report_tomail").val();
      var daily_report_mail_subject=$("#daily_report_mail_subject").val();
      // alert(is_daily_report_send)
      if(is_daily_report_send=='Y')
      {
        if(daily_report_tomail=='')
        {
          swal("Oops!", "Please provide to email.");                    
          return false;
        }

        if(daily_report_mail_subject=='')
        {
          swal("Oops!", "Please provide email subject.");          
          return false;
        }
      }
      
      var data = "is_daily_report_send="+is_daily_report_send+"&daily_report_tomail="+daily_report_tomail+"&daily_report_mail_subject="+daily_report_mail_subject;  
      // alert(data); //return false; 
      $.ajax({
            url: base_url+"setting/daily_report_email_send_settings_update",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);  
                // console.log(result)   
                if(result.status=='success')
                {
                  swal("", "Daily Report updated.","success"); 
                    load_email_forwarding_settings();

                }
                
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
      });
  });
  // Email Forwarding Settings
  // =================================================

  // =================================================
  // SMS Forwarding Settings
  $("body").on("click",".sms_setting_update",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var id=ThisObj.attr("data-id");
    var field=ThisObj.attr("data-field");
    var value=ThisObj.val();
    // alert(id+' / '+field+' / ' +value);
    var value_tmp=(ThisObj.is(':checked'))?'Y':'N'; 
    var data = 'id='+id+"&field="+field+"&value="+value_tmp;  
    // alert(data); return false; 
    $.ajax({
          url: base_url+"setting/sms_forwarding_settings_update",
          data: data,                    
          cache: false,
          method: 'GET',
          dataType: "html",                   
          beforeSend: function( xhr ) { 
            //$("#preloader").css('display','block');                           
          },
          success: function(data){
              result = $.parseJSON(data);  
              // console.log(result)   
              if(result.status=='success')
              {
                  load_sms_forwarding_settings(); 
                  // swal({
                  //       title: "Success!",
                  //       text: "The record successfully updated",
                  //        type: "success",
                  //       confirmButtonText: "ok",
                  //       allowOutsideClick: "false"
                  //   }, function () { 
                  //       //location.reload(true); 
                  //       load_email_forwarding_settings(); 
                  //   });
              }
              
          },
          complete: function(){
          //$("#preloader").css('display','none');
          },
  });
  });

  $("body").on("click",".sms_template_update",function(e){
      var base_url=$("#base_url").val(); 
      var ThisObj=$(this);
      var id=ThisObj.attr("data-id");
      var field=ThisObj.attr("data-field");   
      // alert(id+' / '+field);
      $.ajax({
          url: base_url + "setting/sms_template_change_view_rander_ajax",
          type: "POST",
          data: {
              'id': id,
              'field':field
          },
          async: true,
          success: function(response) {
              $("#common_view_modal_title_md").text("Template Change");
              $('#rander_common_view_modal_html_md').html(response);
              $('#rander_common_view_modal_md').modal({
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

  $("body").on("click","#update_sms_template_change_confirm",function(e){
        var base_url = $("#base_url").val();
        var sms_api_id=$("#sms_api_id").val();
        var template_id=$("#template_id").val();
        var id=$(this).attr("data-id");
        var field=$(this).attr("data-field");
        // alert(sms_api_id+'/'+template_id+'/'+id+'/'+field)
        var data = 'id='+id+"&field="+field+"&value="+template_id;  
        // alert(data); return false;
        $.ajax({
            url: base_url+"setting/sms_forwarding_settings_template_update",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);  
                // console.log(result)   
                if(result.status=='success')
                {
                    $("#rander_common_view_modal_md").modal('hide');
                    load_sms_forwarding_settings(); 
                    // swal({
                    //       title: "Success!",
                    //       text: "The record successfully updated",
                    //        type: "success",
                    //       confirmButtonText: "ok",
                    //       allowOutsideClick: "false"
                    //   }, function () { 
                    //       //location.reload(true); 
                    //       load_email_forwarding_settings(); 
                    //   });
                }
                
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
        });

  });

  $("body").on("click",".sms_template_view",function(e){
    
    var base_url = $("#base_url").val();
    var ThisObj=$(this);
    var id=ThisObj.attr("data-id");
    var field=ThisObj.attr("data-field"); 
    
    $.ajax({
      url: base_url+"setting/get_sms_template_view_ajax",
      type: "POST",
      data: {
        'id': id,
        'field':field,
      },			
      async: true,
      beforeSend: function( xhr ) {
        
      },
      complete: function (){
        
      },
      success: function(response){		
        
            $("#common_view_modal_title_md").text("Template");
              $('#rander_common_view_modal_html_md').html(response);
              $('#rander_common_view_modal_md').modal({
                  backdrop: 'static',
                  keyboard: false
            });
      }			
    });
  });

  $("body").on("click",".sms_template_del",function(e){
    
        var base_url = $("#base_url").val();
        var ThisObj=$(this);
        var id=ThisObj.attr("data-id");
        var field=ThisObj.attr("data-field"); 
        
        swal({
          title: "Are you sure?",
          text: "Do you want to delete the template?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: true
        }, function () {
          $.ajax({
            url: base_url+"setting/get_sms_template_del_ajax",
            type: "POST",
            data: {
              'id': id,
              'field':field,
            },			
            async: true,
            beforeSend: function( xhr ) {
              
            },
            complete: function (){
              
            },
            success: function(response){
              load_sms_forwarding_settings(); 
            }			
          });          
        });	
  });

// SMS Forwarding Settings
// =================================================


  // =================================================
  // SMS Forwarding Settings
  $("body").on("click",".whatsapp_setting_update",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var id=ThisObj.attr("data-id");
    var field=ThisObj.attr("data-field");
    var value=ThisObj.val();
    // alert(id+' / '+field+' / ' +value);
    var value_tmp=(ThisObj.is(':checked'))?'Y':'N'; 
    var data = 'id='+id+"&field="+field+"&value="+value_tmp;  
    // alert(data); return false; 
    $.ajax({
          url: base_url+"setting/whatsapp_forwarding_settings_update",
          data: data,                    
          cache: false,
          method: 'GET',
          dataType: "html",                   
          beforeSend: function( xhr ) { 
            //$("#preloader").css('display','block');                           
          },
          success: function(data){
              result = $.parseJSON(data);  
              // console.log(result)   
              if(result.status=='success')
              {
                  load_whatsapp_forwarding_settings(); 
                  // swal({
                  //       title: "Success!",
                  //       text: "The record successfully updated",
                  //        type: "success",
                  //       confirmButtonText: "ok",
                  //       allowOutsideClick: "false"
                  //   }, function () { 
                  //       //location.reload(true); 
                  //       load_email_forwarding_settings(); 
                  //   });
              }
              
          },
          complete: function(){
          //$("#preloader").css('display','none');
          },
  });
  });

  $("body").on("click",".whatsapp_template_update",function(e){
      var base_url=$("#base_url").val(); 
      var ThisObj=$(this);
      var id=ThisObj.attr("data-id");
      var field=ThisObj.attr("data-field");   
      // alert(id+' / '+field);
      $.ajax({
          url: base_url + "setting/whatsapp_template_change_view_rander_ajax",
          type: "POST",
          data: {
              'id': id,
              'field':field
          },
          async: true,
          success: function(response) {
              $("#common_view_modal_title_md").text("Template Change");
              $('#rander_common_view_modal_html_md').html(response);
              $('#rander_common_view_modal_md').modal({
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

  $("body").on("click","#update_whatsapp_template_change_confirm",function(e){
        var base_url = $("#base_url").val();
        var whatsapp_api_id=$("#whatsapp_api_id").val();
        var template_id=$("#template_id").val();
        var id=$(this).attr("data-id");
        var field=$(this).attr("data-field");
        // alert(whatsapp_api_id+'/'+template_id+'/'+id+'/'+field)
        var data = 'id='+id+"&field="+field+"&value="+template_id;  
        // alert(data); return false;
        $.ajax({
            url: base_url+"setting/whatsapp_forwarding_settings_template_update",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);  
                // console.log(result)   
                if(result.status=='success')
                {
                    $("#rander_common_view_modal_md").modal('hide');
                    load_whatsapp_forwarding_settings(); 
                    // swal({
                    //       title: "Success!",
                    //       text: "The record successfully updated",
                    //        type: "success",
                    //       confirmButtonText: "ok",
                    //       allowOutsideClick: "false"
                    //   }, function () { 
                    //       //location.reload(true); 
                    //       load_email_forwarding_settings(); 
                    //   });
                }
                
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
        });

  });

  $("body").on("click",".whatsapp_template_view",function(e){
    
    var base_url = $("#base_url").val();
    var ThisObj=$(this);
    var id=ThisObj.attr("data-id");
    var field=ThisObj.attr("data-field"); 
    
    $.ajax({
      url: base_url+"setting/get_whatsapp_template_view_ajax",
      type: "POST",
      data: {
        'id': id,
        'field':field,
      },			
      async: true,
      beforeSend: function( xhr ) {
        
      },
      complete: function (){
        
      },
      success: function(response){		
        
            $("#common_view_modal_title_md").text("Template");
              $('#rander_common_view_modal_html_md').html(response);
              $('#rander_common_view_modal_md').modal({
                  backdrop: 'static',
                  keyboard: false
            });
      }			
    });
  });

  $("body").on("click",".whatsapp_template_del",function(e){
    
        var base_url = $("#base_url").val();
        var ThisObj=$(this);
        var id=ThisObj.attr("data-id");
        var field=ThisObj.attr("data-field"); 
        
        swal({
          title: "Are you sure?",
          text: "Do you want to delete the template?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: true
        }, function () {
          $.ajax({
            url: base_url+"setting/get_whatsapp_template_del_ajax",
            type: "POST",
            data: {
              'id': id,
              'field':field,
            },			
            async: true,
            beforeSend: function( xhr ) {
              
            },
            complete: function (){
              
            },
            success: function(response){
              load_whatsapp_forwarding_settings(); 
            }			
          });          
        });	
  });

  // WhatsApp Forwarding Settings
  // =================================================
  // =================================================
  // smtp Settings
  $("body").on("click","#smtp_add_div_toggle",function(e){
        $("#smtp_add_div").slideToggle();  
        $(this).hide();
  });
  $("body").on("click","#smtp_submit_close",function(e){
        $("#smtp_add_div").slideToggle();  
        $("#smtp_add_div_toggle").show();      

        $('input[name=smtp_type]').attr('checked',false);
        $("#smtp_setting_id").val('');
        $("#smtp_host").val('');
        $("#smtp_port").val('');
        $("#smtp_username").val('');
        $("#smtp_password").val('');
        $("#smtp_setting_id").val('');
        $("#smtp_submit_confirm").html('Save');

  });

  $("body").on("click","#show_smtp_password",function(e){
        $("#smtp_password").attr("type","text");
        $(this).text("Key Hide");
        $(this).attr("id","hide_smtp_password");
      });

      $("body").on("click","#hide_smtp_password",function(e){
        $("#smtp_password").attr("type","password");
        $(this).text("Key Show");
        $(this).attr("id","show_smtp_password");
      });

      $("body").on("click","#smtp_submit_confirm",function(e){   
    
            var base_url=$("#base_url").val();  
            var smtp_host_obj=$("#smtp_host");
            var smtp_port_obj=$("#smtp_port");          
            var smtp_username_obj=$("#smtp_username");
            var smtp_password_obj=$("#smtp_password");
            var data = "";
           
            if ($('input[name=smtp_type]:checked').length==0) {
                swal("Oops!", "Please checked SMTP Server.");          
                return false;
            }

            if(smtp_host_obj.val()=='')
            {
              swal("Oops!", "SMTP host should not be blank."); 
              smtp_host_obj.focus();
              return false;
            }

            if(smtp_port_obj.val()=='')
            {
              swal("Oops!", "SMTP port should not be blank."); 
              smtp_port_obj.focus();
              return false;
            }


            if(smtp_username_obj.val()=='')
            {
              swal("Oops!", "SMTP Email should not be blank."); 
              smtp_username_obj.focus();
              return false;
            }

            if(smtp_password_obj.val()=='')
            {
              swal("Oops!", "SMTP Password should not be blank."); 
              smtp_password_obj.focus();
              return false;
            }
            // alert(data); 
            // return false;
            $.ajax({
                url: base_url + "setting/add_edit_smtp_credentials",
                data: new FormData($('#profile_update_form')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                    //$("#company_assigne_change_submit").attr("disabled",true);
                },
                success: function(data) {
                    result = $.parseJSON(data);
                    // alert(result.status)
                    if(result.status=='success')
                    {
                        swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                         type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                        }, function () { 
                            //location.reload(true); 
                            $("#smtp_add_div_toggle").show(); 
                            $("#smtp_add_div").slideToggle();
                            smtp_username_obj.val('');
                            smtp_password_obj.val('');                        
                            $('input[name=smtp_type]').attr('checked',false);
                            $("#smtp_setting_id").val('');
                            $("#smtp_submit_confirm").html('Save');
                            load_smtp_credentials(); 
                        });
                    }
                    else if(result.status='exist')
                    {
                        swal("Oops!", "The SMTP Type already exist.",'error'); 
                    }
                    else
                    {
                        swal("Oops!", "Something wrong.",'error'); 
                    }
                }
            });
    });

    $("body").on("click",".smtp_edit",function(e){
          var id = $(this).attr('data-id');
          var data = 'id='+id;   
          var base_url=$("#base_url").val();            
          $.ajax({
                  url: base_url+"setting/get_smtp_credentials",
                  data: data,                    
                  cache: false,
                  method: 'GET',
                  dataType: "html",                   
                  beforeSend: function( xhr ) { 
                    //$("#preloader").css('display','block');                           
                  },
                  success: function(data){
                      result = $.parseJSON(data);     
                      $("#smtp_add_div_toggle").hide(); 
                      $("#smtp_add_div").slideDown( "slow" );
                      // $("body, html").animate({ scrollTop: 250 }, "slow");               
                      //alert(result.assign_to);    
                      $("#smtp_setting_id").val(result.id);
                      $("#smtp_host").val(result.host);
                      $("#smtp_port").val(result.port);
                      $("#smtp_username").val(result.username);
                      $("#smtp_password").val(result.password);                        
                      $("#smtp_submit_confirm").html('Save');                       

                      $('input[name=smtp_type]').attr('checked',false);

                      $('input[name=smtp_type]').each(function() { 
                        // if(this.checked == true)
                        // {
                        //   indiamart_assign_to_checked_flag++;
                        // } 
                        // alert(this.value)
                        if(result.smtp_type.indexOf(this.value)>-1){
                          this.checked=true;
                        }
                      });
                      
                  },
                  complete: function(){
                  //$("#preloader").css('display','none');
                  },
          });
  });
  $("body").on("click",".smtp_delete",function(e){
      var id = $(this).attr('data-id');
          if(id!='')
          {
              var base_url=$("#base_url").val();
  
              //Warning Message            
              swal({
                  title: "Are you sure?",
                  text: "You will not be able to recover this record!",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonClass: 'btn-warning',
                  confirmButtonText: "Yes, delete it!",
                  closeOnConfirm: false
              }, function () {
                  var data = 'id='+id;               
                  $.ajax({
                          url: base_url+"setting/delete_smtp",
                          data: data,
                          //data: new FormData($('#frmAccount')[0]),
                          cache: false,
                          method: 'GET',
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
                              $(".preloader").hide();
                              //alert(result.status);
                              swal({
                                  title: "Deleted!",
                                  text: "The record(s) have been deleted",
                                   type: "success",
                                  confirmButtonText: "ok",
                                  allowOutsideClick: "false"
                              }, function () { 
                                  //location.reload(true); 
                                  load_smtp_credentials(); 
                              });
                             
                          },
                          complete: function(){
                          //$("#preloader").css('display','none');
                         },
                  });
                  
              });
             
          }
          else
          { 
              swal("Oops!", "Check the record to delete.");            
          }
    });

  $("body").on("click",".smtp_update",function(e){
      var base_url=$("#base_url").val(); 
      var ThisObj=$(this);
      var id=ThisObj.attr("data-id");
      var field=ThisObj.attr("data-field");
      var value=ThisObj.val();
      // alert(id+' / '+field+' / ' +value);
      var value_tmp=(ThisObj.is(':checked'))?'Y':'N'; 
      var data = 'id='+id+"&field="+field+"&value="+value_tmp;  
      // alert(data); //return false; 
      $.ajax({
            url: base_url+"setting/smtp_settings_update",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);  
                // console.log(result)   
                if(result.status=='success')
                {
                    load_smtp_credentials(); 
                    // swal({
                    //       title: "Success!",
                    //       text: "The record successfully updated",
                    //        type: "success",
                    //       confirmButtonText: "ok",
                    //       allowOutsideClick: "false"
                    //   }, function () { 
                    //       //location.reload(true); 
                    //       load_email_forwarding_settings(); 
                    //   });
                }
                
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
    });
  });

  $("body").on("click",".smtp_type",function(e){
      // var existing_id=$("#smtp_setting_id").val();
      var type=$(this).val();
      if(type==2)
      {
        $("#smtp_host").val("ssl://smtp.gmail.com");
        $("#smtp_port").val("465");
        $("#smtp_host").attr("readonly",true);
        $("#smtp_port").attr("readonly",true);
      }
      else
      {
        $("#smtp_host").val("");
        $("#smtp_port").val("");
        $("#smtp_host").attr("readonly",false);
        $("#smtp_port").attr("readonly",false);
      }
  });
  // emtp Settings
  // =================================================

  // =================================================
  // INDIAMART RULES
  $("body").on("change","#assign_rule",function(e){
    var r_id=$(this).val();    
    fn_rander_im_rule_wise_view(r_id);    
  });
  // INDIAMART RULES
  // =================================================

  // =================================================
  // TRADEINDIA RULES
  $("body").on("change","#ti_assign_rule",function(e){
    var r_id=$(this).val(); 
    fn_rander_ti_rule_wise_view(r_id);    
  });
  // TRADEINDIA RULES
  // =================================================

  // =================================================
  // AAJJO RULES
  $("body").on("change","#aj_assign_rule",function(e){
    var r_id=$(this).val(); 
    fn_rander_aj_rule_wise_view(r_id);    
  });
  // AAJJO RULES
  // =================================================


  // =================================================
  // JUSTDIAL RULES
  $("body").on("change","#jd_assign_rule",function(e){
    var r_id=$(this).val(); 
    fn_rander_jd_rule_wise_view(r_id);    
  });
  // JUSTDIAL RULES
  // =================================================

  // =================================================
  // WEBSITE RULES
  $("body").on("change","#web_assign_rule",function(e){
    var r_id=$(this).val(); 
    fn_rander_web_rule_wise_view(r_id);    
  });
  // WEBSITE RULES
  // =================================================


  // =================================================
  // SMS API 

  $("body").on("click",".sms_template_add",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var id=ThisObj.attr("data-id");
    // var field=ThisObj.attr("data-field");   
    // alert('ok'); return false;
    $.ajax({
        url: base_url + "setting/sms_template_add_view_rander_ajax",
        type: "POST",
        data: {
            'id': id
        },
        async: true,
        success: function(response) {
            $("#common_view_modal_title_lg").text("Manage Template");
            $('#rander_common_view_modal_html_lg').html(response);
            $('#rander_common_view_modal_lg').modal({
                backdrop: 'static',
                keyboard: false
            });
            load_sms_template(id);
            
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
  
  $("body").on("click","#add_sms_template_confirm",function(e){    
      
    var base_url=$("#base_url").val(); 
    var sms_api_id=$("#sms_api_id").val();
    var sms_api_name_obj=$("#sms_t_api_name");           
    var sms_name_obj=$("#sms_t_name");
    var sms_template_id_obj=$("#sms_t_template_id");
    var sms_text_obj=$("#sms_t_text");
    var data = "";
    
    if(sms_api_name_obj.val()=='')
    {
      swal("Oops!", "SMS Api should not be blank.");       
      return false;
    }
  
    if(sms_name_obj.val()=='')
    {
      swal("Oops!", "Template name should not be blank.");      
      return false;
    }
  
    if(sms_template_id_obj.val()=='')
    {
      swal("Oops!", "Template ID should not be blank."); 
      return false;
    }
  
    if(sms_text_obj.val()=='')
    {
      swal("Oops!", "Template Text should not be blank."); 
      return false;
    }
   
    // alert(data); return false;
    $.ajax({
        url: base_url + "setting/add_edit_sms_template",
        data: new FormData($('#frmTemplateAddEdit')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(xhr) {
            //$("#company_assigne_change_submit").attr("disabled",true);
        },
        success: function(data) {
            result = $.parseJSON(data);
            if(result.status='success'){
  
              swal({
                title: "Success!",
                text: "The record(s) have been saved",
                 type: "success",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            }, function () { 
                $("#sms_auto_template_id").val('');                
                sms_name_obj.val('')
                sms_template_id_obj.val('');
                sms_text_obj.val('');
                load_sms_template(sms_api_id); 
            });
  
                
            }
               
            
        }
    });
  });

  $("body").on("click",".sms_template_edit",function(e){
    var id = $(this).attr('data-id');
    var data = 'id='+id;   
    var base_url=$("#base_url").val();    
    // alert(data) ; return false;       
    $.ajax({
            url: base_url+"setting/get_sms_template",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);  
                // console.log(result)                   
                   
                $("#sms_auto_template_id").val(result.id);
                $("#sms_api_id").val(result.sms_api_id);
                $("#sms_t_name").val(result.name);
                $("#sms_t_template_id").val(result.template_id);
                $("#sms_t_text").val(result.text);                      
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
    });
  });
  $("body").on("click",".sms_template_delete",function(e){
    var id = $(this).attr('data-id');
    var sms_api_id=$("#sms_api_id").val(); 
    if(id!='')
    {
        var base_url=$("#base_url").val();

        //Warning Message            
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record! Do you confirm?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-warning',
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            var data = 'id='+id;               
            $.ajax({
                    url: base_url+"setting/delete_sms_template",
                    data: data,                            
                    cache: false,
                    method: 'GET',
                    dataType: "html",                            
                    beforeSend: function( xhr ) { 
                      //$("#preloader").css('display','block');                           
                    },
                    success: function(data){
                        result = $.parseJSON(data);
                        $(".preloader").hide();                                
                        swal({
                            title: "Deleted!",
                            text: "The record(s) have been deleted",
                             type: "success",
                            confirmButtonText: "ok",
                            allowOutsideClick: "false"
                        }, function () { 
                           
                          load_sms_template(sms_api_id);
                        });
                       
                    },
                    complete: function(){
                    //$("#preloader").css('display','none');
                   },
            });
            
        });
       
    }
    else
    { 
        swal("Oops!", "Check the record to delete.");            
    }
  });
  // SMS API 
  // =================================================

  // =================================================
  // SMS API 

  $("body").on("click",".whatsapp_template_add",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var id=ThisObj.attr("data-id");
    // var field=ThisObj.attr("data-field");   
    // alert('ok'); return false;
    $.ajax({
        url: base_url + "setting/whatsapp_template_add_view_rander_ajax",
        type: "POST",
        data: {
            'id': id
        },
        async: true,
        success: function(response) {
            $("#common_view_modal_title_lg").text("Manage Template");
            $('#rander_common_view_modal_html_lg').html(response);
            $('#rander_common_view_modal_lg').modal({
                backdrop: 'static',
                keyboard: false
            });
            load_whatsapp_template(id);
            
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
  
  $("body").on("click","#add_whatsapp_template_confirm",function(e){    
      
    var base_url=$("#base_url").val(); 
    var whatsapp_api_id=$("#whatsapp_api_id").val();
    var whatsapp_api_name_obj=$("#whatsapp_t_api_name");           
    var whatsapp_name_obj=$("#whatsapp_t_name");
    var whatsapp_template_id_obj=$("#whatsapp_t_template_id");
    var whatsapp_t_template_variable_obj=$("#whatsapp_t_template_variable");
    var whatsapp_text_obj=$("#whatsapp_t_text");
    var data = "";
    
    if(whatsapp_api_name_obj.val()=='')
    {
      swal("Oops!", "SMS Api should not be blank.");       
      return false;
    }
  
    if(whatsapp_name_obj.val()=='')
    {
      swal("Oops!", "Template name should not be blank.");      
      return false;
    }
  
    if(whatsapp_template_id_obj.val()=='')
    {
      swal("Oops!", "Template ID should not be blank."); 
      return false;
    }
  
    // if(whatsapp_text_obj.val()=='')
    // {
    //   swal("Oops!", "Template Text should not be blank."); 
    //   return false;
    // }
   
    // alert(data); return false;
    $.ajax({
        url: base_url + "setting/add_edit_whatsapp_template",
        data: new FormData($('#frmTemplateAddEdit')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(xhr) {
            //$("#company_assigne_change_submit").attr("disabled",true);
        },
        success: function(data) {
            result = $.parseJSON(data);
            if(result.status='success'){  
              swal({
                title: "Success!",
                text: "The record(s) have been saved",
                 type: "success",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
              }, function () { 
                  $("#whatsapp_auto_template_id").val('');                
                  whatsapp_name_obj.val('')
                  whatsapp_template_id_obj.val('');
                  whatsapp_t_template_variable_obj.val('');
                  $('#whatsapp_t_template_variable').tagsinput('removeAll');
                  whatsapp_text_obj.val('');
                  load_whatsapp_template(whatsapp_api_id); 
              });                 
            }
        }
    });
  });

  $("body").on("click",".whatsapp_template_edit",function(e){
    var id = $(this).attr('data-id');
    var data = 'id='+id;   
    var base_url=$("#base_url").val();    
    // alert(data) ; return false;       
    $.ajax({
            url: base_url+"setting/get_whatsapp_template",
            data: data,                    
            cache: false,
            method: 'GET',
            dataType: "html",                   
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');                           
            },
            success: function(data){
                result = $.parseJSON(data);                
                $("#whatsapp_auto_template_id").val(result.id);
                $("#whatsapp_api_id").val(result.whatsapp_api_id);
                $("#whatsapp_t_name").val(result.name);
                $("#whatsapp_t_template_id").val(result.template_id);
                $("#whatsapp_t_template_variable").val(result.template_variable);
                $('#whatsapp_t_template_variable').tagsinput('add', result.template_variable);
                $("#whatsapp_t_text").val(result.text);                   
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
    });
  });
  $("body").on("click",".whatsapp_template_delete",function(e){
    var id = $(this).attr('data-id');
    var whatsapp_api_id=$("#whatsapp_api_id").val(); 
    if(id!='')
    {
        var base_url=$("#base_url").val();            
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record! Do you confirm?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-warning',
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            var data = 'id='+id;               
            $.ajax({
                    url: base_url+"setting/delete_whatsapp_template",
                    data: data,                            
                    cache: false,
                    method: 'GET',
                    dataType: "html",                            
                    beforeSend: function( xhr ) {},
                    success: function(data){
                        result = $.parseJSON(data);
                        $(".preloader").hide();                                
                        swal({
                            title: "Deleted!",
                            text: "The record(s) have been deleted",
                             type: "success",
                            confirmButtonText: "ok",
                            allowOutsideClick: "false"
                        }, function () {                            
                          load_whatsapp_template(whatsapp_api_id);
                        });                       
                    },
                    complete: function(){},
            });
            
        });
       
    }
    else
    { 
        swal("Oops!", "Check the record to delete.");            
    }
  });
  // WhaysApp API 
  // =================================================

  // ==================================================
  // TARGET SETTING
  // let kpi_target_by = $("input:radio[name=kpi_target_by]:checked").val();
  // if(kpi_target_by)
  // {
  //     var kpi_setting_id=$("#kpi_setting_id").val();
  //     fn_rander_kpi_setting_view(kpi_target_by,kpi_setting_id);
  // }

  $("body").on("change","input:radio[name=kpi_target_by]:checked",function(e){
    let kpi_target_by=$(this).val();
    // var kpi_setting_id=$("#kpi_setting_id").val();

      swal({
        title: '',
        text: 'All Previous KPIs will be deleted.',
        type: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ok, got it!',
        closeOnConfirm: true
      }, function() {
        fn_rander_kpi_setting_view(kpi_target_by);
      });    
  });

  if($("#kpi_target_by_id").val()){
    // var id=$("#kpi_target_by_id").val();
    // var kpi_target_by=$("input:radio[name=kpi_target_by]:checked").val();    
    // fn_rander_kpi_target_by_view(id,kpi_target_by);
  }

  $("body").on("click","#kpi_target_by_id",function(e){
    var id=$(this).attr("data-id");
    var kpi_target_by=$(this).attr("data-kpi_target_by");    
    $("#kpi_target_by_id").val(id);
    fn_rander_kpi_target_by_view(id,kpi_target_by);
  });

  $("body").on("click",".kpi_submit_confirm",function(e){
    var thisObj=$(this);
    var base_url=$("#base_url").val();
    var serial_no=$(this).attr("data-serial_no");
    var kpi_target_by=$("input:radio[name=kpi_target_by]:checked").val();
    var kpi_target_by_id=$(this).attr("data-kpi_target_by_id");
    var kpi_ids_arr=[];
    var kpi_names_arr=[];
     
    var kpi_flag=0;
    $("input:checkbox[name='kpi_ids_"+serial_no+"[]']:checked").each(function(){      
      kpi_ids_arr.push($(this).val());   
      kpi_names_arr.push($(this).attr('data-name'));  
      kpi_flag++;                                
    });
    
    // $("input:checkbox[name=kpi_ids:checked").each(function (i) {
    //   alert('ok')
    //   //kpi_ids_arr[i] = $(this).val();          
    // });
    // alert(kpi_target_by+'/'+kpi_target_by_id+'/'+kpi_ids_arr+'/'+kpi_names_arr)

    if(kpi_target_by==''){
        swal("Oops!", 'Please check KPI Type', "error");
				return false;
    }

    if(kpi_target_by_id==''){
      swal("Oops!", 'Please check KPI Type', "error");
      return false;
    }

    if(kpi_ids_arr.length==0){
      swal("Oops!", 'Please check any KPI', "error");
      return false;
    }
    var data = 'kpi_target_by='+kpi_target_by+'&kpi_target_by_id='+kpi_target_by_id+'&kpi_ids_str='+kpi_ids_arr.toString()+'&kpi_names_str='+kpi_names_arr.toString();               
    // alert(data); return false;
    $.ajax({
            url: base_url+"setting/add_edit_kpi_setting",
            data: data,                            
            cache: false,
            method: 'GET',
            dataType: "html",                            
            beforeSend: function( xhr ) { 
              //$("#preloader").css('display','block');  
              thisObj.text("Wait.."); 
              thisObj.attr("disabled",true);                        
            },
            complete: function(){
              //$("#preloader").css('display','none');
              thisObj.text("Save"); 
              thisObj.attr("disabled",false);
            },
            success: function(data){
                result = $.parseJSON(data);
                // $(".preloader").hide();                                
                swal({
                    title: "Saved!",
                    text: "The record(s) have been saved",
                      type: "success",
                    confirmButtonText: "ok",
                    allowOutsideClick: "false"
                }, function () { 
                  fn_rander_kpi_setting_view(kpi_target_by);
                  // fn_rander_kpi_target_by_view(kpi_target_by_id,kpi_target_by);
                  
                });
                
            },
            
    });
  });

  $("body").on("click",".kpi_setting_delete_action",function(e){
    var base_url=$("#base_url").val();
    var serial_no=$(this).attr("data-serial_no");
    var kpi_target_by=$("input:radio[name=kpi_target_by]:checked").val();
    var kpi_target_by_id=$(this).attr("data-kpi_target_by_id");
    var data = 'kpi_target_by='+kpi_target_by+'&kpi_target_by_id='+kpi_target_by_id+'&kpi_ids_str=&kpi_names_str=';               
    // alert(data); return false;
    swal({
      title: 'All KPIs will be deleted.Are you sure?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: false
    }, function() {
          $.ajax({
                  url: base_url+"setting/add_edit_kpi_setting",
                  data: data,                            
                  cache: false,
                  method: 'GET',
                  dataType: "html",                            
                  beforeSend: function( xhr ) { 
                    //$("#preloader").css('display','block');                           
                  },
                  success: function(data){
                      result = $.parseJSON(data);
                      // $(".preloader").hide();                                
                      swal({
                          title: "Success!",
                          text: "The KPIs have been deleted",
                            type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () { 
                        fn_rander_kpi_setting_view(kpi_target_by);
                        // fn_rander_kpi_target_by_view(kpi_target_by_id,kpi_target_by);
                        
                      });
                      
                  },
                  complete: function(){
                  //$("#preloader").css('display','none');
                  },
          });
    });
  });

  $("body").on("click",".cancel_kpi_submit_confirm",function(e){

    var kpi_target_by=$("input:radio[name=kpi_target_by]:checked").val();
    fn_rander_kpi_setting_view(kpi_target_by);
    // var serial_no=$(this).attr("data-serial_no");    
    // $("#kpi_setting_div_view_"+serial_no).show();
    // $("#kpi_setting_div_action_icon_"+serial_no).show();
    // $("#kpi_setting_div_edit_"+serial_no).hide();

  });

  $("body").on("click",".kpi_setting_edit_action",function(e){
    var serial_no=$(this).attr("data-serial_no");
    // $("#kpi_setting_div_view_"+serial_no).css("display","none");
    // $("#kpi_setting_div_edit_"+serial_no).css("display","block");
    $("#kpi_setting_div_view_"+serial_no).hide();
    $("#kpi_setting_div_action_icon_"+serial_no).hide();
    $("#kpi_setting_div_action_icon_na_"+serial_no).toggle();
    $("#kpi_setting_div_edit_"+serial_no).show();
  });

  $("body").on("click","#kpi_add_div_toggle",function(e){
    var base_URL=$("#base_url").val();
    var data = "";   
    $.ajax({
        url: base_URL+"setting/chk_for_set_target",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success:function(res){ 
           result = $.parseJSON(res); 
           
           if(result.kpi_setting_count>0){            
            document.location.href = base_URL+'setting/target_set';
            
           }
           else{
            swal("Oops!", 'No KPI available!', "error");
           }
       },
       complete: function(){},
       error: function(response) {}
   });
  });

  $("body").on("change","#set_kpi_target_by",function(e){
    var base_URL=$("#base_url").val();
    var kpi_by=$(this).val();
    var data="kpi_by="+kpi_by;
    $.ajax({
        url: base_URL+"setting/rander_user_by_kpi_type",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success:function(res){ 
           result = $.parseJSON(res); 
           
            if(kpi_by){
              $("#randet_kpi_target_by_wise_user_div").html(result.html);
            }
            else{
              $("#randet_kpi_target_by_wise_user_div").html('');
              $("#rander_user_wise_kpi_set_div").html('');
            }
            
       },
       complete: function(){},
       error: function(response) {}
   });
  });

  $("body").on("change","#get_user_by_kpi_target_by",function(e){
    var base_URL=$("#base_url").val();
    var id=$(this).val();
    var kpi_setting_id=$(this).find(':selected').attr('data-kpi_setting_id');
    var tmp_kpi_target_by=$("#tmp_kpi_target_by").val();
    var data="id="+id+"&tmp_kpi_target_by="+tmp_kpi_target_by+"&kpi_setting_id="+kpi_setting_id;
    // alert(data);
    $.ajax({
        url: base_URL+"setting/rander_user_by_kpi_id",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success:function(res){ 
          result = $.parseJSON(res); 
          
          $("#set_kpi_user_id").html(result.html);
          $("#rander_user_wise_kpi_set_div").html('');
          // if(id=='' || result.user_count=='0'){
          //   $("#rander_user_wise_kpi_set_div").html('');
          // }

            
       },
       complete: function(){},
       error: function(response) {}
   });
  });

  
  
  
  var url_str=window.location.href;
  var url_arr=url_str.split('/');
  var last_str=url_arr.reverse()[0];  
  if(last_str=='target_set'){
    set_kpi_user_id();
  }

  

  $("body").on("change","#set_kpi_user_id",function(e){
    // var base_URL=$("#base_url").val();
    var user_id=$(this).val();
    var kpi_setting_id=$(this).find(':selected').attr('data-kpi_setting_id'); 
    set_kpi_user_id(user_id,kpi_setting_id);   
    // var data="user_id="+user_id+"&kpi_setting_id="+kpi_setting_id;    
    // $.ajax({
    //     url: base_URL+"setting/rander_user_wise_kpi_set",
    //     data: data,
    //     cache: false,
    //     method: 'GET',
    //     dataType: "html",
    //     beforeSend: function( xhr ) {},
    //     success:function(res){ 
    //       result=$.parseJSON(res); 
    //       if(user_id){
    //         $("#rander_user_wise_kpi_set_div").html(result.html);
    //       }
    //       else{
    //         $("#rander_user_wise_kpi_set_div").html('');
    //       }   
    //       //====================================================================
    //       // Namutal number
    //       $('.only_natural_number').keyup(function(e)
    //       { 
    //           if (/\D/g.test(this.value))
    //               {
    //                 // Filter non-digits from input value.
    //                 this.value = this.value.replace(/\D/g, '');
    //               }                 
    //       });
    //       // Namutal number
    //       //====================================================================         
    //    },
    //    complete: function(){},
    //    error: function(response) {}
    // });
  });

  $("body").on('focusin', '.weighted_score', function(){
    // console.log("Saving value " + $(this).val());
    
  }).on('change','.weighted_score', function(){
    
      var uid=$(this).attr('data-uid');
      var tmp_weighted_score=0;
      $('.weighted_score_'+uid).each(function(){ 
        var curr_val=(this.value>0)?parseInt(this.value):0;
        tmp_weighted_score=curr_val+tmp_weighted_score;
      });    
      if(tmp_weighted_score>100)
      {
        swal("Oops!", "Weighted Score should not be greater than 100%"); 
        $(this).val(0);
      }
      
      var tmp_weighted_score=0;
      $('.weighted_score_'+uid).each(function(){    
        var curr_val=(this.value>0)?parseInt(this.value):0; 
        tmp_weighted_score=curr_val+tmp_weighted_score;
      });
      $("#weighted_score_out_of_"+uid+"_100").text(tmp_weighted_score+'/100');
    
  });

  $("body").on("click",".set_kpi_for_user_btn_confirm_inactive",function(e){

      var ThisObj=$(this);
      var uid=ThisObj.attr("data-uid");
      $('input[name^="weighted_score_'+uid+'[]"]').each(function(){
          $(this).attr("readonly",false);          
      });

      $('input[name^="target_'+uid+'[]"]').each(function(){
          $(this).attr("readonly",false);          
      });

      $('input[name^="min_target_threshold_'+uid+'[]"]').each(function(){
          $(this).attr("readonly",false);          
      });


      $("#is_apply_pli_"+uid).attr("disabled",false);
      $("#pli_in_"+uid).attr("readonly",false);
      
      
      $("#is_apply_pip_"+uid).attr("disabled",false);
      $("#set_total_target_threshold_"+uid).attr("readonly",false);
      $("#set_total_target_threshold_for_x_consecutive_month_"+uid).attr("readonly",false);

      ThisObj.val("Save");
      ThisObj.removeClass("set_kpi_for_user_btn_confirm_inactive");
      ThisObj.addClass("set_kpi_for_user_btn_confirm");
      $("#copy_kpi_"+uid).addClass("copy_kpi");
      $("#copy_kpi_"+uid).removeClass("copy_kpi_inactive");

  });

  $("body").on("click",".set_kpi_for_user_btn_confirm",function(e){
      var base_URL=$("#base_url").val();
      var uid=$(this).attr('data-uid');
      

      // Weighted Score
      // var weighted_score_arr = [];
      var tmp_weighted_score=0;
      var weighted_score_flag=0;
      $('.weighted_score_'+uid).each(function(){
        // weighted_score_arr.push(parseInt(this.value)); 
        
        tmp_weighted_score=parseInt(this.value)+tmp_weighted_score;        
        if(parseInt(this.value)==0 || this.value==''){
          weighted_score_flag++;
          return false;
        }
      });
      
      if(tmp_weighted_score!=100)
      {
        swal("Oops!", "Weighted Score should be exact 100%",'error'); 
        return false;
      }
      else
      {
        $("#weighted_score_out_of_"+uid+"_100").text(tmp_weighted_score+'/100');        
        if(weighted_score_flag>0)
        {
          swal("Oops!", "Each and every Weighted Score should be greater than zero/ Blank.",'error'); 
          return false;
        }
      }


     
      // Target
      var target_flag=0;
      $('.target_'+uid).each(function(){
        //if(parseInt(this.value)==0 || this.value==''){
        if(this.value==''){
          target_flag++;
          return false;
        }
      });       
      if(target_flag>0)
      {
        swal("Oops!", "Each and every Target should be greater than zero/ Blank.",'error'); 
        return false;
      }

      
    
      
      $('#profile_update_form_'+uid).append('<input type="hidden" name="uid" value="'+uid+'" />');
      $.ajax({
        url: base_URL + "setting/set_kpi_for_user",
        data: new FormData($('#profile_update_form_'+uid)[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(xhr) {
            //$("#company_assigne_change_submit").attr("disabled",true);
        },
        success: function(data) {
            result = $.parseJSON(data);
            //alert(result.status)
            if(result.status='success'){
              
              
              swal({
                title: "Success!",
                text: "The record(s) have been saved",
                  type: "success",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            }, function () { 
                
              var kpi_user_id=$("#set_kpi_user_id :selected").val();
              var kpi_setting_id=$("#set_kpi_user_id :selected").attr('data-kpi_setting_id');                
              set_kpi_user_id(kpi_user_id,kpi_setting_id);
            });

                
            }
        }
      });
  });

    
    // KPIS
    $("body").on("click","#key_performance_indicator_add_div_toggle",function(e){
      $("#key_performance_indicator_add_div").slideToggle();  
      $(this).hide();
    });
    $("body").on("click","#key_performance_indicator_submit_close",function(e){
      $("#key_performance_indicator_add_div").slideToggle();  
      $("#key_performance_indicator_add_div_toggle").show();  
      
      $("#key_performance_indicator_id").val('');
      $("#key_performance_indicator_name").val('');
    });

    $("body").on("click","#key_performance_indicator_submit_confirm",function(e){ 
        var base_url=$("#base_url").val();         
        var key_performance_indicator_name_obj=$("#key_performance_indicator_name");           
        
        var data = "";

        if(key_performance_indicator_name_obj.val()=='')
        {
          swal("Oops!", "Name should not be blank.",'error'); 
          sms_account_name_obj.focus();
          return false;
        }

             
        // alert(data); return false;
        $.ajax({
            url: base_url + "setting/add_edit_key_performance_indicator",
            data: new FormData($('#profile_update_form')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
                //$("#company_assigne_change_submit").attr("disabled",true);
            },
            success: function(data) {
                result = $.parseJSON(data);
                if(result.status='success'){

                  swal({
                    title: "Success!",
                    text: "The record(s) have been saved",
                    type: "success",
                    confirmButtonText: "ok",
                    allowOutsideClick: "false"
                }, function () { 
                    //location.reload(true); 
                    $("#key_performance_indicator_add_div_toggle").show(); 
                    $("#key_performance_indicator_add_div").slideToggle();
                    key_performance_indicator_name_obj.val('');                
                  
                    $("#key_performance_indicator_id").val('');
                    $("#key_performance_indicator_submit_confirm").html('Save');
                    load_key_performance_indicator(); 
                });

                    
                }
                  
                
            }
        });
    });

    $("body").on("click",".key_performance_indicator_edit",function(e){
      var id = $(this).attr('data-id');
      var data = 'id='+id;   
      var base_url=$("#base_url").val();           
      $.ajax({
              url: base_url+"setting/get_key_performance_indicator",
              data: data,                    
              cache: false,
              method: 'GET',
              dataType: "html",                   
              beforeSend: function( xhr ) { 
                //$("#preloader").css('display','block');                           
              },
              success: function(data){
                  result = $.parseJSON(data);  
                  // console.log(result) 
                  // $("body, html").animate({ scrollTop: 200 }, "slow");   
                  $("#key_performance_indicator_add_div_toggle").hide(); 
                  $("#key_performance_indicator_add_div").slideDown( "slow" );
                     
                  
                  $("#key_performance_indicator_id").val(result.id);
                  $("#key_performance_indicator_name").val(result.name);
              },
              complete: function(){
              //$("#preloader").css('display','none');
              },
      });
    });

    $("body").on("click",".key_performance_indicator_delete",function(e){
      var id = $(this).attr('data-id');
      if(id!='')
      {
          var base_url=$("#base_url").val();

          //Warning Message            
          swal({
              title: "Are you sure?",
              text: "You will not be able to recover this record! Do you confirm?",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-warning',
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
          }, function () {
              var data = 'id='+id;               
              $.ajax({
                      url: base_url+"setting/delete_key_performance_indicator",
                      data: data,                            
                      cache: false,
                      method: 'GET',
                      dataType: "html",                            
                      beforeSend: function( xhr ) { 
                        //$("#preloader").css('display','block');                           
                      },
                      success: function(data){
                          result = $.parseJSON(data);
                          $(".preloader").hide();                                
                          swal({
                              title: "Deleted!",
                              text: "The record(s) have been deleted",
                               type: "success",
                              confirmButtonText: "ok",
                              allowOutsideClick: "false"
                          }, function () { 
                             
                              load_key_performance_indicator(); 
                          });
                         
                      },
                      complete: function(){
                      //$("#preloader").css('display','none');
                     },
              });
              
          });
         
      }
      else
      { 
          swal("Oops!", "Check the record to delete.");            
      }
    });
  // TARGET SETTING
  // ===================================================

  $("body").on("click",".is_apply_pli",function(e){
      var uid=$(this).attr('data-uid');     
      if($(this).is(':checked')){
        $("#pli_in_"+uid).attr("readonly",false);
      }
      else{
        $("#pli_in_"+uid).attr("readonly",true);
      }
       
  });

  $("body").on("click",".is_apply_pip",function(e){
    var uid=$(this).attr('data-uid');    
    if($(this).is(':checked')){
      $("#set_total_target_threshold_"+uid).attr("readonly",false);
      $("#set_total_target_threshold_for_x_consecutive_month_"+uid).attr("readonly",false);
    }
    else{
      $("#set_total_target_threshold_"+uid).attr("readonly",true);
      $("#set_total_target_threshold_for_x_consecutive_month_"+uid).attr("readonly",true);
    }     
  });

  $("body").on("click",".copy_kpi",function(e){
     var to_uid=$(this).attr("data-uid");     
     var base_URL=$("#base_url").val();
     var data="to_uid="+to_uid; 
     $.ajax({
         url: base_URL+"setting/copy_user_wise_kpi_set",
         data: data,
         cache: false,
         method: 'GET',
         dataType: "html",
         beforeSend: function( xhr ) {},
         success:function(res){ 
           result=$.parseJSON(res);
           if(result.html)
           {
              $("#common_view_modal_title_md").text("Copy KPIs from a user");
              $('#rander_common_view_modal_html_md').html(result.html);
              $('#rander_common_view_modal_md').modal({
                  backdrop: 'static',
                  keyboard: false
              });
             
           }
           else
           { 
           }          
                    
        },
        complete: function(){},
        error: function(response) {}
     });
  });  

  $("body").on("click","#copy_kpi_confirm",function(e){
      var to_uid=$(this).attr("data-to_uid");
      var from_uid=$('input[name="copied_user"]:checked').val();

      var flag_copied_user=0;
      $('input[name="copied_user"]:checked').each(function() {
        flag_copied_user++;
      });
      if(flag_copied_user==0)
      {
        swal("Oops!", "Select any user to copy KPIs.",'error'); 
        sms_account_name_obj.focus();
        return false;
      }
      var base_URL=$("#base_url").val();
      var data="from_uid="+from_uid; 
      $.ajax({
          url: base_URL+"setting/get_user_wise_kpi_target_for_copied",
          data: data,
          cache: false,
          method: 'GET',
          dataType: "html",
          beforeSend: function( xhr ) {},
          success:function(res){ 
            result=$.parseJSON(res);
            if(result.rows.length>0)
            {
                var is_apply_pli_tmp='N';
                var is_apply_pip_tmp='N';
                var pli_in_tmp=0;
                var get_set_total_target_threshold_tmp=0;
                var get_set_total_target_threshold_for_x_consecutive_month_tmp=0;
                for(var i=0; i<result.rows.length;i++)
                {
                  // console.log(result.rows[i])
                  is_apply_pli_tmp=result.rows[i].is_apply_pli;
                  is_apply_pip_tmp=result.rows[i].is_apply_pip;
                  pli_in_tmp=result.rows[i].pli_in;
                  var get_set_total_target_threshold_tmp=result.rows[i].target_threshold;
                  var get_set_total_target_threshold_for_x_consecutive_month_tmp=result.rows[i].target_threshold_for_x_consecutive_month;
                  
                  // -------------------
                  var from_kpi_id=result.rows[i].kpi_id;
                  var get_weighted_score=result.rows[i].weighted_score;
                  $("#weighted_score_"+to_uid+"_"+from_kpi_id).val(get_weighted_score);
                  // -------------------

                  // -------------------
                  var get_target=result.rows[i].target;
                  $("#target_"+to_uid+"_"+from_kpi_id).val(get_target);
                  // -------------------

                  // -------------------
                  var get_min_target_threshold=result.rows[i].min_target_threshold;
                  $("#min_target_threshold_"+to_uid+"_"+from_kpi_id).val(get_min_target_threshold);
                  // ------------------- 
                }    
                
                if(is_apply_pli_tmp=='Y'){
                  $("#is_apply_pli_"+to_uid).attr("checked",true);
                  $("#pli_in_"+to_uid).attr('readonly',false);
                }
                else{
                  $("#is_apply_pli_"+to_uid).attr("checked",false);
                  $("#pli_in_"+to_uid).attr('readonly',true);
                } 
                $("#pli_in_"+to_uid).val(pli_in_tmp);

                
                if(is_apply_pip_tmp=='Y'){
                    $("#is_apply_pip_"+to_uid).attr("checked",true);
                    $("#set_total_target_threshold_"+to_uid).attr('readonly',false);
                    $("#set_total_target_threshold_for_x_consecutive_month_"+to_uid).attr('readonly',false);
                }
                else{
                    $("#is_apply_pip_"+to_uid).attr("checked",false);
                    $("#set_total_target_threshold_"+to_uid).attr('readonly',true);
                    $("#set_total_target_threshold_for_x_consecutive_month_"+to_uid).attr('readonly',true);
                }
                $("#set_total_target_threshold_"+to_uid).val(get_set_total_target_threshold_tmp);
                $("#set_total_target_threshold_for_x_consecutive_month_"+to_uid).val(get_set_total_target_threshold_for_x_consecutive_month_tmp);
               
            }
            else
            { 
            }
            $('#rander_common_view_modal_md').modal('hide');                      
          },
          complete: function(){},
          error: function(response) {}
      });


      // var flag_copied_user=0;
      // $('input[name="copied_user"]:checked').each(function() {
      //   flag_copied_user++;
      // });
      // if(flag_copied_user==0)
      // {
      //   swal("Oops!", "Select any user to copy KPIs.",'error'); 
      //   sms_account_name_obj.focus();
      //   return false;
      // }

      // var get_pli_in=$("#pli_in_"+from_uid).val();
      // var get_set_total_target_threshold=$("#set_total_target_threshold_"+from_uid).val();
      // var get_set_total_target_threshold_for_x_consecutive_month=$("#set_total_target_threshold_for_x_consecutive_month_"+from_uid).val();

      // if($("#is_apply_pli_"+from_uid).is(':checked')){
      //   $("#is_apply_pli_"+to_uid).attr("checked",true);
      //   $("#pli_in_"+to_uid).attr('readonly',false);
      // }
      // else{
      //   $("#is_apply_pli_"+to_uid).attr("checked",false);
      //   $("#pli_in_"+to_uid).attr('readonly',true);
      // } 
      // $("#pli_in_"+to_uid).val(get_pli_in);

      // if($("#is_apply_pip_"+from_uid).is(':checked')){
      //   $("#is_apply_pip_"+to_uid).attr("checked",true);
      //   $("#set_total_target_threshold_"+to_uid).attr('readonly',false);
      //   $("#set_total_target_threshold_for_x_consecutive_month_"+to_uid).attr('readonly',false);
      // }
      // else{
      //   $("#is_apply_pip_"+to_uid).attr("checked",false);
      //   $("#set_total_target_threshold_"+to_uid).attr('readonly',true);
      //   $("#set_total_target_threshold_for_x_consecutive_month_"+to_uid).attr('readonly',true);
      // } 
      // $("#set_total_target_threshold_"+to_uid).val(get_set_total_target_threshold);
      // $("#set_total_target_threshold_for_x_consecutive_month_"+to_uid).val(get_set_total_target_threshold_for_x_consecutive_month);


      // $('input[name^="weighted_score_'+from_uid+'[]"]').each(function(){
      //     var from_kpi_id=$(this).attr('data-kpi_id');
      //     var get_weighted_score=$("#weighted_score_"+from_uid+"_"+from_kpi_id).val();
      //     $("#weighted_score_"+to_uid+"_"+from_kpi_id).val(get_weighted_score);           
      // });

      // $('input[name^="target_'+from_uid+'[]"]').each(function(){
      //   var from_kpi_id=$(this).attr('data-kpi_id');
      //   var get_target=$("#target_"+from_uid+"_"+from_kpi_id).val();
      //   $("#target_"+to_uid+"_"+from_kpi_id).val(get_target);           
      // });

      // $('input[name^="min_target_threshold_'+from_uid+'[]"]').each(function(){
      //   var from_kpi_id=$(this).attr('data-kpi_id');
      //   var get_min_target_threshold=$("#min_target_threshold_"+from_uid+"_"+from_kpi_id).val();
      //   $("#min_target_threshold_"+to_uid+"_"+from_kpi_id).val(get_min_target_threshold);           
      // });      

      // $('#rander_common_view_modal_md').modal('hide');
  });

  $("body").on("click","#menu_label_submit_confirm",function(e){ 
      var base_url=$("#base_url").val();
      var flag=0;
      $('.menu_label').each(function(index) {
        var thisObj = $(this);
        if(thisObj.val()=='')
        {
          flag++;
        }
      });

      if(flag>0)
      {
        swal('Oops!', 'Menu label should not be blank.', 'error');   
        return false;  
      }
      var data = "";   
      // alert(data); return false;
      // alert("ok"); return false;
      $.ajax({
          url: base_url + "setting/update_menu_alias",
          data: new FormData($('#profile_update_form')[0]),
          cache: false,
          method: 'POST',
          dataType: "html",
          mimeType: "multipart/form-data",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function(xhr) {
              //$("#company_assigne_change_submit").attr("disabled",true);
          },
          success: function(data) {
              result = $.parseJSON(data);
              if(result.status='success'){

                swal({
                  title: "Success!",
                  text: "The record(s) have been saved",
                  type: "success",
                  confirmButtonText: "ok",
                  allowOutsideClick: "false"
              }, function () { 
                  location.reload(true); 
              });

                  
              }
                
              
          }
      });
  });



  $("body").on("click", ".add_branch_view", function(e) {
    
      var base_url=$("#base_url").val();
      var id='';
      
      $.ajax({
          url: base_url + "setting/branch_add_edit_view_rander_ajax",
          type: "POST",
          data: {
              'id': id
          },
          async: true,
          success: function(response) {
              $("#common_view_modal_title").text('Add Branch');
              $('#rander_common_view_modal_html').html(response);
              $('#rander_common_view_modal').modal({
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

  $("body").on("click", "#branch_submit", function(e) {
      e.preventDefault();
      var base_url = $("#base_url").val();  
      var ename_obj= $("#branch_name");  
      var contact_person_obj= $("#branch_contact_person");      
      var email_obj= $("#branch_email");
      var mobile_obj= $("#branch_mobile");
      var country_id=$("#branch_country_id").val();
      
      if(ename_obj.val()=='')
      {
          swal('Oops! Please enter branch name.');
          return false;
      }
      
      if(contact_person_obj.val()=='')
      {
          swal('Oops! Please enter contact persion.');
          return false;
      }

      if(email_obj.val()!='')
      {
          if(is_email_validate(email_obj.val())==false)
          {
              swal('Oops! Please enter valid Email.');
              return false;
          }
      }

      if(email_obj.val()=='' && mobile_obj.val()=='')
      {
          swal('Oops! Please enter valid Email/ Mobile.');
          return false;
      }
      
      if(country_id=='')
      {
          swal('Oops! Please select country.');
          return false;
      }
      
      $.ajax({
          url: base_url + "setting/add_edit_branch_ajax",
          data: new FormData($('#frmBranch')[0]),
          cache: false,
          method: 'POST',
          dataType: "html",
          mimeType: "multipart/form-data",
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function(xhr) {
            $('#rander_common_view_modal .modal-body').addClass('logo-loader');
          },
          complete: function (){
            $('#rander_common_view_modal .modal-body').removeClass('logo-loader');
          },
          success: function(data) {
              result = $.parseJSON(data);
              if (result.status == 'success') {                                       
                  swal({
                      title: 'Success',
                      text: result.msg,
                      type: 'success',
                      showCancelButton: false
                  },function() {                      
                      if(result.id){
                        rander_branch_list_view();
                      }
                      else{
                          $('#rander_common_view_modal').modal('hide');
                      }
                      
                  });
              }
          }
      });
  });

  
  $("body").on("click", ".list_branch_view", function(e) {     
    rander_branch_list_view();    
  });

  $("body").on("click", ".delete_branch_view", function(e) {
      var base_url=$("#base_url").val();
      var id=$(this).attr('data-id');
      
      swal({
          title: "Confirmation",
          text: 'The record will be deleted permanently. Are you sure?',
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, delete it!",
          closeOnConfirm: true
      }, function () { 
          $.ajax({
              url: base_url + "setting/branch_delete_ajax",
              type: "POST",
              data: {
                  'id': id
              },
              async: true,
              success: function(response) {                
                  swal({
                      title: 'Success',
                      text: 'Record successfully deleted.',
                      type: 'success',
                      showCancelButton: false
                  }, function() {
                    rander_branch_list_view();
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
      
      
  });

  $("body").on("click", ".edit_branch_view", function(e) {
      var base_url=$("#base_url").val();
      var id=$(this).attr('data-id');;
      $.ajax({
          url: base_url+"setting/branch_add_edit_view_rander_ajax",
          type: "POST",
          data: {'id':id},
          async: true,
          success: function(response) {
            $("#common_view_modal_title").text('Edit Branch');
            $('#rander_common_view_modal_html').html(response);
            $('#rander_common_view_modal').modal({
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

  // $("#rander_common_view_modal").on("hidden.bs.modal", function () {
  //     $('#rander_common_view_modal').modal('hide');
  //     var branch_id=$("#branch_id").val();  
  //     alert(branch_id)    
  //     if(branch_id!=''){
  //       if ($.isNumeric(branch_id)) {
  //         rander_branch_list_view();
  //       }
  //       else{}        
  //     }
  //     else{}
  // });

  // ========================================================================
  // =========== facebook ===================================================
  
  $("body").on("change","#page_list",function(e){
    // var base_url=$("#base_url").val();
    var fb_page_id=$(this).find(':selected').attr('data-id');
    var fb_page_access_token=$(this).find(':selected').attr('data-token');
    var is_fb_connected=$("#is_fb_connected").val(); 
    
    $("#form_list").html('<option value="">Select Form</option>');
    $("#form_field").html('');
    $("#form_field_for_tag").html(''); 

    get_facebook_page_wise_form_list(fb_page_id,fb_page_access_token,is_fb_connected);
    // var data='fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token+'&is_fb_connected='+is_fb_connected;    
    // $.ajax({
    //         url: base_url+"setting/get_facebook_page_wise_form_list",
    //         data: data,                    
    //         cache: false,
    //         method: 'GET',
    //         dataType: "html",                   
    //         beforeSend: function( xhr ) { 
    //           $.blockUI({ 
    //               message: 'Please wait...', 
    //               css: { 
    //                  padding: '10px', 
    //                  backgroundColor: '#fff', 
    //                  border:'0px solid #000',
    //                  '-webkit-border-radius': '10px', 
    //                  '-moz-border-radius': '10px', 
    //                  opacity: .5, 
    //                  color: '#000',
    //                  width:'450px',
    //                  'font-size':'14px'
    //                 }
    //           });
    //         },
    //         complete: function(){
    //           $.unblockUI();
    //         },
    //         success: function(data){
    //             result = $.parseJSON(data); 
    //             // alert(result.status)                
    //             if(result.status=='success')
    //             {
    //                 // alert(result.html)
    //                 $("#form_list").html(result.html);
    //             }
    //         }, 
    //         error: function(response) {
    //           swal("Oops!", response,'error');      
    //         }                   
    // });
});

$("body").on("change","#form_list",function(e){
    // var base_url=$("#base_url").val();
    var fb_form_id=$(this).find(':selected').attr('data-id');
    var fb_page_id=$(this).find(':selected').attr('data-page_id');
    var fb_page_access_token=$(this).find(':selected').attr('data-page_access_token');
    
    get_fb_fields(fb_form_id,fb_page_id,fb_page_access_token);
    // var data='fb_form_id='+fb_form_id+'&fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token;    
    // $.ajax({
    //         url: base_url+"setting/get_facebook_form_wise_lead",
    //         data: data,                    
    //         cache: false,
    //         method: 'GET',
    //         dataType: "html",                   
    //         beforeSend: function( xhr ) { 
    //           $.blockUI({ 
    //               message: 'Please wait...', 
    //               css: { 
    //                  padding: '10px', 
    //                  backgroundColor: '#fff', 
    //                  border:'0px solid #000',
    //                  '-webkit-border-radius': '10px', 
    //                  '-moz-border-radius': '10px', 
    //                  opacity: .5, 
    //                  color: '#000',
    //                  width:'450px',
    //                  'font-size':'14px'
    //                 }
    //           });
    //         },
    //         complete: function(){
    //           $.unblockUI();
    //         },
    //         success: function(data){
    //             result = $.parseJSON(data); 
                          
    //             if(result.status=='success')
    //             {
                   
    //                 $("#form_field").html(result.html);
    //             }
    //         }, 
    //         error: function(response) {
    //           swal("Oops!", response,'error');      
    //         }                   
    // });
});

$("body").on("click","#fb_update_available_fields",function(e){
  var fb_form_id=$("#form_list").find(':selected').attr('data-id');
  var fb_page_id=$("#form_list").find(':selected').attr('data-page_id');
  var fb_page_access_token=$("#form_list").find(':selected').attr('data-page_access_token');
  get_fb_fields(fb_form_id,fb_page_id,fb_page_access_token);
});

$("body").on("click","#confirm_fb_setting",function(e){
  var base_url=$("#base_url").val();

  var page_values = [];
  var $select = $('select[name=page_list]');
  $select.find('option').each(function() {
    if($(this).val()){
      page_values.push($(this).val());
    }    
  });
  $new_input = $('<input type="hidden" name="page_list_all">'); // use 'type="hidden"' in production
  $new_input.val(page_values.join('^'));
  $select.parent().append($new_input);


  var form_values = [];
  var $select = $('select[name=form_list]');
  $select.find('option').each(function() {
    if($(this).val()){
      form_values.push($(this).val());
    }    
  });
  $new_input = $('<input type="hidden" name="form_list_all">'); // use 'type="hidden"' in production
  $new_input.val(form_values.join('^'));
  $select.parent().append($new_input);

  $.ajax({
          url: base_url + "setting/add_edit_fb_lead_field",
          data: new FormData($('#profile_update_form')[0]),
          cache: false,
          method: 'POST',
          dataType: "html",
          mimeType: "multipart/form-data",
          contentType: false,
          cache: false,
          processData: false,
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
          complete: function(){
            $.unblockUI();
          },
          success: function(data) {
              result = $.parseJSON(data);
              // alert(result.status)
              if(result.status='success'){
                      swal({
                        title: "Success!",
                        text: "The record(s) have been saved",
                          type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    }, function () { 
                      
                                          
                      // $("#page_list").html('<option value="">Select Page</option>');
                      // $("#page_list").attr('disabled',true);
                      // $("#form_list").html('<option value="">Select Form</option>');
                      // $("#form_list").attr('disabled',true);
                      $("#form_list option:first").attr('selected','selected');
                      $("#form_field").html('');
                      $("#form_field_for_tag").html('');  

                      get_fb_existing_form_listing();
                    });
              }
              else{
                swal('fail',msg,'error'); 
              }
          }
      });
});

$(document).on('click', '.fb_default_change', function (e) {
        
  var base_URL = $("#base_url").val();   
  var id = $(this).attr('data-id');   
  var curr_status = $(this).attr('data-curr_status');  
  //Warning Message            
      swal({
          title: "Confirmation",
          text: "Do you want to change the current default API?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, change it!",
          closeOnConfirm: false
      }, function () { 
          var data="id="+id+"&curr_status="+curr_status;  
          $.ajax({
                  url: base_URL+"setting/change_default_fb_form_api",
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
                  $("#preloader").css('display','block');                           
                  },
                  success: function(data){
                      result = $.parseJSON(data);
                      $(".preloader").hide();
                      //alert(result.status);
                      swal({
                          title: "Updated!",
                          text: "The form has been changed",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () { 
                          //location.reload(true); 
                          get_fb_existing_form_listing();
                      });
                     
                  },
                  complete: function(){
                  $("#preloader").css('display','none');
                 },
          });
          
      });

  
}); 

// function get_facebook_page_wise_form_list(fb_page_id='',fb_page_access_token='',is_fb_connected='N')
// {
//     var base_url=$("#base_url").val();
//     var data='fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token+'&is_fb_connected='+is_fb_connected;
//     $.ajax({
//       url: base_url+"setting/get_facebook_page_wise_form_list",
//       data: data,                    
//       cache: false,
//       method: 'GET',
//       dataType: "html",                   
//       beforeSend: function( xhr ) { 
//         $.blockUI({ 
//             message: 'Please wait...', 
//             css: { 
//               padding: '10px', 
//               backgroundColor: '#fff', 
//               border:'0px solid #000',
//               '-webkit-border-radius': '10px', 
//               '-moz-border-radius': '10px', 
//               opacity: .5, 
//               color: '#000',
//               width:'450px',
//               'font-size':'14px'
//               }
//         });
//       },
//       complete: function(){
//         $.unblockUI();
//       },
//       success: function(data){
//           result = $.parseJSON(data); 
//           // alert(result.status)                
//           if(result.status=='success')
//           {
//               alert(result.html)
//               $("#form_list").html(result.html);
              
//           }
//       }, 
//       error: function(response) {
//         swal("Oops!", response,'error');      
//       }                   
//   });
// }

// function get_fb_fields(fb_form_id='',fb_page_id='',fb_page_access_token='')
// {
//     var base_url=$("#base_url").val();   
//     var is_fb_connected=$("#is_fb_connected").val();
//     if(fb_page_id==''){
//       swal("Oops!", "Page is missing",'error');   
//       return false;
//     }

//     if(fb_page_access_token==''){
//       swal("Oops!", "Page token is missing",'error');   
//       return false;
//     }

//     if(fb_form_id==''){
//       swal("Oops!", "Form is missing",'error');   
//       return false;
//     }
//     var data='fb_form_id='+fb_form_id+'&fb_page_id='+fb_page_id+'&fb_page_access_token='+fb_page_access_token+'&is_fb_connected='+is_fb_connected;
//     $.ajax({
//             url: base_url+"setting/get_facebook_form_wise_lead",
//             data: data,                    
//             cache: false,
//             method: 'GET',
//             dataType: "html",                   
//             beforeSend: function( xhr ) { 
//               $.blockUI({ 
//                   message: 'Please wait...', 
//                   css: { 
//                      padding: '10px', 
//                      backgroundColor: '#fff', 
//                      border:'0px solid #000',
//                      '-webkit-border-radius': '10px', 
//                      '-moz-border-radius': '10px', 
//                      opacity: .5, 
//                      color: '#000',
//                      width:'450px',
//                      'font-size':'14px'
//                     }
//               });
//             },
//             complete: function(){
//               $.unblockUI();
//             },
//             success: function(data){
//                 result = $.parseJSON(data); 
                          
//                 if(result.status=='success')
//                 {
                   
//                     $("#form_field").html(result.html);
//                     $("#form_field_for_tag").html(result.html2);
//                 }
//             }, 
//             error: function(response) {
//               swal("Oops!", response,'error');      
//             }                   
//     });
// }

$("body").on("click",".fb_edit",function(e){
  $("body, html").animate({ scrollTop: 100 }, "slow");
  var id=$(this).attr("data-id");  
  load_facebook_connect_btn(id);
});

$("body").on("click",".fb_delete",function(e){  
  var base_URL = $("#base_url").val();   
  var id = $(this).attr('data-id'); 
  //Warning Message            
      swal({
          title: "Confirmation",
          text: "Do you want to delete the current Form? The record will be deleted permanently.",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, do it!",
          closeOnConfirm: false
      }, function () { 
          var data="id="+id;  
          $.ajax({
                  url: base_URL+"setting/delete_fb_form_api",
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
                  $("#preloader").css('display','block');                           
                  },
                  success: function(data){
                      result = $.parseJSON(data);
                      $(".preloader").hide();
                      //alert(result.status);
                      swal({
                          title: "Deleted!",
                          text: "The form has been deleted",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false"
                      }, function () { 
                          //location.reload(true); 
                          get_fb_existing_form_listing();
                      });
                     
                  },
                  complete: function(){
                  $("#preloader").css('display','none');
                 },
          });
          
      });
});
// =========== facebook ===================================================
// ========================================================================
  
});

function rander_branch_list_view()
{
    var base_url=$("#base_url").val();          
    $.ajax({
        url: base_url + "setting/branch_list_view_rander_ajax",
        type: "POST",
        data: {},
        async: true,
        success: function(response) {
            $("#common_view_modal_title").text('Branch List');
            $('#rander_common_view_modal_html').html(response);
            $('#rander_common_view_modal').modal({
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
}

function set_kpi_user_id(user_id='',kpi_setting_id='')
{
    var base_URL=$("#base_url").val();
    var data="user_id="+user_id+"&kpi_setting_id="+kpi_setting_id;  
    $.ajax({
        url: base_URL+"setting/rander_user_wise_kpi_set",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success:function(res){ 
          result=$.parseJSON(res);
          if(result.html)
          {
            $("#rander_user_wise_kpi_set_div").html(result.html); 
            //====================================================================
            // Namutal number
            $('.only_natural_number').keyup(function(e)
            { 
                if (/\D/g.test(this.value))
                    {
                      // Filter non-digits from input value.
                      this.value = this.value.replace(/\D/g, '');
                    }                 
            });
            // Namutal number
            //====================================================================
            //====================================================================
            // integer / float number
            $(".double_digit").keydown(function(e) {
                  debugger;
                  var charCode = e.keyCode;
                  if (charCode != 8 && charCode != 37 && charCode != 39 && charCode != 46) {
                      //alert($(this).val());
                      if (!$.isNumeric($(this).val()+e.key)) {
                          return false;
                      }
                  }
              return true;
            }); 
            // integer / float number
            //====================================================================
            
          }
          else
          {
            $("#rander_user_wise_kpi_set_div").html('No record found!');  
          }
          
                   
       },
       complete: function(){},
       error: function(response) {}
    });
}

function fn_rander_kpi_user_option()
{
    var base_URL=$("#base_url").val();
    var data="";  
    $.ajax({
        url: base_URL+"setting/rander_kpi_user_option",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success:function(res){ 
          result=$.parseJSON(res);         
          $("#set_kpi_user_id").html(result.html);
                  
       },
       complete: function(){},
       error: function(response) {}
    });

}


function fn_rander_im_rule_wise_view(rule_id,im_s_id='')
{
  var base_URL     = $("#base_url").val();
  var data = "rule_id="+rule_id+"&im_s_id="+im_s_id;
  // alert(data);
  //return false;
  $.ajax({
      url: base_URL+"setting/rander_im_rule_wise_view",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
          result = $.parseJSON(res);  
          $("#im_rule_wise_view").html(result.html);
          $('.custom-select').select2({
                tags: false,                
          });
     },
     complete: function(){},
     error: function(response) {}
 });
}

function fn_rander_ei_rule_wise_view(rule_id,ti_s_id='')
{
  var base_URL     = $("#base_url").val();
  var data = "rule_id="+rule_id+"&ti_s_id="+ti_s_id;
  $.ajax({
      url: base_URL+"setting/rander_ei_rule_wise_view",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
         result = $.parseJSON(res);  
         $("#ei_rule_wise_view").html(result.html);
         $('.custom-select').select2({
                tags: false,
          });
     },
     complete: function(){},
     error: function(response) {}
 });
}

function fn_rander_ti_rule_wise_view(rule_id,ti_s_id='')
{
  var base_URL     = $("#base_url").val();
  var data = "rule_id="+rule_id+"&ti_s_id="+ti_s_id;
  // alert(data);
  //return false;
  $.ajax({
      url: base_URL+"setting/rander_ti_rule_wise_view",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
         result = $.parseJSON(res);  
         $("#ti_rule_wise_view").html(result.html);
         $('.custom-select').select2({
                tags: false,
          });
     },
     complete: function(){},
     error: function(response) {}
 });
}

function fn_rander_aj_rule_wise_view(rule_id,aj_s_id='')
{
  var base_URL     = $("#base_url").val();
  var data = "rule_id="+rule_id+"&aj_s_id="+aj_s_id;
  // alert(data);
  //return false;
  $.ajax({
      url: base_URL+"setting/rander_aj_rule_wise_view",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
         result = $.parseJSON(res);  
         $("#aj_rule_wise_view").html(result.html);
         $('.custom-select').select2({
                tags: false,
          });
     },
     complete: function(){},
     error: function(response) {}
 });
}

function fn_rander_jd_rule_wise_view(rule_id,jd_s_id='')
{
  var base_URL     = $("#base_url").val();
  var data = "rule_id="+rule_id+"&jd_s_id="+jd_s_id;
  // alert(data);
  //return false;
  $.ajax({
      url: base_URL+"setting/rander_jd_rule_wise_view",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
         result = $.parseJSON(res);  
         $("#jd_rule_wise_view").html(result.html);
         $('.custom-select').select2({
                tags: false,
          });
     },
     complete: function(){},
     error: function(response) {}
 });
}

function fn_rander_web_rule_wise_view(rule_id,web_s_id='')
{
  var base_URL=$("#base_url").val();
  var data = "rule_id="+rule_id+"&web_s_id="+web_s_id;
  // alert(data);
  //return false;
  $.ajax({
      url: base_URL+"setting/rander_web_rule_wise_view",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
         result = $.parseJSON(res);  
         $("#web_rule_wise_view").html(result.html);
         $('.custom-select').select2({
                tags: false,
          });
     },
     complete: function(){},
     error: function(response) {}
 });
}


function load_domestic_terms()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_domestic_terms_and_conditions_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#dterms_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       });
}

function load_international_terms()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_international_terms_and_conditions_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#iterms_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_im_credentials()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_im_credentials_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#im_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_smtp_credentials()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_smtp_credentials_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#smtp_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_c2c_credentials()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_c2c_credentials_list_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#c2c_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}
function remove_pdf(id='') 
{
  var base_URL     = $("#base_url").val();
  swal({
    title: 'Are you sure?',
    text: 'The PDF will be deleted permanently.',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: 'Yes, delete it!',
    closeOnConfirm: false
  }, 
  function(isConfirm) {
    if (isConfirm) {
        
        $.ajax({
              url: base_URL+"setting/delete_existing_brochure",
              type: "POST",
              data: {'id':id},      
              success: function (response) 
              {
                $("#PreviewPdf").hide();
                swal('Deleted!', 'The brochur PDF has been deleted successfully!', 'success');          
              },
              error: function () 
              {

              }
          });
      
    }
     return false;
  });
}
function openCity(evt, cityName) 
{ 

  $("body, html").animate({ scrollTop: 100 }, "slow");
		var i, tabcontent, tablinks;
		
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		
		document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
    

    if(cityName =='email_forwarding_settings'){   
      $('#profile_update_form').bind('submit',function(e){e.preventDefault();});   
      
    }else{    
      $('#profile_update_form').unbind('submit');
    }
    //////////
    if(cityName=='lead_api_settings')
    {     
      load_im_credentials();
      // $("#submit_div").hide();
    }
    else if(cityName=='terms_and_conditions')
    {
      load_domestic_terms();
      load_international_terms();
      //$("#submit_div").show();
    }
    else if(cityName=='ti_api_settings')
    {      
      load_ti_credentials();
    }
    else if(cityName=='aj_api_settings')
    {      
      load_aj_credentials();
    }
    else if(cityName=='jd_api_settings')
    {    
      load_jd_credentials();
    }
    else if(cityName=='websitep_api_setting')
    {     
      load_web_credentials();
    }
    else if(cityName=='gmail_for_sync')
    {      
      load_user_gmail_for_sync();
    }
    else if(cityName=='email_forwarding_settings')
    {   
      //$('#profile_update_form').bind('submit',function(e){e.preventDefault();});   
      load_email_forwarding_settings();
    }
    else if(cityName=='sms_forwarding_settings')
    {   
      //$('#profile_update_form').bind('submit',function(e){e.preventDefault();});   
      load_sms_forwarding_settings();
    }
    else if(cityName=='whatsapp_forwarding_settings')
    {   
      //$('#profile_update_form').bind('submit',function(e){e.preventDefault();});   
      load_whatsapp_forwarding_settings();
    }
    else if(cityName=='smtp_settings')
    {      
      load_smtp_credentials();
    }
    else if(cityName=='sms_api_settings')
    {      
      load_sms_credentials();
    }
    else if(cityName=='whatsapp_api_settings')
    {      
      load_whatsapp_credentials();
    }
    else if(cityName=='c2c_settings')
    {      
      load_c2c_credentials();
    }
    else if(cityName=='lead_stage_settings')
    {    
      load_lead_stage_list();
    }
    else if(cityName=='my_documents')
    {    
      load_my_document();
    }
    else if(cityName=='auto_regrete_setting')
    {    
      //load_my_document();
    }
    else if(cityName=='product_group_setting')
    {    
      load_product_group();
    }
    else if(cityName=='product_category_setting')
    {    
      load_product_category();
    }
    else if(cityName=='product_unit_setting')
    {    
      load_product_unit_type();
    }
    else if(cityName=='lead_source_setting')
    {    
      load_lead_source();
    }
    else if(cityName=='business_type_setting')
    {    
      load_business_type();
    }
    else if(cityName=='lead_regret_setting')
    {    
      load_lead_regret_reason();
    }
    else if(cityName=='key_performance_indicator')
    {   
      
      load_key_performance_indicator();
    }
    else if(cityName=='kpi_settings')
    {      
      let kpi_target_by = $("input:radio[name=kpi_target_by]:checked").val();
      if(kpi_target_by)
      {
          // var kpi_setting_id=$("#kpi_setting_id").val();
          fn_rander_kpi_setting_view(kpi_target_by);
      }
    }
    else if(cityName=='kpi_set_target_settings')
    {       
      fn_rander_kpi_user_option();
      set_kpi_user_id();      
    }
    else if(cityName=='auto_expire_for_idle_setting')
    {    
      
    }
    else if(cityName=='menu_label_setting')
    { 
      load_menu_label_alias();
    }
    else if(cityName=='employee_type_setting')
    {    
      load_employee_type();
    }
    else if(cityName=='ei_api_settings')
    {      
      load_ei_credentials();
    }
    else if(cityName=='facebook_api_key_setting')
    {      
      load_facebook_connect_btn();      
    }
    else if(cityName=='fb_lead_assignment_setting')
    {      
      // load_facebook_connect_btn();  
      load_fb_lead_assignment_setting();    
    }
    
}

function get_fb_existing_form_listing()
{
    var base_url=$("#base_url").val();   
    var is_fb_connected=$("#is_fb_connected").val();    
    var data='is_fb_connected='+is_fb_connected;
    $.ajax({
            url: base_url+"setting/get_facebook_form_list",
            data: data,                    
            cache: false,
            method: 'GET',
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
            complete: function(){
              $.unblockUI();
            },
            success: function(data){
                result = $.parseJSON(data); 
                          
                if(result.status=='success')
                {
                  $("#facebook_form_list_div").html(result.html);
                  
                }
            }, 
            error: function(response) {
              swal("Oops!", response,'error');      
            }                   
    });
}
function load_facebook_connect_btn(id='')
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var is_fb_connected=($("#is_fb_connected").val())?$("#is_fb_connected").val():'N';
        var data = "is_fb_connected="+is_fb_connected+"&id="+id;
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_facebook_connect_btn_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#facebook_api_connect_btn").html(result.html);
               get_fb_existing_form_listing();
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function GetStateList(cont)
{
  var base_URL     = $("#base_url").val();
	$.ajax({
		  url: base_URL+"lead/getstatelist",
		  type: "POST",
		  data: {'country_id':cont},		  
		  success: function (response) 
		  { 
        if(response!='')
        {
          document.getElementById('state').innerHTML=response;
        }
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}
	
function GetCityList(state)
{
  var base_URL     = $("#base_url").val();
	$.ajax({
		  url: base_URL+"lead/getcitylist",
		  type: "POST",
		  data: {'state_id':state},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{
				document.getElementById('city').innerHTML=response;
			}
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}

function load_ei_credentials()
{
    var base_URL     = $("#base_url").val();
    var data = "";
    $.ajax({
      url: base_URL+"setting/rander_ei_credentials_list_ajax/",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {  
      },
      success:function(res){ 
          result = $.parseJSON(res);  
          $("#ei_tcontent").html(result.table);
      },
      complete: function(){
      },
      error: function(response) {
      }
    })
}

function load_ti_credentials()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(base_URL+"setting/rander_ti_credentials_list_ajax/");// return false;
        $.ajax({
            url: base_URL+"setting/rander_ti_credentials_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#ti_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_aj_credentials()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(base_URL+"setting/rander_aj_credentials_list_ajax/");// return false;
        $.ajax({
            url: base_URL+"setting/rander_aj_credentials_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#aj_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_jd_credentials()
{
    //return;
    //var page_num=page;
    var base_URL     = $("#base_url").val();
    var data = "";
    // alert(base_URL+"setting/rander_ti_credentials_list_ajax/");// return false;
    $.ajax({
        url: base_URL+"setting/rander_jd_credentials_list_ajax/",
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
           //alert(result.table);
           //alert(3);        
            //alert(result.table);
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
           if(result.row_count>0)
           {
            $('#jd_add_div_toggle').hide();
           }
           else
           {
            $('#jd_add_div_toggle').show();
           }
           $("#jd_tcontent").html(result.table);
       },
       complete: function(){
        //removeLoader();
       },
       error: function(response) {
        //alert('Error'+response.table);
        }
   })
}

function load_web_credentials()
{
    //return;
    //var page_num=page;
    var base_URL     = $("#base_url").val();
    var data = "";
    // alert(base_URL+"setting/rander_ti_credentials_list_ajax/");// return false;
    $.ajax({
        url: base_URL+"setting/rander_web_credentials_list_ajax/",
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
           //alert(result.table);
           //alert(3);        
            //alert(result.table);
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
           if(result.row_count>0)
           {
            $('#web_add_div_toggle').hide();
           }
           else
           {
            $('#web_add_div_toggle').show();
           }
           $("#web_tcontent").html(result.table);
       },
       complete: function(){
        //removeLoader();
       },
       error: function(response) {
        //alert('Error'+response.table);
        }
   })
}

function load_fb_lead_assignment_setting()
{
    //return;
    //var page_num=page;
    var base_URL     = $("#base_url").val();
    var data = "";
    // alert(base_URL+"setting/rander_ti_credentials_list_ajax/");// return false;
    $.ajax({
        url: base_URL+"setting/rander_fb_lead_assignment_list_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
          },
        success:function(res){ 
           result = $.parseJSON(res);           
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
           if(result.row_count>0)
           {
            $('#fb_add_div_toggle').hide();
           }
           else
           {
            $('#fb_add_div_toggle').show();
           }
           $("#fb_tcontent").html(result.table);
       },
       complete: function(){
        //removeLoader();
       },
       error: function(response) {
        //alert('Error'+response.table);
        }
   })
}

function load_sms_credentials()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(base_URL+"setting/rander_ti_credentials_list_ajax/");// return false;
        $.ajax({
            url: base_URL+"setting/rander_sms_credentials_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#sms_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_whatsapp_credentials()
{        
  var base_URL=$("#base_url").val();
  var data = "";
  $.ajax({
      url: base_URL+"setting/rander_whatsapp_credentials_list_ajax/",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
          result = $.parseJSON(res);   
          $("#whatsapp_tcontent").html(result.table);
      },
      complete: function(){},
      error: function(response) {}
  });
}

function load_user_gmail_for_sync()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_gmail_sync_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#gmail_sync_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_email_forwarding_settings()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_email_forwarding_settings_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#email_forwarding_tcontent").html(result.table);
                $('input#daily_report_tomail').tagsinput({
                    maxTags: 5,
                    trimValue: true,
                    allowDuplicates: false
                });
                $('input#daily_report_tomail').on('beforeItemAdd', function(event) {
                  var tag = event.item; 
                  if(is_email_validate(tag)==false)
                  {       
                    event.cancel = true;
                  }      
                });
                // $('#profile_update_form').on('submit', function(event){
                //      event.preventDefault();
                //      event.stopPropagation();
                // });                
                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       });
}


function load_sms_forwarding_settings()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        //alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_sms_forwarding_settings_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#sms_forwarding_tcontent").html(result.table);
                $('input#daily_report_tomail').tagsinput({
                    maxTags: 5,
                    trimValue: true,
                    allowDuplicates: false
                });
                $('input#daily_report_tomail').on('beforeItemAdd', function(event) {
                  var tag = event.item; 
                  if(is_email_validate(tag)==false)
                  {       
                    event.cancel = true;
                  }      
                });
                // $('#profile_update_form').on('submit', function(event){
                //      event.preventDefault();
                //      event.stopPropagation();
                // });                
                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       });
}

function load_whatsapp_forwarding_settings()
{
    var base_URL     = $("#base_url").val();
    var data = "";
    $.ajax({
        url: base_URL+"setting/rander_whatsapp_forwarding_settings_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) { },
        success:function(res){ 
            result = $.parseJSON(res);  
            $("#whatsapp_forwarding_tcontent").html(result.table);
            $('input#daily_report_tomail').tagsinput({
                maxTags: 5,
                trimValue: true,
                allowDuplicates: false
            });
            $('input#daily_report_tomail').on('beforeItemAdd', function(event) {
              var tag = event.item; 
              if(is_email_validate(tag)==false)
              {       
                event.cancel = true;
              }      
            });                           
            
        },
        complete: function(){ },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}

function load_lead_stage_list()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_lead_stage_list_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#lead_stage_tcontent").html(result.table);
                $( "#lead_stage_sortable" ).sortable({
                  axis: 'y',
                  update: function (event, ui) {                  
                      var new_sort = $("#lead_stage_sortable").sortable("serialize", {key:'new_sort[]'});
                      var base_url=$("#base_url").val();
                      var data=new_sort;
                      $.ajax({
                              url: base_url+"setting/resort_lead_stage",
                              data: data,                    
                              cache: false,
                              method: 'GET',
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
                              complete: function(){
                                $.unblockUI();
                              },
                              success: function(data){
                                  result = $.parseJSON(data);                        
                                  if(result.status=='success')
                                  {
                                    load_lead_stage_list();
                                  }
                              },
                      });
                    }
                });
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}


function load_my_document()
{        
  var base_URL=$("#base_url").val();
  var data = "";        
  $.ajax({
      url: base_URL+"setting/rander_my_document_ajax/",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {                
          //addLoader('.table-responsive');
        },
      success:function(res){ 
         result = $.parseJSON(res);
         $("#my_document_tcontent").html(result.table);
          
     },
      complete: function(){},
      error: function(response) {}
  })
}

function load_product_group()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_product_group_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#product_group_tcontent").html(result.table);                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_product_category()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_product_category_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#product_category_tcontent").html(result.table);                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_product_unit_type()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_product_unit_type_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#product_unit_type_tcontent").html(result.table);                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_lead_source()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_lead_source_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#lead_source_tcontent").html(result.table);                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_business_type()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_business_type_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#business_type_tcontent").html(result.table);                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_employee_type()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_employee_type_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#employee_type_tcontent").html(result.table);                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_lead_regret_reason()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"setting/rander_lead_regret_reason_ajax/",
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
               // alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
                $("#lead_regret_reason_tcontent").html(result.table);                
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_sms_template(sms_api_id)
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "sms_api_id="+sms_api_id;
        // alert(base_URL+"setting/rander_ti_credentials_list_ajax/");// return false;
        $.ajax({
            url: base_URL+"setting/rander_sms_template_list_ajax/",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
            beforeSend: function( xhr ) {                
                //addLoader('.table-responsive');
              },
            success:function(res){ 
               result = $.parseJSON(res);                
               $("#sms_t_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_whatsapp_template(whatsapp_api_id)
{
        var base_URL     = $("#base_url").val();
        var data = "whatsapp_api_id="+whatsapp_api_id;
        $.ajax({
            url: base_URL+"setting/rander_whatsapp_template_list_ajax/",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
            beforeSend: function( xhr ) {                
                //addLoader('.table-responsive');
              },
            success:function(res){ 
               result = $.parseJSON(res);                
               $("#whatsapp_t_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}


function fn_rander_kpi_setting_view(kpi_target_by)
{
    // alert(kpi_target_by+'/'+kpi_setting_id); return false;
    var base_URL     = $("#base_url").val();
    var data = "kpi_target_by="+kpi_target_by;
    // alert(data) 
    $.ajax({
        url: base_URL+"setting/rander_kpi_setting_view_ajax/",
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
            // alert(result.table);
            //alert(3);        
            //alert(result.table);
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
            $("#kpi_setting_div").html(result.html);                
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    })

}

function fn_rander_kpi_target_by_view(kpi_target_by_id,kpi_target_by)
{
    var base_URL=$("#base_url").val();
    var data = "kpi_target_by_id="+kpi_target_by_id+"&kpi_target_by="+kpi_target_by;
    // alert(data) 
    $.ajax({
        url: base_URL+"setting/rander_kpi_target_by_view_ajax/",
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
            $("#kpi_target_by_div").html(result.html);                
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    })
}

function load_key_performance_indicator()
{
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var data = "";
        // alert(base_URL+"setting/rander_ti_credentials_list_ajax/");// return false;
        $.ajax({
            url: base_URL+"setting/rander_kpi_list_ajax/",
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
               //alert(result.table);
               //alert(3);        
                //alert(result.table);
                // $("body, html").animate({ scrollTop: 500 }, "slow");   
               $("#kpi_tcontent").html(result.table);
           },
           complete: function(){
            //removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
}

function load_menu_label_alias()
{
    var base_URL=$("#base_url").val();
    var data = "";       
    $.ajax({
        url: base_URL+"setting/rander_menu_label_alias_list_ajax/",
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
            //alert(result.table);
            //alert(3);        
            //alert(result.table);
            // $("body, html").animate({ scrollTop: 500 }, "slow");   
            $("#menu_label_alias_tcontent").html(result.table);
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    })
}

function fn_rander_fb_rule_wise_view(rule_id,fb_s_id='')
{
  var base_URL=$("#base_url").val();
  var data = "rule_id="+rule_id+"&fb_s_id="+fb_s_id;
  // alert(data);
  //return false;
  $.ajax({
      url: base_URL+"setting/rander_fb_rule_wise_view",
      data: data,
      cache: false,
      method: 'GET',
      dataType: "html",
      beforeSend: function( xhr ) {},
      success:function(res){ 
         result = $.parseJSON(res);  
        //  alert(result.html)
         $("#fb_rule_wise_view").html(result.html);
         $('.custom-select').select2({
                tags: false,
          });
     },
     complete: function(){},
     error: function(response) {}
 });
}
