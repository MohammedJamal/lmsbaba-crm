<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
<head>
<?php $this->load->view('adminmaster/includes/head'); ?>   
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script> -->
</head>
<body>
<div class="app full-width">
<div class="main-panel">            
<div class="main-content">              
    <div class="content-view"> 
        <div class="topnav">
            <?php $this->load->view('adminmaster/includes/top_menu'); ?>            
        </div>
    	<div class="card process-sec">
    			<div class="filter_holder new">
              <div class="pull-left">
                <h5 class="lead_board">  </h5>
              </div>


                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_account_type">Service Call Type</label>
                            <select class="form-control" id="call_type" name="call_type">
                                <option value="">===Select One===</option>
                                <?php
                                if(is_array($service_call_type) && count($service_call_type)>0){
                                    foreach($service_call_type AS $type){ ?>
                                    <option value="<?php echo $type['id']; ?>" ><?php echo $type['name']; ?></option>
                                    <?php }
                                } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_is_active">Select Next Follow-Up Date</label>
                            <input type="date" id="selected_date" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter_is_active">Company Name/ID</label>
                            <input type="text" id="comp_name_id" placeholder="Enter Company Name or ID" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <label for="filter_is_active">&nbsp;</label><br>
                        <button type="button" class="btn btn-success" id="filter_service_call_confirm">Filter</button>
                        <button type="button" class="btn btn-info" id="filter_service_call_reset">Reset</button>
                        </div>
                    </div>
                    
                    
                </div>


               
                
          	</div>
			<div class="form-group">
		    	<div class="col-sm-12 text-center" id="response_div"></div>
                <input type="hidden" id="f_account_type" />
                <input type="hidden" id="f_is_active" />
		  </div>
    	</div>                	 
    </div>                
</div>
<div class="content-footer">
  <?php $this->load->view('adminmaster/includes/footer'); ?>
</div>
</div>
</div>
</body>
</html>
<script type="text/javascript">
    function load(page=1)
    {
        var base_url = $("#base_url").val();
        var f_account_type=$("#f_account_type").val();
        var f_is_active=$("#f_is_active").val();
		var call_type=$("#call_type").val();
		var selected_date=$("#selected_date").val();
        var comp_name_id=$("#comp_name_id").val();

        
        var data="account_type="+f_account_type+"&is_active="+f_is_active+"&call_type="+call_type+"&selected_date="+selected_date+"&comp_name_id="+comp_name_id+"&page="+page;       
        
        $.ajax({
        url: base_url + "my_pending_calls/get_all_client_wise_service_list_ajax/"+page,
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
        complete: function(){
            $.unblockUI();
        },
        success: function(data) {
            result = $.parseJSON(data); 	            
            if (result.status == 'success') {
                $("#response_div").html(result.html);
            }
            else
            {

            
            }
        }
        });
        
    }



$(document).ready(function(){
load();





$("body").on("click",".date_update_confirm",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var expire_date=$("#expire_date_"+cid).val();
    var data="cid="+cid+"&expire_date="+expire_date;    
      
    $.ajax({
    url: base_url + "my_pending_calls/update_expiry_date_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            alert("Successfully Updated.")
        }
        else
        {
            alert("Oops! Fail to update.");                    
        }
        load();
    }
    });
});

