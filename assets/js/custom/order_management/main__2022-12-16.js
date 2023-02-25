$(document).ready(function(){
  document.getElementById("defaultOpen").click(); 

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
        // alert(str_tmp);return false;
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
    $.ajax({
        url: base_url + "order_management/om_detail_view_rander_ajax",
        type: "POST",
        data: {
            'lowp': lowp,
            'pfi': pfi,
            'stage_id': stage_id
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
        },
        success:function(res){ 
            result = $.parseJSON(res);  
            $("#order_tcontent").html(result.html);
        },
        complete: function(){
        //removeLoader();
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