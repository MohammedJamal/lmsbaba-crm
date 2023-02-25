$(document).ready(function(){
	    
        $(document).on('click', '.btn-status-change', function (e) {
            
            var result = confirm("Do you want to change the current status?");
            if(result == true)
            {
               
                var base_URL          = $("#base_URL").val();
                var ID = this.id;
                var curr_status = $(this).attr('data-id');
                
                var data     = "index="+ID+"&curr_status="+curr_status; 
               
                //alert(data); return false;
                $.ajax({
                    url: base_URL+"cls/change_status",
                    data: data,
                    cache: false,
                    method: 'POST',
                    dataType: "html",
                    beforeSend: function( xhr ) {
                    $(".preloader").show();
                    },
                    success: function(data){
                        $(".preloader").hide();
                        result = $.parseJSON(data);
                        if(result.status == 'success')
                        {
                            $("#notification-error").hide();
                            $("#notification-success").slideDown().html('Status succesfully changed..');
                            load(1);
                            return false;
                        }
                        else
                        {
                            $("#notification-error").html('ERROR: The class status should not be changed. You have to delete the child record first then you can change the status.');
                            $("#notification-error").show();
                            $("#notification-success").hide();
                        }
                        },
                        error: function(response) {
                            //alert('Error'+response.status);
                        }
                     });
            }
        });
    
    
        $(document).on('click', '.btn-delete', function (e) {
            var result = confirm("Do you want to delete this record?");
            if(result == true)
            {
                var base_URL         = $("#base_URL").val();
                //window.location.href = base_URL+'reader/get';
                var base_URL          = $("#base_URL").val();
                var deleteID = this.id;
                var data     = "deleteID="+deleteID; 
                $.ajax({
                    url: base_URL+"cls/delete",
                    data: data,
                    cache: false,
                    method: 'POST',
                    dataType: "html",
                    beforeSend: function( xhr ) {
                    $(".preloader").show();
                    },
                    success: function(data){
                        $(".preloader").hide();
                        result = $.parseJSON(data); 
                        if(result.status == 'success')
                        {
                            $("#notification-error").hide();
                            $("#notification-success").slideDown().html('Record succesfully deleted..');
                            load(1);
                            //window.location.href = base_URL+'cls/get';
                            return false;
                        }
                        else
                        {
                            $("#notification-error").html('ERROR: The class should not be deleted. You have to delete the child record first then you can delete the record.');
                            $("#notification-error").show();
                            $("#notification-success").hide();
                        }
                        },
                        error: function(response) {
                            //alert('Error'+response.status);
                        }
                     });
            }
        });
    
    
    
     /* ########## FOR FIRST LIME LOADING START ############# */
    //alert($( "#org_code option:selected" ).val());
    //alert($("#account_user_type").val());
    if($("#account_user_type").val() == 'account') {
        load(1);
    }
    else
        {
            var tmp_aid_segment = parseInt($("#tmp_aid_segment").val()); 
            var url      = window.location.href;  
            var segments = url.split( '/' );
            var tmp_aid = segments[tmp_aid_segment]; //alert(segments); 7
            if(tmp_aid){
            var tmp_str =   tmp_aid.split( '=' ); //alert(tmp_str[0]);
            if(tmp_str[0] == '?a_id') {
                load(1);
             }
            }
        }
    /* ########## FOR FIRST LIME LOADING END ############# */
    
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH ################# */
    /* ######################################################################## */
    function validate()
    {
        if($("#org_code").val() == '')
        {
            $("#org_code_msg").html('Please Select Account ORG Code..');
            $("#org_code_msg").show();
            return false;
        }
        else
        {
            $("#org_code_msg").hide();
        }
        return true;
    }
    
    // AJAX SEARCH START
    $(document).on('click', '#submit', function (e) {
			e.preventDefault();
			var base_URL      = $("#base_URL").val();
			var org_code      = $("#org_code").val();
			
			//alert(org_code); return false;
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
           if(cur_page) {
                load(cur_page);
            }
            else {
                load(1);
            }
	});
    // AJAX SEARCH END
    
    
    
    /* ######################################################################## */
    /* ############### AJAX LISTING WITH PAGINATION BY SEARCH END ############# */
    /* ######################################################################## */
    
    
});


// AJAX LOAD START
function load(page) { 
//var page_num=page;
var base_URL     = $("#base_URL").val();
var vendor_id = $("#vendor_id").val();
var data = "page="+page+"&v_id="+vendor_id;

    $.ajax({
        url: base_URL+"vendor/vendor_product_list_ajax/"+page,
        data: data,
        cache: false,
        method: 'GET',
        dataType: "html",
         beforeSend: function( xhr ) {
            $(".preloader").show();
          },
       success:function(res){ 
           result = $.parseJSON(res);
           //console.log(result);
           //console.log(result);
           $(".preloader").hide();
           $("#tcontent").html(result.table);
           $("#page").html(result.page);
       },
       error: function(response) {
        alert('Error ok '+response.table);
        }
   })

}
// AJAX LOAD END

