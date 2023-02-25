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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filter_account_type">Account Type</label>
                            <select class="form-control" id="filter_account_type" name="filter_account_type">
                                <option value="" >===Select One===</option>             
                                <?php if(count($account_type)){ ?>
                                    <?php foreach($account_type AS $type){ ?>
                                    <option value="<?php echo $type['id']; ?>" <?php if($client_row->account_type_id==$type['id']){echo'SELECTED';} ?>><?php echo $type['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filter_is_active">Status</label>
                            <select class="form-control" id="filter_is_active" name="filter_is_active">   
                                <option value="" >===Select One===</option>        
                                <option value="Y" >Enable</option>
                                <option value="N" >Disable</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="filter_company_confirm">Filter</button>
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

<style>
    .datatable .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{
        padding: 13px 6px !important;
    }
</style>

</body>
</html>
<script type="text/javascript">
    function load(page=1)
    {
        var base_url = $("#base_url").val();
        var f_account_type=$("#f_account_type").val();
        var f_is_active=$("#f_is_active").val();
        var data="account_type="+f_account_type+"&is_active="+f_is_active+"&page="+page;       
        
        $.ajax({
        url: base_url + "client/get_client_liat_ajax/"+page,
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


    // START FOR MULTIPLE CHECK BOX PART ===============================================================
        $("body").on("change","#client_all_checkbox",function(e){
        
            $('input:checkbox[name="checked_to_customer"]').prop('checked', $(this).prop("checked"));
        
            if($(this).prop("checked") == true){
                showUpdateBt();
            }else{
                hideUpdateBt();
            }

        });
    

        

        $("body").on("change",'#tcontent input[name="checked_to_customer"]',function(e){

            var totalcheck = $('#tcontent input[name="checked_to_customer"]').length;

            var countCheckedCheckboxes = $('#tcontent').find('input[name="checked_to_customer"]:checked').length;

            if(countCheckedCheckboxes > 0 && countCheckedCheckboxes < totalcheck){
                $('#client_all_checkbox').prop('checked', false);
                showUpdateBt();
            }else if(countCheckedCheckboxes == totalcheck){
                $('#client_all_checkbox').prop('checked', true);
                showUpdateBt();
            }else{
                $('#client_all_checkbox').prop('checked', false);
                hideUpdateBt();
            }
        }); 
    
        function showUpdateBt(){
            $(".bulk_bt_holder").slideDown(300);
        }
        function hideUpdateBt(){
            $(".bulk_bt_holder").slideUp(300);
        }
        // END FOR MULTIPLE CHECK BOX PART ===============================================================
    


// ============================================
$("body").on("click",".update_package_end_date",function(e){
    var cid=$(this).attr("data-cid");
    $("#div_package_end_date_edit_html_"+cid).show();
    $("#span_package_end_date_show_"+cid).hide();

});
$("body").on("click",".package_end_date_close",function(e){
    var cid=$(this).attr("data-cid");
    $("#div_package_end_date_edit_html_"+cid).hide();
    $("#span_package_end_date_show_"+cid).show();

});

$("body").on("click",".package_end_date_update_confirm",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var package_end_date=$("#package_end_date_"+cid).val();
    var data="cid="+cid+"&package_end_date="+package_end_date; 
    
    var page_number=$("#page_number").val();
    
    $.ajax({
    url: base_url + "client/update_package_end_date_ajax",
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
        load(page_number);
    }
    });
});
// ============================================

// ============================================
$("body").on("click",".update_package_price",function(e){
    var cid=$(this).attr("data-cid");
    $("#div_package_price_edit_html_"+cid).show();
    $("#span_package_price_show_"+cid).hide();

});
$("body").on("click",".package_price_close",function(e){
    var cid=$(this).attr("data-cid");
    $("#div_package_price_edit_html_"+cid).hide();
    $("#span_package_price_show_"+cid).show();

});

$("body").on("click",".package_price_update_confirm",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var package_price=$("#package_price_"+cid).val();
    var data="cid="+cid+"&package_price="+package_price;  
    
    var page_number=$("#page_number").val();
    
    $.ajax({
    url: base_url + "client/update_package_price_ajax",
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
        load(page_number);
    }
    });
});
// ============================================
$("body").on("click",".date_close",function(e){
    var cid=$(this).attr("data-cid");
    $("#div_expire_date_edit_html_"+cid).hide();
    $("#span_expire_date_show_"+cid).show();

});
$("body").on("click",".update_expiry_date",function(e){
    var cid=$(this).attr("data-cid");
    $("#div_expire_date_edit_html_"+cid).show();
    $("#span_expire_date_show_"+cid).hide();

});
$("body").on("click",".date_update_confirm",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var expire_date=$("#expire_date_"+cid).val();
    var data="cid="+cid+"&expire_date="+expire_date;    
    var page_number=$("#page_number").val();
      
    $.ajax({
    url: base_url + "client/update_expiry_date_ajax",
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
        load(page_number);
    }
    });
});

