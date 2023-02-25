$(document).ready(function(){    

	load();
	$(document).on('click', '.myclass', function (e) { 
		e.preventDefault();
		// closeExpendTable();
		var vt=($(this).attr('data-viewtype')=='grid')?'grid':'list';
		var str = $(this).attr('href'); 
		var res = str.split("/");
		var cur_page = res[1];
		$("#page_number").val(cur_page);
		$("#is_scroll_to_top").val('Y'); 
        
		if(cur_page) {              
		load(cur_page);
		}
		else {
		load(1);
		}
	});
	$("body").on("click",".sort_order",function(e){
		var tmp_field=$(this).attr('data-field');
		var curr_orderby=$(this).attr('data-orderby');
		var new_orderby=(curr_orderby=='asc')?'desc':'asc';
		$(this).attr('data-orderby',new_orderby);
		$(".sort_order").removeClass('asc');
		$(".sort_order").removeClass('desc');
		$(this).addClass(curr_orderby);
		$("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
		load();
		//alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
	});
	// AJAX LOAD START
    function load() 
    { 
        //return;
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var filter_sort_by=$("#filter_sort_by").val();
        var view_type=$("#view_type").val();
        var page=$("#page_number").val(); 
        var is_scroll_to_top=$("#is_scroll_to_top").val(); 
        
        

        var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&view_type="+view_type;
         //alert(data);// return false;
        $.ajax({
            url: base_URL+"order/get_proforma_invoice_management_list_ajax/"+page,
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
              beforeSend: function( xhr ) {                
                addLoader('#listing_view');
                // $('#tcontent').addClass('logo-loader');
              },
              complete: function(){
                // $('#tcontent').removeClass('logo-loader');
                removeLoader();
                },
              success:function(res){ 
                result = $.parseJSON(res);
                $("#tcontent").html(result.table);
                $("#page").html(result.page);
			    $("#page_record_count_info").html(result.page_record_count_info);
               
               	if(view_type == 'grid')
               	{
					updateGrid();
					$('#lead_table').addClass('datatable_grid');
					$(".grey-card-block").removeClass('list_view');
					$(".grey-card-block").addClass('grid_view');
                }
                else
                {
	                updateLeadView();
	                $('#lead_table').removeClass('datatable_grid');
	                $(".grey-card-block").addClass('list_view');
	                $(".grey-card-block").removeClass('grid_view');
                } 
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
    }
    // AJAX LOAD END

    $("body").on("click",".get_view",function(e){
      $(".get_view").removeClass("active");
      $(this).addClass('active');
      var view_type=$(this).attr('data-target');
      $("#view_type").val(view_type);
      load();
    });

    $("body").on("click",".open_po_popup_steps",function(e){
    	var step =$(this).attr("data-step");
    	var lowp =$(this).attr("data-lowp");
    	var l_opp_id =$(this).attr("data-lo_id");
    	var lid =$(this).attr("data-lid");
    	// alert(lowp);
    	// go_to_next_step(step);        
        
    	fn_get_po_upload_view(lid,l_opp_id,step,lowp);
    	
    });

    $("body").on("click", "#po_payment_terms_submit", function(e) {
        e.preventDefault(); 

        var base_url = $("#base_url").val();
        var payment_type=$('input[type=radio][name="payment_type"]:checked').val();
        if ($('input[name=payment_type]:checked').length==0) 
        {
          swal('Oops','Please choose any payment.','error');
          return false;
        }        

        
        if(payment_type=='F')
        {

          if($('#f_payment_mode_id option:selected').val()=='')
          {
            swal('Oops','Please choose any payment mode.','error');
            return false;
          }

          if($('#f_payment_date').val()=='')
          {
            swal('Oops','Please provide payment date.','error');
            return false;
          }

          if($('#f_amount').val()=='')
          {
            swal('error','Please provide amount.','error');
            return false;
          }

          // if($('#f_narration').val()=='')
          // {
          //   swal('Oops','Please provide narration.','error');
          //   return false;
          // }

          $.ajax({
           url: base_url + "order/po_payment_terms_post_ajax",
           data: new FormData($('#frmPoUpload')[0]),
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
               // $("#po_upload_submit").attr("disabled", true);
               // $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
           },
           complete: function (){
              // $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
            },
           success: function(data) {
               result = $.parseJSON(data);
               // alert(result.msg);
               if (result.status == 'success') {
                    
                    $("#po_payment_term_id").val(result.po_payment_term_id);
                    $("#po_curr_step").val(3);
                    go_to_next_step(3);
                    //alert(result.po_payment_term_id)                    
                    
                    // swal({
                    //    title: 'Success',
                    //    text: result.msg,
                    //    type: 'success',
                    //    showCancelButton: false
                    // }, function() {
                    //   go_to_next_step(3);
                    //   $("#po_payment_term_id").val(result.po_payment_term_id);
                    // });
               }
               else
               {
                  swal('Oops!',result.msg,'error');
               }
           }
        });
        }
        else
        {   

            var p_payment_mode_flag=0;
            var p_payment_date_flag=0;
            var p_amount_flag=0;
            var p_amount_balance_flag=0;
            // alert($('select[name="p_payment_mode_id[]"]').val())
            $('select[name="p_payment_mode_id[]"] > option:checked').each(function(e){
               
               if($(this).val()=='')
               {                    
                    p_payment_mode_flag++;
               }
            });

            $('input:input[name="p_payment_date[]"]').each(function(e){
               if($(this).val()=='')
               {
                    p_payment_date_flag++;
               }
            });

            var deal_value_as_per_purchase_order=$("#deal_value_as_per_purchase_order").val();
            var p_value_tmp = 0;
            $('input:input[name="p_amount[]"]').each(function(e){
               if($(this).val()=='')
               {
                    p_amount_flag++;                    
               }
               p_value_tmp += ($(this).val())?parseInt($(this).val()):0;
            });
            
            if(p_payment_mode_flag>0)
            {
                swal('Oops','All payment mode should be selected.','error');
                return false;
            }

            if(p_payment_date_flag>0)
            {
                swal('Oops','All payment date should be selected.','error');
                return false;
            }

            if(p_amount_flag>0)
            {
                swal('Oops','All part payment amount should be filled.','error');
                return false;
            }
            

            var p_amount_balance=(deal_value_as_per_purchase_order-p_value_tmp);
            if(p_amount_balance!=0)
            {
              swal({
              title: 'Amount missmatch',
              text: 'PO Amount and input amount missmatch. Do you continue with your amount?',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#DD6B55',
              confirmButtonText: 'Yes, Continue!'
              }, function(inputValue) {          
                if(inputValue===false){
                  
                  $('input:input[name="p_amount[]"]').each(function(e){
                     $(this).val('');
                  });
                  $("#p_amount_balance").html(deal_value_as_per_purchase_order);
                  p_amount_balance_flag++; 
                }else{  

                    $.ajax({
                    url: base_url + "order/po_payment_terms_post_ajax",
                    data: new FormData($('#frmPoUpload')[0]),
                    cache: false,
                    method: 'POST',
                    dataType: "html",
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function(xhr) {
                    // $("#po_upload_submit").attr("disabled", true);
                    // $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
                    },
                    complete: function (){
                    // $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
                    },
                    success: function(data) {
                    result = $.parseJSON(data);
                    // alert(result.msg);
                    if (result.status == 'success') {
                        $("#po_payment_term_id").val(result.po_payment_term_id);
                        $("#po_curr_step").val(3);
                        go_to_next_step(3);
                        // alert(result.po_payment_term_id)
                          
                        
                        // swal({
                        //    title: 'Success',
                        //    text: result.msg,
                        //    type: 'success',
                        //    showCancelButton: false
                        // }, function() {
                        //   go_to_next_step(2);
                        //   $("#po_payment_term_id").val(result.po_payment_term_id);
                        // });
                    }
                    else
                    {
                      swal('Oops!',result.msg,'error');
                    }
                    }
                    });          
                }
              });
            }
            else
            {
                $.ajax({
                url: base_url + "order/po_payment_terms_post_ajax",
                data: new FormData($('#frmPoUpload')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {
                // $("#po_upload_submit").attr("disabled", true);
                // $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
                },
                complete: function (){
                // $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
                },
                success: function(data) {
                result = $.parseJSON(data);
                // alert(result.msg);
                if (result.status == 'success') {

                    $("#po_payment_term_id").val(result.po_payment_term_id);
                    $("#po_curr_step").val(3);
                    go_to_next_step(3);
                    // alert(result.po_payment_term_id)
                    
                    
                    // swal({
                    //    title: 'Success',
                    //    text: result.msg,
                    //    type: 'success',
                    //    showCancelButton: false
                    // }, function() {
                    //   go_to_next_step(2);
                    //   $("#po_payment_term_id").val(result.po_payment_term_id);
                    // });
                }
                else
                {
                  swal('Oops!',result.msg,'error');
                }
                }
                });
            }
        }
        /*
        return false;
        $.ajax({
           url: base_url + "order/po_payment_terms_post_ajax",
           data: new FormData($('#frmPoUpload')[0]),
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
               // $("#po_upload_submit").attr("disabled", true);
               // $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
           },
           complete: function (){
              // $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
            },
           success: function(data) {
               result = $.parseJSON(data);
               // alert(result.msg);
               if (result.status == 'success') {

                   swal({
                       title: 'Success',
                       text: result.msg,
                       type: 'success',
                       showCancelButton: false
                   }, function() {
                      go_to_next_step(2);
                      // alert(result.po_payment_term_id)
                      $("#po_payment_term_id").val(result.po_payment_term_id);
                    });
               }
               else
               {
                  swal('Oops!',result.msg,'error');
               }
           }
        });
        */
    });

    $("body").on("click","#add_p_payment_btn",function(e){
        var base_URL = $("#base_url").val(); 
        var data="";
        $.ajax({
                url: base_URL+"/order/get_po_payment_html",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function( xhr ) {                            
                },
                complete: function(){
                },
                success: function(data){
                    result = $.parseJSON(data);
                    // alert(result.html)
                    $("#payment_type_p_div").append(result.html);
                    var assets_base_url = $("#assets_base_url").val();
                    $( ".input_date" ).datepicker({
                          showOn: "both",
                          dateFormat: "dd-M-yy",
                          buttonImage: assets_base_url+"images/cal-icon.png",
                          // changeMonth: true,
                          // changeYear: true,
                          // yearRange: '-100:+0',
                          buttonImageOnly: true,
                          buttonText: "Select date",
                          // minDate: 0,
                    });
                },
                
        });
    });

    $("body").on("click",".payment_div_del",function(e){
        var divid=$(this).attr("data-divid");
        $("#div_"+divid).remove();

        var deal_value_as_per_purchase_order=$("#deal_value_as_per_purchase_order").val();
        var value = 0;
        $('input:input[name="p_amount[]"]').each(function(e){

        value += ($(this).val())?parseInt($(this).val()):0;
        });
        var p_amount_balance=(deal_value_as_per_purchase_order-value);
        $("#p_amount_balance").html(p_amount_balance);

    });

    $("body").on("click", "#po_pro_forma_invoice_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        var pfi_pro_forma_no_obj=$("#pfi_pro_forma_no");
        var pfi_pro_forma_date_obj=$("#pfi_pro_forma_date");
        var pfi_due_date_obj=$("#pfi_due_date");
        var pfi_expected_delivery_date_obj=$("#pfi_expected_delivery_date");
        if(pfi_pro_forma_no_obj.val()=='')
        {
            swal('Oops','Please enter pro-forma No.','error');
            return false;
        }
        if(pfi_pro_forma_date_obj.val()=='')
        {
            swal('Oops','Please select pro-forma invoice date.','error');
            return false;
        }
        // if(pfi_due_date_obj.val()=='')
        // {
        //     swal('Oops','Please select due date.','error');
        //     return false;
        // }

        // if(pfi_expected_delivery_date_obj.val()=='')
        // {
        //     swal('Oops','Please select expected delivery date.','error');
        //     return false;
        // }

        $.ajax({
           url: base_url + "order/po_pro_forma_invoice_post_ajax",
           data: new FormData($('#frmPoUpload')[0]),
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
                $("#PoUploadLeadModal").modal('hide');
               // $("#po_upload_submit").attr("disabled", true);
               // $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
           },
           complete: function (){
              // $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
            },
           success: function(data) {
                result = $.parseJSON(data);
                // alert(result.msg);
                
                if (result.status == 'success') {
                    $("#po_curr_step").val(4);
                    
                    var step =4;
                    var lowp =result.lowp;
                    var l_opp_id =$("#po_lead_opp_id").val();
                    var lid =$("#lead_id").val();
                    // alert(step+'/'+lowp+'/'+l_opp_id+'/'+lid)
                    fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                    // go_to_next_step(4);
                    // swal({
                    //    title: 'Success',
                    //    text: result.msg,
                    //    type: 'success',
                    //    showCancelButton: false
                    // }, function() {
                        //go_to_next_step(3);
                        // var step =3;
                        // var lowp =$("lead_opportunity_wise_po_id").val();
                        // var l_opp_id =$("#po_lead_opp_id").val();
                        // var lid =$("#lead_id").val();
                        // alert(step+'/'+lowp+'/'+l_opp_id+'/'+lid)
                        // fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                        // alert(lowp);
                        // go_to_next_step(step);                        
                    // });
               }
               else
               {
                  swal('Oops!',result.msg,'error');
               }
           }
        });
    });

    $("body").on("change", ".calculate_pfi_price_update", function(e) {
        // var base_url=$("#base_url").val(); 
        var pid = $(this).attr('data-pid');
        var pfi_id = $(this).attr('data-pfi_id');
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        var value = $(this).val();
        var lowp = $("#lead_opportunity_wise_po_id").val();
        

        calculate_pfi_price_update(pid, pfi_id, id, field, value,lowp);
        
    });
    $("body").on("click", ".del_pfi_product", function(e) {

        var base_url = $("#base_url").val();
        var id = $(this).attr('data-id');
        var pfi_id = $(this).attr('data-pfi_id');
        var lowp = $("#lead_opportunity_wise_po_id").val();
        var pid=$(this).attr('data-pid');

        // alert(id+'/'+pfi_id+'/'+lowp+'/'+pid); 
        // return false;
        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "order/del_pfi_product_ajax",
                type: "POST",
                data: {
                    'id': id,
                    'pfi_id': pfi_id,
                    'lowp': lowp,
                    'pid': pid,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#tr_"+id).remove();
                    $("#total_sale_price_" + pid).html(result.total_sale_price);
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

    $("body").on("change", ".calculate_pfi_additional_charges_price_update", function(e) {
        // var base_url=$("#base_url").val();         
        var pid = $(this).attr('data-pid');
        var pfi_id = $(this).attr('data-pfi_id');
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        var value = $(this).val();
        var lowp = $("#lead_opportunity_wise_po_id").val();
        // calculate_quotation_additional_charges_price_update(opportunity_id, id, field, value,quotation_id);
        calculate_pfi_additional_charges_price_update(pid, pfi_id, id, field, value,lowp);
    });

    $("body").on("click", ".del_pfi_additional_charges_update", function(e) {

        var base_url = $("#base_url").val();
        var id = $(this).attr('data-id');
        var pfi_id = $(this).attr('data-pfi_id');
        var lowp = $("#lead_opportunity_wise_po_id").val();
        var pid=$(this).attr('data-pid');
        // alert(id+'/'+pfi_id+'/'+lowp); 
        // return false;
        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "order/del_pfi_additional_charges_ajax",
                type: "POST",
                data: {
                    'id': id,
                    'pfi_id': pfi_id,
                    'lowp': lowp,
                    'pid':pid,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#tr_additional_charge_"+id).remove();
                    $("#total_sale_price_" + pid).html(result.total_sale_price);
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


    
    

    

    

    $("body").on('click',"#add_product_to_pfi",function(e){
        $('#PoUploadLeadModal').modal('hide');
        $("#is_pfi_or_inv").val('pfi');
        $("#po_selected_prod_id").val('');
        GetPoProdLeadList();            
    });

    $("body").on('click',"#add_additional_charges_to_pfi",function(e){
        // $('#PoUploadLeadModal').modal('hide');
        $("#is_pfi_or_inv").val('pfi');
        $("#po_selected_prod_id").val('');

        var base_url=$("#base_url").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv=$("#is_pfi_or_inv").val();
        // alert(po_pro_forma_invoice_id+'/'+po_invoice_id+'/'+is_pfi_or_inv);
        // return false;
        $.ajax({
              url: base_url+"order/get_additional_charges_checkbox_view_ajax",
              type: "POST",
              data: {
                    "po_pro_forma_invoice_id":po_pro_forma_invoice_id,
                    "po_invoice_id":po_invoice_id,
                    "is_pfi_or_inv":is_pfi_or_inv
                },       
              async:true,     
              beforeSend: function( xhr ) {
                $('#PoUploadLeadModal').modal('hide');
              },
              complete: function (){
                
              },
              success: function (response) 
              {
                  $('#po_additional_charges_list_body').html(response);  
                  $('#po_additional_charges_list_modal').modal({backdrop: 'static',keyboard: false});
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


                   
    });

    $("body").on("click", "#po_search_product_by_keyword", function(e) {
        var base_url = $("#base_url").val();
        var search_keyword = $('.po_search_product_by_keyword').val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv=$("#is_pfi_or_inv").val();
        var po_selected_prod_id=$("#po_selected_prod_id").val();
        // var temp_prod_id = document.getElementById('selected_prod_id').value;
        // alert(temp_prod_id);return false;
        $.ajax({
            url: base_url + "product/getpoprodlist_ajax",
            //type: "POST",
            cache: false,
            method: 'POST',
            dataType: "html",
            data: {
                'search_keyword':search_keyword,
                'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
                'po_invoice_id': po_invoice_id,
                'is_pfi_or_inv': is_pfi_or_inv,
                'po_selected_prod_id':po_selected_prod_id,
            },
            //async: true,
            beforeSend: function( xhr ) {
                    // $.blockUI({ 
                    //     message: 'Please wait...', 
                    //     css: { 
                    //        padding: '10px', 
                    //        backgroundColor: '#fff', 
                    //        border:'0px solid #000',
                    //        '-webkit-border-radius': '10px', 
                    //        '-moz-border-radius': '10px', 
                    //        opacity: .5, 
                    //        color: '#000',
                    //        width:'450px',
                    //        'font-size':'14px'
                    //       }
                    // });
            },
            complete: function (){
                //$.unblockUI();
            },
            success: function(response) {
                $('#po_prod_lead_list').html(response);
                $('#po_prod_lead').modal();
                var existing_selected_prod=$("#po_selected_prod_id").val();
                if(existing_selected_prod)
                {
                    $("#po_porduct_update_confirm").removeClass('hide');
                    $("#po_porduct_update_confirm").show();
                }
                else
                {
                    $("#po_porduct_update_confirm").hide();
                }
                // var selected_prod_id=$("#selected_prod_id").val(); 
                // if(selected_prod_id=='')
                // {            
                //     $("#create_new_opportunity").hide();
                // } 
                // else
                // {
                //     $("#create_new_opportunity").show();
                // }
                //del_prod();
            },
            error: function() {
                swal({
                        title: 'Something went wrong there!',
                        text: '',
                        type: 'danger',
                        showCancelButton: false
                    }, function() {

                });
                //alert('Something went wrong there');
            }
        });
    });

    $("body").on("click",".po_unchecked_pro",function(e){
        var id=$(this).attr('data-id');
        $(this).parent().parent().remove(); 
        var selected_prod_id=$("#po_selected_prod_id").val(); 
       
        if(selected_prod_id)
        {            
            var array=selected_prod_id.split(",");
            var newArray = array.filter((value)=>value!=id); 
            
            if(newArray.length>0)
            {
                $("#po_selected_prod_id").val(newArray.toString(','));
            }
            else
            {
                $("#po_selected_prod_id").val('');
                $("#po_porduct_update_confirm").removeClass('hide');
                $("#po_porduct_update_confirm").show(); 
                // $("#create_new_opportunity").hide();
            }
        } 
        if(newArray.length==0)
        {
            $("#po_porduct_update_confirm").addClass('hide');
        }
        // var flag=0;
        // $('input:checkbox[name="select[]"]:checked').each(function(){
        //     flag++;
        // });

        // if(flag==0)
        // {
        //     $("#create_new_opportunity").hide();
        // }
    });

    $("body").on('click',"#po_porduct_update_confirm",function(e){
        
        var base_url = $("#base_url").val();
        var lid=$("#lead_id").val();
        var l_opp_id=$("#po_lead_opp_id").val();
        var step=$("#po_curr_step").val()
        var lowp=$("#lead_opportunity_wise_po_id").val();
        

         
        var lead_opportunity_wise_po_id=$("#lead_opportunity_wise_po_id").val();
        var po_selected_prod_id=$("#po_selected_prod_id").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv=$("#is_pfi_or_inv").val();

        if (po_selected_prod_id!="" && is_pfi_or_inv!="") 
        {
            $.ajax({
                url: base_url + "order/addpopoprod_ajax",
                //type: "POST",
                cache: false,
                method: 'POST',
                dataType: "html",
                data: {
                    'lead_opportunity_wise_po_id':lead_opportunity_wise_po_id,
                    'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
                    'po_invoice_id': po_invoice_id,
                    'is_pfi_or_inv': is_pfi_or_inv,
                    'po_selected_prod_id':po_selected_prod_id,
                },
                //async: true,
                beforeSend: function( xhr ) {
                        // $.blockUI({ 
                        //     message: 'Please wait...', 
                        //     css: { 
                        //        padding: '10px', 
                        //        backgroundColor: '#fff', 
                        //        border:'0px solid #000',
                        //        '-webkit-border-radius': '10px', 
                        //        '-moz-border-radius': '10px', 
                        //        opacity: .5, 
                        //        color: '#000',
                        //        width:'450px',
                        //        'font-size':'14px'
                        //       }
                        // });
                },
                complete: function (){
                    //$.unblockUI();
                    $('#po_prod_lead').modal('hide');
                },
                success: function(response) {
                    // alert('ok-'+lid+','+l_opp_id+','+step+','+lowp)
                    // fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                },
                error: function() {
                    swal({
                            title: 'Something went wrong there!',
                            text: '',
                            type: 'danger',
                            showCancelButton: false
                        }, function() {

                    });
                    //alert('Something went wrong there');
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
                
    });

    $("#po_prod_lead").on("hidden.bs.modal", function () {
        
        var lid=$("#lead_id").val();
        var l_opp_id=$("#po_lead_opp_id").val();
        var step=$("#po_curr_step").val()
        var lowp=$("#lead_opportunity_wise_po_id").val();
        fn_get_po_upload_view(lid,l_opp_id,step,lowp)
        
    });

    $("#po_additional_charges_list_modal").on("hidden.bs.modal", function () {
        
        var lid=$("#lead_id").val();
        var l_opp_id=$("#po_lead_opp_id").val();
        var step=$("#po_curr_step").val()
        var lowp=$("#lead_opportunity_wise_po_id").val();
        fn_get_po_upload_view(lid,l_opp_id,step,lowp)
        
    });

    $("#PoUploadLeadModal").on("hidden.bs.modal", function () {
        load();    
    });

    $("body").on("click","#add_new_row_pfi",function(e){
        var base_url=$("#base_url").val();
        var lid=$("#lead_id").val();
        var l_opp_id=$("#po_lead_opp_id").val();
        var step=$("#po_curr_step").val()
        var lowp=$("#lead_opportunity_wise_po_id").val();
        
        var lead_opportunity_wise_po_id=$("#lead_opportunity_wise_po_id").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv='pfi';

        $.ajax({
            url: base_url+"order/new_row_added_ajax",
            type: "POST",
            data: {
              'lead_opportunity_wise_po_id':lead_opportunity_wise_po_id,
              'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
              'po_invoice_id':po_invoice_id,
              'is_pfi_or_inv':is_pfi_or_inv,
            },       
            async:true,     
            beforeSend: function( xhr ) {
                $('#PoUploadLeadModal').modal('hide');
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
                // $('#po_additional_charges_list_modal').modal('hide');
                fn_get_po_upload_view(lid,l_opp_id,step,lowp);
            },
            success: function (data) 
            {
                result = $.parseJSON(data);
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
    });

    $("body").on("change","#is_discount_p_or_a_pfi",function(e){
        
        var base_url = $("#base_url").val();
        var lowp=$("#lead_opportunity_wise_po_id").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var is_discount_p_or_a=$(this).val();
        $.ajax({
            url: base_url + "order/pfi_product_discount_type_update_ajax",
            type: "POST",
            data: {
                'lowp':lowp,
                'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
                'is_discount_p_or_a':is_discount_p_or_a
            },
            async: false,
            beforeSend: function( xhr ) {
                // $("#pfi_product_list").css('opacity','0');
            },
            complete: function (){
                // $("#pfi_product_list").css('opacity','1');
            },
            success: function(data) {
                result = $.parseJSON(data);

                // alert(result.html); return false;
                $("#pfi_product_list").html(result.html);
                // $("#total_deal_value").html(result.total_deal_value);
                $("#total_price").html(result.total_price);
                $("#total_discount").html(result.total_discount);
                // $("#total_tax").html(result.total_tax);
                $("#grand_total_round_off").html(result.grand_total_round_off);
                $("#number_to_word_final_amount").html(result.number_to_word_final_amount);
                if($("#is_same_state").val()=='Y')
                {
                    var sgst=result.total_tax/2;
                    var cgst=result.total_tax/2;
                    $("#pfi_total_sgst").html(sgst);
                    $("#pfi_total_cgst").html(cgst);
                }
                else
                {
                    $("#pfi_total_tax").html(result.total_tax);
                }
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
                
            },
            error: function() {}
        });

    });

    $("body").on("change", "#change_currency_type_pfi", function(e) {
        var base_url = $("#base_url").val();
        var c_t = $(this).val();  
        var c_t_code = $('#change_currency_type_pfi option:selected').text();              
        var lowp=$("#lead_opportunity_wise_po_id").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        // alert(c_t_code); return false;
        
        if(c_t!='' && lowp!='' && po_pro_forma_invoice_id!='')
        {
           $.ajax({
                url: base_url + "order/pfi_update_currency_type_ajax",
                type: "POST",
                data: {
                    'currency_type_code':c_t_code,
                    'currency_type': c_t,
                    'lowp': lowp,
                    'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $(".pfi_currency_code_div").html(c_t_code);
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
            // if (c_t != 1) {
            //     input_gst_disable_by_name('gst[]');
            // } else {
            //     input_gst_enable_by_name('gst[]')
            // } 
        }
        else
        {
            swal('Oops','Please select a currency','error');
        }
        
    });


    $("body").on("change", ".calculate_inv_price_update", function(e) {
        // var base_url=$("#base_url").val(); 
        var pid = $(this).attr('data-pid');
        var inv_id = $(this).attr('data-inv_id');
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        var value = $(this).val();
        var lowp = $("#lead_opportunity_wise_po_id").val();
        calculate_inv_price_update(pid, inv_id, id, field, value,lowp);
        
    });

    $("body").on("click", ".del_inv_product", function(e) {

        var base_url = $("#base_url").val();
        var id = $(this).attr('data-id');
        var inv_id = $(this).attr('data-inv_id');
        var lowp = $("#lead_opportunity_wise_po_id").val();
        var pid=$(this).attr('data-pid');

        // alert(id+'/'+inv_id+'/'+lowp+'/'+pid); 
        // return false;
        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "order/del_inv_product_ajax",
                type: "POST",
                data: {
                    'id': id,
                    'inv_id': inv_id,
                    'lowp': lowp,
                    'pid': pid,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#inv_tr_"+id).remove();
                    $("#inv_total_sale_price_" + pid).html(result.total_sale_price);
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

    $("body").on("change", ".calculate_inv_additional_charges_price_update", function(e) {
        // var base_url=$("#base_url").val();         
        var pid = $(this).attr('data-pid');
        var inv_id = $(this).attr('data-inv_id');
        var id = $(this).attr('data-id');
        var field = $(this).attr('data-field');
        var value = $(this).val();
        var lowp = $("#lead_opportunity_wise_po_id").val();
        // calculate_quotation_additional_charges_price_update(opportunity_id, id, field, value,quotation_id);
        calculate_inv_additional_charges_price_update(pid, inv_id, id, field, value,lowp);
    });

    $("body").on("click", ".del_inv_additional_charges_update", function(e) {

        var base_url = $("#base_url").val();
        var id = $(this).attr('data-id');
        var inv_id = $(this).attr('data-inv_id');
        var lowp = $("#lead_opportunity_wise_po_id").val();
        var pid=$(this).attr('data-pid');
        // alert(id+'/'+pfi_id+'/'+lowp); 
        // return false;
        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {

            $.ajax({
                url: base_url + "order/del_inv_additional_charges_ajax",
                type: "POST",
                data: {
                    'id': id,
                    'inv_id': inv_id,
                    'lowp': lowp,
                    'pid':pid,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $("#inv_tr_additional_charge_"+id).remove();
                    $("#inv_total_sale_price_" + pid).html(result.total_sale_price);
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

    $("body").on('click',"#add_product_to_inv",function(e){
        $('#PoUploadLeadModal').modal('hide');
        $("#is_pfi_or_inv").val('inv');
        $("#po_selected_prod_id").val('');
        GetPoProdLeadList();            
    });

    $("body").on('click',"#add_additional_charges_to_inv",function(e){
        // $('#PoUploadLeadModal').modal('hide');
        $("#is_pfi_or_inv").val('inv');
        $("#po_selected_prod_id").val('');

        var base_url=$("#base_url").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv=$("#is_pfi_or_inv").val();
        // alert(po_pro_forma_invoice_id+'/'+po_invoice_id+'/'+is_pfi_or_inv);
        // return false;
        $.ajax({
              url: base_url+"order/get_additional_charges_checkbox_view_ajax",
              type: "POST",
              data: {
                    "po_pro_forma_invoice_id":po_pro_forma_invoice_id,
                    "po_invoice_id":po_invoice_id,
                    "is_pfi_or_inv":is_pfi_or_inv
                },       
              async:true,     
              beforeSend: function( xhr ) {
                $('#PoUploadLeadModal').modal('hide');
              },
              complete: function (){
                
              },
              success: function (response) 
              {
                  $('#po_additional_charges_list_body').html(response);  
                  $('#po_additional_charges_list_modal').modal({backdrop: 'static',keyboard: false});
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


                   
    });

    $("body").on("click","#add_new_row_inv",function(e){
        var base_url=$("#base_url").val();
        var lid=$("#lead_id").val();
        var l_opp_id=$("#po_lead_opp_id").val();
        var step=$("#po_curr_step").val()
        var lowp=$("#lead_opportunity_wise_po_id").val();
        
        var lead_opportunity_wise_po_id=$("#lead_opportunity_wise_po_id").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv='inv';

        $.ajax({
            url: base_url+"order/new_row_added_ajax",
            type: "POST",
            data: {
              'lead_opportunity_wise_po_id':lead_opportunity_wise_po_id,
              'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
              'po_invoice_id':po_invoice_id,
              'is_pfi_or_inv':is_pfi_or_inv,
            },       
            async:true,     
            beforeSend: function( xhr ) {
                $('#PoUploadLeadModal').modal('hide');
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
                // $('#po_additional_charges_list_modal').modal('hide');
                fn_get_po_upload_view(lid,l_opp_id,step,lowp);
            },
            success: function (data) 
            {
                result = $.parseJSON(data);
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
    });

    $("body").on("change", "#change_currency_type_inv", function(e) {
        var base_url = $("#base_url").val();
        var c_t = $(this).val();  
        var c_t_code = $('#change_currency_type_inv option:selected').text();              
        var lowp=$("#lead_opportunity_wise_po_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        // alert(c_t_code); return false;
        
        if(c_t!='' && lowp!='' && po_invoice_id!='')
        {
           $.ajax({
                url: base_url + "order/inv_update_currency_type_ajax",
                type: "POST",
                data: {
                    'currency_type_code':c_t_code,
                    'currency_type': c_t,
                    'lowp': lowp,
                    'po_invoice_id': po_invoice_id,
                },
                async: true,
                success: function(data) {
                    result = $.parseJSON(data);
                    $(".inv_currency_code_div").html(c_t_code);
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
            // if (c_t != 1) {
            //     input_gst_disable_by_name('gst[]');
            // } else {
            //     input_gst_enable_by_name('gst[]')
            // } 
        }
        else
        {
            swal('Oops','Please select a currency','error');
        }
        
    });

    $("body").on("change","#is_discount_p_or_a_inv",function(e){
        
        var base_url = $("#base_url").val();
        var lowp=$("#lead_opportunity_wise_po_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_discount_p_or_a=$(this).val();
        $.ajax({
            url: base_url + "order/inv_product_discount_type_update_ajax",
            type: "POST",
            data: {
                'lowp':lowp,
                'po_invoice_id': po_invoice_id,
                'is_discount_p_or_a':is_discount_p_or_a
            },
            async: true,
            success: function(data) {
                result = $.parseJSON(data);
                // alert(result.html); return false;
                $("#po_inv_product_list").html(result.html);
                // $("#total_deal_value").html(result.total_deal_value);
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
            },
            error: function() {}
        });

    });

    $("body").on("click", "#po_invoice_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();

        var po_inv_no_obj=$("#po_inv_no");
        var po_inv_date_obj=$("#po_inv_date");
        var po_inv_due_date_obj=$("#po_inv_due_date");
        var po_inv_expected_delivery_date_obj=$("#po_inv_expected_delivery_date");
        if(po_inv_no_obj.val()=='')
        {
            swal('Oops','Please enter invoice No.','error');
            return false;
        }
        if(po_inv_date_obj.val()=='')
        {
            swal('Oops','Please select invoice date.','error');
            return false;
        }
        // if(po_inv_due_date_obj.val()=='')
        // {
        //     swal('Oops','Please select invoice due date.','error');
        //     return false;
        // }

        // if(po_inv_expected_delivery_date_obj.val()=='')
        // {
        //     swal('Oops','Please select expected delivery date.','error');
        //     return false;
        // }
        $.ajax({
           url: base_url + "order/po_invoice_post_ajax",
           data: new FormData($('#frmPoUpload')[0]),
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
               // $("#po_upload_submit").attr("disabled", true);
               // $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
           },
           complete: function (){
              // $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
            },
           success: function(data) {
               result = $.parseJSON(data);
               if (result.status == 'success') {

                   swal({
                       title: 'Success',
                       text: result.msg,
                       type: 'success',
                       showCancelButton: false
                   }, function() {
                        //go_to_next_step(3);
                        // var step =3;
                        // var lowp =$("lead_opportunity_wise_po_id").val();
                        // var l_opp_id =$("#po_lead_opp_id").val();
                        // var lid =$("#lead_id").val();
                        // alert(step+'/'+lowp+'/'+l_opp_id+'/'+lid)
                        // fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                        // alert(lowp);
                        // go_to_next_step(step);

                        
                    });
               }
               else
               {
                  swal('Oops!',result.msg,'error');
               }
           }
        });
    });

    $("body").on("click",".skip_to_next",function(e){
        var curr_step=$("#po_curr_step").val();
        var next_step=(parseInt(curr_step)+1);
        $("#po_curr_step").val(next_step);
        go_to_next_step(next_step)
    });

    $("body").on("click",".skip_to_prev",function(e){
        var curr_step=$("#po_curr_step").val();
        var prev_step=(parseInt(curr_step)-1);
        $("#po_curr_step").val(prev_step);
        go_to_next_step(prev_step)
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
          $("#lead_history_log_title").html(result.title);
          $("#lead_history_log_body").html(result.html);
          $('#lead_history_log_modal').modal({backdrop: 'static',keyboard: false}); 
        }
      });
    
    });

    $("body").on("click",".search_by_customer",function(e){
        var cust_email=$(this).attr('data-custemail');
        var cust_mobile=$(this).attr('data-custmobile');
        var post_url=$("#search_type").find('option:first-child').attr("data-id");
        var search_str=(cust_email)?cust_email:cust_mobile;
        $("#top_search_frm").attr("action",post_url);
        $("#search_keyword").val(search_str);
        $("#top_search_frm").submit();      
    });

    $("body").on("click", ".get_detail_modal", function(e) {
        var id = $(this).attr('data-id');
        $("#lead_company_details").modal({
          backdrop: 'static',
          keyboard: false,
          callback: fn_rander_company_details(id)
        });
    });
    $("body").on("click", ".edit_customer_view", function(e) {

        var base_url = $("#base_url").val();
        var customer_id = $(this).attr('data-id');

        $.ajax({
            url: base_url + "customer/customer_edit_view_rander_ajax",
            type: "POST",
            data: {
                'customer_id': customer_id
            },
            async: true,
            success: function(response) {
                $('#edit_customer_view_rander').html(response);
                $('#edit_customer_view_modal').modal({
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

    $("body").on("click", "#update_customer_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();        
        var email_obj= $("#email");
        var alt_email_obj= $("#alt_email");
        var website_obj= $("#website");

        if(email_obj.val()!='')
        {
            if(is_email_validate(email_obj.val())==false)
            {
                swal('Oops! Please enter valid Buyer’s Email.');
                return false;
            }
        }

        if(alt_email_obj.val()!='')
        {
            if(is_email_validate(alt_email_obj.val())==false)
            {
                swal('Oops! Please enter valid Alternate Email.');
                return false;
            }
        }

        if(website_obj.val()!='')
        {
            if(isUrl(website_obj.val())==false)
            {
                swal('Oops! Please enter valid Website.');
                return false;
            }
        }

        

        $.ajax({
            url: base_url + "customer/update_customer_ajax",
            data: new FormData($('#frmCustomerEdit')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
              $('#edit_customer_view_modal .modal-body').addClass('logo-loader');
            },
            complete: function (){
              $('#edit_customer_view_modal .modal-body').removeClass('logo-loader');
            },
            success: function(data) {
                result = $.parseJSON(data);

                if (result.status == 'success') {
                    $("#company_name_div").html(result.company_name);
                    $("#contact_person_div").html(result.contact_person);
                    $("#email_div").html(result.email);
                    $("#mobile_div").html(result.mobile);
                    $("#country_div").html(result.country);

                    swal({
                        title: 'Company details updated successfully!',
                        text: '',
                        type: 'success',
                        showCancelButton: false
                    }, function() {
                        load();
                        $("#lead_info_div").html('');
                        $("#lead_info_div").hide();
                        $('#edit_customer_view_modal').modal('hide');
                    });
                }
            }
        });
    });

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
    // ===================================================
    // CUSTOMER REPLY
    $("body").on("click",".open_cust_reply_box",function(e){  
       var lead_id = $(this).attr('data-leadid');
       var customer_id = $(this).attr('data-custid');
       //alert(lead_id+' / '+customer_id); return false;
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
    // CUSTOMER REPLY
    // ===================================================

    $("body").on("click","input:checkbox[name=pfi_is_ship_to_available]",function(e){
        var checked = $(this).is(':checked');
        if(checked) 
        {
            var updated_content = tinymce.get("pfi_bill_to").getContent();         
            tinymce.get('pfi_ship_to').setContent(updated_content);
            tinymce.get('pfi_ship_to').getBody().setAttribute('contenteditable', true);
        } 
        else 
        {              
            var updated_content='';        
            tinymce.get('pfi_ship_to').setContent(updated_content);  
            tinymce.get('pfi_ship_to').getBody().setAttribute('contenteditable', false);
        }
        var updated_field_name='pfi_ship_to';
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
    });

    $("body").on("click","input:checkbox[name=pfi_is_digital_signature_checked]",function(e){
        var checked = $(this).is(':checked');
        if(checked) 
        {
            $("#digital_signature_title").html('Name of Authorise signature');
            $(".name_of_authorised_signature_div").css("display",'block');
            $("#thanks_and_regards_div").css("display",'none');
            var updated_content = 'Y';         
        } 
        else 
        {     
            $("#digital_signature_title").html('Thanks & Regards');
            $(".name_of_authorised_signature_div").css("display",'none');
            $("#thanks_and_regards_div").css("display",'block')         
            var updated_content = 'N';        
        }
        var updated_field_name='pfi_is_digital_signature_checked';
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
    });    

    $("body").on("click","input:checkbox[name=po_inv_is_ship_to_available]",function(e){
        var checked = $(this).is(':checked');
        if(checked) 
        {
            var updated_content = tinymce.get("pfi_bill_to").getContent();         
            tinymce.get('po_inv_ship_to').setContent(updated_content);
            tinymce.get('po_inv_ship_to').getBody().setAttribute('contenteditable', true);
        } 
        else 
        {              
            var updated_content='';        
            tinymce.get('po_inv_ship_to').setContent(updated_content);  
            tinymce.get('po_inv_ship_to').getBody().setAttribute('contenteditable', false);
        }
        var updated_field_name='po_inv_ship_to';
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
    });

    $("body").on("click","input:checkbox[name=po_inv_is_digital_signature_checked]",function(e){
        var checked = $(this).is(':checked');
        if(checked) 
        {
            $("#po_inv_digital_signature_title").html('Name of Authorise signature');
            $(".po_inv_name_of_authorised_signature_div").css("display",'block');
            $("#po_inv_thanks_and_regards_div").css("display",'none');
            var updated_content = 'Y';         
        } 
        else 
        {     
            $("#po_inv_digital_signature_title").html('Thanks & Regards');
            $(".po_inv_name_of_authorised_signature_div").css("display",'none');
            $("#po_inv_thanks_and_regards_div").css("display",'block')         
            var updated_content = 'N';        
        }
        var updated_field_name='po_inv_is_digital_signature_checked';
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        fn_update_wysiwyg_textarea(po_pro_forma_invoice_id,po_invoice_id,updated_field_name,updated_content);
    }); 
});
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
            // console.log(response);
		 // $('#CommentUpdateLeadModal').modal('hide')
		 $('#PoUploadLeadModal').html(response);
		 // $(".buyer-scroller").mCustomScrollbar({
		 //   scrollButtons:{enable:true},
		 //   theme:"rounded-dark"
		 //   });
		 //////
		 //$('.select2').select2();
		 //simpleEditer();
		 ////////////////////////
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

