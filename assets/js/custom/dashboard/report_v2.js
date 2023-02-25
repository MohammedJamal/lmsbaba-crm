$(document).ready(function(){

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
	$('.display_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5'
    });

	$('select.blue').niceSelect();
	$(".latest_lead_scroll").mCustomScrollbar({
		theme:"rounded-dots",
		scrollInertia:0,
		advanced:{ 
			autoScrollOnFocus: false, 
			updateOnContentResize: true 
		}
	});
  //latest_lead_scroll_two
  $(".latest_lead_scroll_two").mCustomScrollbar({
		theme:"rounded-dots",
		scrollInertia:0,
		advanced:{ 
			autoScrollOnFocus: false, 
			updateOnContentResize: true 
		}
	});

	// SHOW/HIDE USERS CHECK BOX
	$("body").on("click","#user_checked_close",function(e){
		//alert(123)
		$("#tree_div").slideToggle('fast');
		$(".tree_clickable").toggleClass("tree-down-arrow");
	});
	$("body").on("click","#manager_checked_close",function(e){
		//alert(123)
		$("#m_tree_div").slideToggle('fast');
		$(".manager_tree_clickable").toggleClass("m-tree-down-arrow");
	});
	// $("#select_scroller").mCustomScrollbar({
	// 	theme:"dark"
	// });
	//.nice-select .list

	if(window.innerWidth < 768){
		$("#report_one").mCustomScrollbar({
			axis:"x",
			theme:"dark",
			advanced:{autoExpandHorizontalScroll:true}
		});
		$("#report_two").mCustomScrollbar({
			axis:"x",
			theme:"dark",
			advanced:{autoExpandHorizontalScroll:true}
		});
	}else{
		// $("#report_one").mCustomScrollbar({
		// 	theme:"minimal-dark",
		// 	scrollInertia:0,
		// 	advanced:{ 
		// 		autoScrollOnFocus: false, 
		// 		updateOnContentResize: true,
		// 		updateOnSelectorChange: true
		// 	}
		// });
		// $("#report_two").mCustomScrollbar({
		// 	theme:"minimal-dark",
		// 	scrollInertia:0,
		// 	advanced:{ 
		// 		autoScrollOnFocus: false, 
		// 		updateOnContentResize: true,
		// 		updateOnSelectorChange: true
		// 	}
		// });
	}
	///////
	
	////////
	
	$("body").on("click",".view_lead_history",function(e){
		$("#dashboardDetailReport").css("display","none");
	});
	$('#lead_history_log_modal').on('hidden.bs.modal', function () {
		$("#dashboardDetailReport").css("display","block");
	})

	


	$("body").on("click","#download_leads_csv",function (e){        
        var base_URL     = $("#base_url").val();       
		var filter_selected_user_id=$("#csv_filter_selected_user_id").val();
		var report=$("#csv_report").val();
		var filter1=$("#csv_filter1").val();
		var filter2=$("#csv_filter2").val();
		var filter_date_range_pre_define=$("#csv_filter_date_range_pre_define").val();
		var filter_date_range_user_define_from=$("#csv_filter_date_range_user_define_from").val();
		var filter_date_range_user_define_to=$("#csv_filter_date_range_user_define_to").val();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&report="+report+"&filter1="+filter1+"&filter2="+filter2+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+filter_date_range_user_define_from+"&filter_date_range_user_define_to="+filter_date_range_user_define_to;
		// alert(data)
      	document.location.href = base_URL+'dashboard_v2/download_csv/?'+data;
    });


	// $("body").on("click",".rander_detail_popup",function(e){
	// 	var base_URL=$("#base_url").val();	
	// 	var filter_selected_user_id=$("#filter_selected_user_id").val();
	// 	var report=$(this).attr("data-report");
	// 	var filter1=$(this).attr("data-filter1");
	// 	var filter2=$(this).attr("data-filter2");
		
	// 	if(report=='daily_sales_report')
	// 	{
	// 		var filter_date_range_pre_define=$("#date_range_pre_define_dsr").val();
	// 		var date_range_user_define_from=$("#date_range_user_define_from_dsr").val();
	// 		var date_range_user_define_to=$("#date_range_user_define_to_dsr").val();
	// 	}
	// 	else if(report=='user_activity_report')
	// 	{
	// 		var filter_date_range_pre_define=$("#date_range_pre_define_uar_v2").val();
	// 		var date_range_user_define_from=$("#date_range_user_define_from_uar_v2").val();
	// 		var date_range_user_define_to=$("#date_range_user_define_to_uar_v2").val();
	// 	}
	// 	else if(report=='user_wise_sales_pipeline_report')
	// 	{
	// 		var filter_date_range_pre_define='';
	// 		var date_range_user_define_from='';
	// 		var date_range_user_define_to='';
	// 	}
	// 	else if(report=='lead_pipeline')
	// 	{
	// 		var filter_date_range_pre_define='';
	// 		var date_range_user_define_from='';
	// 		var date_range_user_define_to='';
	// 	}
	// 	else if(report=='top_performer_of_month')
	// 	{
	// 		var filter_date_range_pre_define='';
	// 		var date_range_user_define_from='';
	// 		var date_range_user_define_to='';
	// 	}
		

	// 	var data = "filter_selected_user_id="+filter_selected_user_id+"&report="+report+"&filter1="+filter1+"&filter2="+filter2+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to;
	// 	// alert(data)
	// 	$.ajax({
	// 		url: base_URL+"dashboard_v2/rander_detail_report_view_popup_ajax/",
	// 		data: data,
	// 		cache: false,
	// 		method: 'GET',
	// 		dataType: "JSON",
	// 		// dataType: "HTML",
	// 		beforeSend: function( xhr ) {   
	// 			$.blockUI({ 
	// 				message: 'Please wait...', 
	// 				css: { 
	// 						padding: '10px', 
	// 						backgroundColor: '#fff', 
	// 						border:'0px solid #000',
	// 						'-webkit-border-radius': '10px', 
	// 						'-moz-border-radius': '10px', 
	// 						opacity: .5, 
	// 						color: '#000',
	// 						width:'450px',
	// 						'font-size':'14px'
	// 				}
	// 			});
	// 		},
	// 		success:function(res){ 
	// 			// result = $.parseJSON(res);						
	// 			$('#dashboardDetailReport').html(res.html); 
	// 			$('#dashboardDetailReport').modal({
	// 					backdrop: 'static',
	// 					keyboard: false
	// 			});
	// 		},
	// 		complete: function(){
	// 			$.unblockUI();
	// 		},
	// 		error: function(response) {
	// 			//alert('Error'+response.table);
	// 		}
	// 	});
	// });

	$(document).on('click', '.dr_pagination_class', function (e) { 
		e.preventDefault();
		var str = $(this).attr('href'); 
		var res = str.split("/");
		var cur_page = res[1];
		$("#dr_page_number").val(cur_page);        
		rander_dr_list();
	});
	
	$("body").on("click",".rander_detail_popup",function(e){
		var base_URL=$("#base_url").val();	
		var page =1;
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var report=$(this).attr("data-report");
		var filter1=$(this).attr("data-filter1");
		var filter2=$(this).attr("data-filter2");
		
		if(report=='daily_sales_report')
		{
			var filter_date_range_pre_define=$("#date_range_pre_define_dsr").val();
			var date_range_user_define_from=$("#date_range_user_define_from_dsr").val();
			var date_range_user_define_to=$("#date_range_user_define_to_dsr").val();
		}
		else if(report=='user_activity_report')
		{
			var filter_date_range_pre_define=$("#date_range_pre_define_uar_v2").val();
			var date_range_user_define_from=$("#date_range_user_define_from_uar_v2").val();
			var date_range_user_define_to=$("#date_range_user_define_to_uar_v2").val();
		}
		else if(report=='lead_by_source_report')
		{
			var filter_date_range_pre_define=$("#date_range_pre_define_lbs").val();
			var date_range_user_define_from=$("#date_range_user_define_from_lbs").val();
			var date_range_user_define_to=$("#date_range_user_define_to_lbs").val();
		}
		else if(report=='user_wise_sales_pipeline_report')
		{
			var filter_date_range_pre_define='';
			var date_range_user_define_from='';
			var date_range_user_define_to='';
		}
		else if(report=='lead_pipeline_report')
		{
			var filter_date_range_pre_define='';
			var date_range_user_define_from='';
			var date_range_user_define_to='';
		}
		else if(report=='top_performer_of_month')
		{
			var filter_date_range_pre_define='';
			var date_range_user_define_from='';
			var date_range_user_define_to='';
		}
		else if(report=='this_month_report')
		{
			var filter_date_range_pre_define=$("#thisMonth_lrsc").val();
			var date_range_user_define_from='';
			var date_range_user_define_to='';
		}
		var data = "page="+page+"&filter_selected_user_id="+filter_selected_user_id+"&report="+report+"&filter1="+filter1+"&filter2="+filter2+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to;
		// alert(data);return false;
		$.ajax({
			url: base_URL+"dashboard_v2/rander_detail_report_view_popup_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "JSON",
			// dataType: "HTML",
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
			success:function(res){ 
				// result = $.parseJSON(res);						
				$('#dashboardDetailReport').html(res.html); 
				$('#dashboardDetailReport').modal({
						backdrop: 'static',
						keyboard: false
				});
				
			},
			complete: function(){
				$.unblockUI();
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		});
	});

	$("body").on("click","#latest_sales_orders_download_csv",function (e){        
        var base_URL     = $("#base_url").val();       
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var data = "filter_selected_user_id="+filter_selected_user_id;
		// alert(data)
      	document.location.href = base_URL+'dashboard_v2/latest_sales_orders_download_csv/?'+data;
    });


	// ======================================
	// DASHBOARD REPORT	
	load_dashboard_summery_count();	
	load_user_wise_pending_followup(); // POPUP AFTER PAGE LOAD
	// ================= START FOR V2 DASHBOARD ====================
	load_this_month();
	load_lead_pipeline();
	load_opportunity();
	/*load_financial_review();
	load_sales_orders();
	load_leads_opportunity();
	load_sales_pipeline(1);
	load_user_activity_report_v2(1);
	load_daily_sales_report_v2(1);
	load_lead_by_source_report();
	load_top_selling_produts();
	load_latest_sales_orders();
	load_leads_by_source();
	load_lead_lost_reasons();
	load_unfollowed_leads_by_users();
	load_top_performers_of_month();	*/
	// ================= END FOR V2 DASHBOARD ======================
	// $("#pending_followup_limit").val('');	
	// load_product_vs_leads();		
	// DASHBOARD REPORT
	// ======================================
	//load_user_wise_pending_followup();
	//load_business_report_weekly(1);
	//load_business_report_monthly();	
	$("body").on("click",".get_all_user_wise_pending_followup_popup",function(e){
		$("#pending_followup_limit").val('');
		load_user_wise_pending_followup();
		
	});

	$("body").on("click",".get_all_unfollowed_leads_by_user_popup",function(e){
		$("#unfollowed_leads_by_user_limit").val('');
		load_all_unfollowed_leads_by_user_popup();		
	});

	$("body").on("click",".get_all_product_vs_leads_popup",function(e){
		$("#product_vs_leads_limit").val('');
		load_all_product_vs_leads_popup();		
	});	

	$("body").on("click","#user_checked_reset",function(e){
		location.reload();
	});	
	$("body").on("click","#user_checked_submit",function(e){
		userArr=[];
		userNameArr=[];
		
		var selectedElms = $('#tree').jstree("get_selected", true);
		
		$.each(selectedElms, function() {
			userArr.push(this.id);
			userNameArr.push($("#"+this.id+"_anchor").attr('data-name'));
		});

		// $("input:checkbox[name=user]:checked").each(function(){
		// 	userArr.push($(this).val());
		// 	userNameArr.push($(this).attr('data-name'));
		// });
		if(userArr.length==0)
		{
			swal('Oops!','User not checked.','error');
			return false;
		}
		var userStr=userArr.join();
		var userNameStr=userNameArr.join(", ");
		$("#filter_selected_user_id").val(userStr);
		$("#select_div").slideToggle('fast');	
		$("#report_applied_for_div").html(userNameStr);
		load_dashboard_summery_count();	
		// ================= START FOR V2 DASHBOARD ====================
		load_this_month();
		load_lead_pipeline();
		load_opportunity();
		load_financial_review();
		load_sales_orders();
		load_leads_opportunity();
		load_sales_pipeline(1);
		load_user_activity_report_v2(1);
		load_daily_sales_report_v2(1);
		load_lead_by_source_report();
		load_top_selling_produts();
		load_latest_sales_orders();
		load_leads_by_source();
		load_lead_lost_reasons();
		load_unfollowed_leads_by_users();
		load_top_performers_of_month();
		// ================= END FOR V2 DASHBOARD ======================
		$("#tree_div").slideToggle('fast');
      	$(".tree_clickable").toggleClass("tree-down-arrow");
		// load_dashboard_summery_count();		
		// load_product_vs_leads();
	});

	$("body").on("click","#manager_checked_submit",function(e){

		$('#tree').jstree(true).check_all();
		userArr=[];
		userNameArr=[];
		
		var selectedElms = $('#manager_and_user_users').val();
		var temp_user=[];
		temp_user = selectedElms.split(",");
		
		for (let i = 0; i < temp_user.length; i++) {			
			var temp_user_arr=[];
			temp_user_arr = temp_user[i].split("~");
			userArr.push(temp_user_arr[0]);
			userNameArr.push(temp_user_arr[1]);
		}
		
		if(userArr.length==1)
		{
			swal('Oops!','Manager not checked.','error');
			return false;
		}
		var userStr=userArr.join();
		var userNameStr=userNameArr.join(", ");
		$("#filter_selected_user_id").val(userStr);
		$("#select_div").slideToggle('fast');	
		$("#report_applied_for_div").html(userNameStr);
		load_dashboard_summery_count();	
		// ================= START FOR V2 DASHBOARD ====================
		load_this_month();
		load_lead_pipeline();
		load_opportunity();
		load_financial_review();
		load_sales_orders();
		load_leads_opportunity();
		load_sales_pipeline(1);
		load_user_activity_report_v2(1);
		load_daily_sales_report_v2(1);
		load_lead_by_source_report();
		load_top_selling_produts();
		load_latest_sales_orders();
		load_leads_by_source();
		load_lead_lost_reasons();
		load_unfollowed_leads_by_users();
		load_top_performers_of_month();
		// ================= END FOR V2 DASHBOARD ======================
		$("#m_tree_div").slideToggle('fast');
      	$(".manager_tree_clickable").toggleClass("m-tree-down-arrow");		
	});
	/////////
	$(".user_all_checkbox").change(function () {
		$('.dropdown_new .check-box-sec').removeClass('same-checked');
		
		if($(this).prop("checked") == true){
			$('#dropdownMenuUser').html('All');
		}else{
			$('#dropdownMenuUser').html('None');
		}
    	$('input:checkbox[name=user]').prop('checked', $(this).prop("checked"));
	});
	$("body").on("click",".cAll",function(e){
		e.preventDefault();
		$('#dropdownMenuUser').html('All');
		$('input:checkbox[name=user], .user_all_checkbox').prop('checked',true);
	});
	$("body").on("click",".uAll",function(e){
		e.preventDefault();
		$('#dropdownMenuUser').html('None');
		$('input:checkbox[name=user], .user_all_checkbox').prop('checked',false);
	});
	$("input:checkbox[name=user]").change(function () {
    	if ($('input:checkbox[name=user]').not(':checked').length == 0) {
    		$('#dropdownMenuUser').html('None');
	        $('.user_all_checkbox').prop('checked',true);
	        $('.dropdown_new .check-box-sec').removeClass('same-checked');
	    } else {
	    	$('#dropdownMenuUser').html('All');
	        $('.user_all_checkbox').prop('checked',false);
	        $('.dropdown_new .check-box-sec').addClass('same-checked');
	    }
	});
	// $("body").on("click","#user_checked_reset",function(e){
	// 	e.preventDefault();
	// 	$('input:checkbox[name=user]').prop('checked',false);
	// 	userArr=[];
	// 	$("input:checkbox[name=user]:checked").each(function(){
	// 		userArr.push($(this).val());
	// 	});
	// 	var userStr=userArr.join();
	// 	$("#filter_selected_user_id").val(userStr);
	// 	$("#select_div").slideToggle('fast');	
	// 	load_dashboard_summery_count();
	
	
	// 	load_business_report_weekly(1);
	// 	load_business_report_monthly();			
	// });
	/////////

	$("body").on("change","#is_count_or_percentage",function(e){
		var tmp_val=($(this).is(':checked'))?'P':'C';
		$("#filter_summery_count_or_percentage").val(tmp_val);
		load_dashboard_summery_count();
	});	

	$("body").on("change","#business_report_weekly_period",function(e){
		var tmp_val=$(this).val();
		$("#filter_business_report_weekly_period").val(tmp_val);
		load_business_report_weekly(1);
	});

	$("body").on("change","#business_report_monthly_period",function(e){
		var tmp_val=$(this).val();
		$("#filter_business_report_monthly_period").val(tmp_val);
		load_business_report_monthly(1);
	});

	$("body").on("click",".go_list",function(e){
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var filter_by=$(this).attr("data-filter");
		var base_URL=$("#base_url").val();	
		var limit=$('#pending_followup_limit').val();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_by="+filter_by;
		//alert(data); return false;
		$.ajax({
				url: base_URL+"dashboard_v2/get_base64_encode_for_lead_list_filter/",
				data: data,
				cache: false,
				method: 'GET',
				dataType: "html",
				beforeSend: function( xhr ) {                
					//showContentLoader('#dashboard_summery_count_div', 'loading data...');
				},
				success:function(res){ 
					result = $.parseJSON(res);	
					// alert(result.url_str);
					window.location.href = base_URL+'lead/manage/?filter_like_dsc='+result.url_str+"&dsc_filter_txt="+filter_by;
				},
				complete: function(){
					//removeContentLoader('#dashboard_summery_count_div');
				},
				error: function(response) {
					//alert('Error'+response.table);
				}
			})
	});	

	// pagination page click
	$(document).on('click', '.v2_uar_report_page', function (e) { 
			e.preventDefault();
			var str = $(this).attr('href'); 
			var res = str.split("/");
			var cur_page = res[1];
			
			if(cur_page) {
				load_user_activity_report_v2(cur_page);
				//alert(cur_page);
			}
			else {
				load_user_activity_report_v2(1);
			}
	});

	

	$("body").on("click","#tsp_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();	
		var filter_date_range_pre_define=$("#date_range_pre_define_tsp").val();
		var filter_date_range_user_define_from=$("#date_range_user_define_from_tsp").val();
		var filter_date_range_user_define_to=$("#date_range_user_define_to_tsp").val();		
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+filter_date_range_user_define_from+"&filter_date_range_user_define_to="+filter_date_range_user_define_to;
	    document.location.href = base_URL+'/dashboard_v2/download_top_selling_products_csv/?'+data;
    });

	

	$("body").on("click","#lso_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();
	
		var data = "filter_selected_user_id="+filter_selected_user_id;
		
        document.location.href = base_URL+'/dashboard_v2/download_latest_sales_orders_csv/?'+data;
    });

	$("body").on("click","#v2_uar_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var show_daterange_html_uar_v2=$("#show_daterange_html_uar_v2").text();
		var filter_date_range_pre_define=$("#date_range_pre_define_uar_v2").val();
		var date_range_user_define_from=$("#date_range_user_define_from_uar_v2").val();
		var date_range_user_define_to=$("#date_range_user_define_to_uar_v2").val();
	
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&show_daterange_html_uar_v2="+show_daterange_html_uar_v2;
		
        document.location.href = base_URL+'/dashboard_v2/download_user_activity_report_v2_csv/?'+data;
    });

	$("body").on("click","#v2_dsr_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var show_daterange_html_dsr=$("#show_daterange_html_dsr").text();
		var filter_date_range_pre_define=$("#date_range_pre_define_dsr").val();
		var date_range_user_define_from=$("#date_range_user_define_from_dsr").val();
		var date_range_user_define_to=$("#date_range_user_define_to_dsr").val();
	
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&show_daterange_html_dsr="+show_daterange_html_dsr;
		
        document.location.href = base_URL+'/dashboard_v2/download_daily_sales_report_v2_csv/?'+data;
    });

    $('#c2c_modal').on('hide.bs.modal', function (e) {
        $('.mediPlayer').mediaPlayer("closeAllSounds");
    });

	$("body").on("change","#thisMonth_lrsc",function(e){
		$(this).attr("selected");
		load_this_month();
	});

	$("body").on("change","#top_performer_of_month",function(e){
		// $(this).attr("selected");		
		load_top_performers_of_month();
	});


	$("body").on("click","#fr_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();			
		var data = "filter_selected_user_id="+filter_selected_user_id;
	    document.location.href = base_URL+'dashboard_v2/financial_review_download_csv/?'+data;
    });

	$("body").on("click","#so_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();			
		var data = "filter_selected_user_id="+filter_selected_user_id;
	    document.location.href = base_URL+'dashboard_v2/get_sales_orders_download_csv/?'+data;
    });

	$("body").on("click","#lg_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();			
		var data = "filter_selected_user_id="+filter_selected_user_id;
	    document.location.href = base_URL+'dashboard_v2/get_leads_opportunity_download_csv/?'+data;
    });

	$("body").on("click","#sp_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var data = "filter_selected_user_id="+filter_selected_user_id;			
        document.location.href = base_URL+'/dashboard_v2/get_sales_pipeline_download_csv/?'+data;
		// document.location.href = base_URL+'/dashboard_v2/download_sales_pipeline_report_csv/?'+data;
		
    });

	$("body").on("click","#uar_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();	
		var filter_date_range_pre_define=$("#date_range_pre_define_uar_v2").val();
		var filter_date_range_user_define_from=$("#date_range_user_define_from_uar_v2").val();
		var filter_date_range_user_define_to=$("#date_range_user_define_to_uar_v2").val();		
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+filter_date_range_user_define_from+"&filter_date_range_user_define_to="+filter_date_range_user_define_to;
	    
		document.location.href = base_URL+'/dashboard_v2/get_user_activity_report_download_csv/?'+data;
    });

	$("body").on("click","#dsr_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();	
		var filter_date_range_pre_define=$("#date_range_pre_define_dsr").val();
		var filter_date_range_user_define_from=$("#date_range_user_define_from_dsr").val();
		var filter_date_range_user_define_to=$("#date_range_user_define_to_dsr").val();		
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+filter_date_range_user_define_from+"&filter_date_range_user_define_to="+filter_date_range_user_define_to;
	    
		document.location.href = base_URL+'/dashboard_v2/get_daily_sales_report_v2_download_csv/?'+data;
    });

	$("body").on("click","#lbs_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();	
		var filter_date_range_pre_define=$("#date_range_pre_define_lbs").val();
		var filter_date_range_user_define_from=$("#date_range_user_define_from_lbs").val();
		var filter_date_range_user_define_to=$("#date_range_user_define_to_lbs").val();		
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+filter_date_range_user_define_from+"&filter_date_range_user_define_to="+filter_date_range_user_define_to;
	    
		document.location.href = base_URL+'/dashboard_v2/get_lead_by_source_report_download_csv/?'+data;
    });

	$("body").on("click","#lbsGraph_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();			
		var data = "filter_selected_user_id="+filter_selected_user_id;		
	    document.location.href = base_URL+'dashboard_v2/get_leads_by_source_download_csv/?'+data;
    });

	$("body").on("click","#llrGraph_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();			
		var data = "filter_selected_user_id="+filter_selected_user_id;		
	    document.location.href = base_URL+'dashboard_v2/get_lead_lost_reasons_download_csv/?'+data;
    });

	$("body").on("click","#ulbuGraph_download",function (e){

		var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();			
		var data = "filter_selected_user_id="+filter_selected_user_id;		
	    document.location.href = base_URL+'dashboard_v2/get_unfollowed_leads_by_users_download_csv/?'+data;
    });

});

