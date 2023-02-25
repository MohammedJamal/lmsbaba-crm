$(document).ready(function(){

    
	load();
    $("body").on("click","#string_search_btn",function(e){
        load();        
    });
    $("body").on("click","#string_search_btn_reset",function(e){
        $("#filter_string_search").val('');
        load();        
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
        // alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });
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
        var filter_string_search=$("#filter_string_search").val();
        var is_scroll_to_top=$("#is_scroll_to_top").val();
        var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&view_type="+view_type+"&filter_string_search="+filter_string_search;
        // alert(data);// return false;
        $.ajax({
            url: base_URL+"order/get_payment_followup_list_ajax/"+page,
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
               
                updateLeadView();
                $('#lead_table').removeClass('datatable_grid');
                $(".grey-card-block").addClass('list_view');
                $(".grey-card-block").removeClass('grid_view');
               	if(view_type == 'grid')
               	{
					// updateGrid();
					// $('#lead_table').addClass('datatable_grid');
					// $(".grey-card-block").removeClass('list_view');
					// $(".grey-card-block").addClass('grid_view');
                }
                else
                {
	                // updateLeadView();
	                // $('#lead_table').removeClass('datatable_grid');
	                // $(".grey-card-block").addClass('list_view');
	                // $(".grey-card-block").removeClass('grid_view');
                } 
           },           
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
    }
    // AJAX LOAD END

    $("body").on("click","#download_payment_followup_csv",function (e){
        
        var base_URL     = $("#base_url").val();    
        var filter_sort_by=$("#filter_sort_by").val();       
        var filter_string_search=$("#filter_string_search").val();
        var data = "filter_sort_by="+filter_sort_by+"&filter_string_search="+filter_string_search;        
        document.location.href = base_URL+'order/download_payment_followup_csv/?'+data;
    });


    $("body").on("click",".get_view",function(e){
      $(".get_view").removeClass("active");
      $(this).addClass('active');
      var view_type=$(this).attr('data-target');
      $("#view_type").val(view_type);
      load();
    });

    $("body").on("click",".get_split",function(e){
        
        var base_url = $("#base_url").val();
        var pt_id=$(this).attr('data-pt_id');

        $.ajax({
            url: base_url + "order/rander_trans_payment_terms_ajax",
            type: "POST",
            data: {
             'pt_id':pt_id
            },
            async: false,
            beforeSend: function( xhr ) {               
                // $('#PoPaymentLedgerModal .modal-body').addClass('logo-loader');
            },
            complete: function (){ 
                // $('#PoPaymentLedgerModal .modal-body').removeClass('logo-loader');
            },
            success: function(data) {
                result = $.parseJSON(data);  
                // alert(result.html)
                $( "#rander_html_div_"+pt_id ).html(result.html);
                ///////////////////////////////////////////////
                $( '#rander_html_div_'+pt_id +' tr').each(function( index ) {
                    $( this).find('td').each(function( indexs ) {
                        var c = indexs+1;
                        var getW = $('.parent_div_'+pt_id+' td:nth-child('+c+')').outerWidth();
                        console.log( index + ": " + getW );
                        $( this).css({'width':getW})
                    });
                });
                ///////////////////////////////////////////////
                $( "#split_outer_tr_"+pt_id ).slideToggle( "fast", function() {
                    // Animation complete.
                }); 
                $( ".spacer_"+pt_id ).css({'display': 'table-row'})
            },
            error: function() {
             
            }
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
    $("body").on("click",".skip_to_prev",function(e){
        var curr_step=$("#po_curr_step").val();
        var prev_step=(parseInt(curr_step)-1);
        $("#po_curr_step").val(prev_step);        
        go_to_next_step(prev_step)
    });
    $("body").on("click",".skip_to_next",function(e){
        var curr_step=$("#po_curr_step").val();
        var next_step=(parseInt(curr_step)+1);
        $("#po_curr_step").val(next_step);
        go_to_next_step(next_step)
    });
    // ================================

    // ================================
    // PO
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
        if (po_upload_cc_to_employee_obj.val() == null) {

        swal('Oops! Please select CC to Employee.');
        return false;            
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
    
    // -----------------
    // --Payment Terms--
    // -----------------
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
    });
    $("#PoPaymentLedgerModal").on("hidden.bs.modal", function () {
       load();
    });
    // -----------------
    // --Payment Terms--
    // -----------------

    // -----------------
    // --proforma --
    // -----------------
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
           async: false,
           cache: false,
           method: 'POST',
           dataType: "html",
           mimeType: "multipart/form-data",
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function(xhr) {
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
                // alert(result.msg);
                
                if (result.status == 'success') {                    
                    var lowp =result.lowp;
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
                        $("#PoUploadLeadModal").css("display", "none");
                        pro_forma_inv_send_to_buyer_modal(lowp);
                    } 
                    else if(actiontype=='pfi_download')
                    {
                        window.open(actionurl, '_blank');
                    }
               }
               else
               {
                  swal('Oops!',result.msg,'error');
               }
           }
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
                $.unblockUI();
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
    // -----------------
    // --proforma --
    // -----------------

    // -----------------
    // --invoice --
    // -----------------
    $("body").on("click", ".po_invoice_submit", function(e) {
        e.preventDefault();
        var currObj=$(this);
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
        var actiontype=$(this).attr('data-actiontype');
        var actionurl=$(this).attr('data-actionurl');
        
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
                    if(actiontype=='inv_save_and_continue')
                    {
                        $('#PoUploadLeadModal').modal('hide');
                    }
                    else if(actiontype=='inv_preview')
                    {
                        window.open(actionurl, '_blank');
                    }   
                    else if(actiontype=='inv_send') 
                    {
                        $("#PoUploadLeadModal").css("display", "none");
                        invoice_send_to_buyer_modal(lowp);
                    } 
                    else if(actiontype=='inv_download')
                    {
                        window.open(actionurl, '_blank');
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

    $("#ReplyPopupModal").on("hidden.bs.modal", function () {
       $("#PoUploadLeadModal").css("display", "block");
    });

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
    // -----------------
    // --invoice --
    // -----------------

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
    // PO
    // ================================
    
    
});