$("body").on("click",".update_row",function(e){
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var expire_date=$("#expire_date_"+cid).val();
    var data="cid="+cid;          
    $.ajax({
    url: base_url + "client/get_client_edit_view_ajax",
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

$("body").on("click",".sold_user_list",function(e){
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;          
    $.ajax({
    url: base_url + "client/get_client_wise_user_list_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            $("#user_list_view_html").html(result.html);
            $('#user_list_view_modal').modal({backdrop: 'static',keyboard: false});
        }
    }
    });   

});

    $("body").on("click","#edit_company_confirm",function(e){

        var base_url=$("#base_url").val();
        var page_number=$("#page_number").val();
        $.ajax({
            url: base_url + "client/save_client_edit_ajax",
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
            load(page_number);
            }
        }); 
    });

        $("body").on("click","#filter_company_confirm",function(e){         
            var filter_account_type=$("#filter_account_type").val();
            var filter_is_active=$("#filter_is_active").val();
            $("#f_account_type").val(filter_account_type);
            $("#f_is_active").val(filter_is_active);
            load();
        });

});


$("body").on("click",".view_comment_list",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "client/get_client_wise_comment_log_list_ajax",
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

$("body").on("click","#client_assigne_change_multiple",function(e){
    
    var base_url = $("#base_url").val();
    var assigne_user_id=$("#assigne_user_id").val();
    var page_number=$("#page_number").val();

    if(assigne_user_id==''){
        alert("Please select user to assign.");
        return false;
    }

    var c_id_arr = []; 
    $.each($("input[name='checked_to_customer']:checked"), function(){            
        c_id_arr.push($(this).val());
    });
    var client_id_str=c_id_arr.toString(',');

    var data="assigne_user_id="+assigne_user_id+"&client_id_str="+client_id_str;
    
    $.ajax({
    url: base_url + "client/change_client_assigned_multiple_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {
        $("#client_assigne_change_multiple").html('<i class="fa fa-hand-paper-o" aria-hidden="true"></i> Please Wait... ');
    },
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        //alert(result.html);
        if (result.status == 'success') {
            
            load(page_number);
        }
        $("#client_assigne_change_multiple").html('<i class="fa fa-refresh" aria-hidden="true"></i> Bulk Assignee ');
    }
    });   

});

$("body").on("click",".client_assigne_change_single",function(e){
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var exas_id=$(this).attr("data-exas_id");
    
    var data="cid="+cid+"&exas_id="+exas_id;
    
    $.ajax({
    url: base_url + "client/change_client_assigned_single_view_ajax",
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
            $("#client_assigned_single_view_html").html(result.html);
            $('#client_assigned_single_view_model').modal({backdrop: 'static',keyboard: false});
        }
        
    }
    });   

});

$("body").on("click","#single_assigne_client_confirm",function(e){
    
    var base_url = $("#base_url").val();
    var client_id=$("#single_assigne_client_id").val();
    var user_id=$("#single_assigne_user_id").val();
    var page_number=$("#page_number").val();

    if(user_id==''){
        $("#single_assigne_client_errmsg_html").html("Please select user to assign.");
        return false;
    }

    var data="client_id="+client_id+"&user_id="+user_id;
    
    $.ajax({
    url: base_url + "client/change_client_assigned_single_ajax",
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
            $("#client_assigned_single_view_model").modal('hide');
            load(page_number);
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

<div id="edit_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Edit Company Details</h4>
            </div>
            <div class="modal-body" id="edit_view_html"></div>
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

<div id="client_assigned_single_view_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Update Client Assign to User</h4>
            </div>
            <div class="modal-body" id="client_assigned_single_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