$("body").on("click",".update_row",function(e){
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var expire_date=$("#expire_date_"+cid).val();
    var data="cid="+cid;          
    $.ajax({
    url: base_url + "my_pending_calls/get_client_edit_view_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            $("#edit_view_html").html(result.html);
            $('#edit_view_modal').modal({backdrop: 'static',keyboard: false});
        }
        else
        {
            alert("Oops! Fail to update.");                    
        }
    }
    });   

});

    $("body").on("click",".tagged_user_list",function(e){

        var base_url = $("#base_url").val();
        var cid=$(this).attr("data-cid");
        var data="cid="+cid;

        $.ajax({
            url: base_url + "my_pending_calls/get_tagged_user_list_ajax",
            data: data,
            cache: false,
            method: 'POST',
            dataType: "html",
            beforeSend: function(xhr) {},
            complete: function(){},
            success: function(data) {
                result = $.parseJSON(data);  
                //alert(result.html);
                if (result.status == 'success') {
                    $("#user_list_view_html").html(result.html);
                    $('#comment_update').modal('hide');
                    $('#user_list_view_modal').modal({backdrop: 'static',keyboard: false});
                    
                }
            }
        });   

    });

    $("body").on("click","#edit_company_confirm",function(e){

        var base_url=$("#base_url").val();
        $.ajax({
            url: base_url + "my_pending_calls/save_client_edit_ajax",
            data: new FormData($('#clientForm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
            
            },
            complete: function (){
            
            },
            success: function(data){
            result = $.parseJSON(data);
            if (result.status == 'success') {
                alert("Successfully Updated.")
            }
            else
            {
                alert("Oops! Fail to update.");                    
            }
            $('#edit_view_modal').modal('hide');
            load();
            }
        }); 
    });

        $("body").on("click","#filter_service_call_confirm",function(e){         
            var filter_account_type=$("#filter_account_type").val();
            var filter_is_active=$("#filter_is_active").val();
            $("#f_account_type").val(filter_account_type);
            $("#f_is_active").val(filter_is_active);
            load();
        });
        $("body").on("click","#filter_service_call_reset",function(e){         
            var call_type=$("#call_type").val('');
		    var selected_date=$("#selected_date").val('');
            var comp_name_id=$("#comp_name_id").val('');
            load();
        });

        // $("body").on("click","#call_done",function(e){         
        //     if ($(this).prop('checked')==true){ 
        //         $("#shownxt_flwdate").hide();
        //     } else {
        //         $("#shownxt_flwdate").show();
        //     }
        // });

        

});


$("body").on("click",".view_comment_list",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "my_pending_calls/get_client_wise_comment_log_list_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        //alert(result.html);
        if (result.status == 'success') {
            $("#view_comment_list_html").html(result.html);
            $('#view_comment_list_modal').modal({backdrop: 'static',keyboard: false});
            
        }
    }
    });   

});

$("body").on("click",".call_comment_update_row",function(e){
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var sid=$(this).attr("data-sid");
    var tid=$(this).attr("data-tid");
    var title=$(this).attr("data-title");
    var scid=$(this).attr("data-scid");
    var data="cid="+cid+"&sid="+sid+"&tid="+tid+"&title="+title+"&scid="+scid;
    //alert(data);
    
    $.ajax({
    url: base_url + "my_pending_calls/get_call_comment_view_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            $("#call_comment_view_html").html(result.html);
            $("#comment_view_modal_title").html("("+title+")");
            $('#call_comment_update').modal({backdrop: 'static',keyboard: false});
            //alert(1);
            $( "#followup_date" ).datepicker({
                minDate: 0,
                maxDate: 15
            });
        }
        else
        {
            alert("Oops! Fail to update.");                    
        }
    }
    });   

});

        $("body").on("click",".comment_update_row",function(e){
        
        var base_url = $("#base_url").val();
        var cid=$(this).attr("data-cid");
        var tid=6;
        var title="Renewal Calls";
        var scid=$(this).attr("data-scid");
        
        var data="cid="+cid+"&tid="+tid+"&title="+title+"&scid="+scid;
        $.ajax({
        url: base_url + "renewal/get_client_comment_view_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function(xhr) {},
        complete: function(){},
        success: function(data) {
            result = $.parseJSON(data); 
            if (result.status == 'success') {
                $("#renewal_edit_view_html").html(result.html);
                $('#renewal_comment_update').modal({backdrop: 'static',keyboard: false});
                $("#followup_date").datepicker({
                    minDate: 0,
                    maxDate: 15
                }); 
            }
            else
            {
                alert("Oops! Fail to update.");                    
            }
        }
        });   

        });

