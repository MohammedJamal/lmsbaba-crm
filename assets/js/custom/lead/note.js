$(document).ready(function(){  
      
    // =============================================================
    // GET PAGE NAME
    var curr_path_str=window.location.href;
    var curr_path_str = curr_path_str.replace(/\/\s*$/, "");
    var curr_path_arr = curr_path_str.split("/");    
    var page_name=curr_path_arr.slice(-1)[0];
    // =============================================================
    if(page_name=='note'){        
        load_note();
    }
	
    
    $("body").on("click",".sort_order_sync_call--",function(e){
        var tmp_field=$(this).attr('data-field');
        var curr_orderby=$(this).attr('data-orderby');
        var new_orderby=(curr_orderby=='asc')?'desc':'asc';
        $(this).attr('data-orderby',new_orderby);
        $(".sort_order").removeClass('asc');
        $(".sort_order").removeClass('desc');
        $(this).addClass(curr_orderby);
        $("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
        load_note();
        // alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });
	$(document).on('click', '.myclass_note', function (e) { 
		e.preventDefault();
		// closeExpendTable();
		var vt=($(this).attr('data-viewtype')=='grid')?'grid':'list';
		var str = $(this).attr('href'); 
		var res = str.split("/");
		var cur_page = res[1];
		$("#page_number").val(cur_page);
		$("#is_scroll_to_top").val('Y'); 
        
		if(cur_page) {              
            load_note(cur_page);
		}
		else {
            load_note(1);
		}
	});

    //////////////////////////////////////////////////////////
	// NOTE
	$("body").on('click', '.note_btn', function (e) {
		e.preventDefault();
		var id=$(this).attr("data-id");	
        var lead_id=$(this).attr("data-leadid");	
        rander_note_html(id,lead_id);	    		
	});
	$("body").on('click', '.note_close', function (e) {
		e.preventDefault();
		id=$(this).attr("data-id");
        $("#note_inner_div_"+id).html('');
		$("#note_inner_div_"+id).hide('fast');

        $("#vc_note_inner_div_"+id).html('');
		$("#vc_note_inner_div_"+id).hide('fast');

	});
	var $modalAnimateTime = 300;
    $(document).on('click', '.note_add_btn', function (e) {
      e.preventDefault();
      var parentid=$(this).attr("data-parentid");
      var note=$(this).attr("data-note");
      var user_name=$(this).attr("data-user_name");
      var id=$(this).attr("data-id");    
      
      $("#note_text").val('');
      $("#add_note_confirm").attr("data-parentid",parentid);	
      $("#parent_note").html('');
      if(note){
        $("#parent_note").html('<span class="text-danger">Reply To '+user_name+':</span><br>'+note);
      }
      
      var $formComments = $("#note_list_"+id);
      var $formAdd = $('#note_add_'+id);
      var $divForms = $("#note_inner_div_"+id);		
      modalAnimate($formComments, $formAdd,'',$divForms);         
    });	
    $(document).on('click', '.note_back', function (e) {
      e.preventDefault();
      var id=$(this).attr("data-id"); 
      var $formComments = $("#note_add_"+id);
      var $formAdd = $('#note_list_'+id);
      var $divForms = $("#note_inner_div_"+id);	
      
      modalAnimate($formComments, $formAdd,'',$divForms);        
    });   
    $("body").on("click","#add_note_confirm",function(e){
    
		var click_btn_obj=$(this);
        var parentid=$(this).attr("data-parentid");
		var id=click_btn_obj.attr("data-id");
        var lead_id=click_btn_obj.attr("data-leadid");
		var base_URL = $("#base_url").val();        
		var note_obj=$("#note_text"); 
		
		
		if(note_obj.val()=='')
		{
			$("#note_error").html("( Please enter note )");
			note_obj.focus();
			return false;
		}
		else
		{
			$("#note_error").html("");
		}     
        var note_text = note_obj.val();
        note_text=note_text.replace(/\r?\n/g, '<br />');      
		var data = "lead_id="+lead_id+"&note="+note_text+"&parentid="+parentid;	
       
		$.ajax({
			url: base_URL+"lead/add_note_ajax",
			data: data,
			cache: false,
			method: 'POST',
			dataType: "html",
			beforeSend: function( xhr ) {
				click_btn_obj.attr("disabled",true);
			},
			success:function(res){ 
				result = $.parseJSON(res);
				if(result.status=='success')
                {				
                    if(page_name=='note'){        
                        load_note();
                    }
                    else if(page_name=='manage'){
                        load();
                    }

                    // $("#note_count_"+id).css("background","#59bb60");
                    // $("#note_count_"+id).text(result.note_count); 
                    
                    // $(".note_count_"+lead_id).css("background","#59bb60");
                    // $(".note_count_"+lead_id).text(result.note_count); 
                    
                    // $("#note_inner_div_"+id).html('');
                    // $("#note_inner_div_"+id).hide('fast');
                    
                    // $("#vc_note_inner_div_"+id).html('');
                    // $("#vc_note_inner_div_"+id).hide('fast');
				}
			},
			complete: function(){
				click_btn_obj.attr("disabled",false);
			},
			error: function(response) {
			
			}
		})	  	
    });  
  
    function rander_note_html(id,lead_id,nid='')
    {
        var base_URL = $("#base_url").val();            	
        var data="id="+id+"&lead_id="+lead_id+"&nid="+nid;
        $.ajax({
            url: base_URL+"lead/rander_note_html",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function( xhr ) { 
                                  
            },
            success: function(data){
                result = $.parseJSON(data);  
                
                if(result.is_seen_updated=='Y')
                {
                    if($("#view_comments_"+id ).hasClass("view_comments"))
                    {
                        $("#view_comments_"+id).css("font-weight", "");
                    }
                }       

                if(nid)
                {
                    $("#vc_note_inner_div_"+id).html(result.html);                
                    $("#vc_note_inner_div_"+id).show('fast');
                }
                else
                {
                    $("#note_inner_div_"+id).html(result.html);                
                    $("#note_inner_div_"+id).show('fast'); 
                }
                
            },
            complete: function(){
              
            },
        });	
    }
	function modalAnimate ($oldForm, $newForm, extraHeight,$divForms) 
    {
      
      extraHeight = extraHeight || 0;
      var $oldH = $oldForm.outerHeight();
      var $newH = $newForm.outerHeight()+(40+extraHeight);
      $oldForm.hide();
      $newForm.show();      
    }

    $("body").on("click","#unread_reply",function(e){
        
        if ($(this).is(':checked')) {
            $("#filter_show_all_unread_reply").val("Y");
            // $(".read").css("display","none");
            // $("#note_listing_view").css("min-height",'');
            // $("#page_info_div").css("display","none");            
        } else {
            $("#filter_show_all_unread_reply").val("N");
            // $(".read").css("display","");
            // $("#note_listing_view").css("min-height",'300');
            $("#page_info_div").css("display","");           
        }
        load_note();
    });
	// NOTE
	///////////////////////////////////////////////////////////

    $("body").on("click",".view_comments",function(e){        
        
        e.preventDefault();
        var base_URL = $("#base_url").val(); 
        var id=$(this).attr("data-id");	
        var lead_id=$(this).attr("data-leadid");
        var nid=$(this).attr("data-nid");
        rander_note_html(id,lead_id,nid);

        // var data="id="+id+"&lead_id="+lead_id+"&nid="+nid;       
        // $.ajax({
        //     url: base_URL+"lead/rander_note_html",
        //     data: data,
        //     cache: false,
        //     method: 'POST',
        //     dataType: "html",
        //     beforeSend: function( xhr ) {                                  
        //     },
        //     success: function(data){
        //         result = $.parseJSON(data);
        //         $("#vc_note_inner_div_"+id).html(result.html);                
        //         $("#vc_note_inner_div_"+id).show('fast');                           
        //     },
        //     complete: function(){
              
        //     },
        // });
    });

    // =============================
    // FILTER
    $("body").on("click","#note_filter_btn",function(e){
        $('#noteFilterModal').modal({
            backdrop: 'static',
            keyboard: false
        });        
    });
    $("body").on("click","#notel_filter_confirm",function(e){
        var filter_arr=[];

        var note_search_by_keyword=$('#note_search_by_keyword').val();
        var note_search_by_keyword_text=$("#note_search_by_keyword").attr('data-text')+": '"+note_search_by_keyword+"'";        
        (note_search_by_keyword)?filter_arr.push(note_search_by_keyword_text):'';

        var note_added_by=$("#note_added_by").val();
        var note_added_by_text=$('#note_added_by option:selected').attr('data-text');
        (note_added_by)?filter_arr.push('Note added By: '+note_added_by_text):'';

        var lead_assign_to=$("#lead_assign_to").val();
        var lead_assign_to_text=$('#lead_assign_to option:selected').attr('data-text');
        (lead_assign_to)?filter_arr.push('Lead Assign To: '+lead_assign_to_text):'';


        var note_from_date=$("#note_from_date").val();
        var note_to_date=$("#note_to_date").val();

        if(note_from_date!='' && note_to_date!='')
        {
            var date_range_text='Date between "'+note_from_date+'" - "'+note_to_date+'"';
            (date_range_text)?filter_arr.push(date_range_text):'';
        }   

       
        if(filter_arr.join())
        {
            $("#note_selected_filter_div").css({'display':'inline-block'}).html('<span><b>Filter Applied:</b></span> '+filter_arr.join().replace(new RegExp(",", "g"), ", ")+' <a href="JavaScript:void(0);" class="text-danger" id="note_filter_reset"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>');
        }
        else
        {
            $("#note_selected_filter_div").css({'display':'none'}).html('');
        }

        $("#filter_by_keyword").val(note_search_by_keyword);
        $("#filter_note_from_date").val(note_from_date);
        $("#filter_note_to_date").val(note_to_date);
        $("#filter_note_added_by").val(note_added_by);
        $("#filter_lead_assign_to").val(lead_assign_to);        
        $("#page_number").val('1');
        $("#noteFilterModal").modal('hide');  
        load_note();

    });

    $("body").on("click","#note_filter_reset",function(e){
        //location.reload(true);
        $("#note_selected_filter_div").css({'display':'none'}).html('');
        // ------------------------------------------------------
        // FILTER RE-SET
        $("#note_search_by_keyword").val('');
        $("#note_added_by").val($("#note_added_by option:first").val());
        $("#lead_assign_to").val($("#lead_assign_to option:first").val());
        $("#note_from_date").val('');
        $("#note_to_date").val('');


        
        // -----
        $("#filter_by_keyword").val('');
        $("#filter_note_from_date").val('');
        $("#filter_note_to_date").val('');
        $("#filter_note_added_by").val('');
        $("#filter_lead_assign_to").val('');      

        // FILTER RE-SET
        // ------------------------------------------------------  

        // $(".filter_dd").removeClass('open');
        // $(".filter_dd").removeClass('show');
        // $('.dd_overlay').hide();

        // $("#note_selected_filter_div").html('');
        // $("#filterModal").modal('hide');          
        load_note();
    });
    // FILTER
    // =============================
    
});

function load_note() 
{ 
    //return;
    //var page_num=page;
    var base_URL     = $("#base_url").val();
    var filter_sort_by=$("#filter_sort_by").val();
    var view_type=$("#view_type").val();
    var page=$("#page_number").val(); 
    var is_scroll_to_top=$("#is_scroll_to_top").val();
    var filter_by_keyword=$("#filter_by_keyword").val();    
    var filter_note_from_date=$("#filter_note_from_date").val();    
    var filter_note_to_date=$("#filter_note_to_date").val();  
    
    var filter_note_added_by=$("#filter_note_added_by").val();  
    var filter_lead_assign_to=$("#filter_lead_assign_to").val();
    
    var filter_show_all_unread_reply=$("#filter_show_all_unread_reply").val();

    var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&view_type="+view_type+"&filter_by_keyword="+filter_by_keyword+"&filter_note_from_date="+filter_note_from_date+"&filter_note_to_date="+filter_note_to_date+"&filter_note_added_by="+filter_note_added_by+"&filter_lead_assign_to="+filter_lead_assign_to+"&filter_show_all_unread_reply="+filter_show_all_unread_reply;
    //alert(data); 
    //return false;
    $.ajax({
        url: base_URL+"lead/get_note_list_ajax/"+page,
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
          beforeSend: function( xhr ) {              
            addLoaderNew('#note_listing_view');
          },
          complete: function(){
            removeLoader();
            },
          success:function(res){ 
            result = $.parseJSON(res);
            //alert(result); 
            //return false;
            $("#tcontent_note").html(result.table);
            $("#page_note").html(result.page);
            $("#page_record_count_info_note").html(result.page_record_count_info);
            $('#note_listing_view').removeClass('datatable_grid');
            $(".grey-card-block").addClass('list_view');
            $(".grey-card-block").removeClass('grid_view');

            


            if ($("#unread_reply").is(':checked')) {
                var flag=0;
                $(".unread").each(function(){
                    flag++;
                });

                if(flag==0){
                    // $("#tcontent_note").html('<tr><td colspan="8" style="text-align:center;">No record found!</td></tr>');
                    swal("Oops!", "No unread reply found!", "warning");
                    $("#unread_reply").prop('checked',false);
                    $("#filter_show_all_unread_reply").val('N');
                    load_note();
                }
                else{
                    $(".read").css("display","none");
                    $("#note_listing_view").css("min-height",'');
                    $("#page_record_count_info_note").css("display","none");
                }

                            
            } else {
                //$(this).prop('checked',true);
                $(".read").css("display","");
                $("#note_listing_view").css("min-height",'300');
                $("#page_record_count_info_note").css("display","");           
            }
       },           
       error: function(response) {
        //alert('Error'+response.table);
        }
   })
}
// AJAX LOAD END
function addLoaderNew(getele)
{   
    var gets = 100;
    if ($(window).scrollTop() > 200) {
        gets = $(window).scrollTop();
    }
    var loaderhtml = '<div class="loader" style="background-position: 50% '+gets+'px"></div>';
    $(getele).css({'position':'relative', 'min-height': '300px'}).prepend(loaderhtml);
    $('.loader').fadeIn('fast', function() {
        // Animation complete.
        //$(getele).css({'min-height': 'inherit'})
    });
}



