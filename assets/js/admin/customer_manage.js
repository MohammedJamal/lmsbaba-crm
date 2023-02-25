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
					    url: base_url+"customer/delete_ajax",
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
					    url: base_url+"customer/change_status_ajax",
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
		var company_name_obj=$("#company_name");
		var contact_person_name_obj=$("#contact_person_name");
		var address_obj=$("#address");
		var mobile_obj=$("#mobile");
		var phone_number_obj=$("#phone_number");
		var email_obj=$("#email");
		
		
		
		if(company_name_obj.val()=='')
		{
			$("#company_name_error").html('Company name should not be blank.');
			company_name_obj.focus();
			return false
		}
		else
		{
			$("#company_name_error").html('');
		}

		if(contact_person_name_obj.val()=='')
		{
			$("#contact_person_name_error").html('Contact Person should not be blank.');
			contact_person_name_obj.focus();
			return false
		}
		else
		{
			$("#contact_person_name_error").html('');
		}

		if(address_obj.val()=='')
		{
			$("#address_error").html('Company address should not be blank.');
			address_obj.focus();
			return false
		}
		else
		{
			$("#address_error").html('');
		}

		if(mobile_obj.val()=='')
		{
			$("#mobile_error").html('Password should not be blank.');
			mobile_obj.focus();
			return false
		}
		else
		{
			$("#mobile_error").html('');
		}

		if(phone_number_obj.val()=='')
		{
			$("#phone_number_error").html('Phone Number should not be blank.');
			phone_number_obj.focus();
			return false
		}
		else
		{
			$("#phone_number_error").html('');
		}


		if(email_obj.val()=='')
		{
			$("#email_error").html('Email should not be blank.');
			email_obj.focus();
			return false
		}
		else
		{
			if(is_email_validate(email_obj.val())==false)
            {                
                $("#email_error").html("Please enter valid email.");
                email_obj.focus();
                return false;
            }
            else
            {               
                $("#email_error").html('');
            }
			
		}
		
		
		$.ajax({
			url: base_url+"customer/add_edit_ajax/",
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
				//console.log(result.status); return false;
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
        var data = "page="+page;        
            $.ajax({
                url: base_URL+"customer/get_list_ajax/"+page,
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
            url: base_URL+"customer/render_add_edit_view_ajax/",
			data: data,
			cache: false,
			method: 'GET',
			dataType: "html",
			beforeSend: function( xhr ) {
				$("#add_edit_html").addClass('loadingover');
			},
			success:function(res){ 
			   result = $.parseJSON(res);
			   // $(".preloader").hide();				   
			   $("#add_edit_html").html(result.html);
			},
			complete: function(){
				//$("#add_edit_html").removeClass('loadingover');
			},
			error: function(response) {
			//alert('Error'+response.table);
			}
       })
}