$("body").on("click",".view_call_log_list",function(e){
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var sid=$(this).attr("data-sid");
    var data="cid="+cid+"&sid="+sid;
    
    $.ajax({
    url: base_url + "service_calls/get_call_log_list_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        //alert(result.html);
        if (result.status == 'success') {
            $("#view_call_log_list_html").html(result.html);
            $('#view_call_log_list_modal').modal({backdrop: 'static',keyboard: false});
            
        }
    }
    });   

});

$("body").on("click",".create_new_call",function(e){

var base_url = $("#base_url").val();
var cid=$(this).attr("data-cid");
var sid=$(this).attr("data-sid");
var data="cid="+cid+"&sid="+sid;

//alert(data);
$.ajax({
url: base_url + "renewal/get_create_call_view_popup_ajax",
data: data,
cache: false,
method: 'POST',
dataType: "html",
beforeSend: function(xhr) {},
complete: function(){},
success: function(data) {
    result = $.parseJSON(data);  
    if (result.status == 'success') {
        $("#create_call_view_html").html(result.html);
        $('#create_call_update').modal({backdrop: 'static',keyboard: false});
    }
    else
    {
        alert("Oops! Fail to update.");                    
    }
}
});   

});


    $("body").on("click","#edit_comment_confirm",function(e){
    //save_client_edit_ajax
        var base_url=$("#base_url").val();
        $.ajax({
            url: base_url + "renewal/save_comment_update_ajax",
            data: new FormData($('#clientForm')[0]),
            cache: false,
            method: 'POST',
            dataType: "html",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function( xhr ) {
            
            },
            complete: function (){
            
            },
            success: function(data){
            result = $.parseJSON(data);
            if (result.status == 'success') {
                alert("Your Comment Successfully Updated.")
                $('#renewal_comment_update').modal('hide');
                load();
            }
            else
            {
                alert(result.status); 
                                
            }
            
            }
        }); 
    });

$("body").on("click","#craete_call_confirm",function(e){
//save_renewal_call_ajax
var base_url=$("#base_url").val();
$.ajax({
    url: base_url + "renewal/create_renewal_call_ajax",
    data: new FormData($('#clientForm')[0]),
    cache: false,
    method: 'POST',
    dataType: "html",
    mimeType: "multipart/form-data",
    contentType: false,
    cache: false,
    processData:false,
    beforeSend: function( xhr ) {
    
    },
    complete: function (){
    
    },
    success: function(data){
    result = $.parseJSON(data);
    if (result.status == 'success') {
        alert("Your Call Successfully Updated.")
        $('#create_call_update').modal('hide');
        load();
    }
    else
    {
        alert(result.status); 
                        
    }
    
    }
}); 
});

$("body").on("click","#call_comment_confirm",function(e){
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url + "my_pending_calls/save_call_comment_update_ajax",
        data: new FormData($('#clientForm')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function( xhr ) {
        
        },
        complete: function (){
        
        },
        success: function(data){
        result = $.parseJSON(data);
        if (result.status == 'success') {
            alert("Your Comment Successfully Updated.")
            $('#call_comment_update').modal('hide');
            load();
        }
        else
        {
            alert(result.status); 
                            
        }
        
        }
    }); 
});

// pagination page click
$(document).on('click', '.client_list_page', function (e) { 
        e.preventDefault();
        var str = $(this).attr('href'); 
        var res = str.split("/");
        var cur_page = res[1];
        
        if(cur_page) {
            //alert(cur_page);
            load(cur_page);
        }
        else {
            load(1);
        }
});

</script>

<div id="view_comment_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Client All Log</h4>
            </div>
            <div class="modal-body" id="view_comment_list_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="view_call_log_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Client & Service wise all service calls log</h4>
            </div>
            <div class="modal-body" id="view_call_log_list_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="user_list_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">User List</h4>
            </div>
            <div class="modal-body" id="user_list_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="call_comment_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Service Call<span id="comment_view_modal_title"></span> Comments</h4>
            </div>
            <div class="modal-body" id="call_comment_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="create_call_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Renewal Call</h4>
            </div>
            <div class="modal-body" id="create_call_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="renewal_comment_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Update Renewal Comments</h4>
            </div>
            <div class="modal-body" id="renewal_edit_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>



