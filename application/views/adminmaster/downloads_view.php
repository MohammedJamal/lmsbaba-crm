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
        var f_account_type=1;
        var f_is_active='N';
        var data="account_type="+f_account_type+"&is_active="+f_is_active+"&page="+page;       
        
        $.ajax({
        url: base_url + "downloads/get_client_list_ajax",
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
        load();
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
        load();
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
            load();
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

$("body").on("click",".comment_update_row",function(e){

    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var tid=$(this).attr("data-tid");
    var title=$(this).attr("data-title");
    var scid=$(this).attr("data-scid");
    var data="cid="+cid+"&tid="+tid+"&title="+title+"&scid="+scid;
    
    //alert(data);
    $.ajax({
    url: base_url + "downloads/get_call_comment_view_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            $("#editcomment_view_html").html(result.html);
            $('#comment_update').modal({backdrop: 'static',keyboard: false});
        }
        else
        {
            alert("Oops! Fail to update.");                    
        }
    }
    });   

});

    $("body").on("click",".create_new_call",function(e){

        var base_url = $("#base_url").val();
        var cid=$(this).attr("data-cid");
        var data="cid="+cid;

        //alert(data);
        $.ajax({
        url: base_url + "downloads/get_create_call_view_popup_ajax",
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

    $("body").on("click","#call_done",function(e){         
        if ($(this).prop('checked')==true){ 
            $("#shownxt_flwdate").hide();
        } else {
            $("#shownxt_flwdate").show();
        }
    });

$("body").on("click",".view_comment_list",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "downloads/get_download_comment_log_list_ajax",
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

$("body").on("click","#edit_comment_confirm",function(e){
//save_client_edit_ajax
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url + "downloads/save_comment_update_ajax",
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
            $('#comment_update').modal('hide');
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
//save_client_edit_ajax
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url + "downloads/create_downloads_call_ajax",
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

<div id="comment_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Update Downloads Comments</h4>
            </div>
            <div class="modal-body" id="editcomment_view_html"></div>
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
                <h4 class="modal-title">Create Downloads Call</h4>
            </div>
            <div class="modal-body" id="create_call_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>



