$(document).ready(function(){
    $("#edit_customer_view_modal").on("hidden.bs.modal", function () {
        $(".lead_renewal_success_info_div").css("display","none");
    });
	//open_indiamart_popup
    var popupWindow;
    $("body").on("click","#open_indiamart_popup",function(e){
        e.preventDefault();
        popupWindow =window.open('https://seller.indiamart.com/bltxn/?pref=relevant','Indiamart Buyleads','width='+window.innerWidth+',height='+window.innerHeight+',toolbar=0,menubar=0,location=0');  
        popupWindow.focus();
        //$('#indiamartModal').modal('show')
    });
    $("body").on("click",".quotation_sent_by_whatsapp",function(e){
		var lid=$(this).attr('data-lid');
		var oppid=$(this).attr('data-oppid');
		var qid=$(this).attr('data-qid');
        var is_quoted=($(this).attr('data-is_quoted'))?$(this).attr('data-is_quoted'):'N';
		var is_mobile=$("#is_mobile").val();
		//alert(lid+'/'+oppid+'/'+qid);
		var base_URL = $("#base_url").val();
		var data="lid="+lid+"&oppid="+oppid+"&qid="+qid+"&is_quoted="+is_quoted;
        
		$.ajax({
			url: base_URL+"/lead/rander_html_for_quotation_sent_by_whatsapp_ajax",
			data: data,                        
			cache: false,
			method: 'POST',
			dataType: "html",
			beforeSend: function( xhr ) {},
			complete: function(){},
			success: function(data){
				result = $.parseJSON(data);	
				//alert(result.html)
				var whatsapp_txt=result.html;
				var recipient_mobile=result.recipient_mobile;				
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
			},
			error: function(response) {}
		});
	});

	$("body").on("click","#common_mail_send_confirm",function(e){
        e.preventDefault();
        var thisObj=$(this);
        var base_url=$("#base_url").val();
        var box = $('.buying-requirements');
        var email_body = box.html();       
        $('#reply_email_body').val(email_body);
		
        $.ajax({
                url: base_url+"app/common_mail_send_confirm_ajax",
                //data: data,
                data: new FormData($('#cust_reply_mail_frm')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function( xhr ) {
                  thisObj.attr("disabled", true);
                  $('#ReplyPopupModal .modal-body').addClass('logo-loader');
                },
                complete: function (){
                    thisObj.attr("disabled", false);
                    $('#ReplyPopupModal .modal-body').removeClass('logo-loader');
                },
                success: function(data){
                    result = $.parseJSON(data);                    
                    // $("#send_to_buyer_confirm").attr("disabled", false);
                    if(result.status == 'success')
                    {
                        swal({
                            title: 'Success',
                            text: result.msg,
                            type: 'success',
                            showCancelButton: false
                        }, function() {
                            // window.location.reload();
                            $('#ReplyPopupModal').modal('hide'); 
                        });
                    }
                    else
                    {                       
                        swal({
                            title: result.msg,
                            text: '',
                            type: 'warning',
                            showCancelButton: false
                        });
                    }
                }
            });  
    });

    $("body").on("click",".view_lead_history",function(e){
        var lid=$(this).attr("data-leadid");
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
            //   $("#dashboardDetailReport").css("display","none");
              $("#lead_history_log_title").html(result.title);
              $("#lead_history_log_body").html(result.html);
              $('#lead_history_log_modal').modal({backdrop: 'static',keyboard: false}); 
            }
          });
        
    });
});

