$(document).ready(function(){
    const params = new URLSearchParams(window.location.search); 
    var page=params.getAll('page');
    if(page=='setting'){
        document.getElementById("settingOpen").click();
    }
    else{
        document.getElementById("defaultOpen").click();
    }
     

    // =======================================================================================
    // ORDER MANAGEMENT DETAILS
    $("body").on("change","#form_id",function(e){
        
        var base_url=$("#base_url").val();
        var f_id=$(this).val();
        var proforma_invoice_id=$("#proforma_invoice_id").val();
        if(f_id==''){
            $('#om_form_wise_fields_div').html('');
            return false;
        }
        $.ajax({
            url: base_url + "order_management/om_form_wise_fields_view_rander_ajax",
            type: "POST",
            data: {
                'f_id':f_id,
                'proforma_invoice_id':proforma_invoice_id
            },
            async: true,            
            success: function(data) {
                result = $.parseJSON(data); 
                
                if(result.status=='success'){
                    $('#om_form_wise_fields_div').html(result.html);
                }
                else{
                    swal({
                        title: 'Oops!',
                        text: result.msg,
                        type: 'error',
                        showCancelButton: false
                        }, function() {
  
                    });
                }
                
                
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
  
    $("body").on("click","#om_stage_wise_document_save_confirm",function(e){
        
        e.preventDefault();
        
        var base_url=$("#base_url").val();  
        var missing_name='';  
        jQuery('.required').each(function() {
            var currentElement = $(this);
            var value = currentElement.val(); 
            if(value==''){
                missing_name +=currentElement.attr('data-label')+' required.<br>';
                
            }
        });
        if(missing_name){
            swal({   
                title: "Oops!",  
                text: missing_name,
                html: true,
                type:'error' 
            });                            
            return false;
        }
       
        var str_tmp='';
        jQuery('.om_custom_form_field').each(function() {
            
            var currentElement = $(this);
            var type=currentElement.prop("type");
            
            if(type=='text' || type=='textarea' || type=='file'){
                var value = currentElement.val(); 
                var id=currentElement.attr('data-id'); 
                var name=currentElement.attr('name'); 
                str_tmp +=id+'~'+value+'~'+name+'!***!';
            }
            else{
                if(type=='radio'){
                    if(currentElement.is(':checked')) {
                    
                        var value = currentElement.val(); 
                        var id=currentElement.attr('data-id'); 
                        var name=currentElement.attr('name');
                        str_tmp +=id+'~'+value+'~'+name+'!***!';
                    }
                } 
                else if(type=='checkbox'){                   
                    // if(currentElement.is(':checked')) {
                    //     var value = currentElement.val(); 
                    //     var id=currentElement.attr('data-id'); 
                    //     var name=currentElement.attr('name');
                    //     str_tmp +=id+'~'+value+'~'+name+'!***!';

                        
                    // }
                    
                }                
                else if(type=='select-one'){
                    if(currentElement.val()) {
                    
                        var value = currentElement.val(); 
                        var id=currentElement.attr('data-id'); 
                        var name=currentElement.attr('name');
                        str_tmp +=id+'~'+value+'~'+name+'!***!';
                        
                    }
                }
            }            
        });        
        var name_arr=[];
        jQuery('.om_custom_form_field').each(function() {            
            var currentElement = $(this);
            var type=currentElement.prop("type");            
            var name_tmp='';
            if(type=='checkbox'){
                var name=currentElement.attr('name');
                name_arr.push(name);                      
            }             
        });
        var name_unique = name_arr.filter(function(itm, i, a) {
            return i == a.indexOf(itm);
        });        
        $.each(name_unique, function( index, value ) {
            var yourArray=[];
            var yourArray2=[];    
            var flag=0;        
            $("input:checkbox[name="+value+"]:checked").each(function(){
                yourArray.push($(this).val());
                yourArray2.push($(this).attr('data-id'));
                flag=1;
                
            });

            if(flag==1){
                var id_unique = yourArray2.filter(function(itm, i, a) {
                    return i == a.indexOf(itm);
                });
                str_tmp +=id_unique+'~'+yourArray.join("^")+'~'+value+'!***!';
            }
        });
        //alert(str_tmp);//return false;
        $('form#omDocFrm').append('<input type="hidden" name="om_custom_form_field" value="'+str_tmp+'" />');
        $.ajax({
                url: base_url + "order_management/om_stage_wise_doc_submit",
                data: new FormData($('#omDocFrm')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,                   
                beforeSend: function( xhr ) {                
                    $("#om_stage_wise_document_save_confirm").attr("disabled",true);
                },
                complete: function(){
                    $("#om_stage_wise_document_save_confirm").attr("disabled",false);
                },
                success: function(data){
                    result = $.parseJSON(data);
                    // alert(result.msg)
                    if(result.status=='success'){
                        swal({
                                title: 'Success!',
                                text: 'Record successfully saved',
                                type: 'success',
                                showCancelButton: false
                            }, function() {                                
                                $("#form_id option:first").prop("selected", "selected");
                                $("#om_form_wise_fields_div").html('');
                                var piid=$("#proforma_invoice_id").val();
                                fn_rander_document_list(piid);
                                fn_lead_order_history(piid);
                        });
                    }
                    else{
                        swal({
                                title: 'Oops!',
                                text: result.msg,
                                type: 'error',
                                showCancelButton: false
                            }, function() {  });
                    }
                    
                    
  
                },                    
        });
    });
  
    $('#rander_common_view_modal_lg').on('hide.bs.modal', function (e) {
        if ($('#OmDetailModal').hasClass('in')) {
            $("#OmDetailModal").css("display","block");            
        }
    })
  
    $("body").on("click",".document_view_popup",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        $.ajax({
          url: base_url + "order_management/document_view_ajax",
          type: "POST",
          data: {
              'id': id
          },
          async: true,
          success: function(data) {
                result = $.parseJSON(data);
                $("#OmDetailModal").css("display","none");
                $("#common_view_modal_title_lg").text(result.title);
                $('#rander_common_view_modal_html_lg').html(result.html);
                $('#rander_common_view_modal_lg').modal({
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
  
    $("body").on("click",".document_edit_popup",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        $.ajax({
          url: base_url + "order_management/document_edit_view_ajax",
          type: "POST",
          data: {
              'id': id
          },
          async: true,
          success: function(data) {
                result = $.parseJSON(data);
                $("#OmDetailModal").css("display","none");
                $("#common_view_modal_title_lg").text(result.title);
                $('#rander_common_view_modal_html_lg').html(result.html);
                $('#rander_common_view_modal_lg').modal({
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
  
    $("body").on("click","#om_stage_wise_document_edit_confirm",function(e){
        e.preventDefault();
        var base_url=$("#base_url").val();  
        var po_pi_id=$(this).attr('data-pi_id');
        
        var missing_name='';  
        jQuery('.required_edit').each(function() {
            var currentElement = $(this);
            var value = currentElement.val(); 
            if(value==''){
                missing_name +=currentElement.attr('data-label')+' required.<br>';
                
            }
        });
        if(missing_name){
            swal({   
                title: "Oops!",  
                text: missing_name,
                html: true,
                type:'error' 
            });                            
            return false;
        }
       
        var str_tmp='';
        jQuery('.om_custom_form_field_edit').each(function() {
            
            var currentElement = $(this);
            var type=currentElement.prop("type");
            
            if(type=='text' || type=='textarea' || type=='file'){
                var value = currentElement.val(); 
                var id=currentElement.attr('data-id'); 
                var name=currentElement.attr('name'); 
                str_tmp +=id+'~'+value+'~'+name+'!***!';
            }
            else{
                if(type=='radio'){
                    if(currentElement.is(':checked')) {
                    
                        var value = currentElement.val(); 
                        var id=currentElement.attr('data-id'); 
                        var name=currentElement.attr('name');
                        str_tmp +=id+'~'+value+'~'+name+'!***!';
                    }
                } 
                else if(type=='checkbox'){
                    // if(currentElement.is(':checked')) {
                    
                    //     var value = currentElement.val(); 
                    //     var id=currentElement.attr('data-id'); 
                    //     str_tmp +=id+'~'+value+'!***!';
                    // }
                }                
                else if(type=='select-one'){
                    if(currentElement.val()) {
                    
                        var value = currentElement.val(); 
                        var id=currentElement.attr('data-id');
                        var name=currentElement.attr('name'); 
                        str_tmp +=id+'~'+value+'~'+name+'!***!';
                        
                    }
                }
            }            
        });

        var name_arr=[];
        jQuery('.om_custom_form_field_edit').each(function() {            
            var currentElement = $(this);
            var type=currentElement.prop("type");            
            var name_tmp='';
            if(type=='checkbox'){
                var name=currentElement.attr('name');
                name_arr.push(name);                      
            }             
        });
        var name_unique = name_arr.filter(function(itm, i, a) {
            return i == a.indexOf(itm);
        });        
        $.each(name_unique, function( index, value ) {
            var yourArray=[];
            var yourArray2=[];    
            var flag=0;        
            $("input:checkbox[name="+value+"]:checked").each(function(){
                yourArray.push($(this).val());
                yourArray2.push($(this).attr('data-id'));
                flag=1;
                
            });

            if(flag==1){
                var id_unique = yourArray2.filter(function(itm, i, a) {
                    return i == a.indexOf(itm);
                });
                str_tmp +=id_unique+'~'+yourArray.join("^")+'~'+value+'!***!';
            }
        });
        // alert(str_tmp);return false;
        $('form#omDocEditFrm').append('<input type="hidden" name="om_custom_form_field_edit" value="'+str_tmp+'" />');
        $.ajax({
                url: base_url + "order_management/om_stage_wise_doc_submit_edit",
                data: new FormData($('#omDocEditFrm')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,                   
                beforeSend: function( xhr ) { 
                    
                },
                complete: function(){
                    
                },
                success: function(data){
                    result = $.parseJSON(data);
                    // alert(result.msg)
                    if(result.status=='success'){
  
                        swal({
                                title: 'Success!',
                                text: 'Record successfully updated',
                                type: 'success',
                                showCancelButton: false
                            }, function() {
                                fn_lead_order_history(po_pi_id);
                                $("#rander_common_view_modal_lg").modal("hide");
                        });
  
                        
                    }
                    
                },                    
        });
    });
  
    $("body").on("click",".document_delete",function(e){
        var base_url=$("#base_url").val();
        var id=$(this).attr('data-id');
        
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover the document!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
  
                $.ajax({
                    url: base_url + "order_management/document_delete_ajax",
                    type: "POST",
                    data: {
                        'id': id
                    },
                    async: true,
                    success: function(data) {
                            result = $.parseJSON(data);
                            if(result.status=='success'){
                                swal({
                                        title: 'Success!',
                                        text: 'Successfully deleted',
                                        type: 'success',
                                        showCancelButton: false
                                    }, function() {
                                        var piid=$("#proforma_invoice_id").val();
                                        fn_lead_order_history(piid);
                                        fn_rander_document_list(piid);
                                });
                            }
                        
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
      // =======================================================================================
    // ====================================================================================
    // ====================================================================================
     
    $("body").on("click",".get_om_detail",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var lowp=ThisObj.attr("data-lowp");
    var pfi=ThisObj.attr("data-pfi");
    var stage_id=ThisObj.attr("data-stageid");
    var po_pi_wise_stage_tag_id=ThisObj.attr("data-po_pi_wise_stage_tag_id");
    $.ajax({
        url: base_url + "order_management/om_detail_view_rander_ajax",
        type: "POST",
        data: {
            'lowp': lowp,
            'pfi': pfi,
            'stage_id': stage_id,
            'po_pi_wise_stage_tag_id': po_pi_wise_stage_tag_id
        },
        async: true,
        success: function(data) {
                result = $.parseJSON(data); 
                $('#OmDetailModalBody').html(result.html);
                $('#OmDetailModal').modal({
                        backdrop: 'static',
                        keyboard: false
                });

                // $("#common_view_modal_title_lg").text("Assign Form");
                // $('#rander_common_view_modal_html_lg').html(result.html);
                // $('#rander_common_view_modal_lg').modal({
                //     backdrop: 'static',
                //     keyboard: false
                // });
            
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
    // ====================================================================================
    // ====================================================================================

  $("body").on("click","#om_stage_add_submit",function(e){

    var base_url=$("#base_url").val();
    var om_stage_name=$("#om_stage_name").val();
    var om_stage_position=$("#om_stage_position").find("option:selected").val();
    var om_stage_id_as_per_position=$("#om_stage_id").find("option:selected").val();
    var data='om_stage_name='+om_stage_name+'&om_stage_position='+om_stage_position+'&om_stage_id_as_per_position='+om_stage_id_as_per_position;
    

    if (om_stage_name=='') 
    {
        swal("Oops!", "Name should not be null",'error');        
        return false;
    } 

    if (om_stage_position=='') 
    {
        swal("Oops!", "Stage add position should not be null",'error');        
        return false;
    } 

    if (om_stage_id_as_per_position=='') 
    {
        swal("Oops!", "Stage as per position should not be null",'error');        
        return false;
    }  
    
    $.ajax({
            url: base_url+"order_management/add_om_stage_setting",
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
                    load_om_stage_view();
                }
            },                    
    });
});


$("body").on("click",".tag_assign_user_popup",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var stage_id=ThisObj.attr("data-stage_id");       
    //   alert(stage_id); return false;
    $.ajax({
      url: base_url + "order_management/tag_assign_user_to_stage_view_rander_ajax",
      type: "POST",
      data: {
          'stage_id': stage_id
      },
      async: true,
      success: function(response) {
          $("#common_view_modal_title_md").text("Assign User");
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

$("body").on("click","#tag_assign_user_popup_confirm",function(e){
    var base_url = $("#base_url").val();        
    var stage_user_id=$("#om_stage_user_id").val();
    var stage_id=$(this).attr("data-stage_id");        
    var data = 'stage_id='+stage_id+"&stage_user_id="+stage_user_id;  
    
    if(!stage_user_id){
        swal("Oops!", "Please select user.",'error');          
        return false;
    }
    // alert(data); return false;
    $.ajax({
        url: base_url+"order_management/tag_assign_user_to_stage_update",
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
                load_om_stage_wise_user_assign_view(); 
                
            }
            
        },
        complete: function(){
        //$("#preloader").css('display','none');
        },
    });

});

$("body").on("click",".untag_assign_user_popup",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var stage_id=ThisObj.attr("data-stage_id");       
    //   alert(stage_id); return false;
    $.ajax({
      url: base_url + "order_management/untag_assign_user_to_stage_view_rander_ajax",
      type: "POST",
      data: {
          'stage_id': stage_id
      },
      async: true,
      success: function(response) {
          $("#common_view_modal_title_md").text("Assign User");
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

$("body").on("click",".untag_assign_user_popup_confirm",function(e){
    
    var base_url = $("#base_url").val();        
    var user_id=$(this).attr("data-user_id");
    var stage_id=$(this).attr("data-stage_id");        
    var data = 'stage_id='+stage_id+"&user_id="+user_id;  
    
    $.ajax({
        url: base_url+"order_management/untag_assign_user_to_stage_update",
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
                // $("#rander_common_view_modal_md").modal('hide');
                load_om_stage_wise_user_assign_view();                    
                $("#div_"+user_id).remove();
                if($("#un_tbody").find('tr').length==0){
                    $("#un_tbody").html('<tr><td colspan="2" class="align-middle" class="text-center">No Record Found!</td></tr>');
                }
                
            }
            
        },
        complete: function(){
        //$("#preloader").css('display','none');
        },
    });

});
  $("body").on("click","#om_stage_form_add_submit",function(e){
    var base_url=$("#base_url").val();
    var om_form_name=$("#om_form_name").val();        
    var data='om_form_name='+om_form_name;       

    if (om_form_name=='') 
    {
        swal("Oops!", "Name should not be null",'error');        
        return false;
    } 

    
    $.ajax({
            url: base_url+"order_management/add_om_stage_form_setting",
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
                    load_om_stage_form_view();
                }
            },                    
    });
});

$("body").on("click",".om_stage_form_edit",function(e){
    
    var id = $(this).attr('data-id');        
    $("#form_output_div_"+id).hide();
    $("#form_input_div_inner_"+id).show();
});

$("body").on("click",".om_stage_form_edit_submit",function(e){
    var base_url=$("#base_url").val();
    var id=$(this).attr('data-id');
    var form_name=$("#stage_form_name_"+id).val();
    var data='edit_id='+id+'&form_name='+form_name;           

    if (form_name=='') 
    {
        swal("Oops!", "Name should not be null",'error');        
        return false;
    }  
    
    $.ajax({
            url: base_url+"order_management/edit_stage_form_setting",
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
                    $("#form_output_div_"+id).html(form_name);
                    $("#form_output_div_"+id).show();
                    $("#form_input_div_inner_"+id).hide();
                }
            },
            complete: function(){
            //$("#preloader").css('display','none');
            },
    });
});

$("body").on("click",".form_input_div_close",function(e){
    var id = $(this).attr('data-id');
    $("#form_output_div_"+id).show();
    $("#form_input_div_inner_"+id).hide();
});

$("body").on("click",".om_stage_form_delete",function(e){
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
                    url: base_url+"order_management/delete_stage_form",
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
                            load_om_stage_form_view();
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

    $("body").on("click",".om_stage_form_field_set_popup",function(e){        
    
    
        // var base_url=$("#base_url").val(); 
        var ThisObj=$(this);
        var id=ThisObj.attr('data-id');     
        //   alert(stage_id); return false;
        load_om_stage_form_field_set_popup_view(id);
        // $.ajax({
        //   url: base_url + "order_management/stage_form_field_set_popup_view_rander_ajax",
        //   type: "POST",
        //   data: {
        //       'id': id
        //   },
        //   async: true,
        //   dataType: "html", 
        //   success: function(data) {
        //     result = $.parseJSON(data); 
        //     $("#common_view_modal_title_lg").text(result.popup_title);
        //     $('#rander_common_view_modal_html_lg').html(result.html);
        //     $('#rander_common_view_modal_lg').modal({
        //         backdrop: 'static',
        //         keyboard: false
        //     });
            
        //   },
        //   error: function() {
        //       swal({
        //               title: 'Something went wrong there!',
        //               text: '',
        //               type: 'danger',
        //               showCancelButton: false
        //           }, function() {

        //       });
        //   }
        // });   
        
    });

    $("body").on("click","#om_stage_form_fields_add_submit",function(e){ 
        var base_url=$("#base_url").val();
        var form_id=$("#form_id").val();        
        $.ajax({
                url: base_url + "order_management/add_om_stage_form_fields_setting",
                data: new FormData($('#om_form_fields_set_frm')[0]),
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
                complete: function(){
                    $('#rander_common_view_modal_lg .modal-body').removeClass('logo-loader');
                },
                success: function(data){
                    result = $.parseJSON(data);
                    if(result.status=='success'){
                        load_om_stage_form_field_set_popup_view(form_id);
                    }
                    else{
                        swal("Oops!", result.msg, "error");
                    }

                },                    
        });
    });
    $("body").on("click",".om_stage_form_fields_delete",function(e){
        var id = $(this).attr('data-id');
        var form_id=$("#form_id").val();        

        if(id!='' && form_id!='')
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
                        url: base_url+"order_management/delete_stage_form_fields",
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
                                load_om_stage_form_field_set_popup_view(form_id);
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
    $("body").on("click",".om_stage_form_fields_edit_popup",function(e){
        var id = $(this).attr('data-id');
        var form_id=$("#form_id").val();        
        
        if(id!='' && form_id!='')
        {
            var base_url=$("#base_url").val();
            var data = 'id='+id+"&form_id="+form_id;
            
            $.ajax({
                    url: base_url+"order_management/edit_stage_form_fields_popup_ajax",
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
                        $("#rander_common_view_modal_lg").css("display","none");
                        $("#OmFormFieldsEditModal_title").text(result.popup_title);
                        $('#OmFormFieldsEditModal_html').html(result.html);
                        $('#OmFormFieldsEditModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });                       
                        
                    },
            });            
            
        }
        else
        { 
            swal("Oops!", "Check the record to edit.");            
        }
    });

    $('#OmFormFieldsEditModal').on('hide.bs.modal', function (e) {
        if ($('#rander_common_view_modal_lg').hasClass('in')) {
            $("#rander_common_view_modal_lg").css("display","block");            
        }
    })

    $("body").on("click","#om_stage_form_fields_edit_submit",function(e){ 
        var base_url=$("#base_url").val();
        var form_id=$("#form_id").val();        
        $.ajax({
                url: base_url + "order_management/edit_om_stage_form_fields_setting",
                data: new FormData($('#om_form_fields_edit_frm')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,                   
                beforeSend: function( xhr ) { 
                    $('#OmFormFieldsEditModal .modal-body').addClass('logo-loader');
                },
                complete: function(){
                    $('#OmFormFieldsEditModal .modal-body').removeClass('logo-loader');
                },
                success: function(data){
                    result = $.parseJSON(data);
                    if(result.status=='success'){

                        swal({
                            title: 'Success',
                            text: 'The field successfully updated',
                            type: 'success',
                            showCancelButton: false
                        }, function() {
                            $('#OmFormFieldsEditModal').modal('hide'); 
                            load_om_stage_form_field_set_popup_view(form_id);
                        });


                        
                    }
                    else{
                        swal("Oops!", result.msg, "error");
                    }

                },                    
        });
    });


    $("body").on("click",".tag_assign_form_popup",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var stage_id=ThisObj.attr("data-stage_id");       
    //   alert(stage_id); return false;
    $.ajax({
      url: base_url + "order_management/tag_assign_form_to_stage_view_rander_ajax",
      type: "POST",
      data: {
          'stage_id': stage_id
      },
      async: true,
      success: function(response) {
          $("#common_view_modal_title_md").text("Assign Form");
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
  $("body").on("click","#tag_assign_form_popup_confirm",function(e){
    var base_url = $("#base_url").val();        
    var stage_form_id=$("#om_stage_form_id").val();
    var stage_id=$(this).attr("data-stage_id");        
    var data = 'stage_id='+stage_id+"&stage_form_id="+stage_form_id;  
    
    if(!stage_form_id){
        swal("Oops!", "Please select user.",'error');          
        return false;
    }
    // alert(data); return false;
    $.ajax({
        url: base_url+"order_management/tag_assign_form_to_stage_update",
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
                load_om_stage_wise_form_assign_view(); 
                
            }
            
        },
        complete: function(){
        //$("#preloader").css('display','none');
        },
    });

    

  });

  $("body").on("click",".untag_assign_form_popup",function(e){
    var base_url=$("#base_url").val(); 
    var ThisObj=$(this);
    var stage_id=ThisObj.attr("data-stage_id");       
      //alert(stage_id);// return false;
    $.ajax({
      url: base_url + "order_management/untag_assign_form_to_stage_view_rander_ajax",
      type: "POST",
      data: {
          'stage_id': stage_id
      },
      async: true,
      success: function(response) {
          $("#common_view_modal_title_md").text("Assign Form");
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

$("body").on("click",".untag_assign_form_popup_confirm",function(e){
    
    var base_url = $("#base_url").val();        
    var form_id=$(this).attr("data-form_id");
    var stage_id=$(this).attr("data-stage_id");        
    var data = 'stage_id='+stage_id+"&form_id="+form_id;  
    
    $.ajax({
        url: base_url+"order_management/untag_assign_form_to_stage_update",
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
                // $("#rander_common_view_modal_md").modal('hide');
                load_om_stage_wise_form_assign_view();                    
                $("#div_form_"+form_id).remove();
                if($("#un_tbody_form").find('tr').length==0){
                    $("#un_tbody_form").html('<tr><td colspan="2" class="align-middle" class="text-center">No Record Found!</td></tr>');
                }
                
            }
            
        },
        complete: function(){
        //$("#preloader").css('display','none');
        },
    });

    

});

// ---------------------------------------------
    // ---------------------------------------------
    $("body").on("click",".om_stage_edit",function(e){
      var id = $(this).attr('data-id');
      $("#output_div_"+id).hide();
      $("#input_div_inner_"+id).show();
  });
  $("body").on("click",".input_div_close",function(e){
  var id = $(this).attr('data-id');
  $("#output_div_"+id).show();
  $("#input_div_inner_"+id).hide();
  });

  $("body").on("click",".om_stage_edit_submit",function(e){
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
              url: base_url+"order_management/edit_stage_setting",
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

  $("body").on("click",".om_stage_delete",function(e){
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
                      url: base_url+"order_management/delete_stage",
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
                              load_om_stage_view();
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

  // ---------------------------------------------
  // ---------------------------------------------

    $("body").on("click",".change_priority",function(e){
        var base_url=$("#base_url").val();
        var po_pi_wise_stage_tag_id=$(this).attr("data-po_pi_wise_stage_tag_id");
        var data = 'po_pi_wise_stage_tag_id='+po_pi_wise_stage_tag_id;
        $.ajax({
                url: base_url+"order_management/change_pi_priority_ajax",
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
                        load_order();
                    }
                    
                    
                },
        });
    });

    // ---------------
    // customer
    $("body").on("click",".set_call_schedule_from_app",function(e){
        var lid=$(this).attr("data-leadid");
        var base_url=$("#base_url").val();
        var mobile=$(this).attr('data-mobile');
        var contact_person=$(this).attr('data-contactperson');
    
        // alert(data); return false;
        swal({
              title: "",
              text: "Do you want to call to "+contact_person+" ("+mobile+")?",
              type: "warning",
              showCancelButton: true,
              cancelButtonClass: 'btn-warning',
              cancelButtonText: "No, cancel it!",
              confirmButtonClass: 'btn-warning',
              confirmButtonText: "Yes, do it!",
              closeOnConfirm: true
            }, function () { 
            var data="lid="+lid;
            $.ajax({
            url: base_url+"lead/set_call_schedule_from_app_ajax",
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
                // console.log(result.msg);
                if(result.status=='success')
                {
                      swal({
                        title: "Success!",
                        text: "You can call the customer from your app",
                        type: "success",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                      });
                }
                else
                {
                    swal({
                        title: "Oops!",
                        text: "Something went wrong there",
                        type: "danger",
                        confirmButtonText: "ok",
                        allowOutsideClick: "false"
                    });
                }
            }
        });
          
    });
    });
    $("body").on("click",".set_c2c",function(e){
        var base_url=$("#base_url").val();
        var lid=$(this).attr("data-leadid");    
        var cust_mobile=$(this).attr('data-custmobile');
        var cust_id=$(this).attr('data-cusid');
        var contact_person=$(this).attr('data-contactperson');
        var user_mobile=$(this).attr('data-usermobile');
        var user_id=$(this).attr('data-userid');
        var data="lid="+lid+"&cust_mobile="+cust_mobile+"&cust_id="+cust_id+"&user_mobile="+user_mobile+"&user_id="+user_id+"&contact_person="+contact_person;
        // alert(data); return false;
        swal({
          title: "",
          text: "Do you want to call to "+contact_person+" ("+cust_mobile+")?",
          type: "warning",
          showCancelButton: true,
          cancelButtonClass: 'btn-warning',
          cancelButtonText: "No, cancel it!",
          confirmButtonClass: 'btn-warning',
          confirmButtonText: "Yes, do it!",
          closeOnConfirm: true
        }, function () {
          $.ajax({
              url: base_url+"lead/set_c2c_using_api_ajax",
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
                      // alert(result.api_url);
                      // window.open(result.api_url,'_blank');
                      let params = 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=800';
                      popup = window.open(result.api_url, "c2c_popup_box", params);
                      popup.focus();
                     
                    }
              });
        });
    });
    $('#WebWhatsappModal').on('hide.bs.modal', function (e) {
        if ($('#OmDetailModal').hasClass('in')) {
            $("#OmDetailModal").css("display","block");            
        }
    });
    // -----------------------------------------------
    // WEB WHATSAPP MSG

   

    $(document).on("click","#whatsapp_msg",function(event) {
        event.preventDefault();
        $('.what-templete').slideToggle();
    });
    $(document).on("click","#add_msg",function(event) {
        event.preventDefault();
        // $('.add_txt').slideToggle();
        // $(this).prop('disabled', true);
        $('.add_txt').show();
        $(this).hide();
    });
    $(document).on("click",".what-templete ul li input:radio",function(event) {
        var base_url = $("#base_url").val();     
        var getval = $(this).parent().parent().find('.use_m').attr("data-text");
        var id = $(this).parent().parent().find('.use_m').attr("data-id");
        var lead_id=$("#lead_id").val();
        //use_m
        // alert(id);
        var data='id='+id+'&lead_id='+lead_id;
        // alert(data);
        $.ajax({
                url: base_url + "lead/rander_web_whatsapp_template_html",
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
                    // alert(result.html)           
                    $('#whatsapp_txt').val(result.html);
                    $('.what-templete').slideUp();
                    $(this).prop('checked', false);            
                }
            });
    
    });
    $(document).on("click","#save_msg",function(event) {
        event.preventDefault();
        // var getval = $('.add_txt .msg-input').val();
        var base_url = $("#base_url").val(); 
        var t_title=$("#t_title").val();
        var t_desc=$("#t_desc").val();
        var data='t_title='+t_title+'&t_desc='+t_desc; 
        if(t_title=='')
        {
            swal("Oops!", 'Template title is required.', "error");
            return false;
        }
        if(t_desc=='')
        {
            swal("Oops!", 'Template description is required.', "error");
            return false;
        }
        $.ajax({
                url: base_url + "lead/save_web_whatsapp_template",
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
                    if(result.status=='success')
                    {
                    var t_added_id=result.id;
                    swal("Success", 'A new template added.', "success");
                    var hh = `<li>
                                <label class="check-box-sec">
                                <input type="radio" value="" class="" name="pre_templete">
                                    <span class="checkmark"></span>
                                </label><span class="use_m" data-text="`+t_desc+`" data-id="`+t_added_id+`">`
                                +t_title+
                            `</span></li>`;
                    $('.what-templete ul').append(hh);
                    $(".what-scroller").mCustomScrollbar("scrollTo","bottom");
                    $('.add_txt').hide();
                    $('#add_msg').show();
                    $('#t_title').val('');
                    $('#t_desc').val('');
                    }               
                }
            });
        //https://web.whatsapp.com/send?phone=+919831767490&text=
        
        
    });
    $("body").on("click","#close_msg",function(e){
        event.preventDefault();
        $('.add_txt').hide();
        $('#add_msg').show();
        $('#t_title').val('');
        $('#t_desc').val('');
    });
    $("body").on("click",".delete_template",function(e){
        event.preventDefault();
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');
        var data='id='+id; 
        $.ajax({
                url: base_url + "lead/delete_web_whatsapp_template",
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
                    if(result.status=='success')
                    {
                        swal("Success!", 'The template successfully deleted', "success"); 
                        $("#li_"+id).html('');
                    }               
                }
            });
    });
    $("body").on("click",".web_whatsapp_popup",function(e){
        $("#OmDetailModal").css("display","none");
        var base_url = $("#base_url").val(); 
        var lead_id=$(this).attr('data-leadid');
        var cust_id=$(this).attr('data-custid');
        var data='lead_id='+lead_id+'&cust_id='+cust_id; 

        $.ajax({
            url: base_url + "lead/rander_web_whatsapp_popup",
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
                //alert(result.msg);
                $("#WebWhatsappModal").html(result.html);
                $('#WebWhatsappModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });               
            }
        });
    
    });
    $("body").on("click",".append_product_quote_whatsapp",function(e){
        // add_product_quote_view('whatsapp');
        $("#search_product_lead_id").val($("#lead_id").val());
        $("#is_mail_or_whatsapp").val('whatsapp');
        $("#search_add_btn_class").val('add_searched_product_confirm_whatsapp');
        $('#WebWhatsappModal').css("display","none");
        setTimeout(function(){ 
          search_product_view();
        }, 600);
    });
    $("body").on("click","#search_product_checked_proceed_confirm",function(e){
        e.preventDefault();
        var base_url = $("#base_url").val();
        // var product_ids = $.map($('input[name="select_product[]"]:checked'), function(c) {
        //       return c.value;
        // });
        var product_ids=$("#selected_p_ids").val();
        var lead_id=$("#search_product_lead_id").val();
        
        // alert(product_ids+'/'+lead_id+'/'+product_ids.length); return false;
        if (product_ids)
        {
            var base_url = $("#base_url").val(); 
            var is_mail_or_whatsapp =$("#is_mail_or_whatsapp").val();  
            $.ajax({ 
                url: base_url + "lead/add_product_quote_ajax",
                type: "POST",
                data: {"product_ids":product_ids,"lead_id":lead_id,"is_mail_or_whatsapp":is_mail_or_whatsapp},
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
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#rander_search_product_view_modal").modal('hide');
                    var is_mail_or_whatsapp=$("#is_mail_or_whatsapp").val();
                    
                    if(is_mail_or_whatsapp=='mail')
                    { 
                      $(".buying-requirements").html(result.quote_text);
                      $('#ReplyPopupModal').css("display","block");
                      // $("#ReplyPopupModal").modal('show');
                      // setTimeout(function(){ 
                      //   $("#ReplyPopupModal").modal('show');
                      // }, 600);                    
                    }
                    else if(is_mail_or_whatsapp=='whatsapp')
                    {
                        $("#whatsapp_txt").html(result.quote_text);
                        $('#WebWhatsappModal').css("display","block");
                    }
                    $("#search_add_btn_class").val('');
                    // $(append_to).html(result.quote_text);
                    // $('#rander_add_product_quote_view_modal').modal('hide');
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
        else
        {
          swal({
                  title: "Warning",
                  text: 'Please seleact a product.',
                  type: "warning",
                  confirmButtonText: "OK",
              });
        }
        // var product_ids=$("#product_quote").val();
        // if(product_ids=='' || product_ids==null)
        // {
        //   $("#product_quote_error").html('Please select Product / Service');
        //   return false;
        // }
        // get_product_quote_text(product_ids,lead_id,'.buying-requirements');
      });



    $("body").on("click","#whatsapp_send_confirm",function(e){
        event.preventDefault();
        
        var base_url = $("#base_url").val(); 
        var whatsapp_txt=$("#whatsapp_txt").val();
        var is_mobile=$("#is_mobile").val();
        if(whatsapp_txt=='')
        {
        swal("Oops!", 'Message should not be blank.', "error"); 
        return false; 
        }
        $.ajax({
            url: base_url+"lead/web_whatsapp_sent_ajax",
            data: new FormData($('#frmWebWhatsappSend')[0]),
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
            success: function(data){
            result = $.parseJSON(data);
            if(result.status=='success')
            {
                //$('#WebWhatsappModal').modal('hide');
                $("#whatsapp_content_div").hide();
                $("#whatsapp_sent_confirm_div").show();                           
                var recipient_mobile=result.recipient_mobile;
                // window.open('https://web.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt, '_blank');
                //var web_whatsapp_url='https://wa.me/'+recipient_mobile+'?text='+whatsapp_txt;
                // var web_whatsapp_url='https://api.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
                
                if(is_mobile=='Y')
                {
                var web_whatsapp_url='https://api.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
                }
                else
                {
                var web_whatsapp_url='https://web.whatsapp.com/send?phone='+recipient_mobile+'&text='+whatsapp_txt;
                }
                let params = 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=4000,height=4000';
                popup = window.open(web_whatsapp_url, "WhatsAppPopup", params);
                popup.focus();
            }   
            }
        });
    });

    $("body").on("click","#web_whatsapp_sent_submit",function(e){
    event.preventDefault();
    var base_url = $("#base_url").val(); 
    var is_message_sent = $("input:radio[name=is_message_sent]:checked").val()
    
    if(is_message_sent=='Y')
    {
        $("#is_history_update").val("Y");  
        $("#mobile_whatsapp_status").val("1"); 
    }
    else if(is_message_sent=='N')
    {
        $("#is_history_update").val("N");  
        $("#mobile_whatsapp_status").val("0"); 
    }
    else if(is_message_sent=='NOT_VALIDE')
    {
        $("#is_history_update").val("Y");  
        $("#mobile_whatsapp_status").val("2"); 
    }

    $.ajax({
            url: base_url+"lead/web_whatsapp_sent_ajax",
            data: new FormData($('#frmWebWhatsappSend')[0]),
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
            success: function(data){
            result = $.parseJSON(data);
            if(result.status=='success')
            {
                swal({
                    title: '',
                    text: 'Lead updated successfully',
                    type: 'success',
                    showCancelButton: false
                }, function() {
                    $('#WebWhatsappModal').modal('hide');
                    load();
                });
                                        
            }   
            }
        });

    });

    

    // WEB WHATSAPP MSG
    // -----------------------------------------------
    // customer
    // --------------
    // ===================================================
    // CUSTOMER REPLY
    $('#ReplyPopupModal').on('hide.bs.modal', function (e) {
        if ($('#OmDetailModal').hasClass('in')) {
            $("#OmDetailModal").css("display","block");            
        }
    });
    $("body").on("click",".open_cust_reply_box",function(e){  
        var lead_id = $(this).attr('data-leadid');
        var customer_id = $(this).attr('data-custid');
        $("#OmDetailModal").css("display","none");
        fn_open_cust_reply_box_view(lead_id);
     });
     $("body").on("click","#cust_reply_submit_confirm",function(e){   
        var ThisObj=$(this);
        var base_URL = $("#base_url").val();  
        var reply_mail_to=$("#reply_mail_to").val();  
        var reply_mail_subject=$("#reply_mail_subject").val();    
        var box = $('.buying-requirements');
        var email_body = box.html();       
        $('#reply_email_body').val(email_body);      
 
        if(reply_mail_to=='')
        {
            swal("Oops", "Please specify at least one recipient.", "error");
            return false;
        }
 
        if(reply_mail_subject=='')
        {
            swal("Oops", "Please enter subject.", "error");         
            return false;
        }
 
        if(email_body=='')
        {
            swal("Oops", "Please enter mail body", "error");
            return false;
        }
         //alert(reply_mail_to+' / '+reply_mail_to_cc+' / '+email_body); return false;
        $.ajax({                
            url: base_URL+"lead/cust_reply_sent_ajax",
            data: new FormData($('#cust_reply_mail_frm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function( xhr ) {
                 $('#ReplyPopupModal .modal-body').addClass('logo-loader');
           },
           complete: function (){
               $('#ReplyPopupModal .modal-body').removeClass('logo-loader');
           },            
            success:function(res){ 
               result = $.parseJSON(res);          
               // alert(result.msg);
               if(result.status=='success')
               {
                 swal({
                     title: "Success!",
                     text: "Mail to customer successfully sent",
                     type: "success",
                     showCancelButton: false,
                     confirmButtonClass: 'btn-warning',
                     confirmButtonText: "Ok",
                     closeOnConfirm: true
                 }, function () {
                    $('#ReplyPopupModal').html('');
                    $('#ReplyPopupModal').modal('hide');
                    $("#OmDetailModal").css("display","block");
                 });                    
               }
               else
               {
                    swal('Fail!', 'There have some system error! Please try again later.', 'error');
               }
                                  
           },         
           error: function(response) {}
       }); 
     });

    
    $("body").on("click",".append_product_quote_mail",function(e){
        // add_product_quote_view('mail');
        $("#search_product_lead_id").val($("#lead_id").val());
        $("#is_mail_or_whatsapp").val('mail');
        $("#search_add_btn_class").val('add_searched_product_confirm_mail');
        $('#ReplyPopupModal').css("display","none");
        // if($('#ReplyPopupModal').hasClass('in'))
        // {
        //     $('#ReplyPopupModal').modal('hide');
        // }
        // else
        // {
            
        // }
        // $("#ReplyPopupModal").modal('hide');
        setTimeout(function(){ 
          search_product_view();
        }, 600);   
    });
     // CUSTOMER REPLY
     // ===================================================

    $("body").on("click",".get_alert",function(e){
        var txt=$(this).attr('data-text');
        swal("Oops!", txt, "error");
      }); 

    $("body").on("click",".set_history_from_link",function(e){
        e.preventDefault();
       
        var objThis=$(this);
        var base_url = $("#base_url").val(); 
        var history_text=$(this).attr('data-history_text');
        var href=$(this).attr('href');
        var pfi=$(this).attr('data-pfi');              
        var data='pfi='+pfi+'&history_text='+history_text; 
        $.ajax({
            url: base_url + "order_management/set_history_ajax",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html", 
            beforeSend: function( xhr ) {
                // addLoader('.table-responsive');
            },
            complete: function (){
                // removeLoader();
            },           
            success: function(data){
                result = $.parseJSON(data);
                if(result.status=='success'){
                    fn_lead_order_history(pfi);
                    if(href){
                        window.location.href=href;
                    }
                    
                }
                
                           
            }
        });
    });
});
function openDiv(evt, divName) 
{ 
  $("body, html").animate({ scrollTop: 200 }, "slow");
  var i, tabcontent, tablinks;
  
  tabcontent = document.getElementsByClassName("tabcontentDiv");
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
    load_order();
   
  } 
  if(divName=='tab_2')
  { 
    load_settings();
  }  
  if(divName=='tab_3')
  { 
    
  }   
}
function load_settings()
{
    var base_URL=$("#base_url").val();    
    var data = "";
     //alert(data);// return false;
    $.ajax({
        url: base_URL+"order_management/rander_settings_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
        },
        success:function(res){ 
            result = $.parseJSON(res);  
            $("#settings_tcontent").html(result.html);
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    });
}

function load_order()
{
    var base_URL=$("#base_url").val();    
    var data = "";
     //alert(data);// return false;
    $.ajax({
        url: base_URL+"order_management/rander_orders_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {                
            //addLoader('.table-responsive');
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
        success:function(res){ 
            result = $.parseJSON(res);  
            $("#order_tcontent").html(result.html);
            updateAfterOrderLoad();
        },
        complete: function(){
            //removeLoader();
            $.unblockUI();
        },
        error: function(response) {
            //alert('Error'+response.table);
        }
    });
}

function SopenDiv(evt, divName) 
{ 
  $("body, html").animate({ scrollTop: 200 }, "slow");
  var i, tabcontent, tablinks;
  
  tabcontent = document.getElementsByClassName("StabcontentDiv");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  
  tablinks = document.getElementsByClassName("Stablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  
  document.getElementById(divName).style.display = "block";
  evt.currentTarget.className += " active";
  
  //////////
  if(divName=='Stab_1')
  { 
    load_om_stage_view();   
  } 
  if(divName=='Stab_2')
  { 
    load_om_stage_wise_user_assign_view();
  }  
  if(divName=='Stab_3')
  { 
    load_om_stage_form_view();
  }
  if(divName=='Stab_4')
  { 
    load_om_stage_wise_form_assign_view();
  } 
    
}
// ---------------------------------------------
// ---------------------------------------------

function load_om_stage_view()
{       
    var base_URL=$("#base_url").val();
    var data = "";        
    $.ajax({
        url: base_URL+"order_management/rander_om_stage_list_ajax/",
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
            $("#om_stage_tcontent").html(result.table);
            $( "#lead_stage_sortable" ).sortable({
                axis: 'y',
                update: function (event, ui) {                  
                    var new_sort = $("#lead_stage_sortable").sortable("serialize", {key:'new_sort[]'});
                    var base_url=$("#base_url").val();
                    var data=new_sort;
                    $.ajax({
                        url: base_url+"order_management/resort_om_stage",
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
                                load_om_stage_view();
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
function load_om_stage_wise_user_assign_view()
{       
    var base_URL=$("#base_url").val();
    var data = "";        
    $.ajax({
        url: base_URL+"order_management/rander_om_stage_wise_user_assign_ajax/",
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
            $("#om_stage_wise_assign_user_tcontent").html(result.table);            
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    })
}
// ---------------------------------------------
// ---------------------------------------------

// ---------------------------------------------
// ---------------------------------------------
function load_om_stage_form_view()
{       
    var base_URL=$("#base_url").val();
    var data = "";        
    $.ajax({
        url: base_URL+"order_management/rander_om_stage_form_list_ajax/",
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
            $("#om_stage_form_tcontent").html(result.table);            
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    })
}
// ---------------------------------------------
// ---------------------------------------------

// ---------------------------------------------
// ---------------------------------------------
function load_om_stage_form_field_set_popup_view(id)
{       
    var base_url=$("#base_url").val(); 
    $.ajax({
        url: base_url + "order_management/stage_form_field_set_popup_view_rander_ajax",
        type: "POST",
        data: {
            'id': id
        },
        async: true,
        dataType: "html", 
        success: function(data) {
        result = $.parseJSON(data); 
        $("#common_view_modal_title_lg").text(result.popup_title);
        $('#rander_common_view_modal_html_lg').html(result.html);
        $( "#stage_form_fields_sortable" ).sortable({
            axis: 'y',
            update: function (event, ui) {                  
                var new_sort = $("#stage_form_fields_sortable").sortable("serialize", {key:'new_sort[]'});
                var base_url=$("#base_url").val();
                var data=new_sort;
                
                $.ajax({
                    url: base_url+"order_management/resort_om_stage_form_fields",
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
                            load_om_stage_form_field_set_popup_view(id);
                        }
                    },
                });
            }
        });
        $('#rander_common_view_modal_lg').modal({
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
// ---------------------------------------------
// ---------------------------------------------

// ---------------------------------------------
// ---------------------------------------------
function load_om_stage_wise_form_assign_view()
{       
    var base_URL=$("#base_url").val();
    var data = "";        
    $.ajax({
        url: base_URL+"order_management/rander_om_stage_wise_form_assign_ajax/",
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
            $("#om_stage_wise_assign_form_tcontent").html(result.table);            
        },
        complete: function(){
        //removeLoader();
        },
        error: function(response) {
        //alert('Error'+response.table);
        }
    })
}
// ---------------------------------------------
// ---------------------------------------------

function fn_update_pi_stage(pi_id,current_stage_id,prev_stage_id)
{   
    
        var base_url = $("#base_url").val(); 
        var data = 'pi_id='+pi_id+"&current_stage_id="+current_stage_id+"&prev_stage_id="+prev_stage_id; 
        
        $.ajax({
        url: base_url+"order_management/pi_stage_change_update",
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
                load_order();
                // load_om_stage_wise_user_assign_view(); 
            }
            
        },
        complete: function(){
        //$("#preloader").css('display','none');
        },
    });
}

function fn_rander_payment_view_popup(lowp)
{
    var base_url = $("#base_url").val(); 
    $.ajax({
        url: base_url + "order/rander_payment_view_popup_ajax",
        type: "POST",
        data: {
         'lowp':lowp,
         'action_type':'view'
        },
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
        success: function(data) {
            result = $.parseJSON(data);             
            $('#PoPaymentLedgerModalBody').html(result.html);
            $('#PoPaymentLedgerModal').modal({
                    backdrop: 'static',
                    keyboard: false
            });
            fn_rander_payment_ledger(lowp);
        },
        error: function() {
         
        }
    });
}
function fn_rander_payment_ledger(lowp)
{   

    var base_url = $("#base_url").val(); 
    $.ajax({
        url: base_url + "order/rander_payment_ledger_view_ajax",
        type: "POST",
        data: {
         'lowp':lowp
        },
        async: false,
        beforeSend: function( xhr ) {               
            $('#PoPaymentLedgerModal .modal-body').addClass('logo-loader');
        },
        complete: function (){ 
            $('#PoPaymentLedgerModal .modal-body').removeClass('logo-loader');
        },
        success: function(data) {
            result = $.parseJSON(data); 
            $("#payment_legder_content").html(result.html);
        },
        error: function() {
         
        }
    });
}

function fn_open_lead_history(lid)
{    
    var base_url=$("#base_url").val();
    var data="lid="+lid;
    
    $.ajax({
    url: base_url+"lead/view_lead_history_ajax",
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
      //alert(result.html);
      $("#lead_history_log_title").html(result.title);
      $("#lead_history_log_body").html(result.html);
      $('#lead_history_log_modal').modal({backdrop: 'static',keyboard: false}); 
    }
  });
}

function fn_lead_order_history(pi_id)
{    
    var base_url=$("#base_url").val();
    var data="pi_id="+pi_id;
    
    $.ajax({
    url: base_url+"order_management/order_history_rander_ajax",
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
      $("#om_history_div").html(result.html);
    
    }
  });
}