$(document).ready(function(){

    const params = new URLSearchParams(window.location.search); 
    
    if(params)
    {
        var action=String(params.getAll('action'));
        var step=parseInt(params.getAll('step'));
        var lid=parseInt(params.getAll('lid'));
        var l_opp_id=parseInt(params.getAll('l_opp_id'));
        var lowp=parseInt(params.getAll('lowp'));        
        if(action=='po_edit' && step!='' && lid!='' && l_opp_id!='' && lowp!='')
        {
            fn_get_po_upload_view(lid,l_opp_id,step,lowp);
        }
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '';
        window.history.pushState({path:newurl},'',newurl);

    }

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
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"order/get_po_register_list_ajax/"+page,
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
        if ($("#rander_common_view_modal_md").hasClass("in")) {
          $("#rander_common_view_modal_md").modal('hide');
        }
    	fn_get_po_upload_view(lid,l_opp_id,step,lowp);
    	
    });

    $("body").on("click", "#po_upload_submit", function(e) {
        e.preventDefault();           
        var currObj=$(this);        
        var base_url = $("#base_url").val();
        var lead_id = $("#po_lead_id").val();
        var po_upload_file_obj = $("#po_upload_file");
        var po_upload_cc_to_employee_obj = $("#po_upload_cc_to_employee");
        var po_number_obj = $("#po_number");
        var po_upload_describe_comments_obj = $("#po_upload_describe_comments");

        var deal_value_as_per_purchase_order_obj = $("#deal_value_as_per_purchase_order");

        var renewal_date_obj = $("#renewal_date");
        var renewal_follow_up_date_obj = $("#renewal_follow_up_date");
        var renewal_requirement_obj = $("#renewal_requirement");

        var po_tds_percentage_obj=$("#po_tds_percentage");


        // if (po_number_obj.val() == '') {
        // swal('Oops! Please enter PO Number.');
        // return false;           
        // }


        if (deal_value_as_per_purchase_order_obj.val() == '') {
        swal('Oops! Please enter Purchase Order amount.');
        return false;           
        }
          

        if(po_upload_file_obj.val())
        {
            if (validate_upload_file_ext(po_upload_file_obj.val(),'pdf,doc,docx') == false) {
              swal('Oops! The PO attachment should be pdf, doc or docx..');
              return false; 
            } 
        }
        

        if (po_upload_describe_comments_obj.val() == '') {
        swal('Oops! Please enter your comments.');
        return false;             
        }
        
        // if (po_upload_cc_to_employee_obj.val() == null) {

        // swal('Oops! Please select CC to Employee.');
        // return false;            
        // }   

        if(document.getElementById("is_po_tds_applicable").checked==true)
        {
            if (po_tds_percentage_obj.val() == '') {
                swal('Oops! TDS Deduction Applicable is checked and %age not filled.');
                return false;             
            }
            else
            {
                if(po_tds_percentage_obj.val()>10)
                {
                    swal('Oops! TDS deduction should not be greater than 10%.');
                    return false;  
                }
            }
        }     

        if(document.getElementById("is_renewal_available").checked==true)
        {          
          // ==================
          var d = new Date(renewal_follow_up_date_obj.val());
          var dateString = [
            d.getFullYear(),
            ('0' + (d.getMonth() + 1)).slice(-2),
            ('0' + d.getDate()).slice(-2)
          ].join('-');
          var follow_date = dateString.substring(1);
          
          var d = new Date(renewal_date_obj.val());
          var dateString = [
            d.getFullYear(),
            ('0' + (d.getMonth() + 1)).slice(-2),
            ('0' + d.getDate()).slice(-2)
          ].join('-');
          var renewal_date = dateString.substring(1);

          // ==================
          if (renewal_date_obj.val() == '') {
            swal('Oops! Please select renewal date.');
            return false;           
          }

          if (renewal_follow_up_date_obj.val() == '') {
            swal('Oops! Please select renewal next follow up date.');
            return false;           
          }

          if (renewal_requirement_obj.val() == '') {
            swal('Oops! Please enter renewal/ AMC Type .');
            return false;           
          }

          // if(renewal_date<follow_date)
          // {
          //   swal('Oops! Renewal date should be equal or greather than Next Follow Up Date.');
          //   return false;             
          // }
        }
        
        $.ajax({
            url: base_url + "order/update_po_upload_post_ajax",
            data: new FormData($('#frmPoUpload')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
                currObj.attr("disabled", true);
                $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
            },
            complete: function (){
                currObj.attr("disabled", false);
                $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
            },
            success: function(data) {
                result = $.parseJSON(data);
                if (result.status == 'success'){
                    var step =2;
                    var lowp =result.lead_opportunity_wise_po_id;
                    var l_opp_id =result.l_opp_id;
                    var lid =result.lid;
                    // alert(lowp);
                    // go_to_next_step(step);
                    if ($("#rander_common_view_modal_md").hasClass("in")) {
                        $("#rander_common_view_modal_md").modal('hide');
                    }
                    if ($("#rander_common_view_modal_sm").hasClass("in")) {
                        $("#rander_common_view_modal_sm").modal('hide');
                    }
                    fn_get_po_upload_view(lid,l_opp_id,step,lowp);

                    // $("#po_curr_step").val(2);
                    // go_to_next_step(2);
                 }
            }
        });
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
           async: false,
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
                $("#po_payment_terms_submit").attr("disabled", true);
                $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
           },
           complete: function (){
                $("#po_payment_terms_submit").attr("disabled", false);
                $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
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
              title: 'Amount mismatch',
              text: 'Amount entered in Payment Terms and PO Amount mismatch. Do you want to continue?',
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

    $("body").on("click", ".po_pro_forma_invoice_submit", function(e) {
        e.preventDefault();
        var currObj=$(this);
        var base_url = $("#base_url").val();
        var pfi_pro_forma_no_obj=$("#pfi_pro_forma_no");
        var pfi_pro_forma_date_obj=$("#pfi_pro_forma_date");
        var pfi_due_date_obj=$("#pfi_due_date");
        var pfi_expected_delivery_date_obj=$("#pfi_expected_delivery_date");
        var actiontype=$(this).attr('data-actiontype');
        var actionurl=$(this).attr('data-actionurl');		
        var po_custom_proforma_obj=$("#po_custom_proforma");
        var po_custom_proforma_existing=$("#po_custom_proforma").attr("data-existing");
		var proforma_type=$('input[type=radio][name="proforma_type"]:checked').val();

        if(pfi_pro_forma_no_obj.val()=='')
        {
            swal('Oops','Please enter proforma No.','error');
            return false;
        }
        if(pfi_pro_forma_date_obj.val()=='')
        {
            swal('Oops','Please select proforma invoice date.','error');
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

        if(actiontype!='pfi_preview'){
            if($('input[type=radio][name="proforma_type"]:checked').val()=='S')
            {
                
            }
            else if($('input[type=radio][name="proforma_type"]:checked').val()=='C')
            {
                
                if(po_custom_proforma_obj.val()=='' && po_custom_proforma_existing=='')
                {
                    swal('Oops! Upload proforma invoice pdf.');
                    return false;
                }
                
                if(po_custom_proforma_obj.val()!='')
                {
                    if (validate_upload_file_ext(po_custom_proforma_obj.val(),'pdf') == false) {
                      swal('Oops! The proforma invoice should be only pdf.');
                      return false; 
                    } 
                }
            }
        }

        $.ajax({
           url: base_url + "order/po_pro_forma_invoice_post_ajax",
           data: new FormData($('#frmPoUpload')[0]),
           async: false,
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
                
                if(actiontype=='pfi_save_and_continue'){                    
                }
                else if(actiontype=='pfi_preview'){

                    if($('input[type=radio][name="proforma_type"]:checked').val()=='S')
                    {
                        
                    }
                    else if($('input[type=radio][name="proforma_type"]:checked').val()=='C')
                    {
                        swal('Oops','Preview not available for Upload Custom Proforma Invoice.','error');
                        return false;
                    }
                }   
                else if(actiontype=='pfi_send'){
                    if ($("#PoUploadLeadModal").hasClass("in")) {
                        $("#PoUploadLeadModal").css("display", "none");
                    }
                } 
                else if(actiontype=='pfi_download'){                    
                }
                // $("#PoUploadLeadModal").modal('hide');
                currObj.attr("disabled", true);
                $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
                
           },
           complete: function (){
                currObj.attr("disabled", false);
                $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
                
            },
           success: function(data) {
                result = $.parseJSON(data);                
                if (result.status == 'success') {                    
                    var lowp =result.lowp;
                    $("#po_custom_proforma").val('');
                    $("#po_custom_proforma").attr('data-existing',result.po_custom_proforma_file_name);
                    if($('input[type=radio][name="proforma_type"]:checked').val()=='S')
                    {
                        $("#po_custom_proforma_div").html('');
                    }
                    else if($('input[type=radio][name="proforma_type"]:checked').val()=='C')
                    {
                        fn_rander_custom_proforma_view("#po_custom_proforma_div",result.po_proforma_id);
                    }

                    if(actiontype=='pfi_save_and_continue')
                    {                        
                        $("#po_curr_step").val(4);                    
                        var step=4;                    
                        var l_opp_id=$("#po_lead_opp_id").val();
                        var lid=$("#lead_id").val();
                        fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                    }
                    else if(actiontype=='pfi_preview')
                    {                        
                        window.open(actionurl, '_blank');
                    }   
                    else if(actiontype=='pfi_send') 
                    {
                        pro_forma_inv_send_to_buyer_modal(lowp);
                    } 
                    else if(actiontype=='pfi_download')
                    {	
                        if(proforma_type=='C'){
                            if(result.po_custom_proforma!=''){
                                // fn_rander_custom_proforma_view('#po_custom_proforma_div',result.po_proforma_id);
                                window.open(result.po_custom_proforma, '_blank'); 
                            }
                            else{
                                swal('Oops','No custom proforma found.','error');
                                return false;
                            }
                        }
                        else{
							var actionurl_c=currObj.attr('data-actionurl_c');
							var actionurl_s=currObj.attr('data-actionurl_s');
							if(proforma_type=='C'){
								actionurl=actionurl_c;
							}
							else{
								actionurl=actionurl_s;
							}
                            window.open(actionurl, '_blank'); 
                        }
                        //window.open(result.po_custom_proforma, '_blank');
                    }
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
        // $('#PoUploadLeadModal').modal('hide');
        $("#PoUploadLeadModal").css("display", "none");
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
                $("#PoUploadLeadModal").css("display", "none");
                // $('#PoUploadLeadModal').modal('hide');
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
            // alert(po_selected_prod_id+'/'+is_pfi_or_inv)
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
                    $("#PoUploadLeadModal").css("display", "block");
                },
                success: function(response) {
                    // alert('ok-'+lid+','+l_opp_id+','+step+','+lowp)
                    // fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                    // $('#po_prod_lead').modal('show');
                    if($("#is_pfi_or_inv").val()=='inv')
                    {
                        fn_rander_po_inv_product_view(lead_opportunity_wise_po_id);
                    }
                    else if($("#is_pfi_or_inv").val()=='pfi')
                    {
                        fn_rander_po_pfi_product_view(lead_opportunity_wise_po_id);
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
        

        $("#PoUploadLeadModal").css("display", "block");
        // var lid=$("#lead_id").val();
        // var l_opp_id=$("#po_lead_opp_id").val();
        // var step=$("#po_curr_step").val()
        // var lowp=$("#lead_opportunity_wise_po_id").val();
        // fn_get_po_upload_view(lid,l_opp_id,step,lowp)
        
    });

    $("#po_additional_charges_list_modal").on("hidden.bs.modal", function () {
        
        $("#PoUploadLeadModal").css("display", "block");
        // var lid=$("#lead_id").val();
        // var l_opp_id=$("#po_lead_opp_id").val();
        // var step=$("#po_curr_step").val()
        // var lowp=$("#lead_opportunity_wise_po_id").val();
        // fn_get_po_upload_view(lid,l_opp_id,step,lowp)
        
    });

    $("#PoUploadLeadModal").on("hidden.bs.modal", function () {
        if ($(".modal-backdrop").hasClass("fade in")) {
            $(".modal-backdrop").removeClass("fade");
            $(".modal-backdrop").removeClass("in");
            $(".modal-backdrop").removeClass("modal-backdrop");
        } 
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
                // $("#PoUploadLeadModal").css("display", "none");
                // $('#PoUploadLeadModal').modal('hide');
                // $.blockUI({ 
                //   message: 'Please wait...', 
                //   css: { 
                //      padding: '10px', 
                //      backgroundColor: '#fff', 
                //      border:'0px solid #000',
                //      '-webkit-border-radius': '10px', 
                //      '-moz-border-radius': '10px', 
                //      opacity: .5, 
                //      color: '#000',
                //      width:'450px',
                //      'font-size':'14px'
                //     }
                // });
                $('#pfi_product_list').addClass('logo-loader');
            },
            complete: function (){
                // $.unblockUI();
                // $('#po_additional_charges_list_modal').modal('hide');
                // fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                $('#pfi_product_list').removeClass('logo-loader');

            },
            success: function (data) 
            {
                result = $.parseJSON(data);
                fn_rander_po_pfi_product_view(lowp);
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
        $("#PoUploadLeadModal").css("display", "none");
        // $('#PoUploadLeadModal').modal('hide');
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
                $("#PoUploadLeadModal").css("display", "none");
                // $('#PoUploadLeadModal').modal('hide');
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
        
        // var lead_opportunity_wise_po_id=$("#lead_opportunity_wise_po_id").val();
        var po_pro_forma_invoice_id=$("#po_pro_forma_invoice_id").val();
        var po_invoice_id=$("#po_invoice_id").val();
        var is_pfi_or_inv='inv';

        $.ajax({
            url: base_url+"order/new_row_added_ajax",
            type: "POST",
            data: {
              'lead_opportunity_wise_po_id':lowp,
              'po_pro_forma_invoice_id': po_pro_forma_invoice_id,
              'po_invoice_id':po_invoice_id,
              'is_pfi_or_inv':is_pfi_or_inv,
            },       
            async:true,     
            beforeSend: function( xhr ) {
                // $('#PoUploadLeadModal').modal('hide');
                // $.blockUI({ 
                //   message: 'Please wait...', 
                //   css: { 
                //      padding: '10px', 
                //      backgroundColor: '#fff', 
                //      border:'0px solid #000',
                //      '-webkit-border-radius': '10px', 
                //      '-moz-border-radius': '10px', 
                //      opacity: .5, 
                //      color: '#000',
                //      width:'450px',
                //      'font-size':'14px'
                //     }
                // });
                $('#po_inv_product_list').addClass('logo-loader');
            },
            complete: function (){
                $.unblockUI();
                // $('#po_additional_charges_list_modal').modal('hide');
                // fn_get_po_upload_view(lid,l_opp_id,step,lowp);
                $('#po_inv_product_list').removeClass('logo-loader');
            },
            success: function (data) 
            {
                result = $.parseJSON(data);
                // alert(result.msg);
                // fn_get_po_upload_view(lid,l_opp_id,step,lowp)
                fn_rander_po_inv_product_view(lowp);
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

    $("body").on("click", ".po_invoice_submit", function(e) {
        e.preventDefault();
        var currObj=$(this);
        var base_url = $("#base_url").val();
        var invoice_type=$('input[type=radio][name="invoice_type"]:checked').val();
        var po_inv_no_obj=$("#po_inv_no");
        var po_inv_date_obj=$("#po_inv_date");
        var po_inv_due_date_obj=$("#po_inv_due_date");
        var po_inv_expected_delivery_date_obj=$("#po_inv_expected_delivery_date");
        
        var po_custom_invoice_obj=$("#po_custom_invoice");
        var po_custom_invoice_existing=$("#po_custom_invoice").attr("data-existing");


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
		
        var actiontype=$(this).attr('data-actiontype');
        var actionurl=$(this).attr('data-actionurl');
		
        if(actiontype!='inv_preview'){
            if($('input[type=radio][name="invoice_type"]:checked').val()=='S')
            {
                
            }
            else if($('input[type=radio][name="invoice_type"]:checked').val()=='C')
            {
                
                if(po_custom_invoice_obj.val()=='' && po_custom_invoice_existing=='')
                {
                    swal('Oops! Upload invoice pdf.');
                    return false;
                }
                
                if(po_custom_invoice_obj.val()!='')
                {
                    if (validate_upload_file_ext(po_custom_invoice_obj.val(),'pdf') == false) {
                      swal('Oops! The invoice should be only pdf.');
                      return false; 
                    } 
                }
            }
        }

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
                
                if(actiontype=='inv_save_and_continue'){                    
                }
                else if(actiontype=='inv_preview'){ 

                    if($('input[type=radio][name="invoice_type"]:checked').val()=='S')
                    {
                        
                    }
                    else if($('input[type=radio][name="invoice_type"]:checked').val()=='C')
                    {
                        swal('Oops','Preview not available for Upload Custom Invoice.','error');
                        return false;
                    }                   
                }   
                else if(actiontype=='inv_send') 
                {
                    if ($("#PoUploadLeadModal").hasClass("in")) {
                        $("#PoUploadLeadModal").css("display", "none");
                    }
                } 
                else if(actiontype=='inv_download'){                    
                }

                currObj.attr("disabled", true);
                $('#PoUploadLeadModal .modal-body').addClass('logo-loader');
                
           },
           complete: function (){
                currObj.attr("disabled", false);
                $('#PoUploadLeadModal .modal-body').removeClass('logo-loader');
            },
           success: function(data) {
               result = $.parseJSON(data);
               if (result.status == 'success') {
                    var lowp =result.lowp;
                    $("#po_custom_invoice").val('');
                    $("#po_custom_invoice").attr('data-existing',result.po_custom_invoice_file_name);
                    if($('input[type=radio][name="invoice_type"]:checked').val()=='S')
                    {
                        $("#po_custom_invoice_div").html('');
                    }
                    else if($('input[type=radio][name="invoice_type"]:checked').val()=='C')
                    {
                        fn_rander_custom_invoice_view("#po_custom_invoice_div",result.po_invoice_id);
                    }

                    if(actiontype=='inv_save_and_continue')
                    {
                        if ($(".modal-backdrop").hasClass("fade in")) {
                            $(".modal-backdrop").removeClass("fade");
                            $(".modal-backdrop").removeClass("in");
                            $(".modal-backdrop").removeClass("modal-backdrop");
                        } 
                        $('#PoUploadLeadModal').modal('hide');
                    }
                    else if(actiontype=='inv_preview')
                    {
                        window.open(actionurl, '_blank');
                    }   
                    else if(actiontype=='inv_send') 
                    {
                        invoice_send_to_buyer_modal(lowp);
                    } 
                    else if(actiontype=='inv_download')
                    {
                        if(invoice_type=='C'){
                            if(result.po_custom_invoice!=''){
                                fn_rander_custom_invoice_view('#po_custom_invoice_div',result.po_invoice_id);
                                window.open(result.po_custom_invoice, '_blank'); 
                            }
                            else{
                                swal('Oops','No custom invoice found.','error');
                                return false;
                            }
                        }
                        else{
							var actionurl_c=currObj.attr('data-actionurl_c');
							var actionurl_s=currObj.attr('data-actionurl_s');
							
							if(invoice_type=='C'){
								actionurl=actionurl_c;
							}
							else{
								actionurl=actionurl_s;
							}
							
                            window.open(actionurl, '_blank'); 
                        }
                        //window.open(actionurl, '_blank');
                    }

                    
                    // swal({
                    //    title: 'Success',
                    //    text: result.msg,
                    //    type: 'success',
                    //    showCancelButton: false
                    // }, function() {
                    //     $('#PoUploadLeadModal').modal('hide');                        
                    // });
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
        fn_open_lead_history(lid);
    
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

    $("#ReplyPopupModal").on("hidden.bs.modal", function () {

        if ($("#PoUploadLeadModal").hasClass("in")) {
            //$("#PoUploadLeadModal").modal('hide');
            $("#PoUploadLeadModal").css("display", "block");
        }   

        if ($("#rander_common_view_modal_md").hasClass("in")) {
            $("#rander_common_view_modal_md").css("display", "block");
        }      
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
            $("#digital_signature_title").html('Name of authorized signatory');
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

    $("body").on("click",".send_pro_forma_inv_to_buyer_modal",function(e){
        if ($("#rander_common_view_modal_md").hasClass("in")) {
          //$("#rander_common_view_modal_md").modal('hide');
          $("#rander_common_view_modal_md").css("display", "none");
        }


        var lowp=$(this).attr('data-lowp');
        pro_forma_inv_send_to_buyer_modal(lowp);
    });

    $("body").on("click",".send_inv_to_buyer_modal",function(e){
        if ($("#rander_common_view_modal_md").hasClass("in")) {
           //$("#rander_common_view_modal_md").modal('hide');
           $("#rander_common_view_modal_md").css("display", "none");
        }
        
        var lowp=$(this).attr('data-lowp');
        invoice_send_to_buyer_modal(lowp);
    });

    $("body").on("click","#send_pfi_to_buyer_confirm",function(e){
        e.preventDefault();
        var thisObj=$(this);
        var base_url=$("#base_url").val();
        var box = $('.buying-requirements');
        var email_body = box.html();       
        $('#reply_email_body').val(email_body);

        $.ajax({
                url: base_url+"order/pfi_send_to_buyer_by_mail_confirm_ajax",
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
                  $("#pfi_success_msg").html('');
                  $("#send_pfi_to_buyer_confirm").hide(); 
  
                  $("#error_msg").html('');
                  $("#send_pfi_to_buyer_error").hide(); 
  
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

    // $("body").on("click","#send_pfi_to_buyer_confirm",function(e){
    //     e.preventDefault();
    //     var base_url=$("#base_url").val();
    //     $.ajax({
    //             url: base_url+"order/pfi_send_to_buyer_by_mail_confirm_ajax",
    //             //data: data,
    //             data: new FormData($('#send_pfi_to_buyer_frm')[0]),
    //             cache: false,
    //             method: 'POST',
    //             dataType: "html",
    //             mimeType: "multipart/form-data",
    //             contentType: false,
    //             cache: false,
    //             processData:false,
    //             beforeSend: function( xhr ) {
    //               $("#pfi_success_msg").html('');
    //               $("#send_pfi_to_buyer_confirm").hide(); 
  
    //               $("#error_msg").html('');
    //               $("#send_pfi_to_buyer_error").hide(); 
  
    //               // $("#send_to_buyer_confirm").attr("disabled", true);
    //               $('#pro_forma_inv_send_to_buyer_modal .modal-body').addClass('logo-loader');
    //             },
    //             complete: function (){
    //                 $('#pro_forma_inv_send_to_buyer_modal .modal-body').removeClass('logo-loader');
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);                    
    //                 // $("#send_to_buyer_confirm").attr("disabled", false);
    //                 if(result.status == 'success')
    //                 {
    //                     swal({
    //                         title: 'Success',
    //                         text: result.msg,
    //                         type: 'success',
    //                         showCancelButton: false
    //                     }, function() {
    //                         // window.location.reload();
    //                         $('#pro_forma_inv_send_to_buyer_modal').modal('hide'); 
    //                     });
    //                 }
    //                 else
    //                 {                       
    //                     swal({
    //                         title: result.msg,
    //                         text: '',
    //                         type: 'warning',
    //                         showCancelButton: false
    //                     });
    //                 }
    //             }
    //         });  
    // });

    $("body").on("click",".send_invoice_to_buyer_modal",function(e){
        var lowp=$(this).attr('data-lowp');
        invoice_send_to_buyer_modal(lowp);
    });

    // $("body").on("click","#send_invoice_to_buyer_confirm",function(e){
    //     e.preventDefault();
    //     var base_url=$("#base_url").val();
    //     $.ajax({
    //             url: base_url+"order/invoice_send_to_buyer_by_mail_confirm_ajax",
    //             //data: data,
    //             data: new FormData($('#send_invoice_to_buyer_frm')[0]),
    //             cache: false,
    //             method: 'POST',
    //             dataType: "html",
    //             mimeType: "multipart/form-data",
    //             contentType: false,
    //             cache: false,
    //             processData:false,
    //             beforeSend: function( xhr ) {
    //               $("#invoice_success_msg").html('');
    //               $("#send_invoice_to_buyer_confirm").hide(); 
  
    //               $("#invoice_error_msg").html('');
    //               $("#send_invoice_to_buyer_error").hide(); 
  
    //               // $("#send_to_buyer_confirm").attr("disabled", true);
    //               $('#invoice_send_to_buyer_modal .modal-body').addClass('logo-loader');
    //             },
    //             complete: function (){
    //                 $('#invoice_send_to_buyer_modal .modal-body').removeClass('logo-loader');
    //             },
    //             success: function(data){
    //                 result = $.parseJSON(data);                    
    //                 // $("#send_to_buyer_confirm").attr("disabled", false);
    //                 if(result.status == 'success')
    //                 {
    //                     swal({
    //                         title: 'Success',
    //                         text: result.msg,
    //                         type: 'success',
    //                         showCancelButton: false
    //                     }, function() {
    //                         // window.location.reload();
    //                         $('#invoice_send_to_buyer_modal').modal('hide'); 
    //                     });
    //                 }
    //                 else
    //                 {                       
    //                     swal({
    //                         title: result.msg,
    //                         text: '',
    //                         type: 'warning',
    //                         showCancelButton: false
    //                     });
    //                 }
    //             }
    //         });  
    // });
    $("body").on("click","#send_invoice_to_buyer_confirm",function(e){
        e.preventDefault();
        var base_url=$("#base_url").val();
        var thisObj=$(this);
        var base_url=$("#base_url").val();
        var box = $('.buying-requirements');
        var email_body = box.html();       
        $('#reply_email_body').val(email_body);
        $.ajax({
                url: base_url+"order/invoice_send_to_buyer_by_mail_confirm_ajax",
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
                  $("#invoice_success_msg").html('');
                  $("#send_invoice_to_buyer_confirm").hide(); 
  
                  $("#invoice_error_msg").html('');
                  $("#send_invoice_to_buyer_error").hide(); 
  
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

    $(document).on('click',".pfi_no_missing",function(e){
        e.preventDefault();

        swal({
            title: "Oops!",
            text: 'Pro-forma invoive no. and date not save. First of all save all mandatory fields.',
            type: 'warning',
            showCancelButton: false
        });
    });

    $(document).on('click',".inv_no_missing",function(e){
        e.preventDefault();
        
        swal({
            title: "Oops!",
            text: 'Invoive no. and date not save. First of all save all mandatory fields.',
            type: 'warning',
            showCancelButton: false
        });
    });


    // ================================
    // PAYMENT TERMS

    $("body").on("click",".view_payment_ledger",function(e){
        e.preventDefault();
        var lowp=$(this).attr('data-lowp');
        fn_rander_payment_view_popup(lowp);     
    });

    $("body").on("click","#open_po_payment_terms",function(e){
        e.preventDefault();
        var step =$(this).attr("data-step");
        var lowp =$(this).attr("data-lowp");
        var l_opp_id =$(this).attr("data-lo_id");
        var lid =$(this).attr("data-lid");
        $("#PoPaymentLedgerModal").modal('hide');       
        fn_get_po_upload_view(lid,l_opp_id,step,lowp);
    });
    $("body").on("click","#add_payment_ledger",function(e){
        e.preventDefault();
        var base_url = $("#base_url").val();
        var lowp=$(this).attr('data-lowp');      
        var pr_date=$("#pr_date").val();
        var pr_amount=$("#pr_amount").val();
        var pr_payment_mode_id=$("#pr_payment_mode_id").val();
        var pr_narration=$("#pr_narration").val();
        var pr_currency_type=$("#pr_currency_type").val();
        // alert(lowp+'/'+pr_date+'/'+pr_amount+'/'+pr_payment_mode_id+'/'+pr_narration)
        if(pr_date=='')
        {
            swal('Oops','Please choose date','error');
            return false;
        }
        if(pr_amount=='')
        {
            swal('Oops','Please provide amount','error');
            return false;
        }
        if(pr_payment_mode_id=='')
        {
            swal('Oops','Please choose payment mode','error');
            return false;
        }

        $.ajax({
            url: base_url + "order/add_payment_ledger_ajax",
            type: "POST",
            data: {
             'lowp':lowp,
             'date':pr_date,
             'amount':pr_amount,
             'payment_mode_id':pr_payment_mode_id,
             'narration':pr_narration,
             'currency_type':pr_currency_type
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
                $("#pr_date").val('');
                $("#pr_amount").val('');
                $("#pr_payment_mode_id").val('');
                $("#pr_narration").val('');           
                fn_rander_payment_ledger(lowp);
            },
            error: function() {
             
            }
        });
        
    });

    $("body").on("click",".del_payment_ledger",function(e){
        
        e.preventDefault();
        var base_url = $("#base_url").val();
        var id=$(this).attr('data-id');
        var lowp=$(this).attr('data-lowp');

        swal({
            title: 'Are you sure?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!'
        }, function() {
            $.ajax({
                url: base_url + "order/del_payment_ledger_ajax",
                type: "POST",
                data: {
                 'lowp':lowp,
                 'id':id
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
                    fn_rander_payment_ledger(lowp);
                },
                error: function() {
                 
                }
            });
        });
        
    });

    // PAYMENT TERMS
    // ================================

    // ================================
    // PROFORMA INVOICE
    $("body").on("click",".open_pfi_preview",function(e){
        var lowp =$(this).attr("data-lowp");
        fn_open_pfi_preview_popup(lowp);        
    });
    $("body").on("click",".pfi_edit_view_confirmation",function(e){
        var step =$(this).attr("data-step");
        var lowp =$(this).attr("data-lowp");
        var l_opp_id =$(this).attr("data-lo_id");
        var lid =$(this).attr("data-lid");
        swal({
            title: 'Pro-forma invoice not available. Do you want to add?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes'
        }, function() { 
            fn_get_po_upload_view(lid,l_opp_id,step,lowp);
        });
    });
    // PROFORMA INVOICE
    // ================================


    // ================================
    // INVOICE
    $("body").on("click",".open_inv_preview",function(e){
        var lowp =$(this).attr("data-lowp");
        fn_open_inv_preview_popup(lowp);        
    });
    $("body").on("click",".inv_edit_view_confirmation",function(e){
        var step =$(this).attr("data-step");
        var lowp =$(this).attr("data-lowp");
        var l_opp_id =$(this).attr("data-lo_id");
        var lid =$(this).attr("data-lid");
        swal({
            title: 'Invoice not available. Do you want to add?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes'
        }, function() { 
            fn_get_po_upload_view(lid,l_opp_id,step,lowp);
        });
    });
    // INVOICE
    // ================================
    
    

    // -----------------------------------------
    // CANCEL PO
    $("body").on("click",".cancel_po",function(event){
        event.preventDefault();        
        var lowp=$(this).attr('data-id');
        var po_no=$(this).attr('data-pono');
        var po_no_msg=(po_no)?' ('+po_no+')':'';
        var po_pf_no=$(this).attr('data-po_pf_no');
        var po_pf_no_msg=(po_pf_no)?' ('+po_pf_no+')':'';
        var po_inv_no=$(this).attr('data-po_inv_no');
        var po_inv_no_msg=(po_inv_no)?' ('+po_inv_no+')':'';
        var lead_id=$(this).attr('data-lead_id');
        var po_lead_id_msg=(lead_id)?' ('+lead_id+')':'';

        $(this).parent().parent().parent().parent().parent().addClass('on-show');
        var x = $(this).offset();
        var getTop = x.top-54;
        var getLeft = x.left+45;
        var base_url = $("#base_url").val(); 
        swal({
            title: 'Are you sure to cancel the PO id: '+po_no_msg+'? ',
            text: 'By clicking on Yes: (1) The status of Lead Id '+po_lead_id_msg+' will changed from Deal Won to Deal Lost. (2)Proforma Invoice Id'+po_pf_no_msg+' will be cancelled. (3)The Invoice Id '+po_inv_no_msg+' will be cancelled. (4) Payment terms & Payment follow up will be cancelled.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes'
        }, function() { 
            $.ajax({
                url: base_url + "order/rander_dislike_stage_view_ajax",
                type: "POST",
                data: {
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

                    $("#dislike_stage_view").html(response);
                    //$('#dislike_icon_block').css({'left':getLeft, 'top': getTop}).fadeIn();
                    if (window.innerWidth > 768) {
                    var newRight = getLeft-430;
                    $('#dislike_icon_block').css({'left':newRight, 'top': getTop}).fadeIn();
                    }else{
                    var newLeft = (window.innerWidth-330)/2;
                    var newTop = x.top+30;
                    $('#dislike_icon_block').addClass('popMobile').css({'left':newLeft, 'top': newTop}).fadeIn();
                    }
                    $('.like_overlay').fadeIn();
                },
                error: function() {
                 
                }
            });
        });  
        
    });
    $("body").on("click",".close-pop",function(event){
      event.preventDefault();
      $(this).parent().parent().fadeOut();
      $('.list_view .custom-table tbody').find('.on-show').removeClass('on-show');
      $('.like_overlay').fadeOut();
    });
    $("body").on("click","#dislike_btn_confirm",function(e){
        
        var thisBtn=$(this);
        var base_url = $("#base_url").val();
        var lead_regret_reason_id=$("input:radio[name=lead_regret_reason_id]:checked").val();
        var lead_regret_reason=$("input:radio[name=lead_regret_reason_id]:checked").attr("data-text");
        var lowp=$("input:radio[name=lead_regret_reason_id]:checked").attr("data-lowp");
        var data="lead_regret_reason_id="+lead_regret_reason_id+"&lead_regret_reason="+lead_regret_reason+"&lowp="+lowp;
        
        if ($('input[name=lead_regret_reason_id]:checked').length==0) 
        {
          swal('Oops','Please choose any reason.','error');
          return false;
        }

        $.ajax({
            url: base_url + "order/cancel_po_ajax",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function(xhr) {
                //thisBtn.attr("")
                $('#dislike_icon_block .pop-body').addClass('logo-loader');
                $('#dislike_btn_confirm').attr('disabled',true);
            },
            complete: function (){
                $('#dislike_icon_block .pop-body').removeClass('logo-loader');
                $('#dislike_btn_confirm').attr('disabled',false);
                $('#dislike_'+lead_id).addClass('down');
            },
            success: function(res) {
                result = $.parseJSON(res);
                if(result.status=='success')
                {
                    load();
                    $('.list_view .custom-table tbody').find('.on-show').removeClass('on-show');
                    $("#dislike_icon_block").fadeOut();
                    $('.like_overlay').fadeOut();
                }
                else
                {
                    swal('Oops',result.msg,'error');
                }
            },
            error: function(response) {}
        });
    });
    // CANCEL PO
    // -----------------------------------------
    $("body").on("click","#filter_btn",function(e){
        $('#leadFilterModal').modal({
            backdrop: 'static',
            keyboard: false
        });        
    });


    

    // $("input:checkbox[name=is_po_tds_applicable]:checked").each(function(){
    //       lead_applicable_for.push($(this).val());
    //       var lead_applicable_for_text=$(this).attr('data-text');     
    //       (lead_applicable_for_text)?filter_arr.push(' <a href="JavaScript:void(0);" class="lead_individual_filter_reset" id="" data-id="'+$(this).val()+'" data-filter="lead_applicable_for"><i class="fa fa-times" aria-hidden="true"></i></a> '+lead_applicable_for_text):'';
    //   });
});