function getLatestLeadHistory(id)
{
    var base_url = $("#base_url").val();
    // var id = this.id;
    var split_id = id.split('_');
    var lid = split_id[2];  
    var tooltipText = "";
    $.ajax({
        url: base_url+"lead/get_latest_lead_history_ajax",
        type: "POST",
        data: {
        'lid': lid,
        },			
        async: false,
        beforeSend: function( xhr ) {
        
        },
        complete: function (){
        
        },
        success: function(response){
            tooltipText = response;
        }			
    });
    return tooltipText;
}
function meeting_schedule_view_popup(lead_id,c_id,m_id='')
{   
    var base_url=$("#base_url").val();
    var data="lead_id="+lead_id+"&c_id="+c_id+"&m_id="+m_id;     
    $.ajax({
        url: base_url + "lead/meeting_schedule_view_popup_rander_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
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
        complete: function() {
            $.unblockUI();
        },
        success: function(res) {
            result = $.parseJSON(res);   
            if(m_id){
                $("#schedule_meeting_title").html('Edit Meeting');
            }  
            else{
                $("#schedule_meeting_title").html('Schedule New Meeting');
            }
            // $('#scheduleMeetingModal').modal('show');
            $("#scheduleMeetingModalBody").html(result.html);
            // $('#timepicker_end').timepicki();
            $('#scheduleMeetingModal').modal({
                backdrop: 'static',
                keyboard: false
            });  
                 
        },              
        error: function(response) {}
    });
}
function fn_rander_vendor_details(vid)
{    
    var base_URL = $("#base_url").val();
    var data="vid="+vid;
    $.ajax({
        url: base_URL+"vendor/view_vendor_detail_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data); 
          $("#vendordetailsbody").html(result.html); 
        }
   });
}

function fn_rander_company_details(cid)
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
          $("#lead_company_details_body").html(result.html); 
          $('#lead_company_details').modal({backdrop: 'static',keyboard: false}); 
        }
   });
}

function fn_rander_company_history(cid)
{    
    var base_URL = $("#base_url").val();
    var data="cid="+cid;
    $.ajax({
        url: base_URL+"customer/view_company_history_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data); 
          $("#company_history_body").html(result.html); 
        }
   });
}

function fn_rander_company_wise_lead(cid,filter)
{    
    var base_URL = $("#base_url").val();
    var data="cid="+cid+"&filter="+filter;
    $.ajax({
        url: base_URL+"customer/view_company_wise_lead_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data); 
          $("#company_wise_lead_body").html(result.html); 
        }
   });
}

function fn_rander_product_details(cid)
{    
    var base_URL = $("#base_url").val();
    var data="cid="+cid;
    $.ajax({
        url: base_URL+"product/view_product_detail_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data); 
          $("#product_details_body").html(result.html); 
        }
   });
}

function rander_option_group(parent_id,selected_id="")
{
    var base_URL = $("#base_url").val(); 	
    var data="parent_id="+parent_id+"&selected_id="+selected_id;
    //alert(data);
    $.ajax({
        url: base_URL+"product/rander_group_cat_option",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {            
        },
        success: function(data){
            result = $.parseJSON(data); 
            $("#group_id").html(result.option_rander);
            $("#search_p_group").html(result.option_rander);
            $("#search_p_group_q").html(result.option_rander);			
        }
    });
}

function rander_option_category(parent_id,selected_id="")
{
    var base_URL = $("#base_url").val(); 	
    var data="parent_id="+parent_id+"&selected_id="+selected_id;
    //alert(data);
    $.ajax({
        url: base_URL+"product/rander_group_cat_option",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {            
        },
        success: function(data){
            result = $.parseJSON(data); 
            $("#cate_id").html(result.option_rander);
            $("#search_p_category").html(result.option_rander);
            $("#search_p_category_q").html(result.option_rander);         			
        }
    });
}

function fn_rander_product_wise_vendor_list(pid)
{    
    var base_URL = $("#base_url").val();
    var data="pid="+pid;
    $.ajax({
        url: base_URL+"product/view_product_wise_vendor_list_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data); 
          $("#product_wise_vendor_list_body").html(result.html); 
        }
   });
}

