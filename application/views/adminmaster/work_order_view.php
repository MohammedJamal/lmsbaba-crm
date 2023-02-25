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
                <h5 class="lead_board"></h5>
              </div>

            <?php if( $this->session->flashdata('success_msg') ){ ?>
            <!--  success message area start  -->
            <div class="alert alert-success alert-alt" style="display:block;" id="notification-success">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="fa fa-check-circle"></i> Success</h4> <span id="success_msg">
                <?php echo $this->session->flashdata('success_msg'); ?></span>
            </div>
                <!--  success message area end  -->
            <?php } ?>

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
        padding: 6px 6px !important;
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
        url: base_url + "work_order/get_users_list_ajax",
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

});





$("body").on("click","#add_work_order",function(e){
    $("#addworkorder_view_html").html('');   
    var base_url = $("#base_url").val();
    var data='';
    $.ajax({
    url: base_url + "work_order/get_add_work_order_view_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {
        $("#addworkorder_view_html").html('');
    },
    complete: function(){},
    success: function(data) {
        
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            $("#addworkorder_view_html").html(result.html);
            $('#add_new_workorder').modal({backdrop: 'static',keyboard: false}); 
        }
    }
    }); 
});

$("body").on("click","#add_workorder_confirm",function(e){
    
    var base_url = $("#base_url").val();
    
    $.ajax({
    url: base_url + "work_order/update_work_order",
    data: new FormData($('#workorderForm')[0]),
    cache: false,
    method: 'POST',
    dataType: "html",
    mimeType: "multipart/form-data",
    contentType: false,
    cache: false,
    processData:false,

    beforeSend: function(xhr) {
        $("#addworkorder_errmsg_html").html('');
    },
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            window.location.href=base_url+"work_order";
        }
        else
        {
            $("#addworkorder_errmsg_html").html(result.html);                   
        }
    }
    });   

});

$("body").on("click","#edit_user_view",function(e){
    var base_url = $("#base_url").val();
    var worder=$(this).attr("data-worder");
    var data="worder="+worder;
    
    $.ajax({
    url: base_url + "work_order/get_edit_work_order_view_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {},
    complete: function(){},
    success: function(data) {
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            $("#editworkorder_view_html").html(result.html);
            $('#editworkorder_view').modal({backdrop: 'static',keyboard: false});
        }
        else
        {
            alert("Oops! Fail to update.");                    
        }
    }
    });   

});

$("body").on("click","#edit_workorder_confirm",function(e){
    
    var base_url = $("#base_url").val();
        
    $.ajax({
        url: base_url + "work_order/update_work_order",
        data: new FormData($('#workorderForm')[0]),
        cache: false,
        method: 'POST',
        dataType: "html",
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(xhr) {
            $("#edituser_errmsg_html").html('');
        },
        complete: function(){},
        success: function(data) {
            result = $.parseJSON(data);  
            if (result.status == 'success') {
                window.location.href=base_url+"work_order";
            }
            else
            {
                $("#edituser_errmsg_html").html(result.html);                   
            }
        }
    });   

});

$("body").on("click","#delete_workorder",function(e){

    if (confirm("Are you sure to delete this user ?") == true) {
        
        var base_url = $("#base_url").val();
        var uid=$(this).attr("data-uid");
        var data="uid="+uid;  

        $.ajax({
        url: base_url + "work_order/delete_workorder",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function(xhr) {  },
        complete: function(){},
        success: function(data) {
            result = $.parseJSON(data);  
            if (result.status == 'success') {
                window.location.href=base_url+"work_order";
            }
        }
        }); 


    }
});

    
$("body").on("click",".parent_access",function(e){
    
    if($(this).prop("checked") == true){
        $(".child_access_"+$(this).attr('data-id')).attr("disabled",false);
        $(".child_access_"+$(this).attr('data-id')).prop("checked",true);
    }
    else if($(this).prop("checked") == false){
        $(".child_access_"+$(this).attr('data-id')).attr("disabled",true);
        $(".child_access_"+$(this).attr('data-id')).prop("checked",false);
    }
});

function GetStateList_maindb(cont)
{
    var base_url = $("#base_url").val();
  $.ajax({
      url: base_url + "work_order/getstatelist",
      type: "POST",
      data: {'country_id':cont},      
      success: function (response) 
      {
        if(response!='')
        {
        document.getElementById('state_id').innerHTML=response;
      }
          
      },
      error: function () 
      {
       //$.unblockUI();
       alert('Something went wrong there');
      }
  });
}

function GetCityList_maindb(state)
{
    var base_url = $("#base_url").val();
  $.ajax({
      url: base_url + "work_order/getcitylist",
      type: "POST",
      data: {'state_id':state},     
      success: function (response) 
      {
        if(response!='')
        {
        document.getElementById('city_id').innerHTML=response;
      }
          
      },
      error: function () 
      {
       //$.unblockUI();
       alert('Something went wrong there');
      }
     });
}

</script>

<div id="editworkorder_view" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-primary" id="common_view_modal_title">Edit Work Order Details</h4>
                <hr>
            </div>
            <div class="modal-body" id="editworkorder_view_html">
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<div id="add_new_workorder" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-primary" id="common_view_modal_title">Add New Work Order</h4>
                <hr>
            </div>
            <div class="modal-body" id="addworkorder_view_html">
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>



