$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip({html: true}); 
    
    $("body").on("click",".tree_clickable",function(e){
      $("#tree_div").slideToggle('fast');
      $(this).toggleClass("tree-down-arrow");
    });

    $("body").on("click",".clickable",function(e){
      var clickable_id=$(this).attr("data-id");
      //$("#clickable_div_"+clickable_id).fadeToggle('slow');
      $("#clickable_div_"+clickable_id).slideToggle('fast');
      $(this).toggleClass("down-arrow");
      $(this).parent().toggleClass("accordion-active");
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
                                load(1);
                                load_managerial_tree();
                            });
                           
                        },
                        complete: function(){
                        $("#preloader").css('display','none');
                       },
                });
                
            });

        
    }); 


    /* ########## FOR FIRST LIME LOADING START ############# */   
        load(1);   
        load_managerial_tree(); 
    /* ########## FOR FIRST LIME LOADING END ############# */
    
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
           var str = $(this).attr('href'); 
           var res = str.split("/");
           var cur_page = res[1];
           $("#page_number").val(cur_page);
           if(cur_page) {
                load(cur_page);
            }
            else {
                load(1);
            }
            
	});
    // AJAX SEARCH END
    
    $("body").on("click","#is_show_only_active_user",function(e){        
        load(1);        
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
      load(1);
      //alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });
    // AJAX LOAD START
    function load() 
    {   
        //var page_num=page;
        var base_URL     = $("#base_url").val();
        var filter_sort_by=$("#filter_sort_by").val();
        var is_show_only_active_user=($('input[name="is_show_only_active_user"]:checked').val())?$('input[name="is_show_only_active_user"]:checked').val():'Y';
        var page=$("#page_number").val();  
        var data = "page="+page+"&filter_sort_by="+filter_sort_by+"&is_show_only_active_user="+is_show_only_active_user;
        
        $.ajax({
            url: base_URL+"user/get_employee_list_ajax/"+page,
      			data: data,
      			cache: false,
      			method: 'GET',
      			dataType: "html",
             beforeSend: function( xhr ) {
                //$("#preloader").css('display','block');
              },
           success:function(res){ 
               result = $.parseJSON(res);
               //alert(result.table);
               $("#tcontent").html(result.table);
               $("#page").html(result.page);
               //$("#tcontent").accordion();
               //alert("okk");
           },
           complete: function(){
            //$("#preloader").css('display','none');
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
    }
    // AJAX LOAD END

    // AJAX LOAD START
    function load_managerial_tree() 
    {   
        var base_URL = $("#base_url").val();
        var data = "";    
        $.ajax({
            url: base_URL+"user/get_employee_tree_list_ajax/",
            data: data,
            cache: false,
            method: 'GET',
            dataType: "html",
            beforeSend: function( xhr ) {
                //$("#preloader").css('display','block');
              },
            success:function(res){ 
               result = $.parseJSON(res);
               //alert(result.html);
               $("#managerial_tree_view").html(result.html);
              
           },
           complete: function(){
            //$("#preloader").css('display','none');
           },
           error: function(response) {
            //alert('Error'+response.table);
            }
       })
    }
    // AJAX LOAD END
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH END ############# */
    /* ######################################################################## */

    $("body").on("click",".view_employee_details",function(e){
        var emp_id=$(this).attr('data-id');
        var m_id=$(this).attr('data-managerid');
        //alert(emp_id+' / '+m_id);
        $("#manager_auto_id").val(m_id);
        
        $("#ViewEmployeeDetailsModal").modal({
            backdrop: 'static',
            keyboard: false,
            callback:fn_employee_details(emp_id)
        });   
    });

    $("body").on("click",".redirect_to_href",function(e){
        var href=$(this).attr('href');
        window.location.href = href;   
    });
	
	$(document).on("click","#download_user_csv",function (e){
        
        var base_URL        = $("#base_url").val();         
		var filter_sort_by=$("#filter_sort_by").val();
        var page=$("#page_number").val();
        var data = "page="+page+"&filter_sort_by="+filter_sort_by;
		//alert(data); return false;
        document.location.href = base_URL+'user/download_csv/?'+data;
    });
    
});

function fn_employee_details(eid)
{
    var base_URL     = $("#base_url").val();
    var data = "eid="+eid;
    $.ajax({
        url: base_URL+"/user/get_employee_details_ajax/",
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
         beforeSend: function( xhr ) {
            //$("#preloader").css('display','block');
          },
       success:function(res){ 
           result = $.parseJSON(res);
           $("#title_text").html(result.title);
           $("#body_html").html(result.html);
       },
       complete: function(){
        //$("#preloader").css('display','none');
       },
       error: function(response) {
        //alert('Error'+response.table);
        }
   })
}
function confirm_delete(id) 
{
    var base_URL = $("#base_url").val(); 
    
    swal({
      title: 'Are you sure?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: false
    }, function() {
        //alert(base_URL+"/user/delete_employee/"+id); return false;
        window.location.href=base_URL+"/user/delete_employee/"+id;
    });
    return false;
}