function fn_rander_regret_reason() {
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "opportunity/rander_regret_reason_ajax",
        type: "POST",
        data: {},
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            //$('#prod_lead_list_update').html(response);         
            //$('#prod_lead_update').modal();
            $('#lead_regret_reason_list_body').html(result.html);
        },
        error: function() {
            //$.unblockUI();
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

function open_quotation_view(id,lead_ids)
{                
    var base_url = $("#base_url").val();
     $.ajax({
          url: base_url + "lead/rander_quotation_view_popup_ajax",
          type: "POST",
          data: {
              'id': id,
              'lead_ids':lead_ids,
          },
          async: true,
          success: function(response) {
              // $('#quotationLeadModal').modal('show')
              $('#QuotationViewModal').html(response);
              $('#QuotationViewModal').modal({
                  backdrop: 'static',
                  keyboard: false
              });
          },
          error: function() {
              
          }
    });
}

function rander_customer_edit_view(customer_id,lead_id)
{
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "customer/customer_edit_view_rander_ajax",
        type: "POST",
        data: {
            'customer_id': customer_id
        },
        async: true,
        success: function(response) {

            if(lead_id)
            {
              $("#added_lead_id").html(lead_id);
              $("#lead_info_div").css("display","block");
            }
            else
            {
              $("#added_lead_id").html('');
              $("#lead_info_div").css("display","none");
            }
            //$('#edit_customer_view_rander').addClass('force-close');
            $('#edit_customer_view_rander').html(response);
            $('#edit_customer_view_modal').modal({
                backdrop: 'static',
                keyboard: false
            }).css('overflow-y', 'auto').addClass('force-close');
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

$(document).on("click","#edit_customer_view_modal.force-close .close-edit-customer-view-modal-new",function(event) {
    event.preventDefault();
    $('#edit_customer_view_modal').removeClass('force-close');
    $('#edit_customer_view_modal').modal('hide');
});

function fn_open_cust_reply_box_view(lead_id)
{   
   // alert('To:'+to_mail+'  / From:'+from_mail);
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "lead/rander_cust_reply_box_view_popup_ajax",
       type: "POST",
       data: {
           'lead_id': lead_id
       },
       async: true,
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
       success: function(response) {           
           $('#ReplyPopupModal').html(response);
           $(".buyer-scroller").mCustomScrollbar({
             scrollButtons:{enable:true},
             theme:"rounded-dark"
             });
           //////
           $('.select2').select2();
           simpleEditer();
           //////
           $('.btn-side .item').each(function( index ) {
                var gItemw = $(this).find('.auto-txt-item').outerWidth();
                //console.log( index + ": " + gItemw );
                $(this).css({'width':gItemw});
             });
             $('.btn-side').addClass('owl-carousel owl-theme')
             $('[data-toggle="tooltip"]').tooltipster();
             $('#txt-carousel').owlCarousel({
                 margin:10,
                 loop:false,
                 autoWidth:true,
                 nav:true,
                 items:4,
                 dots:false,
                 navText: ['<i class="fa fa-chevron-circle-left" aria-hidden="true"></i>','<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>']
             })
           ////////////////////////
           $('#ReplyPopupModal').modal({
               backdrop: 'static',
               keyboard: false
           });

            //    $(".basic-editor").each(function(){
            //         tinymce.init({
            //             force_br_newlines : true,
            //             force_p_newlines : false,
            //             forced_root_block : '',
            //             menubar: false,
            //             statusbar: false,
            //             toolbar: false,
            //             setup: function(editor) {
            //                 editor.on('focusout', function(e) {                   
            //                     var updated_field_name=editor.id;
            //                     var updated_content=editor.getContent();
            //                 })
            //             }
            //         });
            //         tinymce.execCommand('mceRemoveEditor', true, this.id); 
            //         tinymce.execCommand('mceAddEditor', true, this.id); 
            //     });
       },
       error: function() {
           
       }
   });
}

function add_product_quote_view(is_mail_or_whatsapp)
{   
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "lead/add_product_quote_view_ajax",
        type: "POST",
        data: {"is_mail_or_whatsapp":is_mail_or_whatsapp},
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
        //console.log('product_tags.....')
          $('#rander_add_product_quote_view_html').html(response);
          $('#rander_add_product_quote_view_modal').modal({backdrop: 'static',keyboard: false});
          $('.sp-custom-select').select2({
            tags: false,
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

function get_product_quote_text(product_ids,lead_id,append_to)
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
            $(append_to).html(result.quote_text);
            $('#rander_add_product_quote_view_modal').modal('hide');
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

function search_product_view()
{   
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "lead/search_product_view_ajax",
        type: "POST",
        data: {},
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
            $("#search_p_name").val('');
            document.getElementById("search_p_group").selectedIndex = "0";
            document.getElementById("search_p_category").selectedIndex = "0";
            $('#rander_search_product_view_html').html(result.html);
            $('#rander_search_product_view_modal').modal({backdrop: 'static',keyboard: false});
            $('.sp-custom-select').select2({
            tags: false,
            });   
            rander_option_group(0,'');           
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
function rander_quotation_wise_photo_ajax(quotation_id) 
{    
    var base_URL=$("#base_url").val(); 
    var data="quotation_id="+quotation_id;
    //alert(data);
    $.ajax({
        url: base_URL+"opportunity/rander_quotation_wise_photo_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success:function(res){ 
            result = $.parseJSON(res);
            $("#added_quotation_photo").html(result.html);
        },
        complete: function(){},
        error: function(response) {
        //alert('Error'+response.table);
        }
    })
}

function fn_rander_my_document_for_quotation() 
{    
    var base_url=$("#base_url").val();
    var data='';
    $.ajax({
        url: base_url+"setting/rander_my_document_for_quotation_ajax",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
        beforeSend: function( xhr ) {},
        success: function(data){
          result = $.parseJSON(data);               
          $("#my_document_list_popup").html(result.html)
          $('#my_document_modal').modal({backdrop: 'static',keyboard: false});
        }
    }); 
}
function fn_quotation_hide_gst_zero(opportunity_id,quotation_id) 
{
    var base_url = $("#base_url").val();    
    // alert(opportunity_id+'/'+quotation_id); 
    // return false;
    if(opportunity_id!='' && quotation_id!='')
    {
       $.ajax({
            url: base_url + "opportunity/quotation_hide_gst_zero",
            type: "POST",
            data: {
                'opportunity_id': opportunity_id,
                'quotation_id': quotation_id,
            },
            async: true,
            success: function(data) {
                result = $.parseJSON(data);
                // $(".currency_code_div").html(c_t_code);
                // alert(result.html)
                
                $("#product_list_update_" + opportunity_id).html(result.html);
                $("#total_deal_value").text(result.total_deal_value);
                $("#total_price").text(result.total_price);
                $("#total_discount").text(result.total_discount);
                $("#total_tax").text(result.total_tax);
                $("#grand_total_round_off").text(result.grand_total_round_off);
                $("#number_to_word_final_amount").text(result.number_to_word_final_amount);
                $(".currency_code_div").html(result.currency_code);
                
                // if(result.terms_html){
                //     $("#terms_condition_outer_div").show();                        
                // }
                // else{
                //     $("#terms_condition_outer_div").hide();
                // }
                // $("#accordion").html(result.terms_html);
                $(".basic-wysiwyg-editor").each(function(){
                    tinymce.init({
                        force_br_newlines : true,
                        force_p_newlines : false,
                        forced_root_block : '',
                        menubar: false,
                        statusbar: false,
                        toolbar: false,
                        setup: function(editor) {
                            editor.on('focusout', function(e) {                   
                                var quotation_id=$("#quotation_id").val();
                                var updated_field_name=editor.id;
                                var updated_content=editor.getContent();
                                fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
                            })
                        }
                    });
                    tinymce.execCommand('mceRemoveEditor', true, this.id); 
                    tinymce.execCommand('mceAddEditor', true, this.id); 
                });

                
                if ($("input[type='checkbox'][name='is_hide_gst_in_quotation']:checked").length != 0) {
                    $(".chan-col").attr("colspan", 5);
                    $(".h-gst").addClass("hide"); 
                    $('.gst-extra').toggleClass("show"); 
                    $(".gst-extra-block").removeClass("show");             
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
    }
    else
    {
        swal('Oops','Please select a currency','error');
    }
}
function common_mail_send_modal(position,txt='',subject='',cc='',to='')
{  
   
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "app/common_mail_send_modal",
       type: "POST",
       data: {
           'position': position
       },
       async: true,
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
       success: function(response) {   

            $('#ReplyPopupModal').html(response);
            if(txt){
                $(".default-com").html(txt);
            }
            
            if(subject){
                $("#mail_subject").val(subject);
            }
            
            if(cc){                 
                var result = cc.split(',');
                for(var i=0;i<result.length;i++){                  
                    $('#cc_mail').append('<option value="'+result[i]+'" selected>'+result[i]+'</option>');
                }
            }
            
            if(to){              
                var result = to.split(',');
                for(var i=0;i<result.length;i++){                  
                    $('#to_mail').append('<option value="'+result[i]+'" selected>'+result[i]+'</option>');
                }
            }
            $(".buyer-scroller").mCustomScrollbar({
                scrollButtons:{enable:true},
                theme:"rounded-dark"
            });
                
           //////
           $('.select2').select2({tags: true});
           simpleEditer();
           //////
           $('.btn-side .item').each(function( index ) {
                var gItemw = $(this).find('.auto-txt-item').outerWidth();
                console.log( index + ": " + gItemw );
                $(this).css({'width':gItemw});
             });
             $('.btn-side').addClass('owl-carousel owl-theme')
             $('[data-toggle="tooltip"]').tooltipster();
			 
             $('#txt-carousel').owlCarousel({
                 margin:10,
                 loop:false,
                 autoWidth:true,
                 nav:true,
                 items:4,
                 dots:false,
                 navText: ['<i class="fa fa-chevron-circle-left" aria-hidden="true"></i>','<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>']
            });
           ////////////////////////
           $('#ReplyPopupModal').modal({
               backdrop: 'static',
               keyboard: false
           });
		   $(".basic-editor").each(function(){
                tinymce.init({
                    force_br_newlines : true,
                    force_p_newlines : false,
                    forced_root_block : '',
                    menubar: false,
                    statusbar: false,
                    toolbar: false,
                    setup: function(editor) {
                        editor.on('focusout', function(e) {                   
                            var updated_field_name=editor.id;
                            var updated_content=editor.getContent();
                        })
                    }
                });
                tinymce.execCommand('mceRemoveEditor', true, this.id); 
                tinymce.execCommand('mceAddEditor', true, this.id); 
            });

            
            
       },
       error: function() {
           
       }
   });
}


function GetStateList(cont,rander_id='',selected_id='')
{
	var base_url=$("#base_url").val();
	$.ajax({
		  url: base_url+"lead/getstatelist",
		  type: "POST",
		  data: {'country_id':cont,'selected_id':selected_id},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{	//alert(response);
				//document.getElementById('state').innerHTML=response;
				if(rander_id){
					$("#"+rander_id).html(response);
				}
				else{
					$("#state").html(response);
				}	
				
			}
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}
	
function GetCityList(state,rander_id='',selected_id='')
{
	var base_url=$("#base_url").val();
	
	$.ajax({
		  url: base_url+"lead/getcitylist",
		  type: "POST",
		  data: {'state_id':state,'selected_id':selected_id},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{
				if(rander_id){
					document.getElementById(rander_id).innerHTML=response;
				}
				else{
					document.getElementById('city').innerHTML=response;
				}				
			}
		  		
		  },
		  error: function () 
		  {
		   //$.unblockUI();
		   alert('Something went wrong there');
		  }
	   });
}

function addLoader(getele)
{   
    var gets = 100;
    if ($(window).scrollTop() > 200) {
        gets = $(window).scrollTop();
    }
    var loaderhtml = '<div class="loader" style="background-position: 50% '+gets+'px"></div>';
    $(getele).css({'position':'relative', 'overflow':'hidden', 'min-height': '300px'}).prepend(loaderhtml);
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
function simpleEditer()
  {
    // $(".tools").show();
    var box = $('.buying-requirements');
    box.attr('contentEditable', true);

    

    // EDITING LISTENERS
    $('.custom-editer .tools > li input:not(.disabled)').on('click', function() {
       edit($(this).data('cmd'));
    });    
  }
  function inactiveSimpleEditer()
  {
    $(".tools").hide();
    var box = $('.buying-requirements');
    box.attr('contentEditable', false);
  }





