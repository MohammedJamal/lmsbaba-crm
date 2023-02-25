$(document).ready(function(){


    $(".parent_access:checked").each(function() {
        $(".child_access_"+$(this).attr('data-id')).attr("disabled",false);
    });    

    $('.parent_access').click(function(){
    	
	    if($(this).prop("checked") == true){
	        $(".child_access_"+$(this).attr('data-id')).attr("disabled",false);
	        $(".child_access_"+$(this).attr('data-id')).prop("checked",true);
	    }
	    else if($(this).prop("checked") == false){
	        $(".child_access_"+$(this).attr('data-id')).attr("disabled",true);
	        $(".child_access_"+$(this).attr('data-id')).prop("checked",false);
	    }
	});

});