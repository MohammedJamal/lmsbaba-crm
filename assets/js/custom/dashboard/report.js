$(document).ready(function(){

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
	$("body").on("click",".tree_clickable",function(e){
		$("#select_div").slideToggle('fast');
		$(this).toggleClass("tree-down-arrow");
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
	
	// ======================================
	// DASHBOARD REPORT

	
	load_dashboard_summery_count();
	load_latest_lead();		
	load_user_wise_pending_followup(); // POPUP AFTER PAGE LOAD
	load_pending_followup_lead();
	load_lead_source_vs_quality_report(1);
	load_lead_by_source_report(1);
	load_lead_lost_reason_vs_lead_source_report(1);
	load_leads_vs_orders_report(1);
	load_unfollowed_leads_by_user();
	load_user_activity_report(1);
	load_lead_source_vs_conversion_report(1);

	if($("#c2c_is_active").val()=='Y'){
		load_lead_c2c_report();
	}
	
	load_daily_sales_report(1);


	// $("#pending_followup_limit").val('');	
	// load_product_vs_leads();	
	// load_lead_source_vs_quality_report(1);	
	// DASHBOARD REPORT
	// ======================================
	

	//load_user_wise_pending_followup();
	//load_business_report_weekly(1);
	//load_business_report_monthly();
	
	$("body").on("click",".get_all_user_wise_pending_followup_popup",function(e){
		$("#pending_followup_limit").val('');
		load_user_wise_pending_followup();
		//load_pending_followup_lead();
	});

	$("body").on("click",".get_all_unfollowed_leads_by_user_popup",function(e){
		$("#unfollowed_leads_by_user_limit").val('');
		load_all_unfollowed_leads_by_user_popup();		
	});

	$("body").on("click",".get_all_product_vs_leads_popup",function(e){
		$("#product_vs_leads_limit").val('');
		load_all_product_vs_leads_popup();		
	});

	// if($("input:checkbox[name=user]:checked").length)
	// {
	// 	userNameArr=[];
	// 	$("input:checkbox[name=user]:checked").each(function(){		
	// 		userNameArr.push($(this).attr('data-name'));
	// 	});
	// 	var userNameStr=userNameArr.join(", ");
	// 	$("#report_applied_for_div").html(userNameStr);
	// }
	
	
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
		load_latest_lead();		
		load_pending_followup_lead();
		load_lead_source_vs_quality_report(1);
		load_lead_by_source_report(1);
		load_lead_lost_reason_vs_lead_source_report(1);
		load_leads_vs_orders_report(1);
		load_unfollowed_leads_by_user();
		load_user_activity_report(1);
		load_lead_source_vs_conversion_report(1);
		if($("#c2c_is_active").val()=='Y'){
			load_lead_c2c_report();
		}		
		load_daily_sales_report(1);

		$("#tree_div").slideToggle('fast');
      	$(this).toggleClass("tree-down-arrow");

		// load_dashboard_summery_count();
		// load_latest_lead();
		// load_pending_followup_lead();
		// load_unfollowed_leads_by_user();
		// load_product_vs_leads();
		// load_user_activity_report(1);
		// load_lead_source_vs_conversion_report(1);
		// 
		// load_lead_lost_reason_vs_lead_source_report(1);
		// load_daily_sales_report(1);		
	});

	/*
	$("body").on("click","#date_range_submit",function(e){
		var date_range_pre_define=$("#date_range_pre_define").val();
		var date_range_user_define_from=$("#date_range_user_define_from").val();
		var date_range_user_define_to=$("#date_range_user_define_to").val();
		var date_range_pre_define_text=$('#date_range_pre_define option:selected').text();		
		var date_range_user_define_text=date_range_user_define_from+' to '+date_range_user_define_to;
		var flag=0;		
		var date_range_show_div_str='';
		if((date_range_pre_define!="" && (date_range_user_define_from=="" && date_range_user_define_to=="")) || ((date_range_user_define_from!="" && date_range_user_define_to!="") && date_range_pre_define==""))
		{
			if(date_range_pre_define!="" && (date_range_user_define_from=="" && date_range_user_define_to==""))
			{	
				date_range_show_div_str=date_range_pre_define_text;
			}
			else
			{
				date_range_show_div_str=date_range_user_define_text;
			}			
		}
		else
		{
			flag=1;
		}		

		if(flag==1)
		{
			alert("At a time only one request will be processed.")
		}

		if(flag==0)
		{
			$("#date_range_show_div").html(date_range_show_div_str);
			load_user_activity_report(1);
			load_lead_source_vs_conversion_report(1);
			load_lead_source_vs_quality_report(1);
			load_lead_lost_reason_vs_lead_source_report(1);
			load_daily_sales_report(1);
			
		}
		
	});


	$("body").on("change","#date_range_pre_define",function(e){
		// var time_period=$(this).val();
		// var time_period_text=$('#date_range_pre_define option:selected').text();
		// $("#date_range_show_div").html(time_period_text);
		// $("#date_range_user_define_from").val();
		// $("#date_range_user_define_to").val();
		$('#date_range_user_define_from, #date_range_user_define_to').val("").datepicker("update");
		//$('#date_range_user_define_to').val("").datepicker("update");
	});
	
	$("body").on("change","#date_range_user_define_from",function(e){		
		$("#date_range_pre_define").val($("#date_range_pre_define option:first").val());
	});

	$("body").on("change","#date_range_user_define_to",function(e){		
		$("#date_range_pre_define").val($("#date_range_pre_define option:first").val());
	});
	*/
	$("body").on("change","#source_for_lost_reason",function(e){		
		load_lead_lost_reason_vs_lead_source_report(1);
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
	// 	load_latest_lead();
	// 	load_pending_followup_lead();
	// 	load_daily_sales_report(1);
	// 	load_business_report_weekly(1);
	// 	load_business_report_monthly();			
	// });
	/////////

	$("body").on("change","#is_count_or_percentage",function(e){
		var tmp_val=($(this).is(':checked'))?'P':'C';
		$("#filter_summery_count_or_percentage").val(tmp_val);
		load_dashboard_summery_count();
	});

	$("body").on("change","#daily_sales_report_group_by",function(e){
		var tmp_val=$(this).val();
		$("#filter_daily_sales_report_group_by").val(tmp_val);
		if(tmp_val=='D'){
			$("#sales_report_type").text('Daily');
		}
		else if(tmp_val=='W'){
			$("#sales_report_type").text('Weekly');
		}
		else if(tmp_val=='M'){
			$("#sales_report_type").text('Monthly');
		}
		load_daily_sales_report(1);
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
	


	// pagination page click
	$(document).on('click', '.sales_report_page', function (e) { 
			e.preventDefault();
			var str = $(this).attr('href'); 
			var res = str.split("/");
			var cur_page = res[1];
			
			if(cur_page) {
				load_daily_sales_report(cur_page);
			}
			else {
				load_daily_sales_report(1);
			}
	});

	$("body").on("click",".go_list",function(e){
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var filter_by=$(this).attr("data-filter");
		var base_URL=$("#base_url").val();	
		var limit=$('#pending_followup_limit').val();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_by="+filter_by;
		//alert(data); return false;
		$.ajax({
				url: base_URL+"dashboard/get_base64_encode_for_lead_list_filter/",
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

	$(document).on('click', '.svc_report_page', function (e) { 
			e.preventDefault();
			var str = $(this).attr('href'); 
			var res = str.split("/");
			var cur_page = res[1];
			
			if(cur_page) {
				load_lead_source_vs_conversion_report(cur_page);
			}
			else {
				load_lead_source_vs_conversion_report(1);
			}
	});
	

	// pagination page click
	$(document).on('click', '.uar_report_page', function (e) { 
			e.preventDefault();
			var str = $(this).attr('href'); 
			var res = str.split("/");
			var cur_page = res[1];
			
			if(cur_page) {
				load_user_activity_report(cur_page);
			}
			else {
				load_user_activity_report(1);
			}
	});

	$("body").on("click","#uar_download",function (e){
        
        
        var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
		
		var date_range_pre_define=$("#date_range_pre_define_uar").val();
		var date_range_user_define_from=$("#date_range_user_define_from_uar").val();
		var date_range_user_define_to=$("#date_range_user_define_to_uar").val();

		var show_daterange_html_uar=$("#show_daterange_html_uar").text();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&show_daterange_html_uar="+show_daterange_html_uar;
		
        document.location.href = base_URL+'/dashboard/download_user_activity_report_csv/?'+data;
    });

    $("body").on("click","#lsvc_download",function (e){
        
        
        var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
		
		var date_range_pre_define=$("#date_range_pre_define_svc").val();
		var date_range_user_define_from=$("#date_range_user_define_from_svc").val();
		var date_range_user_define_to=$("#date_range_user_define_to_svc").val();
		var show_daterange_html_svc=$("#show_daterange_html_svc").text();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&show_daterange_html_svc="+show_daterange_html_svc;
		
	
        document.location.href = base_URL+'/dashboard/download_lead_source_vs_conversion_report_csv/?'+data;
    });

    $("body").on("click","#dsr_download",function (e){       
        
        var base_URL=$("#base_url").val();	
		var filter_selected_user_id=$("#filter_selected_user_id").val();
		var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
		var date_range_pre_define=$("#date_range_pre_define_sr").val();
		var date_range_user_define_from=$("#date_range_user_define_from_sr").val();
		var date_range_user_define_to=$("#date_range_user_define_to_sr").val();
		var show_daterange_html_sr=$("#show_daterange_html_sr").text();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_daily_sales_report_group_by="+filter_daily_sales_report_group_by+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&show_daterange_html_sr="+show_daterange_html_sr;
		
        document.location.href = base_URL+'/dashboard/download_get_daily_sales_report_csv/?'+data;
    });

    $("body").on("click",".c2c_user_wise_modal",function(e){
    	var base_URL=$("#base_url").val();		
    	var uid=$(this).attr('data-userid');
    	var filterType=$(this).attr('data-filter');
    	var filter_selected_user_id=$("#filter_selected_user_id").val();
		var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
		
		var date_range_pre_define=$("#date_range_pre_define_c2c").val();
		var date_range_user_define_from=$("#date_range_user_define_from_c2c").val();
		var date_range_user_define_to=$("#date_range_user_define_to_c2c").val();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&uid="+uid+"&filter_type="+filterType;;
		// alert(data);//return false;
		$.ajax({
				url: base_URL+"dashboard/get_user_wise_c2c_report_ajax/",
				data: data,
				cache: false,
				method: 'GET',
				dataType: "html",
				beforeSend: function( xhr ) {},
				success:function(res){ 
					result = $.parseJSON(res);						
					$("#c2c_modal_body").html(result.html);
					$('#c2c_modal').modal('show');	
					$('.mediPlayer').mediaPlayer();					   
				},
				complete: function(){},
				error: function(response) {
					//alert('Error'+response.table);
				}
			});
    });

    $("body").on("click",".c2c_date_wise_modal",function(e){
    	var base_URL=$("#base_url").val();		
    	var date=$(this).attr('data-date');
    	var filterType=$(this).attr('data-filter');
    	var filter_selected_user_id=$("#filter_selected_user_id").val();
		var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
		
		var date_range_pre_define=$("#date_range_pre_define_c2c").val();
		var date_range_user_define_from=$("#date_range_user_define_from_c2c").val();
		var date_range_user_define_to=$("#date_range_user_define_to_c2c").val();
		var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&date="+date+"&filter_type="+filterType;;
		// alert(data);
		// alert("Okkk");return false;
		$.ajax({
				url: base_URL+"dashboard/get_date_wise_c2c_report_ajax/",
				data: data,
				cache: false,
				method: 'GET',
				dataType: "html",
				beforeSend: function( xhr ) {},
				success:function(res){ 
					result = $.parseJSON(res);						
					$("#c2c_modal_body").html(result.html);
					$('#c2c_modal').modal('show');
					$('.mediPlayer').mediaPlayer();						   
				},
				complete: function(){},
				error: function(response) {
					//alert('Error'+response.table);
				}
			});
    });
    $('#c2c_modal').on('hide.bs.modal', function (e) {
        $('.mediPlayer').mediaPlayer("closeAllSounds");
    })

});

function load_user_wise_pending_followup()
{	
	var base_URL=$("#base_url").val();	
	var limit=$('#pending_followup_limit').val();
	var data = "limit="+limit;
	// alert(data);
	$.ajax({
			url: base_URL+"dashboard/get_user_wise_pending_followup/",
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
				$("#dashboard_pending_followup_body").html(result.html);
				$('#pendingFollowupPopModal').modal('show');						   
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
			url: base_URL+"dashboard/get_dashboard_summery_count/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
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
function load_latest_lead() 
{	

	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();	
	var data = "filter_selected_user_id="+filter_selected_user_id;
	//alert(data); //return false;
	
	$.ajax({
			url: base_URL+"dashboard/get_latest_lead/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) { 
				$("#latest_lead_div").html('');               
				showContentLoader('#latest_lead_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);				
				$("#latest_lead_div").html(result.html);

				
			},
			complete: function(){
				removeContentLoader('#latest_lead_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


function load_pending_followup_lead() 
{	
	var base_URL=$("#base_url").val();	
	var limit=$('#pending_followup_limit').val();
	var filter_selected_user_id=$("#filter_selected_user_id").val();	
	var data = "filter_selected_user_id="+filter_selected_user_id+"&limit="+limit;
	
	// alert(data); //return false;
	$.ajax({
			url: base_URL+"dashboard/get_pending_followup_lead/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {   
				$("#pending_followup_lead_div").html('')             
				showContentLoader('#pending_followup_lead_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#pending_followup_lead_div").html(result.html);
			},
			complete: function(){
				removeContentLoader('#pending_followup_lead_div');
				$("#pending_followup_limit").val('4');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_unfollowed_leads_by_user() 
{	
	var base_URL=$("#base_url").val();	
	var limit=$('#unfollowed_leads_by_user_limit').val();
	var filter_selected_user_id=$("#filter_selected_user_id").val();	
	//var data = "filter_selected_user_id="+filter_selected_user_id+"&limit="+limit;
	
	var date_range_pre_define=$("#date_range_pre_define_ul").val();
	var date_range_user_define_from=$("#date_range_user_define_from_ul").val();
	var date_range_user_define_to=$("#date_range_user_define_to_ul").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to;


	// alert(data); //return false;
	$.ajax({
			url: base_URL+"dashboard/get_unfollowed_leads_by_user/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {   
				$("#unfollowed_leads_by_user_div").html('')             
				showContentLoader('#unfollowed_leads_by_user_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				$("#unfollowed_leads_by_user_div").html(result.html);
			},
			complete: function(){
				removeContentLoader('#unfollowed_leads_by_user_div');
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
			url: base_URL+"dashboard/get_unfollowed_leads_by_user/",
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
			url: base_URL+"dashboard/get_product_vs_leads/",
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
			url: base_URL+"dashboard/get_product_vs_leads/",
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

function load_user_activity_report(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
	
	var date_range_pre_define=$("#date_range_pre_define_uar").val();
	var date_range_user_define_from=$("#date_range_user_define_from_uar").val();
	var date_range_user_define_to=$("#date_range_user_define_to_uar").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;
	// alert(data); //return false;
	// if(example_table != 'no'){
	// 	$('#example').DataTable().destroy();
	// }
	
	// $('#example').dataTable( {
	//  		"destroy": true
	// });
	
	$.ajax({
			url: base_URL+"dashboard/get_user_activity_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#uar_tcontent', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				
				$("#uar_tcontent").html(result.table);
				$("#uar_page").html(result.page);
				$("#uar_page_record_count_info").html(result.page_record_count_info);
				// example_table = 'yes';
				// $('#example').DataTable({
				//         "responsive": true,
				//         "bLengthMenu" : false,
				//         "bInfo":false,
				//         "lengthChange": false,
				//         "bPaginate": false,
				//         "sDom": 'lrtip',
				//         "bSort" : false,
				//         "fixedHeader": {
				// 	    "header": true,
				// 	    "headerOffset" : 54
				// 	 },
				//         columnDefs: [
				//            { width: '100px', targets: 0 }
				//         ],
				//         fixedColumns: false
				//      });				
			},
			complete: function(){
				removeContentLoader('#uar_tcontent');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_lead_source_vs_conversion_report(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
	
	var date_range_pre_define=$("#date_range_pre_define_svc").val();
	var date_range_user_define_from=$("#date_range_user_define_from_svc").val();
	var date_range_user_define_to=$("#date_range_user_define_to_svc").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;
	// alert(data); //return false;
	// if(example_table != 'no'){
	// 	$('#example').DataTable().destroy();
	// }
	
	// $('#example').dataTable( {
	//  		"destroy": true
	// });
	
	$.ajax({
			url: base_URL+"dashboard/get_lead_source_vs_conversion_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#svc_tcontent', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				
				$("#svc_tcontent").html(result.table);
				$("#svc_page").html(result.page);
				$("#svc_page_record_count_info").html(result.page_record_count_info);
				// example_table = 'yes';
				// $('#example').DataTable({
				//         "responsive": true,
				//         "bLengthMenu" : false,
				//         "bInfo":false,
				//         "lengthChange": false,
				//         "bPaginate": false,
				//         "sDom": 'lrtip',
				//         "bSort" : false,
				//         "fixedHeader": {
				// 	    "header": true,
				// 	    "headerOffset" : 54
				// 	 },
				//         columnDefs: [
				//            { width: '100px', targets: 0 }
				//         ],
				//         fixedColumns: false
				//      });				
			},
			complete: function(){
				removeContentLoader('#svc_tcontent');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}

function load_lead_source_vs_quality_report(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
	
	var date_range_pre_define=$("#date_range_pre_define_svq").val();
	var date_range_user_define_from=$("#date_range_user_define_from_svq").val();
	var date_range_user_define_to=$("#date_range_user_define_to_svq").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;
	// alert(data); //return false;
	// if(example_table != 'no'){
	// 	$('#example').DataTable().destroy();
	// }
	
	// $('#example').dataTable( {
	//  		"destroy": true
	// });
	
	$.ajax({
			url: base_URL+"dashboard/get_lead_source_vs_quality_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#svq_tcontent', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				
				$("#svq_tcontent").html(result.table);
				//$("#svq_page").html(result.page);
				//("#svq_page_record_count_info").html(result.page_record_count_info);
				// example_table = 'yes';
				// $('#example').DataTable({
				//         "responsive": true,
				//         "bLengthMenu" : false,
				//         "bInfo":false,
				//         "lengthChange": false,
				//         "bPaginate": false,
				//         "sDom": 'lrtip',
				//         "bSort" : false,
				//         "fixedHeader": {
				// 	    "header": true,
				// 	    "headerOffset" : 54
				// 	 },
				//         columnDefs: [
				//            { width: '100px', targets: 0 }
				//         ],
				//         fixedColumns: false
				//      });				
			},
			complete: function(){
				removeContentLoader('#svq_tcontent');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}
function load_lead_by_source_report(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
	
	var date_range_pre_define=$("#date_range_pre_define_lbs").val();
	var date_range_user_define_from=$("#date_range_user_define_from_lbs").val();
	var date_range_user_define_to=$("#date_range_user_define_to_lbs").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;
	// alert(data); //return false;
	// if(example_table != 'no'){
	// 	$('#example').DataTable().destroy();
	// }
	
	// $('#example').dataTable( {
	//  		"destroy": true
	// });
	
	$.ajax({
			url: base_URL+"dashboard/get_lead_by_source_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#lead_by_source_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				
				$("#lead_by_source_div").html(result.table);
				//$("#svq_page").html(result.page);
				//("#svq_page_record_count_info").html(result.page_record_count_info);
				// example_table = 'yes';
				// $('#example').DataTable({
				//         "responsive": true,
				//         "bLengthMenu" : false,
				//         "bInfo":false,
				//         "lengthChange": false,
				//         "bPaginate": false,
				//         "sDom": 'lrtip',
				//         "bSort" : false,
				//         "fixedHeader": {
				// 	    "header": true,
				// 	    "headerOffset" : 54
				// 	 },
				//         columnDefs: [
				//            { width: '100px', targets: 0 }
				//         ],
				//         fixedColumns: false
				//      });				
			},
			complete: function(){
				removeContentLoader('#lead_by_source_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}
function load_lead_lost_reason_vs_lead_source_report(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
	
	var date_range_pre_define=$("#date_range_pre_define_rvs").val();
	var date_range_user_define_from=$("#date_range_user_define_from_rvs").val();
	var date_range_user_define_to=$("#date_range_user_define_to_rvs").val();

	var source_id=$('#source_for_lost_reason option:selected').val();		
	//alert(source_id)
	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&filter_source_id="+source_id+"&page="+page;
	// alert(data); //return false;
	// if(example_table != 'no'){
	// 	$('#example').DataTable().destroy();
	// }
	
	// $('#example').dataTable( {
	//  		"destroy": true
	// });
	
	$.ajax({
			url: base_URL+"dashboard/get_lead_lost_reason_vs_lead_source_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#rvs_tcontent', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);
				
				$("#rvs_tcontent").html(result.table);
				//$("#svq_page").html(result.page);
				//("#svq_page_record_count_info").html(result.page_record_count_info);
				// example_table = 'yes';
				// $('#example').DataTable({
				//         "responsive": true,
				//         "bLengthMenu" : false,
				//         "bInfo":false,
				//         "lengthChange": false,
				//         "bPaginate": false,
				//         "sDom": 'lrtip',
				//         "bSort" : false,
				//         "fixedHeader": {
				// 	    "header": true,
				// 	    "headerOffset" : 54
				// 	 },
				//         columnDefs: [
				//            { width: '100px', targets: 0 }
				//         ],
				//         fixedColumns: false
				//      });				
			},
			complete: function(){
				removeContentLoader('#rvs_tcontent');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}
function load_leads_vs_orders_report(page=1) 
{	
	var base_URL=$("#base_url").val();
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_leads_vs_orders_report_group_by").val();	
	var date_range_pre_define=$("#date_range_pre_define_lvo").val();
	var date_range_user_define_from=$("#date_range_user_define_from_lvo").val();
	var date_range_user_define_to=$("#date_range_user_define_to_lvo").val();
	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_daily_sales_report_group_by="+filter_daily_sales_report_group_by+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;

	// alert(data); //return false;
	// if(example_table != 'no'){
	// 	$('#example').DataTable().destroy();
	// }
	
	// $('#example').dataTable( {
	//  		"destroy": true
	// });	
	$.ajax({
			url: base_URL+"dashboard/get_lead_vs_orders_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#lead_vs_orders_div', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);				
				$("#lead_vs_orders_div").html(result.table);
				//$("#svq_page").html(result.page);
				//("#svq_page_record_count_info").html(result.page_record_count_info);
				// example_table = 'yes';
				// $('#example').DataTable({
				//         "responsive": true,
				//         "bLengthMenu" : false,
				//         "bInfo":false,
				//         "lengthChange": false,
				//         "bPaginate": false,
				//         "sDom": 'lrtip',
				//         "bSort" : false,
				//         "fixedHeader": {
				// 	    "header": true,
				// 	    "headerOffset" : 54
				// 	 },
				//         columnDefs: [
				//            { width: '100px', targets: 0 }
				//         ],
				//         fixedColumns: false
				//      });				
			},
			complete: function(){
				removeContentLoader('#lead_vs_orders_div');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


function load_daily_sales_report(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
	var date_range_pre_define=$("#date_range_pre_define_sr").val();
	var date_range_user_define_from=$("#date_range_user_define_from_sr").val();
	var date_range_user_define_to=$("#date_range_user_define_to_sr").val();
	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_daily_sales_report_group_by="+filter_daily_sales_report_group_by+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;
	// alert(data); //return false;	
	$.ajax({
			url: base_URL+"dashboard/get_daily_sales_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#sr_tcontent', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);		
				$("#sr_tcontent").html(result.table);
				$("#sr_page").html(result.page);
				$("#sr_page_record_count_info").html(result.page_record_count_info);
								
			},
			complete: function(){
				removeContentLoader('#sr_tcontent');
			},
			error: function(response) {
				//alert('Error'+response.table);
			}
		})
}


function load_business_report_weekly(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_business_report_weekly_period=$("#filter_business_report_weekly_period").val();	
	
	var data = "filter_selected_user_id="+filter_selected_user_id+"&page="+page+"&filter_business_report_weekly_period="+filter_business_report_weekly_period;
	//$('#report_one').mCustomScrollbar('destroy');
	//alert(data); return false;
	$.ajax({
			url: base_URL+"dashboard/get_business_report_weekly_ajax/",
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
			url: base_URL+"dashboard/get_business_report_monthly_ajax/",
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

function load_lead_c2c_report(page=1) 
{	
	var base_URL=$("#base_url").val();	
	var filter_selected_user_id=$("#filter_selected_user_id").val();
	var filter_daily_sales_report_group_by=$("#filter_daily_sales_report_group_by").val();	
	
	var date_range_pre_define=$("#date_range_pre_define_c2c").val();
	var date_range_user_define_from=$("#date_range_user_define_from_c2c").val();
	var date_range_user_define_to=$("#date_range_user_define_to_c2c").val();

	var data = "filter_selected_user_id="+filter_selected_user_id+"&filter_date_range_pre_define="+date_range_pre_define+"&filter_date_range_user_define_from="+date_range_user_define_from+"&filter_date_range_user_define_to="+date_range_user_define_to+"&page="+page;
	// alert(data); //return false;
	// if(example_table != 'no'){
	// 	$('#example').DataTable().destroy();
	// }
	
	// $('#example').dataTable( {
	//  		"destroy": true
	// });
	
	$.ajax({
			url: base_URL+"dashboard/get_lead_c2c_report_ajax/"+page,
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {                
				showContentLoader('#c2c_r1_tcontent', 'loading data...');
				showContentLoader('#c2c_r2_tcontent', 'loading data...');
			},
			success:function(res){ 
				result = $.parseJSON(res);	
				//alert(result.html);				
				$("#c2c_r1_tcontent").html(result.table1);
				$("#c2c_r2_tcontent").html(result.table2);
				// $("#svc_page").html(result.page);
				// $("#svc_page_record_count_info").html(result.page_record_count_info);
							
			},
			complete: function(){
				removeContentLoader('#c2c_r1_tcontent');
				removeContentLoader('#c2c_r2_tcontent');
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






