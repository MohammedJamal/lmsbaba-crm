$(document).ready(function(){
	load(1);   
    load_add_edit_view(''); 

    $(document).on('click', '.record_edit', function (e) {    	     
	    var id = $(this).attr('data-id');
	    window.scrollTo({top: 0, behavior: 'smooth'});
	    load_add_edit_view(id); 
    });

	$(document).on('click', '.record_delete', function (e) {
        
        var base_url = $("#base_url").val();	            
	    var id = $(this).attr('data-id');
        swal({
			  title: "Are you sure?",
			  text: "You will not be able to recover this record!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, delete it!",
			  cancelButtonText: "No, cancel plx!",
			  closeOnConfirm: false,
			  closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) 
				{			  		            
					var data = "id="+id;
					$.ajax({
					    url: base_url+"customer/plant_delete_ajax",
					    data: data,
					    cache: false,
					    method: 'POST',
					    dataType: "html",
					    beforeSend: function( xhr ) {
					    
					    },
					    success: function(data){	                    
					            result = $.parseJSON(data);
					            if(result.status == 'success')
					            {
					                swal("Deleted!", result.success_msg, "success");
					                load(1);
					                return false;
					            }
					            else
					            {
					                swal("Oops", result.error_msg, "error");
					            }
					        },
					        error: function(response) {
					            //alert('Error'+response.status);
					        }
					    });

				} 
				else 
				{
					swal("Cancelled", "Your record is safe :)", "error");
				}
			});        
    });


    $(document).on('click', '.record_change_status', function (e) {
        
        var base_url = $("#base_url").val();            
	    var id = $(this).attr('data-id');
	    var curr_status = $(this).attr('data-curstatus');
        swal({
			  title: "Are you sure?",
			  text: "The current status will be changed!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "Yes, change it!",
			  cancelButtonText: "No, cancel plx!",
			  closeOnConfirm: false,
			  closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) 
				{			  		            
					var data="id="+id+"&curr_status="+curr_status; 
					
					$.ajax({
					    url: base_url+"customer/plant_change_status_ajax",
					    data: data,
					    cache: false,
					    method: 'POST',
					    dataType: "html",
					    beforeSend: function( xhr ) {
					    
					    },
					    success: function(data){	                    
					            result = $.parseJSON(data);
					            if(result.status == 'success')
					            {
					                swal("Updated!", result.success_msg, "success");
					                load(1);
					                return false;
					            }
					            else
					            {
					                swal("Oops", result.error_msg, "error");
					            }
					        },
					        error: function(response) {
					            //alert('Error'+response.status);
					        }
					    });

				} 
				else 
				{
					swal("Cancelled", "Current status remain unchange :)", "error");
				}
			});        
    });

	$("body").on("click","#submit_confirm",function(e){

		var base_url=$("#base_url").val();
		var customer_id=$("#customer_id").val();
		var name_obj=$("#name");
		var location_obj=$("#location");		
		
		
		if(name_obj.val()=='')
		{
			$("#name_error").html('Plant name should not be blank.');
			name_obj.focus();
			return false
		}
		else
		{
			$("#name_error").html('');
		}

		if(location_obj.val()=='')
		{
			$("#location_error").html('Plant location should not be blank.');
			location_obj.focus();
			return false
		}
		else
		{
			$("#location_error").html('');
		}

		
		
		
		$.ajax({
			url: base_url+"customer/plant_add_edit_ajax/"+customer_id,
            data: new FormData($('#form_custom')[0]),
			cache: false,
			method: 'POST',
			dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
                
            },
			success: function(data){                
				result = $.parseJSON(data); 
				//alert(result.status); return false;
				//alert(result.error_msg); 
				if(result.status=='success')
				{					
					swal("Success!", result.success_msg, "success");
					load(1);
					load_add_edit_view('');
				}
				else
				{
					swal("Oops!", result.error_msg, "error");					
				}
                
			}
		});		
	});

	 /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH ################# */
    /* ######################################################################## */
       
    
    
    
    $(document).on('click', '.myclass', function (e) { 
           e.preventDefault();
           var str = $(this).attr('href'); 
           var res = str.split("/");
           var cur_page = res[1];
           if(cur_page) {
                load(cur_page);
            }
            else {
                load(1);
            }
	});
    // AJAX SEARCH END
    
    
    // AJAX LOAD START
    function load(page) { 
        //var page_num=page;
        var base_URL = $("#base_url").val();    
        var customer_id=$("#customer_id").val();
        var data = "page="+page+"&customer_id="+customer_id;        
            $.ajax({
                url: base_URL+"customer/get_plant_list_ajax/"+page,
				data: data,
				cache: false,
				method: 'GET',
				dataType: "html",
                 beforeSend: function( xhr ) {
                    // $("#preloader").css('display','block');
                  },
               success:function(res){ 
                   result = $.parseJSON(res);
                   // $(".preloader").hide();     
                   //alert(result.table)              
                   $("#tcontent").html(result.table);
                   $("#page").html(result.page);
               },
               complete: function(){
                // $("#preloader").css('display','none');
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
    
    
});

function load_add_edit_view(id='') 
{ 
    var base_URL = $("#base_url").val();  
    var data="id="+id;
    $.ajax({
            url: base_URL+"customer/plant_render_add_edit_view_ajax/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {
			// $("#preloader").css('display','block');
			},
			success:function(res){ 
			   result = $.parseJSON(res);
			   // $(".preloader").hide();				   
			   $("#add_edit_html").html(result.html);
			},
			complete: function(){
			// $("#preloader").css('display','none');
			},
			error: function(response) {
			//alert('Error'+response.table);
			}
       })
}