function rander_dr_list()
{
	var base_URL=$("#base_url").val();	
	var page =$("#dr_page_number").val();
	var filter_selected_user_id=$("#csv_filter_selected_user_id").val();
	var report=$("#csv_report").val();
	var filter1=$("#csv_filter1").val();
	var filter2=$("#csv_filter2").val();
	var filter_date_range_pre_define=$("#csv_filter_date_range_pre_define").val();
	var date_range_user_define_from=$("#csv_filter_date_range_user_define_from").val();
	var date_range_user_define_to=$("#csv_filter_date_range_user_define_to").val();
	
	var data = "page="+page+"&filter_selected_user_id="+filter_selected_user_id+"&report="+report+"&filter1="+filter1+"&filter2="+filter2+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to;
	// alert(data)
	$.ajax({
		url: base_URL+"dashboard_v2/rander_detail_report_list_view_popup_ajax/"+page,
		data: data,
		cache: false,
		method: 'GET',
		dataType: "JSON",
		// dataType: "HTML",
		beforeSend: function( xhr ) {   
			// $.blockUI({ 
			// 	message: 'Please wait...', 
			// 	css: { 
			// 			padding: '10px', 
			// 			backgroundColor: '#fff', 
			// 			border:'0px solid #000',
			// 			'-webkit-border-radius': '10px', 
			// 			'-moz-border-radius': '10px', 
			// 			opacity: .5, 
			// 			color: '#000',
			// 			width:'450px',
			// 			'font-size':'14px'
			// 	}
			// });
			addLoader('#dr_tcontent');
		},
		success:function(res){ 
			// result = $.parseJSON(res);						
			$('#dr_tcontent').html(res.html); 
			$("#dr_page").html(res.page);
			$("#dr_page_record_count_info").html(res.page_record_count_info);
			
		},
		complete: function(){
			// $.unblockUI();
			removeLoader();
		},
		error: function(response) {
			//alert('Error'+response.table);
		}
	});
}
function load_this_month() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_selected_year_month=$("#thisMonth_lrsc").find(":selected").val();
	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_selected_year_month="+filter_selected_year_month;
	// alert(data)
	$.ajax({
			url: base_URL+"dashboard_v2/get_this_month_data/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_this_month_data").html('')             
				showContentLoader('#show_this_month_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);
				$("#show_this_month_data").html(result.html);
			},
			complete: function(){
				removeContentLoader('#show_this_month_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_lead_pipeline() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id;
	//mCustomScrollbar
	if ($("#show_lead_pipeline_data").hasClass("mCustomScrollbar")) {
  		//alert('yes....');
  		$('.content#show_lead_pipeline_data').mCustomScrollbar('destroy');
	}
	$.ajax({
			url: base_URL+"dashboard_v2/get_lead_pipeline_data/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_lead_pipeline_data").html('')             
				showContentLoader('#show_lead_pipeline_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#show_lead_pipeline_data").html(result.html);
				updateAfterLoad();
			},
			complete: function(){
				removeContentLoader('#show_lead_pipeline_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_opportunity() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id;

	$.ajax({
			url: base_URL+"dashboard_v2/get_opportunity_data/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_opportunity_data").html('')             
				showContentLoader('#show_opportunity_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#show_opportunity_data").html(result.html);

				$( '.opportunity-block' ).each(function( index ) {
					var getV = $(this).find('.opportunity-process-bar').attr('data-content');
					//$(this).find('.opportunity-process-bar').css({'width':getV+'%'});
					$(this).find('.opportunity-process-bar').animate({
					   width: getV+'%'
					}, 1500, function() {
					   // Animation complete.
					});
				 });
			},
			complete: function(){
				removeContentLoader('#show_opportunity_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_financial_review() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id;
	$.ajax({
			url: base_URL+"dashboard_v2/get_financial_review/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_financial_review_data").html('')             
				showContentLoader('#show_financial_review_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);




				
				$("#show_financial_review_data").html(result.html);
				$('#one-chart-container').highcharts({
					chart: {
					   height: 250,
					   type: 'column',
					   backgroundColor: '#FFF'
					},
					title: {
					 text: 'Financial Review',
					 style: {  
					  color: '#000'
					 }
					},
					xAxis: {
					 tickWidth: 0,
					 labels: {
					  style: {
						color: '#000',
						}
					  },
					 categories: [
					   result.month_name_6.toUpperCase(), 
					   result.month_name_5.toUpperCase(),
					   result.month_name_4.toUpperCase(),
					   result.month_name_3.toUpperCase(),
					   result.month_name_2.toUpperCase(),
					   result.month_name_1.toUpperCase()
					 ]
					},
					yAxis: {
					 gridLineWidth: .5,
					 gridLineDashStyle: 'solid',
					 gridLineColor: 'white',
					 title: {
					   text: '',
					   style: {
						color: '#000'
						}
					},
					labels: {
					   formatter: function() {
						 return Highcharts.numberFormat(this.value, 0, '', ',');
					   },
					   style: {
						 color: '#000',
					   }
					 }
					},
					legend: {
					 enabled: false,
					},
					credits: {
					 enabled: false
		   
					},
					tooltip: {
					 valuePrefix: ''
					},
					colors: [
					'#1e45d7',
					'#1e45d7',
					'#1e45d7',
					'#1e45d7',
					'#158ef9', 
					'#AA4643', 
					'#89A54E', 
					'#80699B', 
					'#3D96AE', 
					'#DB843D', 
					'#92A8CD', 
					'#A47D7C', 
					'#B5CA92'
					],
					plotOptions: {
					 column: {
					   colorByPoint: true,
					   borderRadius: 0,
					   pointPadding: 0,
					   groupPadding: 0.05
					 } 
					},
					series: [{
					 name: 'Financial Review',
					 data: [
						Number(result.month_value_6), 
						Number(result.month_value_5), 
						Number(result.month_value_4), 
						Number(result.month_value_3), 
					    Number(result.month_value_2),
					    Number(result.month_value_1)
					 ]
					}]
				 });
			},
			complete: function(){
				removeContentLoader('#show_financial_review_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


function load_sales_orders() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id;
	$.ajax({
			url: base_URL+"dashboard_v2/get_sales_orders/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_sales_orders_data").html('')             
				showContentLoader('#show_sales_orders_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);
				
				$("#show_sales_orders_data").html(result.html);

				var charTwoHeight = $('.two-chart-height').innerHeight();
         		var containHeight = charTwoHeight - 78;
				 $('#two-chart-container').css({'height': containHeight});
				 //chartUpdateTwo(containHeight);

				var h=containHeight;

				 $('#two-chart-container').highcharts({
					chart: {
					   height: h,
					   type: 'column',
					   backgroundColor: '#FFF'
					},
					title: {
					 text: 'Sales Orders',
					 style: {  
					  color: '#79c551'
					 }
					},
					xAxis: {
					 tickWidth: 0,
					 labels: {
					  style: {
						color: '#79c551',
						}
					  },
					 categories: [
						result.month_name_6.toUpperCase(), 
						result.month_name_5.toUpperCase(),
						result.month_name_4.toUpperCase(),
						result.month_name_3.toUpperCase(),
						result.month_name_2.toUpperCase(),
						result.month_name_1.toUpperCase()
					 ]
					},
					yAxis: {
					 gridLineWidth: .5,
					 gridLineDashStyle: 'solid',
					 gridLineColor: '#000',
					 title: {
					   text: '',
					   style: {
						color: '#79c551'
						}
					},
					labels: {
					   formatter: function() {
						 return Highcharts.numberFormat(this.value, 0, '', ',');
					   },
					   style: {
						 color: '#79c551',
					   }
					 }
					},
					legend: {
					 enabled: false,
					},
					credits: {
					 enabled: false
					},
					tooltip: {
					 valuePrefix: ''
					},
					colors: [
					'#79c551',
					'#79c551',
					'#79c551',
					'#79c551',
					'#79c551', 
					'#79c551', 
					'#79c551', 
					'#79c551', 
					'#79c551', 
					'#79c551', 
					'#79c551', 
					'#79c551', 
					'#79c551'
					],
					plotOptions: {
					 column: {
					   colorByPoint: true,
					   borderRadius: 0,
					   pointPadding: 0,
					   groupPadding: 0.05
					 } 
					},
					series: [{
					 name: 'Sales Orders',
					 data: [
						Number(result.month_value_6), 
						Number(result.month_value_5), 
						Number(result.month_value_4), 
						Number(result.month_value_3), 
					    Number(result.month_value_2),
					    Number(result.month_value_1)
					 ]
					}]
				 });


				
			},
			complete: function(){
				removeContentLoader('#show_sales_orders_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_leads_opportunity() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id;
	$.ajax({
			url: base_URL+"dashboard_v2/get_leads_opportunity/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_leads_opportunity_data").html('')             
				showContentLoader('#show_leads_opportunity_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);
				
				$("#show_leads_opportunity_data").html(result.html);


				var charTwoHeight = $('.two-chart-height').innerHeight();
				var containHeight = charTwoHeight - 78;
				$('#two-chart-container').css({'height': containHeight});
				var charThreeHeight = $('.three-chart-height').innerHeight();
				var containHeightThree = charThreeHeight - 78;
				$('#three-chart-container').css({'height': containHeightThree});
				var h=containHeight;
			 
				$('#three-chart-container').highcharts({
				   chart: {
					  height: h,
					  type: 'column',
					  backgroundColor: '#FFF'
				   },
				   title: {
					text: 'Leads Opportunity',
					style: {  
					 color: '#c7ac4e'
					}
				   },
				   xAxis: {
					tickWidth: 0,
					labels: {
					 style: {
					   color: '#c7ac4e',
					   }
					 },
					categories: [
						result.month_name_6.toUpperCase(), 
						result.month_name_5.toUpperCase(),
						result.month_name_4.toUpperCase(),
						result.month_name_3.toUpperCase(),
						result.month_name_2.toUpperCase(),
						result.month_name_1.toUpperCase()
					]
				   },
				   yAxis: {
					gridLineWidth: .5,
					gridLineDashStyle: 'solid',
					gridLineColor: '#000',
					title: {
					  text: '',
					  style: {
					   color: '#c7ac4e'
					   }
				   },
				   labels: {
					  formatter: function() {
						return Highcharts.numberFormat(this.value, 0, '', ',');
					  },
					  style: {
						color: '#c7ac4e',
					  }
					}
				   },
				   legend: {
					enabled: false,
				   },
				   credits: {
					enabled: false
				   },
				   tooltip: {
					valuePrefix: ''
				   },
				   colors: [
				   '#c7ac4e'
				   ],
				   plotOptions: {
					column: {
					  colorByPoint: true,
					  borderRadius: 0,
					  pointPadding: 0,
					  groupPadding: 0.05
					} 
				   },
				   series: [{
					name: 'Leads Opportunity',
					data: [
						Number(result.month_value_6), 
						Number(result.month_value_5), 
						Number(result.month_value_4), 
						Number(result.month_value_3), 
					    Number(result.month_value_2),
					    Number(result.month_value_1)
					]
				   }]
				});
			 

				
			},
			complete: function(){
				removeContentLoader('#show_leads_opportunity_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_sales_pipeline(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id+"&page="+page;
	// alert(data)
	$.ajax({
			url: base_URL+"dashboard_v2/get_sales_pipeline/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_sales_pipeline_data").html('')             
				showContentLoader('#show_sales_pipeline_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);			
				$("#show_sales_pipeline_data").html(result.html);
				var getC = $("#get-sales-pipeline tbody > tr").size();
				var sHeight = 400;
				if(getC < 4){
					sHeight = (134*getC)+50;
				}
				$("#show_sales_pipeline_data .responsive-table").mCustomScrollbar({
	                axis:"yx",
	                setHeight: sHeight+'px',
	                setWidth: "100%"
	            });				
			},
			complete: function(){
				removeContentLoader('#show_sales_pipeline_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


function load_user_activity_report_v2(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_date_range_pre_define=$("#date_range_pre_define_uar_v2").val();
	var date_range_user_define_from=$("#date_range_user_define_from_uar_v2").val();
	var date_range_user_define_to=$("#date_range_user_define_to_uar_v2").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;
	// alert(data)
	$.ajax({
			url: base_URL+"dashboard_v2/get_user_activity_report/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_user_activity_report_data").html('')             
				showContentLoader('#show_user_activity_report_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);
				
				$("#show_user_activity_report_data").html(result.html);
				$("#v2_uar_page").html(result.page);
				$("#v2_uar_page_record_count_info").html(result.page_record_count_info);

				var getC = $("#show_user_activity_report_data .responsive-table tbody > tr").size();
				var sHeight = 400;
				if(getC < 4){
					sHeight = (134*getC)+50;
				}
				$("#show_user_activity_report_data .responsive-table").mCustomScrollbar({
                    axis:"yx",
                    setHeight: sHeight+'px',
                    setWidth: "100%"
                }); 
				
				
			},
			complete: function(){
				removeContentLoader('#show_user_activity_report_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_daily_sales_report_v2(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_date_range_pre_define=$("#date_range_pre_define_dsr").val();
	var date_range_user_define_from=$("#date_range_user_define_from_dsr").val();
	var date_range_user_define_to=$("#date_range_user_define_to_dsr").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;

	$.ajax({
			url: base_URL+"dashboard_v2/get_daily_sales_report_v2/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_daily_sales_report_data").html('')             
				showContentLoader('#show_daily_sales_report_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);
				
				$("#show_daily_sales_report_data").html(result.html);

				var getC = $("#show_daily_sales_report_data .responsive-table tbody > tr").size();
				var sHeight = 400;
				if(getC < 4){
					sHeight = (134*getC)+50;
				}

				$("#show_daily_sales_report_data .responsive-table").mCustomScrollbar({
                    axis:"yx",
                    setHeight: sHeight+'px',
                    setWidth: "100%"
                }); 
				
				
			},
			complete: function(){
				removeContentLoader('#show_daily_sales_report_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


function load_lead_by_source_report() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_date_range_pre_define=$("#date_range_pre_define_lbs").val();
	var date_range_user_define_from=$("#date_range_user_define_from_lbs").val();
	var date_range_user_define_to=$("#date_range_user_define_to_lbs").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to;
	
	$.ajax({
			url: base_URL+"dashboard_v2/get_lead_by_source_report/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_lead_by_source_report_data").html('')             
				showContentLoader('#show_lead_by_source_report_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);				
				$("#show_lead_by_source_report_data").html(result.html);
				var getC = $("#show_lead_by_source_report_data .responsive-table tbody > tr").size();
				var sHeight = 400;
				if(getC < 4){
					sHeight = (134*getC)+50;
				}

				$("#show_lead_by_source_report_data .responsive-table").mCustomScrollbar({
                    axis:"yx",
                    setHeight: sHeight+'px',
                    setWidth: "100%"
                }); 
				
				
			},
			complete: function(){
				removeContentLoader('#show_lead_by_source_report_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_top_selling_produts() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();

	var filter_date_range_pre_define=$("#date_range_pre_define_tsp").val();
	var filter_date_range_user_define_from=$("#date_range_user_define_from_tsp").val();
	var filter_date_range_user_define_to=$("#date_range_user_define_to_tsp").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+filter_date_range_pre_define+"&filter_date_range_user_define_from="+filter_date_range_user_define_from+"&filter_date_range_user_define_to="+filter_date_range_user_define_to;
	//alert(data)
	$.ajax({
			url: base_URL+"dashboard_v2/get_top_selling_produts/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_top_selling_produts").html('')             
				showContentLoader('#show_top_selling_produts', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);
				
				$("#show_top_selling_produts").html(result.html);
				update_top_selling_produts();
				
				
			},
			complete: function(){
				removeContentLoader('#show_top_selling_produts');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_latest_sales_orders() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id;
	$.ajax({
			url: base_URL+"dashboard_v2/get_latest_sales_orders/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_latest_sales_orders").html('')             
				showContentLoader('#show_latest_sales_orders', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);
				
				$("#show_latest_sales_orders").html(result.html);

				update_latest_sales_orders();
				
			},
			complete: function(){
				removeContentLoader('#show_latest_sales_orders');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_leads_by_source() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id;
	// alert(data)
	$.ajax({
			url: base_URL+"dashboard_v2/get_leads_by_source/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#lead_by_source_div").html('')             
				showContentLoader('#lead_by_source_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);				
				//$("#show_leads_by_source").html(result.html);
				$("#lead_by_source_div").html(result.html);
				// Highcharts.chart('leads_source_one', {
				// 	chart: {
				// 	  type: 'pie'
				// 	},
				// 	title: {
				// 	  text: 'Leads By Source'
				// 	},
				// 	series: [{
				// 	  data: [{
				// 		name: 'Chicken',
				// 		y: 40
				// 	  }, {
				// 		name: 'Vegetable',
				// 		y: 30
				// 	  }, {
				// 		name: 'Fish',
				// 		y: 20
				// 	  }, {
				// 		name: 'Steak',
				// 		y: 5
				// 	  }, {
				// 		name: 'Other',
				// 		y: 5
				// 	  }]
				// 	}]
				//   });
			},
			complete: function(){
				removeContentLoader('#lead_by_source_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_lead_lost_reasons() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();

	var data = "filter_selected_user_id="+filter_selected_user_id;

	$.ajax({
			url: base_URL+"dashboard_v2/get_lead_lost_reasons/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#lead_by_lost_reasons_div").html('')             
				showContentLoader('#lead_by_lost_reasons_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				$("#lead_by_lost_reasons_div").html(result.html);

				// Highcharts.chart('leads_source_two', {
				// 	chart: {
				// 	  type: 'pie'
				// 	},
				// 	title: {
				// 	  text: 'Lead Lost Reasons'
				// 	},
				// 	series: [{
				// 	  data: [{
				// 		name: 'Chicken',
				// 		y: 40
				// 	  }, {
				// 		name: 'Vegetable',
				// 		y: 30
				// 	  }, {
				// 		name: 'Fish',
				// 		y: 20
				// 	  }, {
				// 		name: 'Steak',
				// 		y: 5
				// 	  }, {
				// 		name: 'Other',
				// 		y: 5
				// 	  }]
				// 	}]
				//   });			

				
				
			},
			complete: function(){
				removeContentLoader('#lead_by_lost_reasons_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_unfollowed_leads_by_users() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();

	var data = "filter_selected_user_id="+filter_selected_user_id;

	$.ajax({
			url: base_URL+"dashboard_v2/get_unfollowed_leads_by_users/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#lead_by_unfollowed_div").html('')             
				showContentLoader('#lead_by_unfollowed_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				$("#lead_by_unfollowed_div").html(result.html);				
			},
			complete: function(){
				removeContentLoader('#lead_by_unfollowed_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_top_performers_of_month() 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_selected_year_month=$("#top_performer_of_month").find(":selected").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_selected_year_month="+filter_selected_year_month;
	// alert(data)
	$.ajax({
			url: base_URL+"dashboard_v2/get_top_performers_of_month/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {   
				$("#show_top_performers_of_month_data").html('')             
				showContentLoader('#show_top_performers_of_month_data', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				 //data_aval = JSON.parse(res);
				//alert(result.html);
				
				$("#show_top_performers_of_month_data").html(result.html);		
				
				updateAfterLoadTopPerformersMonth();
				
			},
			complete: function(){
				removeContentLoader('#show_top_performers_of_month_data');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}




function load_user_wise_pending_followup()
{	
	
	var base_URL=$("#base_url").val();	
	var limit=$('#pending_followup_limit').val();
	//var limit=10;
	var data = "limit="+limit;
	 //alert(data);
	$.ajax({
			url: base_URL+"dashboard_v2/get_user_wise_pending_followup/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {                
				//showContentLoader('#dashboard_summery_count_div', 'loading data...');
				
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#dashboard_pending_followup_body").html(result.html);
				$('#pendingFollowupPopModal').modal('show');
				$.unblockUI();						   
			},
			complete: function(){
				//removeContentLoader('#dashboard_summery_count_div');
				$("#pending_followup_limit").val('4');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		});
}

function load_dashboard_summery_count(count_or_percentage='P') 
{	
	var base_URL=$("#base_url").val();
	var filter_is_count_or_percentage=$("#filter_summery_count_or_percentage").val();
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	
	var data = "filter_is_count_or_percentage="+filter_is_count_or_percentage+"&filter_selected_user_id="+filter_selected_user_id;
	//alert(data); //return false;
	$.ajax({
			url: base_URL+"dashboard_v2/get_dashboard_summery_count/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			async: true,
			beforeSend: function( xhr ) {                
				showContentLoader('#dashboard_summery_count_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#dashboard_summery_count_div").html(result.html);		   
			},
			complete: function(){
				removeContentLoader('#dashboard_summery_count_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}







function load_all_unfollowed_leads_by_user_popup()
{	
	var base_URL=$("#base_url").val();	
	var limit=$('#unfollowed_leads_by_user_limit').val();
	var data = "limit="+limit;
	//alert(data);
	$.ajax({
			url: base_URL+"dashboard_v2/get_unfollowed_leads_by_user/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				//showContentLoader('#dashboard_summery_count_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#unfollowed_leads_by_user_body").html(result.html);
				$('#UnfollowedLeadsByUserPopModal').modal('show');						   
			},
			complete: function(){
				//removeContentLoader('#dashboard_summery_count_div');
				$("#unfollowed_leads_by_user_limit").val('4');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_product_vs_leads() 
{	
	var base_URL=$("#base_url").val();	
	var limit=$('#product_vs_leads_limit').val();
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id+"&limit="+limit;
	
	// alert(data); //return false;
	$.ajax({
			url: base_URL+"dashboard_v2/get_product_vs_leads/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {   
				$("#product_vs_leads_div").html('')             
				showContentLoader('#product_vs_leads_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#product_vs_leads_div").html(result.html);
			},
			complete: function(){
				removeContentLoader('#product_vs_leads_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_all_product_vs_leads_popup()
{	
	var base_URL=$("#base_url").val();	
	var limit=$('#product_vs_leads_limit').val();
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var data = "filter_selected_user_id="+filter_selected_user_id+"&limit="+limit;
	// alert(data);
	$.ajax({
			url: base_URL+"dashboard_v2/get_product_vs_leads/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				//showContentLoader('#dashboard_summery_count_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#product_vs_leads_body").html(result.html);
				$('#ProductVsLeadsPopModal').modal('show');						   
			},
			complete: function(){
				//removeContentLoader('#dashboard_summery_count_div');
				$("#product_vs_leads_limit").val('4');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


var example_table = 'no';






function load_business_report_weekly(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_business_report_weekly_period=$("#filter_business_report_weekly_period").val();	
	
	var data = "filter_selected_user_id="+filter_selected_user_id+"&page="+page+"&filter_business_report_weekly_period="+filter_business_report_weekly_period;
	//$('#report_one').mCustomScrollbar('destroy');
	//alert(data); return false;
	$.ajax({
			url: base_URL+"dashboard_v2/get_business_report_weekly_ajax/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#report_one', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);				
				$("#brw_tcontent").html(result.table);
				$("#brw_page").html(result.page);
				$("#brw_page_record_count_info").html(result.page_record_count_info);
				/////////////////////////
				var getH = $('#report_one').outerHeight(true);
				var tgetH = $('#report_one .table').outerHeight(true);
				//alert(getH+', '+tgetH)
				if(window.innerWidth < 768){
					$("#report_one").mCustomScrollbar({
						axis:"x",
						theme:"dark",
						advanced:{autoExpandHorizontalScroll:true}
					});
					
				}else{
					if(tgetH > getH ){
						// $("#report_one").mCustomScrollbar({
						// 	theme:"minimal-dark",
						// 	scrollInertia:0,
						// 	advanced:{ 
						// 		autoScrollOnFocus: false, 
						// 		updateOnContentResize: true,
						// 		updateOnSelectorChange: true
						// 	}
						// });
					}
					
				}
				/////////////////////////
				$('#brw_tcontent .revenue_btn').click(function(event){
					event.preventDefault();
					///////////////////////////////
					//$('#brw_tcontent').find('.hide_details').css({'display':'none'});
					//$('#brw_tcontent').find('.active').removeClass('active')
					//////////////////////////////
					var getid = $(this).attr('href');
					$(this).toggleClass('active');
					$('#'+getid).fadeToggle();
				});
				/////////////////////////
			},
			complete: function(){
				removeContentLoader('#report_one');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


function load_business_report_monthly(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_business_report_monthly_period=$("#filter_business_report_monthly_period").val();
	
	var data = "filter_selected_user_id="+filter_selected_user_id+"&page="+page+"&filter_business_report_monthly_period="+filter_business_report_monthly_period;
	//alert(data); return false;
	$.ajax({
			url: base_URL+"dashboard_v2/get_business_report_monthly_ajax/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#report_two', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				
				$("#brm_tcontent").html(result.table);
				$("#brm_page").html(result.page);
				$("#brm_page_record_count_info").html(result.page_record_count_info);
				////////////////////////////////
				//$('#report_two').mCustomScrollbar('update');
				$('#brm_tcontent .revenue_btn').click(function(event){
					event.preventDefault();
					var getid = $(this).attr('href');
					$(this).toggleClass('active');
					$('#'+getid).fadeToggle();
				});
				////////////////////////////////
			},
			complete: function(){
				removeContentLoader('#report_two');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}



showContentLoader = function(getEle, getTxt)
{
    getTxt = getTxt || 'loading...';
    var getTarget = $(getEle);   
    //getTarget.css({'min-height': '200px'}) 
    var cLoader = '<div class="loading_bg">';
    // cLoader += '<div class="loading_wrapper">';
    // cLoader += '<div class="circle"></div>';
    // cLoader += '<div class="circle"></div>';
    // cLoader += '<div class="circle"></div>';
    // cLoader += '<div class="shadow"></div>';
    // cLoader += '<div class="shadow"></div>';
    // cLoader += '<div class="shadow"></div>';
    // cLoader += '</div>';
    cLoader += '<span>'+getTxt+'</span>';
    cLoader += '</div>';
    getTarget.css({'position': 'relative'}).append(cLoader);
}
removeContentLoader = function(getEle)
{
    var getTarget = $(getEle);    
    getTarget.removeAttr('style')
    getTarget.find('.loading_bg').fadeOut( 'fast', function() {
        getTarget.find('.loading_bg').remove('.loading_bg');
    });
}






