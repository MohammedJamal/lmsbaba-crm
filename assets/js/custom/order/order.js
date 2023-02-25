$(document).ready(function(){	
    
});

function fn_open_po_preview_popup(lowp)
{
    var base_url = $("#base_url").val(); 
      
    $.ajax({
        url: base_url + "order/rander_po_preview_popup",
        type: "POST",
        data: {
         'lowp':lowp
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
        success: function(response) {
            
            $('#rander_common_view_modal_html_sm').html(response);
            $("#common_view_modal_title_sm").html("PURCHASE ORDER")

            $('#rander_common_view_modal_sm').modal({
                    backdrop: 'static',
                    keyboard: false
            });
        },
        error: function() {
         
        }
    });
}

function fn_open_pfi_preview_popup(lowp)
{
	var base_url = $("#base_url").val(); 
      
    $.ajax({
        url: base_url + "order/rander_pfi_preview_popup",
        type: "POST",
        data: {
         'lowp':lowp
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
        success: function(response) {
            
            $('#rander_common_view_modal_html_md').html(response);
            $("#common_view_modal_title_md").html("PROFORMA INVOICE")

            $('#rander_common_view_modal_md').modal({
                    backdrop: 'static',
                    keyboard: false
            });
        },
        error: function() {
         
        }
    });
}

function fn_open_inv_preview_popup(lowp)
{
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "order/rander_inv_preview_popup",
        type: "POST",
        data: {
         'lowp':lowp
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
        success: function(response) {
            
            $('#rander_common_view_modal_html_md').html(response);
            $("#common_view_modal_title_md").html("INVOICE")

            $('#rander_common_view_modal_md').modal({
                    backdrop: 'static',
                    keyboard: false
            });
        },
        error: function() {
         
        }
    });
}
function fn_get_po_upload_view(lid,l_opp_id,step,lowp)
{
    // alert(l_opp_id)
    var base_url = $("#base_url").val(); 
    var is_back_show='N';    
    $.ajax({
        url: base_url + "lead/rander_po_upload_view_popup_ajax",
        type: "POST",
        data: {
         'lid': lid,
         'l_opp_id':l_opp_id,
         'is_back_show': is_back_show,
         'step':step,
         'lowp':lowp
        },
        async: false,
        beforeSend: function( xhr ) {
            if($('#PoUploadLeadModal').hasClass('in'))
            {
                $('#PoUploadLeadModal').modal('hide');
            }
            else
            {
                
            }
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
            
            $('#PoUploadLeadModal').html(response);
            

            $('#PoUploadLeadModal').modal({
                    backdrop: 'static',
                    keyboard: false
            });
            $("#lead_opportunity_wise_po_id").val(lowp);
            go_to_next_step(step);
            $("#po_curr_step").val(step);
        },
        error: function() {
         
        }
    });
}

function invoice_send_to_buyer_modal(lowp)
{   
   // alert(lowp);return false;
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "order/invoice_send_to_buyer_by_mail_ajax",
       type: "POST",
       data: {
           'lowp': lowp
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
                // console.log( index + ": " + gItemw );
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
       },
       error: function() {
           
       }
   });
}
function invoice_send_to_buyer_modal_old(lowp)
{
    var base_url=$("#base_url").val();  
    var data="lowp="+lowp;
    $.ajax({
            url: base_url+"order/invoice_send_to_buyer_by_mail_ajax/",
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
            success:function(res){ 
               result = $.parseJSON(res);
               $("#invoice_send_to_buyer_body").html(result.html);
               $('#invoice_send_to_buyer_modal').modal({backdrop: 'static',keyboard: false}).css('overflow-y', 'auto');
               
            },
            error: function(response) {
            }
        }); 
}

