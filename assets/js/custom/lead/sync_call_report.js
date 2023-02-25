$(document).ready(function(){  
      
	fn_sync_call_report_filter();
    $("body").on("click",".sort_order_sync_call",function(e){
        var tmp_field=$(this).attr('data-field');
        var curr_orderby=$(this).attr('data-orderby');
        var new_orderby=(curr_orderby=='asc')?'desc':'asc';
        $(this).attr('data-orderby',new_orderby);
        $(".sort_order").removeClass('asc');
        $(".sort_order").removeClass('desc');
        $(this).addClass(curr_orderby);
        $("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
        load_sync_call_report();
        // alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });
	$(document).on('click', '.myclass_sync_call_report', function (e) { 
		e.preventDefault();
		// closeExpendTable();
		var vt=($(this).attr('data-viewtype')=='grid')?'grid':'list';
		var str = $(this).attr('href'); 
		var res = str.split("/");
		var cur_page = res[1];
		$("#page_number").val(cur_page);
		$("#is_scroll_to_top").val('Y'); 
        
		if(cur_page) {              
		load_sync_call_report(cur_page);
		}
		else {
		load_sync_call_report(1);
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
                        load_sync_call_report();
                        $("#chrd_tr_"+id).html('');                    
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

    $("body").on("click",".add_as_lead",function(e){
        e.preventDefault();
        var base_url = $("#base_url").val(); 
        var id=$(this).attr('data-id');
        rander_add_new_lead_view(id);
        /*
        var data="id="+id;
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
        });
        */
    });
    

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
                        load_sync_call_report();                        
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
    $("body").on("click","#sync_call_report_filter_btn",function(e){
        $('#syncCallFilterModal').modal({
            backdrop: 'static',
            keyboard: false
        });        
    });

    $("body").on("click","#sync_call_report_filter_reset",function(e){
        var scr_assigned_user_default=$("#scr_assigned_user_default").val();
        var scr_year_default=$("#scr_year_default").val();
        var scr_month_default=$("#scr_month_default").val();
        // var scr_date_default=$("#scr_date_default").val();
        
        $("#scr_assigned_user").val(scr_assigned_user_default);
        $("#scr_year").val(scr_year_default);
        $("#scr_month").val(scr_month_default); 

        $("#scr_from_date").val(''); 
        $("#scr_to_date").val(''); 
        
        

        var filter_arr=[];
        // var scr_assigned_user=$("#scr_assigned_user").val();
        // var scr_assigned_user_text=$('#scr_assigned_user option:selected').attr('data-text');
        // (scr_assigned_user)?filter_arr.push(scr_assigned_user_text):'';

        //$('#scr_assigned_user option:selected').each(function() {            
            // $(this).attr("selected",false);     
            // $('#scr_assigned_user').multiselect('deselect', $(this).val());
        //});

        // const valArr=[];
        // $('#scr_assigned_user option').each(function() {
        //     valArr.push($(this).val());
        // });
        // i = 0, size = valArr.length;
        // for(i; i < size; i++){
        //     $("#scr_assigned_user").multiselect("widget").find(":checkbox[value='"+valArr[i]+"']").attr("checked","checked");
        //     $("#scr_assigned_user option[value='" + valArr[i] + "']").attr("selected", valArr[i]);
        //     $("#scr_assigned_user").multiselect("refresh");
        // }
        
        var scr_assigned_user=[];
        var x=1;
        $('#scr_assigned_user option').each(function() {
          scr_assigned_user.push($(this).val());
          $('#scr_assigned_user').multiselect('select', $(this).val());

          var scr_assigned_user_text=$(this).attr('data-text');
          if(scr_assigned_user_text!='' && x==1)
          {
            label_text='<b><u>By User:</u></b> ';
          }
          else
          {
            label_text='';
          }      
          (scr_assigned_user_text)?filter_arr.push(label_text+''+scr_assigned_user_text):'';
          x++;
        });
        
        // scr_year='';
        // scr_month='';
        var scr_year=$("#scr_year").val();
        var scr_year_text=$('#scr_year option:selected').attr('data-text');
        (scr_year)?filter_arr.push(scr_year_text):'';

        var scr_month=$("#scr_month").val();
        var scr_month_text=$('#scr_month option:selected').attr('data-text');
        (scr_month)?filter_arr.push(scr_month_text):'';

        var scr_from_date='';
        var scr_to_date='';
        // var scr_from_date=$("#scr_from_date").val();
        // var scr_to_date=$("#scr_to_date").val();
        // if(scr_from_date!='' && scr_to_date!=''){
        //     var scr_by_date_text='<b>By date from </b>'+scr_from_date+' <b>to</b> '+scr_to_date;
        //     filter_arr.push(scr_by_date_text);
        // }
       
        if(filter_arr.join())
        {
            $("#call_sync_report_selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="" id="sync_call_report_filter_reset" title="Reset"><i class="fa fa-refresh" aria-hidden="true"></i></a>&nbsp;/&nbsp;<a href="JavaScript:void(0);" class="text-info" id="sync_call_report_filter_btn" title="Filter"><i class="fa fa-filter" aria-hidden="true"></i></a>');
        }
        else
        {
            $("#call_sync_report_selected_filter_div").css({'display':'none'}).html('');
        }

        $("#filter_scr_assigned_user").val(scr_assigned_user);
        $("#filter_scr_year").val(scr_year);
        $("#filter_scr_month").val(scr_month); 

        $("#filter_scr_from_date").val(scr_from_date);
        $("#filter_scr_to_date").val(scr_to_date); 
          
        load_sync_call_report();
    });

    $("body").on("click","#sync_call_filter",function(e){
        fn_sync_call_report_filter();
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

    
    $("body").on("click",".view_details",function(e){
        
        // var base_url = $("#base_url").val(); 
        var date=$(this).attr("data-date");
        var type=$(this).attr("data-type");
        var filter_assigned_user=$("#filter_scr_assigned_user").val();
        var lead_id='';
        call_report_view_details(date,type,filter_assigned_user,lead_id);
        // var data="date="+date+"&type="+type+"&filter_assigned_user="+filter_assigned_user;
        //alert(data);
        // $.ajax({
        //         url: base_url + "lead/rander_call_history_report_detail_ajax",
        //         data: data,
        //         cache: false,
        //         method: 'POST',
        //         dataType: "html",
        //         beforeSend: function(xhr) { 
        //             $.blockUI({ 
        //                 message: 'Please wait...', 
        //                 css: { 
        //                    padding: '10px', 
        //                    backgroundColor: '#fff', 
        //                    border:'0px solid #000',
        //                    '-webkit-border-radius': '10px', 
        //                    '-moz-border-radius': '10px', 
        //                    opacity: .5, 
        //                    color: '#000',
        //                    width:'450px',
        //                    'font-size':'14px'
        //                   }
        //             });           
        //         },
        //         complete: function (){ 
        //             $.unblockUI();                   
        //         },
        //         success: function(res) {
        //             result = $.parseJSON(res);
        //             if(result.status=='success')
        //             {
		// 				var link_tmp='';
		// 				if($("#scr_assigned_user_default").val()=='1'){
		// 					link_tmp=' ( <a href="JavaScript:void(0)" class="download_call_log_details_csv" data-date="'+date+'" data-type="'+type+'"><i class="fa fa-cloud-download" aria-hidden="true"></i></a> )';
		// 				}
        //                 $("#call_history_report_detail_title").html(result.title+link_tmp);
        //                 $('#rander_call_history_report_detail_html').html(result.html);
        //                 $('#rander_call_history_report_detail_modal').modal({backdrop: 'static',keyboard: false});
        //             }
        //             else
        //             {
        //                 swal('Oops',result.msg,'error');
        //             }
        //         },
        //         error: function(response) {}
        // });
    });
    
    $(document).on("click","#download_call_log_csv",function (e){
        
		var base_URL     = $("#base_url").val();
		var filter_sort_by=$("#filter_sort_by").val();
		var filter_scr_assigned_user=$("#filter_scr_assigned_user").val();
		var filter_scr_year=$("#filter_scr_year").val();
		var filter_scr_month=$("#filter_scr_month").val();
		var data="filter_sort_by="+filter_sort_by+"&filter_assigned_user="+filter_scr_assigned_user+"&filter_scr_year="+filter_scr_year+"&filter_scr_month="+filter_scr_month;
		document.location.href = base_URL+'lead/download_call_log_csv/?'+data;
    });
	
	$(document).on("click",".download_call_log_details_csv",function (e){
        
		var base_URL     = $("#base_url").val();
		var date=$(this).attr("data-date");
        var type=$(this).attr("data-type");
        var filter_assigned_user=$("#filter_scr_assigned_user").val();
        var data="date="+date+"&type="+type+"&filter_assigned_user="+filter_assigned_user;
		//alert(data); return false;
		document.location.href = base_URL+'lead/download_call_log_details_csv/?'+data;
    });
});
function showUpdateBt(){
    //onsole.log('showUpdateBt')
    // $('.sync_call_bulk_bt_holder').css({'display':'inline-block'});
}
function hideUpdateBt(){
    //console.log('hideUpdateBt')
    // $('.sync_call_bulk_bt_holder').hide();
}
// AJAX LOAD START
function load_sync_call_report() 
{ 
    //return;
    //var page_num=page;
    var base_URL     = $("#base_url").val();
    var filter_sort_by=$("#filter_sort_by").val();

    var filter_scr_assigned_user=$("#filter_scr_assigned_user").val();
    var filter_scr_year=$("#filter_scr_year").val();
    var filter_scr_month=$("#filter_scr_month").val();
    // var filter_scr_year='';
    // var filter_scr_month='';
    
    var filter_scr_from_date=$("#filter_scr_from_date").val();
    var filter_scr_to_date=$("#filter_scr_to_date").val();

    var data="filter_sort_by="+filter_sort_by+"&filter_assigned_user="+filter_scr_assigned_user+"&filter_scr_year="+filter_scr_year+"&filter_scr_month="+filter_scr_month+"&filter_scr_from_date="+filter_scr_from_date+"&filter_scr_to_date="+filter_scr_to_date;
    // alert(data);// return false;
    $.ajax({
        url: base_URL+"lead/get_sync_call_report_list_ajax/",
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
       },           
       error: function(response) {
        //alert('Error'+response.table);
        }
   })
}
// AJAX LOAD END

function fn_sync_call_report_filter()
{
    var filter_arr=[];
    // var scr_assigned_user=$("#scr_assigned_user").val();
    // var scr_assigned_user_text=$('#scr_assigned_user option:selected').attr('data-text');
    // (scr_assigned_user)?filter_arr.push(scr_assigned_user_text):'';

    var scr_assigned_user=[];
    var x=1;
    $('#scr_assigned_user option:selected').each(function() {
      scr_assigned_user.push($(this).val());
      var scr_assigned_user_text=$(this).attr('data-text');
      if(scr_assigned_user_text!='' && x==1)
      {
        label_text='<b><u>By User:</u></b> ';
      }
      else
      {
        label_text='';
      }      
      (scr_assigned_user_text)?filter_arr.push(label_text+''+scr_assigned_user_text):'';
      x++;
    });

    // scr_year='';
    // scr_month='';
    // var scr_year=$("#scr_year").val();
    // var scr_year_text=$('#scr_year option:selected').attr('data-text');
    // (scr_year)?filter_arr.push(scr_year_text):'';
    
    // var scr_month=$("#scr_month").val();
    // var scr_month_text=$('#scr_month option:selected').attr('data-text');
    // (scr_month)?filter_arr.push(scr_month_text):'';
    
    // var scr_from_date='';
    // var scr_to_date='';
    var scr_from_date=$("#scr_from_date").val();
    var scr_to_date=$("#scr_to_date").val();
    if(scr_from_date!='' && scr_to_date!=''){
        var scr_by_date_text='<b>By date from </b>'+scr_from_date+' <b>to</b> '+scr_to_date;
        filter_arr.push(scr_by_date_text);
    }
    
    if(scr_from_date=='' && scr_to_date==''){
       
        var scr_year=$("#scr_year").val();
        var scr_year_text=$('#scr_year option:selected').attr('data-text');
        (scr_year)?filter_arr.push(scr_year_text):'';
        
        var scr_month=$("#scr_month").val();
        var scr_month_text=$('#scr_month option:selected').attr('data-text');
        (scr_month)?filter_arr.push(scr_month_text):'';
    }


    if(filter_arr.join())
    {
        $("#call_sync_report_selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="" id="sync_call_report_filter_reset" title="Reset"><i class="fa fa-refresh" aria-hidden="true"></i></a>&nbsp;/&nbsp;<a href="JavaScript:void(0);" class="text-info" id="sync_call_report_filter_btn" title="Filter"><i class="fa fa-filter" aria-hidden="true"></i></a>');
    }
    else
    {
        $("#call_sync_report_selected_filter_div").css({'display':'none'}).html('');
    }

    $("#filter_scr_assigned_user").val(scr_assigned_user);
    $("#filter_scr_year").val(scr_year);
    $("#filter_scr_month").val(scr_month);

    $("#filter_scr_from_date").val(scr_from_date);
    $("#filter_scr_to_date").val(scr_to_date);

    $("#syncCallFilterModal").modal('hide');  
    load_sync_call_report();
}




