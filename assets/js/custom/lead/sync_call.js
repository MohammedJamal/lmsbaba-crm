$(document).ready(function(){    
	load_sync_call();

    $("body").on("click","#load_sync_call",function(e){
        load_sync_call();
    });
    $("body").on("click",".sort_order_sync_call",function(e){
        var tmp_field=$(this).attr('data-field');
        var curr_orderby=$(this).attr('data-orderby');
        var new_orderby=(curr_orderby=='asc')?'desc':'asc';
        $(this).attr('data-orderby',new_orderby);
        $(".sort_order").removeClass('asc');
        $(".sort_order").removeClass('desc');
        $(this).addClass(curr_orderby);
        $("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
        load_sync_call();
        // alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });
	$(document).on('click', '.myclass_sync_call', function (e) { 
		e.preventDefault();
		// closeExpendTable();
		var vt=($(this).attr('data-viewtype')=='grid')?'grid':'list';
		var str = $(this).attr('href'); 
		var res = str.split("/");
		var cur_page = res[1];
		$("#page_number").val(cur_page);
		$("#is_scroll_to_top").val('Y'); 
        
		if(cur_page) {              
		load_sync_call(cur_page);
		}
		else {
		load_sync_call(1);
		}
	});
	

    $("body").on("click",".delete_row",function(event){
        event.preventDefault();
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');
        var data="id="+id;
        swal({
            title: '',
            text: 'Are you sure to delete the record?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes'
        }, function() { 

            $.ajax({
                url: base_url + "lead/delete_row_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function( xhr ) {                
                    addLoader('#listing_view');
                },
                complete: function(){
                    removeLoader();
                },
                success: function(res) {
                    result = $.parseJSON(res);
                    if(result.status=='success')
                    {
                        load_sync_call();                        
                    }
                    else
                    {
                        swal('Oops',result.msg,'error');
                    }
                },
                error: function(response) {}
            });
        });  
        
    });
    $("body").on("click",".add_as_lead_single",function(e){
        e.preventDefault();        
        $('#rander_call_history_popup_action_modal').modal('hide');
        var id=$(this).attr('data-id');
         
        // --------------------------------------
        var is_search_box_show='N';         
        var mobile=$(this).attr("data-cust_mobile"); 
        var email=$(this).attr("data-cust_email"); 
        var cid=$(this).attr('data-cust_id');        
        var is_customer_basic_data_show='N';
        setTimeout(function(){ 
            rander_add_new_lead_view('',is_search_box_show,mobile,email,cid,is_customer_basic_data_show);
        }, 700);
        // ---------------------------------------
    });

    $("body").on("click",".add_as_lead_multiple",function(e){
        
        // CLOSE OTHER DIV
        $("#lead_for_call_update").prop("selectedIndex", 0).val();
        $("#call_history_update_lead_div").slideUp(500);

        // CLOSE THE OTHER DIV
        $("#status_wise_msg").val('');
        $("#add_as_other_business_div").slideUp(500);

        $("#call_history_add_lead_multiple_div").slideDown(500);
    });

    $("body").on("click","#add_as_lead_multiple_close",function(e){
        
        $("#cust_id_for_add_new_lead").prop("selectedIndex", 0).val();
        $("#call_history_add_lead_multiple_div").slideUp(500);

    });

    $("body").on("click","#add_as_lead_multiple_submit",function(e){
        e.preventDefault();   

        var id=$(this).attr('data-id');
        var cust_id_for_add_new_lead=$("#cust_id_for_add_new_lead").val();
        if(cust_id_for_add_new_lead==''){
            swal('Oops!','Select any customer to add a new lead','error');
            return false;
        }

        $('#rander_call_history_popup_action_modal').modal('hide');
                 
        // --------------------------------------
        var is_search_box_show='N';         
        var mobile=$("#cust_id_for_add_new_lead").find(':selected').attr('data-mobile'); 
        var email=$("#cust_id_for_add_new_lead").find(':selected').attr('data-email'); 
        var cid=cust_id_for_add_new_lead;        
        var is_customer_basic_data_show='N';
        setTimeout(function(){ 
            rander_add_new_lead_view('',is_search_box_show,mobile,email,cid,is_customer_basic_data_show);
        }, 700);
        // ---------------------------------------
    });




    $("body").on("click",".add_as_lead",function(e){
        e.preventDefault();
        var base_url = $("#base_url").val(); 
        $('#rander_call_history_popup_action_modal').modal('hide');
        var id=$(this).attr('data-id');
        
        setTimeout(function(){ 
            rander_add_new_lead_view(id);
        }, 700);
        /*var data="id="+id;
        $.ajax({
                url: base_url + "lead/add_row_as_lead_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function(xhr) {            
                },
                complete: function (){                    
                },
                success: function(res) {
                    result = $.parseJSON(res);
                    if(result.status=='success')
                    {
                        rander_add_new_lead_view(id);                  
                    }
                    else
                    {
                        swal('Oops',result.msg,'error');
                    }
                },
                error: function(response) {}
        });*/
    });

    $("body").on("click",".add_as_other_business",function(e){
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');

        // CLOSE OTHER DIV
        $("#lead_for_call_update").prop("selectedIndex", 0).val();
        $("#call_history_update_lead_div").slideUp(500);
        // CLOSE OTHER DIV
        $("#cust_id_for_add_new_lead").prop("selectedIndex", 0).val();
        $("#call_history_add_lead_multiple_div").slideUp(500);

        $("#add_as_other_business_confirm_submit").attr("data-id",id);
        $("#add_as_other_business_div").slideDown(500);
    });

    $("body").on("click","#add_as_other_business_confirm_close",function(e){
        $("#status_wise_msg").val('');
        $("#add_as_other_business_div").slideUp(500);
    });

    $("body").on("click","#add_as_other_business_confirm_submit",function(e){
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');
        var status_wise_msg=$("#status_wise_msg").val();
        if(status_wise_msg==''){
            swal('Oops!','Enter comment for Other Business','error');
            return false;
        }

        if(id!=''){
            var data="id="+id+"&status_wise_msg="+status_wise_msg;
            $.ajax({
                    url: base_url + "lead/add_as_other_business_ajax",
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
                    complete: function (){ 
                        $.unblockUI();                   
                    },
                    success: function(res) {
                        result = $.parseJSON(res);
                        if(result.status=='success')
                        {
                            $('#rander_call_history_popup_action_modal').modal('hide');
                            load_sync_call();    
                        }
                        else
                        {
                            swal('Oops',result.msg,'error');
                        }
                    },
                    error: function(response) {}
            });
        }
        
        
    });

    $("body").on("click",".call_history_update_lead",function(e){
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');

        // CLOSE THE OTHER DIV
        $("#status_wise_msg").val('');
        $("#add_as_other_business_div").slideUp(500);
        // CLOSE OTHER DIV
        $("#cust_id_for_add_new_lead").prop("selectedIndex", 0).val();
        $("#call_history_add_lead_multiple_div").slideUp(500);

        $("#call_history_update_lead_submit").attr("data-id",id);
        $("#call_history_update_lead_div").slideDown(500);
    });

    $("body").on("click","#call_history_update_lead_close",function(e){
        $("#lead_for_call_update").prop("selectedIndex", 0).val();
        $("#call_history_update_lead_div").slideUp(500);
    });

     $("body").on("click","#call_history_update_lead_submit",function(e){
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');
        var lead_for_call_update=$("#lead_for_call_update").val();
        if(lead_for_call_update==''){
            swal('Oops!','Select any lead for Update call history','error');
            return false;
        }

        if(id!='' && lead_for_call_update!=''){
            var data="id="+id+"&lead_for_call_update="+lead_for_call_update;
            $.ajax({
                    url: base_url + "lead/call_history_update_lead_ajax",
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
                    complete: function (){ 
                        $.unblockUI();                   
                    },
                    success: function(res) {
                        result = $.parseJSON(res);
                        if(result.status=='success')
                        {
                            $('#rander_call_history_popup_action_modal').modal('hide');
                            load_sync_call();    
                        }
                        else
                        {
                            swal('Oops',result.msg,'error');
                        }
                    },
                    error: function(response) {}
            });
        }       
        
    });

    $("body").on("click",".popup_action_row",function(e){
        e.preventDefault();
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');        
        var data="id="+id;
        $.ajax({
                url: base_url + "lead/popup_action_row_ajax",
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
                complete: function (){ 
                    $.unblockUI();                   
                },
                success: function(res) {
                    result = $.parseJSON(res);
                    if(result.status=='success')
                    {
                        $("#call_history_popup_action_title").html(result.title);
                        $('#rander_call_history_popup_action_html').html(result.html);
                        $('#rander_call_history_popup_action_modal').modal({backdrop: 'static',keyboard: false});
                    }
                    else
                    {
                        swal('Oops',result.msg,'error');
                    }
                },
                error: function(response) {}
        });
    });

    $(".sync_call_all_checkbox").change(function() {
      $('.cousto_check .check-box-sec').removeClass('same-checked');
      $('input:checkbox[name="sync_call_checked"]').prop('checked', $(this).prop("checked"));

        //console.log($(this).prop("checked"));
        if($(this).prop("checked") == true){
        showUpdateBt();
        }else{
        hideUpdateBt();
        }
        id_arr=[];
        $.each($("input[name='sync_call_checked']:checked"), function(){            
          id_arr.push($(this).val());
        });
        $("#id_bulk").val(id_arr.join(','));
    });
    
    
    $("body").on("change",'input[name="sync_call_checked"]',function(){
        var totalcheck = $('input[name="sync_call_checked"]').length;
        var countCheckedCheckboxes = $('input[name="sync_call_checked"]').filter(':checked').length;
        $('.cousto_check .check-box-sec').removeClass('same-checked');
        if(countCheckedCheckboxes > 0 && countCheckedCheckboxes < totalcheck){
            $('.cousto_check .check-box-sec').addClass('same-checked');
            $('.sync_call_all_checkbox').prop('checked', false);
            showUpdateBt();
        }else if(countCheckedCheckboxes == totalcheck){
            $('.sync_call_all_checkbox').prop('checked', true);
            showUpdateBt();
            hideUpdateBt();
        }else{
            $('.sync_call_all_checkbox').prop('checked', false);
            hideUpdateBt();
        }

        id_arr=[];
        $.each($("input[name='sync_call_checked']:checked"), function(){            
          id_arr.push($(this).val());
        });
        $("#id_bulk").val(id_arr.join(','));
    }); 

    $('.sync_call_bulk_bt_holder').hide();

    $("body").on("click","#delete_row_multiple",function(event){
        event.preventDefault();
        var base_url = $("#base_url").val(); 
        var id=$("#id_bulk").val();
        var data="id="+id;
        
        swal({
            title: '',
            text: 'Do you want to delete the all selected record(s)?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes'
        }, function() { 

            $.ajax({
                url: base_url + "lead/delete_row_ajax",
                data: data,
                cache: false,
                method: 'POST',
                dataType: "html",
                beforeSend: function( xhr ) {                
                    addLoader('#listing_view');
                },
                complete: function(){
                    removeLoader();
                },
                success: function(res) {
                    result = $.parseJSON(res);
                    if(result.status=='success')
                    {
                        $('.sync_call_all_checkbox').prop('checked', false);
                        load_sync_call();                        
                    }
                    else
                    {
                        swal('Oops',result.msg,'error');
                    }
                },
                error: function(response) {}
            });
        });  
        
    });

    // =============================
    // FILTER
    $("body").on("click","#sync_call_filter_btn",function(e){
        $('#syncCallFilterModal').modal({
            backdrop: 'static',
            keyboard: false
        });        
    });
    $("body").on("click","#sync_call_filter_reset",function(e){
        //location.reload(true);
        $("#call_sync_selected_filter_div").css({'display':'none'}).html('');
        // ------------------------------------------------------
        // FILTER RE-SET
        $("#sync_call_filter_by_keyword").val('');
        $("#sync_call_from_date").val('');
        $("#sync_call_to_date").val('');
        $("#sync_call_call_type").val($("#sync_call_call_type option:first").val());
        $("#sync_call_buyer_type").val($("#sync_call_buyer_type option:first").val());


        
        // -----
        $("#filter_sync_call_filter_by_keyword").val('');
        $("#filter_sync_call_from_date").val('');
        $("#filter_sync_call_to_date").val('');
        $("#filter_sync_call_call_type").val('');
        $("#filter_sync_call_buyer_type").val('');      

        // FILTER RE-SET
        // ------------------------------------------------------  

        // $(".filter_dd").removeClass('open');
        // $(".filter_dd").removeClass('show');
        // $('.dd_overlay').hide();

        $("#call_sync_selected_filter_div").html('');
        // $("#filterModal").modal('hide');          
        load_sync_call();
    });
    $("body").on("click","#sync_call_filter",function(e){
        var filter_arr=[];

        var sync_call_filter_by_keyword=$('#sync_call_filter_by_keyword').val();
        var sync_call_filter_by_keyword_text=$("#sync_call_filter_by_keyword").attr('data-text')+": '"+sync_call_filter_by_keyword+"'";        
        (sync_call_filter_by_keyword)?filter_arr.push(sync_call_filter_by_keyword_text):'';

        var sync_call_from_date=$("#sync_call_from_date").val();
        var sync_call_to_date=$("#sync_call_to_date").val();

        if(sync_call_from_date!='' && sync_call_to_date!='')
        {
            var date_range_text='Date between "'+sync_call_from_date+'" - "'+sync_call_to_date+'"';
            (date_range_text)?filter_arr.push(date_range_text):'';
        }       


        var sync_call_call_type=$("#sync_call_call_type").val();
        var sync_call_call_type_text=$('#sync_call_call_type option:selected').attr('data-text');
        (sync_call_call_type)?filter_arr.push(sync_call_call_type_text):'';

        var sync_call_buyer_type=$("#sync_call_buyer_type").val();
        var last_contacted_text=$('#sync_call_buyer_type option:selected').attr('data-text');
        (sync_call_buyer_type)?filter_arr.push(last_contacted_text):'';

       
        if(filter_arr.join())
        {
            $("#call_sync_selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="text-danger" id="sync_call_filter_reset"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>');
        }
        else
        {
            $("#call_sync_selected_filter_div").css({'display':'none'}).html('');
        }

        $("#filter_sync_call_filter_by_keyword").val(sync_call_filter_by_keyword);
        $("#filter_sync_call_from_date").val(sync_call_from_date);
        $("#filter_sync_call_to_date").val(sync_call_to_date);
        $("#filter_sync_call_call_type").val(sync_call_call_type);
        $("#filter_sync_call_buyer_type").val(sync_call_buyer_type);        
        $("#page_number").val('1');
        $("#syncCallFilterModal").modal('hide');  
        load_sync_call();

    });
    // FILTER
    // ==============================

    // ==============================
    // Call Log Report
    $(document).on("click","#sync_call_user_wise_report_download_csv",function (e){
        var base_URL=$("#base_url").val();         
        var data = ""; 
        document.location.href=base_URL+'lead/sync_call_user_wise_report_download_csv/?'+data;
    });   
    // Call Log Report
    // ==============================
    
    
});
function showUpdateBt(){
//onsole.log('showUpdateBt')
$('.sync_call_bulk_bt_holder').css({'display':'inline-block'});
}
function hideUpdateBt(){
//console.log('hideUpdateBt')
$('.sync_call_bulk_bt_holder').hide();
}
// AJAX LOAD START
function load_sync_call() 
{ 
    //return;
    //var page_num=page;
    var base_URL     = $("#base_url").val();
    var filter_sort_by=$("#filter_sort_by").val();
    var view_type=$("#view_type").val();
    var page=$("#page_number").val(); 
    var is_scroll_to_top=$("#is_scroll_to_top").val();

    var filter_sync_call_filter_by_keyword=$("#filter_sync_call_filter_by_keyword").val();
    var filter_sync_call_from_date=$("#filter_sync_call_from_date").val();
    var filter_sync_call_to_date=$("#filter_sync_call_to_date").val();
    var filter_sync_call_call_type=$("#filter_sync_call_call_type").val();
    var filter_sync_call_buyer_type=$("#filter_sync_call_buyer_type").val();

    
    

    var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&view_type="+view_type+"&filter_sync_call_filter_by_keyword="+filter_sync_call_filter_by_keyword+"&filter_sync_call_from_date="+filter_sync_call_from_date+"&filter_sync_call_to_date="+filter_sync_call_to_date+"&filter_sync_call_call_type="+filter_sync_call_call_type+"&filter_sync_call_buyer_type="+filter_sync_call_buyer_type;
    // alert(data);// return false;
    $.ajax({
        url: base_URL+"lead/get_sync_call_list_ajax/"+page,
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
           
            // updateLeadView();
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