function pro_forma_inv_send_to_buyer_modal(lowp)
{   
   // alert(lowp);return false;
   var base_url = $("#base_url").val();   
   $.ajax({
       url: base_url + "order/pro_forma_inv_send_to_buyer_by_mail_ajax",
       type: "POST",
       data: {
           'lowp': lowp
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
             })
           ////////////////////////
           $('#ReplyPopupModal').modal({
               backdrop: 'static',
               keyboard: false
           });
       },
       error: function() {
           
       }
   });
}
function pro_forma_inv_send_to_buyer_modal_old(lowp)
{
    var base_url=$("#base_url").val();  
    var data="lowp="+lowp;
    $.ajax({
            url: base_url+"order/pro_forma_inv_send_to_buyer_by_mail_ajax/",
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
            success:function(res){ 
               result = $.parseJSON(res);
               $("#pro_forma_inv_send_to_buyer_body").html(result.html);
               $('#pro_forma_inv_send_to_buyer_modal').modal({backdrop: 'static',keyboard: false}).css('overflow-y', 'auto');
               
            },
            error: function(response) {
            }
        }); 
}
function calculate_inv_additional_charges_price_update(pid,inv_id,id,field, value,lowp)
{
  var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "order/update_inv_additional_charges_ajax",
        type: "POST",
        data: {
            'field': field,
            'value': value,
            'id': id,
            'pid': pid,
            'inv_id': inv_id,
            'lowp': lowp,
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            // alert(pid)
            $("#inv_additional_charges_total_sale_price_" + id).html(result.total_sale_price);
            // $("#total_sale_price_" + pid).html(result.total_sale_price);
            //$("#sub_total_update_" + opportunity_id).html(result.sub_total);

            $("#inv_total_deal_value").html(result.total_deal_value);
            $("#inv_total_price").html(result.total_price);
            $("#inv_total_discount").html(result.total_discount);
            // $("#inv_total_tax").html(result.total_tax);
            $("#inv_grand_total_round_off").html(result.grand_total_round_off);
            $("#inv_number_to_word_final_amount").html(result.number_to_word_final_amount);
            if($("#is_same_state").val()=='Y')
            {
                var sgst=result.total_tax/2;
                var cgst=result.total_tax/2;
                $("#inv_total_sgst").html(sgst.toFixed(2));
                $("#inv_total_cgst").html(cgst.toFixed(2));
            }
            else
            {
                $("#inv_total_tax").html(result.total_tax.toFixed(2));
            }
            // alert(result.total_price+'/'+result.total_discount+'/'+result.total_tax+'/'+result.grand_total_round_off+'/'+result.number_to_word_final_amount)
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
function calculate_inv_price_update(pid,inv_id,id,field, value,lowp) {
    // alert(pid+'/'+inv_id+'/'+id+'/'+field+'/'+value); 
    // return false;
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "order/update_inv_product_ajax",
        type: "POST",
        data: {
            'field': field,
            'value': value,
            'id': id,
            'pid': pid,
            'inv_id': inv_id,
            'lowp': lowp,
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            // alert(pid)
            $("#inv_total_sale_price_" + id).html(result.total_sale_price);
            //$("#sub_total_update_" + opportunity_id).html(result.sub_total);

            $("#inv_total_deal_value").html(result.total_deal_value);
            $("#inv_total_price").html(result.total_price);
            $("#inv_total_discount").html(result.total_discount);
            // $("#inv_total_tax").html(result.total_tax);
            $("#inv_grand_total_round_off").html(result.grand_total_round_off);
            $("#inv_number_to_word_final_amount").html(result.number_to_word_final_amount);
            if($("#is_same_state").val()=='Y')
            {
                var sgst=result.total_tax/2;
                var cgst=result.total_tax/2;
                $("#inv_total_sgst").html(sgst.toFixed(2));
                $("#inv_total_cgst").html(cgst.toFixed(2));
            }
            else
            {
                $("#inv_total_tax").html(result.total_tax.toFixed(2));
            }
            // alert(result.total_price+'/'+result.total_discount+'/'+result.total_tax+'/'+result.grand_total_round_off+'/'+result.number_to_word_final_amount)
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
function add_po_additional_charges()
{  
    //var total=$('input[type="checkbox"]:checked').length;
    // var selected_id_array = $.map($('input[name="select[]"]:checked'), function(c){return c.value; });
    var base_url=$("#base_url").val();
    var selected_id_array=[];
    $.each($("input[name='q_additional_charges']:checked"), function(){
          selected_id_array.push($(this).val());
    });    
    // alert(selected_id_array.length); //return false;
    if(selected_id_array.length>0)
    {          
        
        var lid=$("#lead_id").val();
        var l_opp_id=$("#po_lead_opp_id").val();
        var step=$("#po_curr_step").val()
        var lowp=$("#lead_opportunity_wise_po_id").val();
        

         
        var lead_opportunity_wise_po_id=$("#lead_opportunity_wise_po_id").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv=$("#is_pfi_or_inv").val();

        var additional_charges = selected_id_array.toString();
        
        $.ajax({
            url: base_url+"order/selected_additional_charges_added_ajax",
            type: "POST",
            data: {
              'additional_charges':additional_charges,
              'lead_opportunity_wise_po_id':lead_opportunity_wise_po_id,
              'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
              'po_invoice_id':po_invoice_id,
              'is_pfi_or_inv':is_pfi_or_inv,
            },       
            async:true,     
            beforeSend: function( xhr ) {
              
            },
            complete: function (){
              $('#po_additional_charges_list_modal').modal('hide');
            },
            success: function (data) 
            {
                result = $.parseJSON(data);

                if($("#is_pfi_or_inv").val()=='inv')
                {
                    fn_rander_po_inv_product_view(lead_opportunity_wise_po_id);
                }
                else if($("#is_pfi_or_inv").val()=='pfi')
                {
                    fn_rander_po_pfi_product_view(lead_opportunity_wise_po_id);
                }
                
                // alert(result.msg);
                // fn_get_po_upload_view(lid,l_opp_id,step,lowp)
            },
            error: function () 
            {        
              swal({
                      title: 'Something went wrong there!',
                      text: '',
                      type: 'danger',
                      showCancelButton: false
              }, function() {});
            }
      });
    }
    else{
        $('#po_err_prod').show();
    }
} 
function add_po_prod() 
{
    // $("#create_new_opportunity").hide();
    $("#po_porduct_update_confirm").hide();
    $("#po_selected_product_div").html('');
    var po_selected_prod_id=$("#po_selected_prod_id").val();
    var base_url = $("#base_url").val();
    var prod_id_array = $.map($('input[name="select[]"]:checked'), function(c) {
        return c.value;
    });


    // var prod_name_array=[]; 
    // $('input:checkbox[name="select[]"]:checked').each(function(){
    //     var tmp_str=$(this).val()+'@'+$(this).attr('data-name');
    //     prod_name_array.push(tmp_str);
    // });
    
    var base_url = $("#base_url").val();    
    if (prod_id_array.length > 0) 
    {
        var prod_id = prod_id_array.toString();
        if(po_selected_prod_id)
        {
            prod_id=prod_id+','+po_selected_prod_id;
        }

        $.ajax({
            url: base_url + "product/selectpoprod_ajax",
            type: "POST",
            data: {
                'prod_id': prod_id
            },
            async: true,
            beforeSend: function( xhr ) {
              
            },
            complete: function (){
              
            },
            success: function(data) {  
                result = $.parseJSON(data);  
                //$('#product_list').html(response);
                //$('#prod_lead').modal('toggle');

                $('#po_err_prod').hide();
                // $('#product_del').hide();
                var new_prod_name_array=[];                     
                var selected_product_html='';
                if(result.selected_prod_id.length>0)
                {
                    for(var i=0;i<result.selected_prod_id.length;i++)
                    {
                        var tmp_str = result.selected_prod_id[i].split("@");
                        selected_product_html +='<div class="col-auto" id="checked_prod_div_'+tmp_str[0]+'"><div class="search-item"><a href="javaScript:void(0)" class="search-remove po_unchecked_pro" data-id="'+tmp_str[0]+'"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a><span>'+tmp_str[1]+'</span></div></div>';
                    
                        new_prod_name_array.push(tmp_str[0]);
                    }
                }
                // alert(new_prod_name_array.length);
                $("#po_selected_product_div").html(selected_product_html);
                $("#po_porduct_update_confirm").removeClass('hide');
                $("#po_porduct_update_confirm").show();
                // $("#create_new_opportunity").show(); 
                $("#po_selected_prod_id").val(new_prod_name_array);
                $("#po_search_prod_lead_list").hide();
                $(".po_search_product_by_keyword").val('');

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
}
function GetPoProdLeadList() 
{

    var html="";
    /*html+='<div class="card-block"><div class="no-more-tables"><table id="" class="table table-striped m-b-0"><thead><tbody><tr><td colspan="6" align="center"><h3 class="no-found-text">No products found!</h3></td></tr></tbody></thead></table></div></div>'*/
    $("#po_selected_product_div").html('');
    $('#po_prod_lead_list').html(html);
    $('.po_search_product_by_keyword').val("");
    $('#po_prod_lead').modal({
        backdrop: 'static',
        keyboard: false
    }).css('overflow-y', 'auto');    
}

function calculate_pfi_additional_charges_price_update(pid,pfi_id,id,field, value,lowp)
{
  var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "order/update_pfi_additional_charges_ajax",
        type: "POST",
        data: {
            'field': field,
            'value': value,
            'id': id,
            'pid': pid,
            'pfi_id': pfi_id,
            'lowp': lowp,
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            // alert(pid)
            $("#additional_charges_total_sale_price_" + id).html(result.total_sale_price);
            // $("#total_sale_price_" + pid).html(result.total_sale_price);
            //$("#sub_total_update_" + opportunity_id).html(result.sub_total);

            $("#total_deal_value").html(result.total_deal_value);
            $("#total_price").html(result.total_price);
            $("#total_discount").html(result.total_discount);
            // $("#total_tax").html(result.total_tax);
            $("#grand_total_round_off").html(result.grand_total_round_off);
            $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
            if($("#is_same_state").val()=='Y')
            {
                var sgst=result.total_tax/2;
                var cgst=result.total_tax/2;
                $("#pfi_total_sgst").html(sgst.toFixed(2));
                $("#pfi_total_cgst").html(cgst.toFixed(2));
            }
            else
            {
                $("#pfi_total_tax").html(result.total_tax.toFixed(2));
            }
            // alert(result.total_price+'/'+result.total_discount+'/'+result.total_tax+'/'+result.grand_total_round_off+'/'+result.number_to_word_final_amount)
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
function calculate_pfi_price_update(pid,pfi_id,id,field, value,lowp) {
    // alert(pid+'/'+pfi_id+'/'+id+'/'+field+'/'+value); return false;
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url + "order/update_pfi_product_ajax",
        type: "POST",
        data: {
            'field': field,
            'value': value,
            'id': id,
            'pid': pid,
            'pfi_id': pfi_id,
            'lowp': lowp,
        },
        async: true,
        success: function(data) {
            result = $.parseJSON(data);
            // alert(pid)
            $("#total_sale_price_" + id).html(result.total_sale_price);
            //$("#sub_total_update_" + opportunity_id).html(result.sub_total);

            $("#total_deal_value").html(result.total_deal_value);
            $("#total_price").html(result.total_price);
            $("#total_discount").html(result.total_discount);
            // $("#total_tax").html(result.total_tax);
            $("#grand_total_round_off").html(result.grand_total_round_off);
            $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
            
            if($("#is_same_state").val()=='Y')
            {
                var sgst=result.total_tax/2;
                var cgst=result.total_tax/2;
                $("#pfi_total_sgst").html(sgst.toFixed(2));
                $("#pfi_total_cgst").html(cgst.toFixed(2));
            }
            else
            {
                $("#pfi_total_tax").html(result.total_tax.toFixed(2));
            }
            // alert(result.total_price+'/'+result.total_discount+'/'+result.total_tax+'/'+result.grand_total_round_off+'/'+result.number_to_word_final_amount)
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




function fn_update_wysiwyg_textarea(pfi_id,inv_id,updated_field_name,updated_content)
{   
    var base_url=$("#base_url").val();
    // alert(base_url+' / '+pfi_id+' / '+inv_id+' / '+updated_field_name+' / '+updated_content);
    // return false;  
    var data="pfi_id="+pfi_id+"&inv_id="+inv_id+"&updated_field_name="+updated_field_name+"&updated_content="+encodeURIComponent(updated_content);
    // alert(data); return false;
    $.ajax({
          url: base_url+"order/po_wysiwyg_textarea_update_ajax/",
          data: data,
          cache: false,
          method: 'POST',
          dataType: "html",
          beforeSend: function( xhr ) {
              
          },
          success:function(res){ 
             result = $.parseJSON(res);
             if(result.status=='success')
             {
                // swal({
                //     title: 'Quation successfully updated',
                //     text: '',
                //     type: 'success',
                //     showCancelButton: false
                // }, function() {                    
                    
                // });
             }
             
          },
          complete: function(){
          
          },
          error: function(response) {
          }
      });
}
/*
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
*/
function fn_rander_po_pfi_product_view(lowp)
{    
    // alert(lowp); return false;
    var base_url = $("#base_url").val(); 
    $.ajax({
        url: base_url + "order/rander_po_pfi_product_view_ajax",
        type: "POST",
        data: {
         'lowp':lowp
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
            $('#pfi_product_list').html(result.html);
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
                          var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
                          var po_invoice_id=$("#po_invoice_id").val();
                          var updated_field_name=editor.id;
                          var updated_content=editor.getContent();          
                          fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
                          //check_submit();
                        })
                    }
                });
                tinymce.execCommand('mceRemoveEditor', true, this.id); 
                tinymce.execCommand('mceAddEditor', true, this.id); 
            });
            
            $("#total_deal_value").html(result.total_deal_value);
            $("#total_price").html(result.total_price);
            $("#total_discount").html(result.total_discount);
            // $("#total_tax").html(result.total_tax);
            $("#grand_total_round_off").html(result.grand_total_round_off);
            $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
            if($("#is_same_state").val()=='Y')
            {
                var sgst=result.total_tax/2;
                var cgst=result.total_tax/2;
                $("#pfi_total_sgst").html(sgst.toFixed(2));
                $("#pfi_total_cgst").html(cgst.toFixed(2));
            }
            else
            {
                $("#pfi_total_tax").html(result.total_tax.toFixed(2));
            }          
            
        },
        error: function() {
         
        }
    });
}

function fn_rander_po_inv_product_view(lowp)
{    
    // alert(lowp); return false;
    var base_url = $("#base_url").val(); 
    $.ajax({
        url: base_url + "order/rander_po_inv_product_view_ajax",
        type: "POST",
        data: {
         'lowp':lowp
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
            $('#po_inv_product_list').html(result.html);
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
                          var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
                          var po_invoice_id=$("#po_invoice_id").val();
                          var updated_field_name=editor.id;
                          var updated_content=editor.getContent();          
                          fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
                          //check_submit();
                        })
                    }
                });
                tinymce.execCommand('mceRemoveEditor', true, this.id); 
                tinymce.execCommand('mceAddEditor', true, this.id); 
            });
            
            $("#inv_total_deal_value").html(result.total_deal_value);
            $("#inv_total_price").html(result.total_price);
            $("#inv_total_discount").html(result.total_discount);
            // $("#total_tax").html(result.total_tax);
            $("#inv_grand_total_round_off").html(result.grand_total_round_off);
            $("#inv_number_to_word_final_amount").html(result.number_to_word_final_amount);
            if($("#is_same_state").val()=='Y')
            {
                var sgst=result.total_tax/2;
                var cgst=result.total_tax/2;
                $("#inv_total_sgst").html(sgst.toFixed(2));
                $("#inv_total_cgst").html(cgst.toFixed(2));
            }
            else
            {
                $("#inv_total_tax").html(result.total_tax.toFixed(2));
            }          
            
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
function fn_rander_payment_view_popup(lowp)
{
    var base_url = $("#base_url").val(); 
    $.ajax({
        url: base_url + "order/rander_payment_view_popup_ajax",
        type: "POST",
        data: {
         'lowp':lowp,
         'action_type':''
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


function fn_rander_tds_certificate_view(rander_div='',lowp='')
{
    var base_url = $("#base_url").val();       
    $.ajax({
        url: base_url + "order/rander_tds_certificate_view",
        type: "POST",
        data: {
         'lowp':lowp
        },
        async: false,
        beforeSend: function( xhr ) {            
            $(rander_div).html('Randering....');
        },
        complete: function (){ 
        },
        success: function(response) {         
            $(rander_div).html(response);            
        },
        error: function() {}
    });
}

function fn_rander_custom_invoice_view(rander_div='',inv_id='')
{
    var base_url = $("#base_url").val();       
    $.ajax({
        url: base_url + "order/rander_custom_invoice_view",
        type: "POST",
        data: {
         'inv_id':inv_id
        },
        async: false,
        beforeSend: function( xhr ) {            
            $(rander_div).html('Randering....');
        },
        complete: function (){ 
        },
        success: function(response) {         
            $(rander_div).html(response);
            $("#po_custom_invoice").val('');            
        },
        error: function() {}
    });
}

function fn_rander_custom_proforma_view(rander_div='',proforma_id='')
{
    var base_url = $("#base_url").val();       
    $.ajax({
        url: base_url + "order/rander_custom_proforma_view",
        type: "POST",
        data: {
         'proforma_id':proforma_id
        },
        async: false,
        beforeSend: function( xhr ) {            
            $(rander_div).html('Randering....');
        },
        complete: function (){ 
        },
        success: function(response) {         
            $(rander_div).html(response);
            $("#po_custom_proforma").val('');            
        },
        error: function() {}
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