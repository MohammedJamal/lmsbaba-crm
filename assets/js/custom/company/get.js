$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
    $('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+0'
    });
});
$(document).ready(function(){  

	load(1);
    $("body").on("click",".blacklist_toggle",function(e){
        var base_URL = $("#base_url").val();
        var id=$(this).attr("data-id");
        var curr_blacklist_status=$(this).attr("data-blacklist_status");
        if(curr_blacklist_status=='N'){
            var msg="Do you want to blacklist this buyer?";
        }
        else{
            var msg="Do you want to change of blacklist status of the customer? Are you sure?";
        }
        swal({
            title: "Confirmation",
            text: msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-warning',
            confirmButtonText: "Yes, do it!",
            closeOnConfirm: true
        }, function () { 
            var data="id="+id;
            $.ajax({
                url: base_URL+"customer/blacklist_toggle",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function( xhr ) { 
                    //$("#preloader").css('display','block');                           
                },
                complete: function(){
                    //$("#preloader").css('display','none');
               },
                success: function(data){
                    result = $.parseJSON(data);
                    if(curr_blacklist_status=='N'){                        
                        swal({
                                title: "Updated!",
                                text: "No lead of this buyer will be tracked.",
                                type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                load(1); 
                            });
                    }
                    else{
                        load(1); 
                    }                    
                                      
                },                
            });
        }); 
    });
    $(document).on('click', '.btn-status-change', function (e) {
        
        var base_URL = $("#base_url").val();
        var curr_status = $(this).attr('data-curstatus');     
        var id = $(this).attr('data-id');                
        

        //Warning Message            
            swal({
                title: "Confirmation",
                text: "Do you want to change the current status?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-warning',
                confirmButtonText: "Yes, change it!",
                closeOnConfirm: false
            }, function () { 

                var data="id="+id+"&curr_status="+curr_status;  

                $.ajax({
                        url: base_URL+"/user/change_status_employee",
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
                                text: "The status has been changed",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                //location.reload(true); 
                                //load(1);
                                load_managerial_tree();
                            });
                           
                        },
                        complete: function(){
                        $("#preloader").css('display','none');
                       },
                });
                
            });        
    }); 

    $("body").on("click",".get_detail_modal",function(e){
        var id=$(this).attr('data-id');        
        $("#lead_company_details").modal({
            backdrop: 'static',
            keyboard: false,
            callback:fn_rander_company_details(id)
        });   
    });

    $("body").on("click",".get_history_modal",function(e){
        var id=$(this).attr('data-id');        
        $("#company_history_modal").modal({
            backdrop: 'static',
            keyboard: false,
            callback:fn_rander_company_history(id)
        });   
    });
	
	$("body").on("click",".open_lead_view",function(){	
		
		var cid=$(this).attr("data-cid");
		var filter=$(this).attr("data-filter");
		$("#company_wise_lead_modal").modal({
			backdrop: 'static',
			keyboard: false,
			callback:fn_rander_company_wise_lead(cid,filter)
		}); 		
	});
	
	$("body").on("click",".send_mail_to_company_modal",function(e){
        var id=$(this).attr('data-id');  
		var email=$(this).attr('data-email');  
		$("#customer_id").val(id);
		if(email)
		{
			$("#com_to_email").val(email);
			$("#com_to_email").attr("readonly",true);
		}
		else
		{
			$("#com_to_email").attr("readonly",false);
		}
		
        $("#send_mail_to_company").modal();   
    });
	
	
	$("body").on("click","#mail_send_to_company_confirm",function(e){
		var base_URL = $("#base_url").val();
		var customer_id=$("#customer_id").val();
		var com_to_email_obj=$("#com_to_email");
		var com_from_email_obj=$("#com_from_email");
		var com_mail_subject_obj=$("#com_mail_subject");
		var com_mail_body_obj=$("#com_mail_body");
		
		if(com_to_email_obj.val()=="")
		{
			com_to_email_obj.focus();			
			$("#com_to_email_error").html("Oops! to mail should not be blank.");
			return false;
		}
		else
		{			
			$("#com_to_email_error").html("");
		}
		
		if(com_from_email_obj.val()=="")
		{
			com_from_email_obj.focus();
			$("#com_from_email_error").html("Oops! from mail should not be blank.");
			return false;
		}
		else
		{
			$("#com_from_email_error").html("");
		}
		
		if(com_mail_subject_obj.val()=="")
		{	
			com_mail_subject_obj.focus();
			$("#com_mail_subject_error").html("Oops! mail subject should not be blank.");
			return false;
		}
		else
		{	
			$("#com_mail_subject_error").html("");
		}
		
		if(com_mail_body_obj.val()=="")
		{
			com_mail_body_obj.focus();
			$("#com_mail_body_error").html("Oops! mail body should not be blank.");
			return false;
		}
		else
		{
			$("#com_mail_body_error").html("");
		}
		
		var data="customer_id="+customer_id;  

		$.ajax({
				url: base_URL+"customer/mail_send_to_company_ajax",				
				data: new FormData($('#frmSendMailToCompany')[0]),
				cache: false,
				method: 'POST',
				dataType: "html",
				mimeType: "multipart/form-data",
				contentType: false,
				cache: false,
				processData:false,
				beforeSend: function( xhr ) { 
				    $('#send_mail_to_company .modal-body').addClass('logo-loader');                   
				},
                complete: function(){                    
                    $('#send_mail_to_company .modal-body').removeClass('logo-loader');
                },
				success: function(data){
					result = $.parseJSON(data);
					
					if(result.status=='success')
					{
						swal({
                                title: "Success!",
                                text: "The mail has been sent to the company",
                                 type: "success",
                                confirmButtonText: "ok",
                                allowOutsideClick: "false"
                            }, function () { 
                                location.reload(true); 
                                //load(1);
                                
                            });
					}
					
				   
				}
		});
		
	});


	$("body").on("click", ".company_assigne_change", function(e) {
        var c_id = $(this).attr("data-cid");
        var currassigned_to = $(this).attr("data-currassigned");
        var base_url = $("#base_url").val();
        var data = "c_id=" + c_id+"&currassigned_to="+currassigned_to;
        $.ajax({
                url: base_url + "customer/change_assigned_to_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {

                },
                success: function(res) {
                    result = $.parseJSON(res);                
                    $("#company_assigne_to_body").html(result.html);
                    $('#company_assigne_to_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                },
                complete: function() {

                },
                error: function(response) {}
        });
        /*
        swal({
			  title: "Are you sure?",
			  text: "To change the user assign to this company all existing lead assign users will be changed accordingly under the company!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, do it!",
			  cancelButtonText: "No, leave it!",
			  closeOnConfirm: true,
			  closeOnCancel: false
			},
			function(isConfirm) {

				if (isConfirm) 
				{
					$.ajax({
			            url: base_url + "customer/change_assigned_to_ajax",
			            data: data,
			            cache: false,
			            method: 'POST',
			            dataType: "html",
			            beforeSend: function(xhr) {

			            },
			            success: function(res) {
			                result = $.parseJSON(res);                
			                $("#company_assigne_to_body").html(result.html);
			                $('#company_assigne_to_modal').modal({
			                    backdrop: 'static',
			                    keyboard: false
			                });

			            },
			            complete: function() {

			            },
			            error: function(response) {}
			        });
				}
				else 
				{
					swal("Cancelled", "You have no changed :)", "error");
				}

		        
        }); 
        */

    });

    $("body").on("click", "#company_assigne_change_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();
        if($("#assigned_to").val()=='')
        {
        	$("#assigned_to_error").html("Please select user to assign.");
        	return false;
        }
        else
        {
        	$("#assigned_to_error").html("");
        }

        $.ajax({
            url: base_url + "customer/update_change_assigned_to_ajax",
            data: new FormData($('#company_assigne_change_frm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
            	$('#company_assigne_to_modal .modal-body').addClass('logo-loader');
            },
            complete: function (){
                $('#company_assigne_to_modal .modal-body').removeClass('logo-loader');
            }, 
            success: function(data) {
                result = $.parseJSON(data);
                //alert(result.return);          
                if (result.status == 'success') {
                    swal({
                        title: 'Success',
                        text: 'The lead has been assigned to ' + result.assigned_to_user_name,
                        type: 'success',
                        showCancelButton: false
                    }, function() {
                        //$("#assigned_to_user_name_span").html(result.assigned_to_user_name);                  
                        location.reload();
                    });
                }
                else
                {

                	swal({
					      title: 'Warning',
					      text: result.msg,
					      type: 'warning',
					      showCancelButton: false,
					      confirmButtonColor: '#DD6B55',
					      confirmButtonText: '',
					      closeOnConfirm: true
					    }, function() {
					    	
					      //$("#company_assigne_change_submit").attr("disabled",false);
					    });
                }
            }
        });
    });

    $(document).on("click","#download_customer_csv",function (e){
        
        var base_URL = $("#base_url").val();
        var filter_search_str=$("#filter_search_str").val();
        var filter_sort_by=$("#filter_sort_by").val();
        var filter_created_from_date=$("#filter_created_from_date").val();
        var filter_created_to_date=$("#filter_created_to_date").val();  
        var filter_assigned_user=$("#filter_assigned_user").val();
        var filter_by_company_available_for=$("#filter_by_company_available_for").val();
        var filter_by_is_available_company_name=$("#filter_by_is_available_company_name").val();
		var filter_by_is_available_email=$("#filter_by_is_available_email").val();
		var filter_by_is_available_phone=$("#filter_by_is_available_phone").val();
        var filter_last_contacted=$("#filter_last_contacted").val();
        var filter_last_contacted_custom_date=$("#filter_last_contacted_custom_date").val();
        var filter_country=$("#filter_country").val();
        var filter_company_type=$("#filter_company_type").val();
        var filter_by_source=$("#filter_by_source").val();
        var filter_business_type_id=$("#filter_business_type_id").val();

        var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&filter_by_is_available_email="+filter_by_is_available_email+"&filter_by_is_available_phone="+filter_by_is_available_phone+"&filter_by_is_available_company_name="+filter_by_is_available_company_name+"&filter_by_company_available_for="+filter_by_company_available_for+"&filter_assigned_user="+filter_assigned_user+"&filter_created_from_date="+filter_created_from_date+"&filter_created_to_date="+filter_created_from_date+"&filter_last_contacted="+filter_last_contacted+"&filter_last_contacted_custom_date="+filter_last_contacted_custom_date+"&filter_country="+filter_country+"&filter_company_type="+filter_company_type+"&filter_search_str="+filter_search_str+"&filter_by_source="+filter_by_source+"&filter_business_type_id="+filter_business_type_id;
      
      document.location.href = base_URL+'customer/download_csv/?'+data;
    });

    $("body").on("click",".sort_by",function(e){
        var clickObj=$(this);
        var filter_sort_by=$('input[name="sort_by"]:checked').val();
        $("#filter_sort_by").val(filter_sort_by);        
        load(1);
    });

    $("body").on("change","#last_contacted",function(e){
        var tmp_val=$(this).val();
        if(tmp_val=='custom_date')
        {
            $(".custom_date_li").show();
        }
        else
        {
            $("#last_contacted_custom_date").val('');
            $(".custom_date_li").hide();
        }
    });

    $("body").on("click","#com_filter",function(e){
        var filter_arr=[];

        var filter_created_from_date=$("#created_from_date").val();
        var filter_created_to_date=$("#created_to_date").val();

        if(filter_created_from_date!='' && filter_created_to_date!='')
        {
            var date_range_text='Created between "'+filter_created_from_date+'" - "'+filter_created_to_date+'"';
            (date_range_text)?filter_arr.push(date_range_text):'';
        }

        var assign_to=$("#assign_to").val();
        var assign_to_text=$('#assign_to option:selected').attr('data-text');
        (assign_to_text)?filter_arr.push(assign_to_text):'';

        var company_available_for=[];
        $("input:checkbox[name=company_available_for]:checked").each(function(){
            company_available_for.push($(this).val());
            var company_available_for_text=$(this).attr('data-text');     
            (company_available_for_text)?filter_arr.push(company_available_for_text):'';
        });

        var is_available_company_name=$('input[name="is_available_company_name"]:checked').val();
        var is_available_company_name_text=$("input:radio[name=is_available_company_name]:checked").attr('data-text');        
        (is_available_company_name_text)?filter_arr.push(is_available_company_name_text):'';

        var is_available_email=$('input[name="is_available_email"]:checked').val();
        var is_available_email_text=$("input:radio[name=is_available_email]:checked").attr('data-text');        
        (is_available_email_text)?filter_arr.push(is_available_email_text):'';
        
        var is_available_phone=$('input[name="is_available_phone"]:checked').val();
        var is_available_phone_text=$("input:radio[name=is_available_phone]:checked").attr('data-text');        
        (is_available_phone_text)?filter_arr.push(is_available_phone_text):'';

        var last_contacted=$("#last_contacted").val();
        if(last_contacted=='custom_date')
        {
            var last_contacted_custom_date=$("#last_contacted_custom_date").val();
            var last_contacted_custom_date_text=$('#last_contacted_custom_date').attr('data-text');
            (last_contacted_custom_date_text)?filter_arr.push(last_contacted_custom_date_text+' '+last_contacted_custom_date):'';
        }
        else
        {
            var last_contacted_text=$('#last_contacted option:selected').attr('data-text');
            (last_contacted_text)?filter_arr.push(last_contacted_text):'';
        }
        

        var country=[];        
        // $("#country :selected").length
        if($("#country :selected").length>0)
        {
            var i=0;
            $("#country :selected").each(function(){ 
                if($(this).val()!='')
                {
                    country.push($(this).val());
                    var ini_text=(i==0)?'By Country-  ':'';
                    var country_text=ini_text+$(this).attr('data-text');     
                    (country_text)?filter_arr.push(country_text):'';
                    i++;
                }
            });  
        }
               

        var company_type=[];
        $("input:radio[name=company_type]:checked").each(function(){
            company_type.push($(this).val());
            var company_type_text=$(this).attr('data-text'); 
            (company_type_text)?filter_arr.push(company_type_text):'';
        });

        var by_source=[];
        var j=1;
        $("input:checkbox[name=by_source]:checked").each(function(){
            by_source.push($(this).val());
            var by_source_text=$(this).attr('data-text');
            if(by_source_text!='' && j==1)
            {
              label_text='By Source: ';
            }
            else
            {
              label_text='';
            }
            
            (by_source_text)?filter_arr.push(label_text+by_source_text):'';
            j++;
        });

        var business_type_id=$("#business_type_id").val();
        var business_type_text=$('#business_type_id option:selected').attr('data-text');
        (business_type_text)?filter_arr.push(business_type_text):'';



        if(filter_arr.join())
        {
            $("#selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="text-danger" id="com_filter_reset"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>');
        }
        else
        {
            $("#selected_filter_div").css({'display':'none'}).html('');
        }


        $("#filter_created_from_date").val(filter_created_from_date);
        $("#filter_created_to_date").val(filter_created_to_date);
        $("#filter_assigned_user").val(assign_to);
        $("#filter_by_company_available_for").val(company_available_for.join());
        $("#filter_by_is_available_company_name").val(is_available_company_name);
        $("#filter_by_is_available_email").val(is_available_email);
        $("#filter_by_is_available_phone").val(is_available_phone);
        $("#filter_last_contacted").val(last_contacted);
        $("#filter_last_contacted_custom_date").val(last_contacted_custom_date);
        $("#filter_country").val(country.join());  
        $("#filter_company_type").val(company_type.join());           
        $("#filter_by_source").val(by_source.join());
        $("#filter_business_type_id").val(business_type_id);
        $("#companyFilterModal").modal('hide');  
        load(1);

    });

    $("body").on("click","#com_filter_reset",function(e){
        //location.reload(true);
        $("#selected_filter_div").css({'display':'none'}).html('');
        // ------------------------------------------------------
        // FILTER RE-SET
        $("#created_from_date").val('');
        $("#created_to_date").val('');
        $("#assign_to").val($("#assign_to option:first").val());
        $("input:checkbox[name=company_available_for]").attr("disabled",false);
        $("input:checkbox[name=company_available_for]").attr("checked",false);
        $("input:radio[name=is_available_company_name]").attr("checked",false);
        $("input:radio[name=is_available_email]").attr("checked",false);
        $("input:radio[name=is_available_phone]").attr("checked",false);
        $("#last_contacted").val($("#last_contacted option:first").val());
        $("#last_contacted_custom_date").val('');
        $("#country").attr("disabled",false);
        $("#country").val($("#country option:first").val());
        $(".custom_date_li").hide();
        $("input:radio[name=company_type]").attr("checked",false);
        $("input:checkbox[name=by_source]").attr("checked",false);

        // -----
        $("#filter_created_from_date").val('');
        $("#filter_created_to_date").val('');
        $("#filter_assigned_user").val('');
        $("#filter_by_company_available_for").val('');
        $("#filter_by_is_available_company_name").val('');
        $("#filter_by_is_available_email").val('');
        $("#filter_by_is_available_phone").val('');
        $("#filter_last_contacted").val('');
        $("#filter_last_contacted_custom_date").val('');
        $("#filter_country").val(''); 
        $("#filter_company_type").val('');  
        $("#filter_by_source").val('');         

        // FILTER RE-SET
        // ------------------------------------------------------  

        // $(".filter_dd").removeClass('open');
        // $(".filter_dd").removeClass('show');
        // $('.dd_overlay').hide();

        $("#selected_filter_div").html('');
        // $("#filterModal").modal('hide');          
        load(1);
    });

    // SHOW/HIDE USERS CHECK BOX
    $("body").on("click",".tree_clickable",function(e){
      $("#select_div").slideToggle('fast');
      $(this).toggleClass("tree-down-arrow");
    });

    $(".filter_source_all_checkbox").change(function () {
      $('.dropdown_new .check-box-sec').removeClass('same-checked');
      
      if($(this).prop("checked") == true){
        $('#dropdownMenuFilterSource').html('All');
      }else{
        $('#dropdownMenuFilterSource').html('None');
      }
        $('input:checkbox[name=by_source]').prop('checked', $(this).prop("checked"));
    });
    $("body").on("click",".cAll",function(e){
      e.preventDefault();
      $('#dropdownMenuFilterSource').html('All');
      $('input:checkbox[name=by_source], .filter_source_all_checkbox').prop('checked',true);
    });
    $("body").on("click",".uAll",function(e){
      e.preventDefault();
      $('#dropdownMenuFilterSource').html('None');
      $('input:checkbox[name=by_source], .filter_source_all_checkbox').prop('checked',false);
    });

    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH ################# */
    /* ######################################################################## */
    function validate()
    {        
        return true;
    }
    
    // AJAX SEARCH START
    $(document).on('click', '#submit', function (e) {
			e.preventDefault();
			var base_URL      = $("#base_url").val();			
            /* Validation Code */
            var r = validate();
            if(r === false) {
                    return false;
            }
            else {
                    load(1);
                    return false;
            }
            /* Validation code end */
            
    });
    
    $(document).on('click', '.myclass', function (e) { 
        e.preventDefault();

        var vt=($(this).attr('data-viewtype')=='grid')?'grid':'list';
        var str = $(this).attr('href'); 
        var res = str.split("/");
        var cur_page = res[1];
        if(cur_page){
            load(cur_page);
        }
        else{
            load(1);
        }
        // $("input:checkbox[id=set_all]").prop('checked', false);
        // $("input:checkbox[class=set_individual]").prop('checked', false);
        // $("#checked_ids").val('');
	});
    // AJAX SEARCH END
    
    
    // AJAX LOAD START
    function load(page=1) 
    {        
        //return;
        //var page_num=page;
        var base_URL = $("#base_url").val();
        var filter_search_str=$("#filter_search_str").val();
        var filter_sort_by=$("#filter_sort_by").val();
        var filter_created_from_date=$("#filter_created_from_date").val();
        var filter_created_to_date=$("#filter_created_to_date").val();  
        var filter_assigned_user=$("#filter_assigned_user").val();
        var filter_by_company_available_for=$("#filter_by_company_available_for").val();
        var filter_by_is_available_company_name=$("#filter_by_is_available_company_name").val();
		var filter_by_is_available_email=$("#filter_by_is_available_email").val();
		var filter_by_is_available_phone=$("#filter_by_is_available_phone").val();
        var filter_last_contacted=$("#filter_last_contacted").val();
        var filter_last_contacted_custom_date=$("#filter_last_contacted_custom_date").val();
        var filter_country=$("#filter_country").val();
        var filter_company_type=$("#filter_company_type").val();
        var filter_by_source=$("#filter_by_source").val();
        var filter_business_type_id=$("#filter_business_type_id").val();

        var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&filter_by_is_available_email="+filter_by_is_available_email+"&filter_by_is_available_phone="+filter_by_is_available_phone+"&filter_by_is_available_company_name="+filter_by_is_available_company_name+"&filter_by_company_available_for="+filter_by_company_available_for+"&filter_assigned_user="+filter_assigned_user+"&filter_created_from_date="+filter_created_from_date+"&filter_created_to_date="+filter_created_from_date+"&filter_last_contacted="+filter_last_contacted+"&filter_last_contacted_custom_date="+filter_last_contacted_custom_date+"&filter_country="+filter_country+"&filter_company_type="+filter_company_type+"&filter_search_str="+filter_search_str+"&filter_by_source="+filter_by_source+"&filter_business_type_id="+filter_business_type_id;
        // alert(data); //return false;
        $.ajax({
            url: base_URL+"customer/get_list_ajax/"+page,
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
             beforeSend: function( xhr ) {                
                addLoader('.table-responsive-holder');
            },
           	success:function(res){ 
               result = $.parseJSON(res);

               $("#tcontent").html(result.table);
               $("#page").html(result.page);
               if ($('.ext-table').hasClass('active')) {
                $('.table-toggle-holder').find('.auto-show').removeClass('hide');
               }
			   $("#page_record_count_info").html(result.page_record_count_info);
               $("#total_row_count").val(result.total_row_count);
               set_all_none_checked_status();
               set_all_unchecked();
           },
           complete: function(){
            removeLoader();
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
    }

    $("body").on("click",".sort_order",function(e){
        var tmp_field=$(this).attr('data-field');
        var curr_orderby=$(this).attr('data-orderby');
        var new_orderby=(curr_orderby=='asc')?'desc':'asc';
        $(this).attr('data-orderby',new_orderby);
        $(".sort_order").removeClass('asc');
        $(".sort_order").removeClass('desc');
        $(this).addClass(curr_orderby);
        //alert(tmp_field+'@'+curr_orderby);
        $("#filter_sort_by").val(tmp_field+'@'+curr_orderby);
        load(1);
        //alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });
    // AJAX LOAD END
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH END ############# */
    /* ######################################################################## */

    // ---------------------------------------------------
    // checked / unchecked
    set_all_unchecked();
    $("body").on("change",".user_all_checkbox",function(e){
        var limit_per_page=$("#limit_per_page").val();
        $('.dropdown_new .check-box-sec').removeClass('same-checked');        
        if($(this).prop("checked") == true){
            $('#dropdownMenuUser').html('All');            
            show_all_checked_functionality();            
        }
        else{
            $('#dropdownMenuUser').html('None');
            set_all_unchecked();
        }
        $('input:checkbox[name=customer_id]').prop('checked', $(this).prop("checked"));        
        set_checked();
    });

    $("body").on("click",".cAll",function(e){
        e.preventDefault();
        $('#dropdownMenuUser').html('All');
        $('input:checkbox[name=customer_id], .user_all_checkbox').prop('checked',true);
        set_checked();
        show_all_checked_functionality();
    });
    $("body").on("click",".uAll",function(e){
        e.preventDefault();
        $('#dropdownMenuUser').html('None');
        $('.dropdown_new .check-box-sec').removeClass('same-checked');
        $('input:checkbox[name=customer_id], .user_all_checkbox').prop('checked',false);
        //set_checked();
        set_all_unchecked();
    });
    
    $("body").on("click","input:checkbox[name=customer_id]",function(e){       
        // if ($('input:checkbox[name=customer_id]').not(':checked').length == 0) {
        //     $('#dropdownMenuUser').html('None');
        //     $('.user_all_checkbox').prop('checked',true);
        //     $('.dropdown_new .check-box-sec').removeClass('same-checked');
        // } else {
        //     $('#dropdownMenuUser').html('All');
        //     $('.user_all_checkbox').prop('checked',false);
        //     $('.dropdown_new .check-box-sec').addClass('same-checked');
        // }
        set_all_none_checked_status();
        set_checked();        
    });    
    // checked / unchecked
    // ---------------------------------------------------
    
    // $("body").on("click","#set_all_checked",function(e){
    //     var base_url = $("#base_url").val();
    //     $.ajax({
    //         url: base_url + "customer/set_all_checked",
    //         data: new FormData($('#customer_list_frm')[0]),
    //         cache: false,
    //         method: 'POST',
    //         dataType: "html",
    //         mimeType: "multipart/form-data",
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         beforeSend: function(xhr) {
    //         },
    //         success: function(data) {
    //             result = $.parseJSON(data);
    //             $("#show_for_select").html('');
    //             $("#show_for_unselect").html('<span>All '+result.checked_count+' records are selected.</span>  &nbsp;/ &nbsp;<a href="JavaScript:void(0);" class="text-info " id="set_all_unchecked">Clear selection</a>');
    //             // console.log(result.msg);          
    //         }
    //     });
    // });

    // $("body").on("click","#set_all_unchecked",function(e){
    //     var base_url = $("#base_url").val();
    //     $.ajax({
    //         url: base_url + "customer/set_all_unchecked",
    //         data: new FormData($('#customer_list_frm')[0]),
    //         cache: false,
    //         method: 'POST',
    //         dataType: "html",
    //         mimeType: "multipart/form-data",
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         beforeSend: function(xhr) {
    //         },
    //         success: function(data) {
    //             result = $.parseJSON(data);
    //             $("#show_for_select").html('');
    //             $("#show_for_unselect").html('');
    //             $('#dropdownMenuUser').html('None');
    //             $('.dropdown_new .check-box-sec').removeClass('same-checked');
    //             $('input:checkbox[name=customer_id], .user_all_checkbox').prop('checked',false);
    //             // console.log(result.msg);          
    //         }
    //     });
    // });

    // ------------------------------------------------------------
    // bulk email upload
    $(document).on("click",".new_filter_btn.bulk",function(event) {
        event.preventDefault();
        if($("#bulk_email_to_email").val()!='')
        {  
            $('body').addClass('sp-noscroll'); 
            $('#bulk_mail_pop').addClass('sp-show');
        }
        else
        {
            swal("Oops", "You have not checked any company.", "error");
        }        
    });

    $(document).on("click",".bulk-close",function(event) {
        event.preventDefault();
        $('body').removeClass('sp-noscroll')
        $('#bulk_mail_pop').removeClass('sp-show');
        $('#bulk_mail_pop').removeClass('sp-mini');
        $('#bulk_mail_pop').removeClass('sp-full');
        $('.bulk_email_to_email_show').removeClass("active");
        $("#show_selected_to_email").removeClass('active').html('');
        set_all_none_checked_status();
        set_all_unchecked();
    });
    
    $(document).on("click",".bulk-mini",function(event) {
        event.preventDefault();

        if ($(this).parent().parent().parent().parent().parent().parent().hasClass('sp-mini')) {
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-mini');
        }else{
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-full');
            $(this).parent().parent().parent().parent().parent().parent().addClass('sp-mini');
        }
    });
    $(document).on("click",".bulk-header a.bulk-full",function(event) {
        event.preventDefault();

        if ($(this).parent().parent().parent().parent().parent().parent().hasClass('sp-full')) {
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-full');
        }else{
            $(this).parent().parent().parent().parent().parent().parent().removeClass('sp-mini');
            $(this).parent().parent().parent().parent().parent().parent().addClass('sp-full');
        }
    });

    $("body").on("click",".bulk_email_to_email_show",function(e){
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $("#show_selected_to_email").removeClass('active').html('');
            return false;
        }
        $(this).addClass('active');
        var base_URL = $("#base_url").val();
        var lead_id=$("#lead_id").val();      
        var data = "lead_id="+lead_id;
        //alert(data); return false;
        $.ajax({
            url: base_URL+"customer/rander_selected_bulk_mail",
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
                       'z-index':'999999999',
                       'font-size':'14px'
                      }
                });
            },
            success:function(res){ 
                result = $.parseJSON(res);
                //alert(result.html);$(this).addClass('active')
                $("#show_selected_to_email").addClass('active').html(result.html);
                
            },
            complete: function(){
                $.unblockUI();
            },
            error: function(response) {
            //alert('Error'+response.table);
            }
        })
    });

    $("body").on("click","#bulk_email_submit_confirm",function(e){
        var base_URL = $("#base_url").val();
        var bulk_email_from_email=$("#bulk_email_from_email").val();
        var bulk_email_subject=$("#bulk_email_subject").val();
        var bulk_email_body = tinymce.get("bulk_email_body").getContent();
        if(bulk_email_from_email=='')
        {
            swal("Oops", "Please enter from email", "error");
            return false;
        }

        if(bulk_email_subject=='')
        {
            swal("Oops", "Please enter mail subject", "error");
            return false;
        }

        if(bulk_email_body=='')
        {
            swal("Oops", "Please enter mail body", "error");
            return false;
        }
        //var data = "bulk_email_from_email="+bulk_email_from_email+"&bulk_email_subject="+bulk_email_subject+"&bulk_email_body="+bulk_email_body+"&bulk_email_to_email_test=";
        //alert(data); //return false;        
        swal({
            title: "Confirmation",
            text: "Do you want to send the mail to all selected customers?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-warning',
            confirmButtonText: "Yes, send it!",
            closeOnConfirm: true
        }, function () {
            $.ajax({                
                url: base_URL+"customer/bulk_email_store",
                data: new FormData($('#bulk_mail_frm')[0]),
                cache: false,
                method: 'POST',
                dataType: "html",
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
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
                           'z-index':'999999999',
                           'font-size':'14px'
                          }
                    });
                },            
                success:function(res){ 
                   result = $.parseJSON(res);
                   
                   //alert(result.bulk_mail_id); 
                   if(result.status=='success' && result.bulk_mail_id!='')
                   {
                        $('#bulk_mail_pop').removeClass('sp-show');
                        $('#bulk_mail_pop').removeClass('sp-mini');
                        $('#bulk_mail_pop').removeClass('sp-full');  
                        $("#bulk_email_to_email").val('');
                        $("#bulk_email_subject").val('');
                        tinymce.get("bulk_email_body").setContent('');
                        set_all_unchecked();              
                        swal('Success!', 'Bulk mails successfully sent', 'success');
                        swal({
                            title: "Success!",
                            text: "Bulk mails successfully sent",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: 'btn-warning',
                            confirmButtonText: "Yes, send it!",
                            closeOnConfirm: true
                        }, function () {
                            window.location.reload();
                        });  
                        /*
                        var data = "bulk_mail_id="+result.bulk_mail_id;                        
                        $.ajax({
                            url: base_URL+"customer/bulk_email_send",
                            data: data,
                            cache: false,
                            method: 'GET',
                            dataType: "html",
                             beforeSend: function( xhr ) {
                            },
                            success:function(res){ 
                               result = $.parseJSON(res);                               
                           },
                           complete: function(){},
                           error: function(response) { }
                       });
                       */
                   }
                   else
                   {
                        swal('Fail!', 'Bulk mails fail to send.', 'error');
                   }
                                      
               },
               complete: function(){$.unblockUI();},
               error: function(response) {}
           });            
        });        
    });


    $("body").on("click","#mail_to_test_btn",function(e){
        $("#mail_to_test_div").toggle();
    });
    $("body").on("change","#bulk_email_to_email_test",function(e){
        var email=$(this).val();        
        if(validateEmail(email)==false)
        {            
            $("#bulk_email_test_submit_confirm").attr("disabled",true);            
            swal("Oops", "Please enter valid test mail", "error");
        }
        else
        {
            $("#bulk_email_test_submit_confirm").attr("disabled",false);
        }
    });
    $("body").on("click","#bulk_email_test_submit_confirm",function(e){
        var base_URL = $("#base_url").val();
        var bulk_email_from_email=$("#bulk_email_from_email").val();
        var bulk_email_subject=$("#bulk_email_subject").val();
        var bulk_email_body = tinymce.get("bulk_email_body").getContent();
        var bulk_email_to_email_test=$("#bulk_email_to_email_test").val();
        $("#mail_to_test_div").show();
        if(bulk_email_from_email=='')
        {
            swal("Oops", "Please enter from email", "error");
            return false;
        }

        if(bulk_email_subject=='')
        {
            swal("Oops", "Please enter mail subject", "error");
            return false;
        }

        if(bulk_email_body=='')
        {
            swal("Oops", "Please enter mail body", "error");
            return false;
        }

        if(bulk_email_to_email_test=='')
        {
            swal("Oops", "Please enter to mail for testing", "error");
            return false;
        }

        $.ajax({
            url: base_URL+"customer/bulk_email_test_send",
            //url: base_url + "lead/create_lead_comment_ajax",
            data: new FormData($('#bulk_mail_frm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {
                $("#bulk_email_test_submit_confirm").attr("disabled",true); 
            },
            success: function(data) {
                //$(".preloader").hide();
                result = $.parseJSON(data);
                //alert(result.msg)
                if(result.status=='success')
                {
                    $("#bulk_email_to_email_test").val('');
                    $("#bulk_email_test_submit_confirm").attr("disabled",false); 
                    $("#mail_to_test_div").hide();
                    swal('Success!', 'Test mail successfully sent', 'success');
                }
                else
                {
                    $("#bulk_email_to_email_test").val('');
                    $("#bulk_email_test_submit_confirm").attr("disabled",false); 
                    swal("Oops", "Test mail not sent", "error");
                }
            },
            complete: function(){},
            error: function(response) {}
        });

        /*
        var data = "bulk_email_from_email="+bulk_email_from_email+"&bulk_email_subject="+bulk_email_subject+"&bulk_email_body="+bulk_email_body+"&bulk_email_to_email_test="+bulk_email_to_email_test;
        
        $.ajax({
            url: base_URL+"customer/bulk_email_send",
            data: data,
            async: true,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) {},            
            success:function(res){ 
               result = $.parseJSON(res);
               if(result.status=='success')
               {
                $("#bulk_email_to_email_test").val('');
                swal('Success!', 'Test mail successfullt sent', 'success');
               }
               else
               {
                swal("Oops", "Test mail not sent", "error");
               }
           },
           complete: function(){},
           error: function(response) {}
       })
       */
    });

    $("body").on("click","input:checkbox[name=company_available_for]",function(){
        var flag=0;
        $("input:checkbox[name=company_available_for]:checked").each(function(){
            flag++;
        });
        if(flag>0)
        {
            $("#country").attr("disabled",true);
        }
        else
        {
            $("#country").attr("disabled",false);
        }
    });

    $("body").on("change","#country",function(){
        var flag=0;
        $("#country :selected").each(function(){  
            if($(this).val())     
                flag++;
        }); 

        
        if(flag>0)
        {
            $("input:checkbox[name=company_available_for]").each(function(){
                $(this).attr("checked",false);
                $(this).attr("disabled",true);
            });
            
        }
        else
        {
            $("input:checkbox[name=company_available_for]").each(function(){
                $(this).attr("disabled",false);
            });
        }
    });
    // bulk email upload
    // ------------------------------------------------------------

    // ======================================================
    // QUOTATION/PROPOSAL VIEW
    $(document).on("click",".quoted_view_popup",function(event) {
        event.preventDefault();
        var base_url = $("#base_url").val();
        var cid=$(this).attr('data-customerid');
        var lead_ids=$(this).attr('data-quotedlids');
        var data="cust_id="+cid+"&lead_ids="+lead_ids; 
        // alert(data);  return false;                   
        $.ajax({
                url: base_url+"lead/get_quotation_id_from_leads",
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
                  //$("#preloader").css('display','block');
                },
                success: function(data){
                    result = $.parseJSON(data);
                    // alert(result.id); return false;
                    if(result.id>0)
                    {                                    
                      open_quotation_view(result.id,lead_ids);  
                    }
                },
                complete: function(){
                  //$("#preloader").css('display','none');
                },
        }); 
                            
    });

    $("body").on("click",".new_quotation_view_popup",function(e){
      var id=$(this).attr('data-id');
      var lead_ids=$(this).attr('data-quotedlids');
      
      if(id>0 && lead_ids!='')
      {
        open_quotation_view(id,lead_ids)
      }
      
    });

    // QUOTATION/PROPOSAL VIEW
    // ======================================================


    // ======================================================
    // ADD NEW LEAD
    $("body").on("click",".open_add_lead_view",function(e){
        var cid=$(this).attr("data-cid"); 
        var mobile=$(this).attr("data-mobile"); 
        var email=$(this).attr("data-email"); 
        var is_search_box_show='N';       
        rander_add_new_lead_view(is_search_box_show,mobile,email,cid);
    });

    $("body").on("click","#add_to_lead_submit_confirm",function(e){
        //alert('get add_to_lead_submit_confirm')
      e.preventDefault();
      var base_url = $("#base_url").val();
      var product_tags_obj=$("#product_tags");
      var lead_title_obj=$("#lead_title");
      var com_source_id_obj=$("#com_source_id");
      //var lead_requirement_obj=$("#lead_requirement");
      var lead_requirement = tinyMCE.activeEditor.getContent();
      var com_contact_person_obj=$("#com_contact_person");
      var com_company_name_obj=$("#com_company_name");
      var com_country_id_obj=$("#com_country_id");
      var com_state_id_obj=$("#com_state_id");
      var assigned_user_id_obj=$("#assigned_user_id");

      var lead_enq_date_obj=$("#lead_enq_date");
      var lead_follow_up_date_obj=$("#lead_follow_up_date");
      
      
      
      var com_designation_obj=$("#com_designation");
      var com_alternate_email_obj=$("#com_alternate_email");

      
      

      if(lead_title_obj.val()=='')
      {
        lead_title_obj.addClass('error_input');
        $("#lead_title_error").html('Please enter lead Product / Service Required');
        product_tags_obj.focus();
        return false;
      }
      else
      {
        product_tags_obj.removeClass('error_input');
        $("#lead_title_error").html('');
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


      $('#lead_requirement').val(lead_requirement);
      if(lead_requirement=='')
      {
        //lead_requirement.addClass('error_input');
        $("#lead_requirement_error").html('Please describe requirements');
        //lead_requirement.focus();
        return false;
      }
      else
      {
        //lead_requirement.removeClass('error_input');
        $("#lead_requirement_error").html('');
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

      // if(com_company_name_obj.val()=='')
      // {
      //   com_company_name_obj.addClass('error_input');
      //   $("#com_company_name_error").html('Please enter company name');
      //   com_company_name_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_company_name_obj.removeClass('error_input');
      //   $("#com_company_name_error").html('');
      // }

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

      if(assigned_user_id_obj.val()=='')
      {
        assigned_user_id_obj.addClass('error_input');
        $("#assigned_user_id_error").html('Please select account manager');
        assigned_user_id_obj.focus();
        return false;
      }
      else
      {
        assigned_user_id_obj.removeClass('error_input');
        $("#assigned_user_id_error").html('');
      }


      if(lead_enq_date_obj.val()=='')
      {
        lead_enq_date_obj.addClass('error_input');
        $("#lead_enq_date_error").html('Please select enquiry date');
        lead_enq_date_obj.focus();
        return false;
      }
      else
      {
        lead_enq_date_obj.removeClass('error_input');
        $("#lead_enq_date_error").html('');
      }

      if(lead_follow_up_date_obj.val()=='')
      {
        lead_follow_up_date_obj.addClass('error_input');
        $("#lead_follow_up_date_error").html('Please select follow up date');
        lead_follow_up_date_obj.focus();
        return false;
      }
      else
      {
        lead_follow_up_date_obj.removeClass('error_input');
        $("#lead_follow_up_date_error").html('');
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

      // if(com_alternate_email_obj.val()!='')
      // {
      //   if(is_email_validate(com_alternate_email_obj.val())==false)
      //   {
      //       com_alternate_email_obj.addClass('error_input');
      //       $("#com_alternate_email_error").html("Please enter valid email.");
      //       com_alternate_email_obj.focus();
      //       return false;
      //   }
      //   else
      //   {
      //       com_alternate_email_obj.removeClass('error_input');
      //       $("#com_alternate_email_error").html('');
      //   }
      // }
      // else
      // {
      //       com_alternate_email_obj.removeClass('error_input');
      //       $("#com_alternate_email_error").html('');
      // }
      

      // if(com_state_id_obj.val()=='')
      // {
      //   com_state_id_obj.addClass('error_input');
      //   $("#com_state_id_error").html('Please select state');
      //   com_state_id_obj.focus();
      //   return false;
      // }
      // else
      // {
      //   com_state_id_obj.removeClass('error_input');
      //   $("#com_state_id_error").html('');
      // }

      
      
       $.ajax({
              url: base_url+"lead/add_lead_ajax",
              data: new FormData($('#frmLeadAdd')[0]),
              cache: false,
              method: 'POST',
              dataType: "html",
              mimeType: "multipart/form-data",
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function( xhr ) {
                $('.btn_enabled').addClass("btn_disabled");
                $(".btn_disabled").html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                $("#add_to_lead_submit_confirm").attr("disabled",true);
                $('#rander_add_new_lead_view_modal .modal-body').addClass('logo-loader');
              },
              complete: function (){
                $('#rander_add_new_lead_view_modal .modal-body').removeClass('logo-loader');
              },
              success: function(data){
                result = $.parseJSON(data);
                // console.log(result.msg);
                // alert(result.msg);return false;
                //alert(result.lead_id+' / '+result.company_id);
                $('.btn_enabled').removeClass("btn_disabled");
                $(".btn_enabled").html('<span class="btn-text">Submit<span> <i class="fa fa-angle-right" aria-hidden="true"></i>');
                $("#add_to_lead_submit_confirm").attr("disabled",false);

                if(result.status=='success')
                {
                    //swal('Success!', 'A new lead successfully added', 'success');
                    $('#rander_add_new_lead_view_modal').modal('hide');
                    load();
                    rander_customer_edit_view(result.company_id,result.lead_id);
                    
                    /*
                    swal({
                          title: "Success!",
                          text: "A new lead successfully added.",
                           type: "success",
                          confirmButtonText: "ok",
                          allowOutsideClick: "false",
                          showCancelButton: false,
                          closeOnConfirm: true
                      }, function () {                           
                          window.location.href=base_url+"lead/add";                        
                      });
                    */
                }   
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
                    load();
                    // $("#company_name_div").html(result.company_name);
                    // $("#contact_person_div").html(result.contact_person);
                    // $("#email_div").html(result.email);
                    // $("#mobile_div").html(result.mobile);
                    // $("#country_div").html(result.country);

                    swal({
                        title: 'Company details updated successfully!',
                        text: '',
                        type: 'success',
                        showCancelButton: false
                    }, function() {
                        $("#lead_info_div").html('');
                        $("#lead_info_div").hide();
                        //edit_customer_view_modal
                        if ($('#edit_customer_view_modal').hasClass('force-close')) {
                            $('#edit_customer_view_modal').removeClass('force-close');
                        }
                        $('#edit_customer_view_modal').modal('hide');
                    });
                }
            }
        });
    });
    $("body").on("change","#product_tags",function(e){
        var product_tags=$(this).val();
        var product_tags_str = product_tags.join(", ");     
        var new_text='Requirement for '+product_tags_str;
        $("#lead_title").val(new_text);
        //alert(new_text)
    });
    // ADD NEW LEAD
    // ======================================================

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
                $("#edit_customer_view_rander_title").text('Edit Details');
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

  $("body").on("click", ".add_more_contact_persion_view", function(e) {
        var base_url=$("#base_url").val();
        var customer_id=$(this).attr('data-id');
        var id='';
        $.ajax({
            url: base_url + "customer/customer_contact_persion_add_edit_view_rander_ajax",
            type: "POST",
            data: {
                'customer_id': customer_id,
                'id': id
            },
            async: true,
            success: function(response) {
                $("#edit_customer_view_rander_title").text('Add Contact Person');
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
    $("body").on("click", "#contact_persion_submit", function(e) {
        e.preventDefault();
        var base_url = $("#base_url").val();  
        var ename_obj= $("#name");  
        var designation_obj= $("#designation");      
        var email_obj= $("#email");
        var mobile_obj= $("#mobile");
        var customer_id=$("#customer_id").val();
        
        if(ename_obj.val()=='')
        {
            swal('Oops! Please enter name.');
            return false;
        }
        if(designation_obj.val()=='')
        {
            swal('Oops! Please enter designation.');
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
        
        
        $.ajax({
            url: base_url + "customer/add_edit_customer_contact_persion_ajax",
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
                    swal({
                        title: 'Success',
                        text: result.msg,
                        type: 'success',
                        showCancelButton: false
                    },function() {                         
                        if(result.id){
                            rander_customer_wise_contact_persion_list_view(customer_id);
                        }
                        else{
                            load();
                            $("#lead_info_div").html('');
                            $("#lead_info_div").hide();
                            $('#edit_customer_view_modal').modal('hide');
                        }
                        
                    });
                }
            }
        });
    });

    $("body").on("click", ".list_more_contact_persion_view", function(e) {

        var customer_id=$(this).attr('data-id');      
        rander_customer_wise_contact_persion_list_view(customer_id);
        
    });

    $("body").on("click", ".edit_more_contact_persion_view", function(e) {
        var base_url=$("#base_url").val();
        var customer_id=$(this).attr('data-cid');
        var id=$(this).attr('data-id');
        var actionTable=$(this).attr('data-actionTable');
        $.ajax({
            url: base_url + "customer/customer_contact_persion_add_edit_view_rander_ajax",
            type: "POST",
            data: {
                'customer_id': customer_id,
                'id': id,
                'action_table': actionTable
            },
            async: true,
            success: function(response) {
                $("#edit_customer_view_rander_title").text('Edit Contact Person');
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

    $("#edit_customer_view_modal").on("hidden.bs.modal", function () {
        // var action=$("#action").val();
        // if(action){
        //     if(action=='cp_edit_mode'){

        //     }
        //     else{
        //         load();
        //     }
        // }        
    });

    $("body").on("click", ".delete_more_contact_persion_view", function(e) {
        var base_url=$("#base_url").val();
        var customer_id=$(this).attr('data-cid');
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
                url: base_url + "customer/customer_contact_persion_delete_ajax",
                type: "POST",
                data: {
                    'customer_id': customer_id,
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
                        rander_customer_wise_contact_persion_list_view(customer_id);
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

   
});

function rander_customer_wise_contact_persion_list_view(customer_id)
{
    var base_url=$("#base_url").val();          
    $.ajax({
        url: base_url + "customer/customer_contact_persion_list_view_rander_ajax",
        type: "POST",
        data: {
            'customer_id': customer_id                
        },
        async: true,
        success: function(response) {
            $("#edit_customer_view_rander_title").text('Contact Person List');
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
}

function rander_add_new_lead_view(is_search_box_show,mobile,email,cid)
{      
    var base_url = $("#base_url").val();
    
    $.ajax({
          url: base_url + "lead/add_ajax",
          type: "POST",
          data: {
              'is_search_box_show': is_search_box_show,
              'mobile': mobile,
              'email': email,
              'is_customer_basic_data_show':'N',
              'cid':cid
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
            $('#rander_add_new_lead_view_html').html(response);
            $('#rander_add_new_lead_view_modal').modal({backdrop: 'static',keyboard: false});
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
/*
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
*/
function set_all_none_checked_status()
{
    var limit_per_page=$("#limit_per_page").val();
    if ($('input:checkbox[name=customer_id]').not(':checked').length == 0) {
        $('#dropdownMenuUser').html('All');
        $('.user_all_checkbox').prop('checked',true);
        $('.dropdown_new .check-box-sec').removeClass('same-checked');
    } 
    else if($('input:checkbox[name=customer_id]').not(':checked').length == limit_per_page)
    {
        $('#dropdownMenuUser').html('None');
        $('.user_all_checkbox').prop('checked',false);
        $('.dropdown_new .check-box-sec').removeClass('same-checked');
    }
    else { 
        $('#dropdownMenuUser').html('All');
        $('.user_all_checkbox').prop('checked',false);
        $('.dropdown_new .check-box-sec').addClass('same-checked');
    }
}

function set_checked()
{
    var base_URL = $("#base_url").val();    
    var ids='';    
    var chkds = $("input[name='customer_id']:checkbox");
    var checked_count=0;    
    for (i=0; i<chkds.length;i++) 
    {       
        if(chkds[i].checked)
        {           
            ids +=chkds[i].value+',';
            checked_count++;
        }
    }
    ids = ids.slice(0, -1); // trim last character  
    var data = "ids="+ids;
    //alert(data); //return false;
    $.ajax({
        url: base_URL+"customer/set_checked",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function( xhr ) { },
        success:function(res){ 
           result = $.parseJSON(res);            
            
            if(checked_count>0)
            {
                $("#bulk_email_to_email").val(''+checked_count+' record(s) are selected.');          
                
            }
            else
            {
                $('#bulk_mail_pop').removeClass('sp-show');
                $('#bulk_mail_pop').removeClass('sp-mini');
                $('#bulk_mail_pop').removeClass('sp-full');  
                $("#bulk_email_to_email").val('');
                $("#bulk_email_subject").val('');
                tinymce.get("bulk_email_body").setContent('');
            }
            
        },
        complete: function(){ },
        error: function(response) { }
    });
}

function show_all_checked_functionality()
{
    var total_row_count=parseInt($("#total_row_count").val());
    var limit_per_page=parseInt($("#limit_per_page").val());
    //alert("total_row_count:"+total_row_count+' / '+"limit_per_page:"+limit_per_page)
    
    if(total_row_count>limit_per_page)
    {
        $("#show_for_select").html('<span>All '+limit_per_page+' conversations on this page are selected.</span>  &nbsp;/ &nbsp;<a href="JavaScript:void(0);" class="text-info " id="set_all_checked" onclick="set_all_checked()">Select all '+total_row_count+' records</a>');
    }
    else
    {
        $("#show_for_select").html('');
    }
}

function set_all_checked()
{
    var base_url = $("#base_url").val();
    var total_row_count=parseInt($("#total_row_count").val());
    var limit_per_page=parseInt($("#limit_per_page").val());
    $.ajax({
        url: base_url + "customer/set_all_checked",
        data: new FormData($('#customer_list_frm')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(xhr) {
        },
        success: function(data) {
            result = $.parseJSON(data);
            $("#show_for_select").html('');           
            //console.log(result.msg)
            $("#show_for_unselect").html('<span>All '+result.checked_count+' records are selected.</span>  &nbsp;/ &nbsp;<a href="JavaScript:void(0);" class="text-info " id="set_all_unchecked" onclick="set_all_unchecked()">Clear selection</a>');
            $("#bulk_email_to_email").val('All '+result.checked_count+' records are selected.');
            
            // console.log(result.msg);          
        }
    });
}

function set_all_unchecked()
{
    var base_url = $("#base_url").val();
    var data='';
    $.ajax({
            url: base_url+"customer/set_all_unchecked",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
             beforeSend: function( xhr ) { },
            success:function(res){ 
                result = $.parseJSON(res);
                $("#show_for_select").html('');
                $("#show_for_unselect").html('');
                $("#bulk_email_to_email").val('');
                $('#dropdownMenuUser').html('None');
                $('.dropdown_new .check-box-sec').removeClass('same-checked');
                $('input:checkbox[name=customer_id], .user_all_checkbox').prop('checked',false);
                
                $('#bulk_mail_pop').removeClass('sp-show');
                $('#bulk_mail_pop').removeClass('sp-mini');
                $('#bulk_mail_pop').removeClass('sp-full');  
                $("#bulk_email_to_email").val('');
                $("#bulk_email_subject").val('');
                // tinymce.get("bulk_email_body").setContent('');
           },
           complete: function(){ },
           error: function(response) {}
       })

    // $.ajax({
    //     url: base_url + "customer/set_all_unchecked",
    //     data: new FormData($('#customer_list_frm')[0]),
    //     cache: false,
    //     method: 'POST',
    //     dataType: "html",
    //     mimeType: "multipart/form-data",
    //     contentType: false,
    //     cache: false,
    //     processData: false,
    //     beforeSend: function(xhr) {
    //     },
    //     success: function(data) {
    //         result = $.parseJSON(data);
    //         $("#show_for_select").html('');
    //         $("#show_for_unselect").html('');
    //         $('#dropdownMenuUser').html('None');
    //         $('.dropdown_new .check-box-sec').removeClass('same-checked');
    //         $('input:checkbox[name=customer_id], .user_all_checkbox').prop('checked',false);
    //         // console.log(result.msg);          
    //     }
    // });
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
