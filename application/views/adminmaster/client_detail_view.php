<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
<head>
<?php $this->load->view('adminmaster/includes/head'); ?> 
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
		    	<div class="col-sm-12 text-center">
                    <div class="container">
                        <h2 class="text-left">
                            <?php echo $client_info->name; ?> - <?php echo $client_info->client_id; ?> 
                            <a href="<?php echo adminportal_url(); ?>client" class="pull-right btn"><font style="font-size: 16px;"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</font> </a>
                        </h2>
                        <div>&nbsp;</div>
                        <div clas="row">
                            <div class="col-md-12 text-left">
                                <a href="<?php echo adminportal_url(); ?>client/manage_user/<?php echo $client_info->client_id; ?>"  class=" btn-primary">Manage User </a> &nbsp;
                                <a href="JavaScript:void(0)" id="add_service" data-client_id="<?php echo $client_info->client_id; ?>" class=" btn-primary">Add Service</a>
                            </div>
                        </div>
                        <div>&nbsp;</div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-left"><u>Services</u>  </h3>
                                <p></p>
                                <div id="client_service_list_response_div"></div>                                                                
                            </div>
                        </div>

                        <?php /* ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-left"><u>User List</u> <a href="<?php echo adminportal_url(); ?>client/manage_user/<?php echo $client_info->client_id; ?>"  class="pull-right btn-primary">Manage User </a></h3>
                                <p></p>            
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Tagged Services</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($user_list)){ ?>
                                            <?php foreach($user_list AS $user){ ?>
                                        <tr>
                                            <td class="text-left"><?php echo $user['id']; ?></td>
                                            <td class="text-left"><?php echo $user['name']; ?></td>
                                            <td class="text-left"><?php echo ($user['email'])?$user['email']:'-'; ?></td>
                                            <td class="text-left"><?php echo ($user['mobile'])?$user['mobile']:'-'; ?></td>
                                            <td class="text-left"><?php if($user['status']=='0'){echo'<b class="text-success">Active</b>';}else if($user['status']=='1'){echo'<b class="text-warning">In-Active</b>';}else if($user['status']=='2'){echo'<b class="text-danger">Deleted</b>';}?></td>
                                            <td class="text-left">--</td>
                                        </tr>
                                        <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php */ ?>
                        
                    </div>
                </div>
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
<script>
$(document).ready(function(){
    load_client_service();
    $('.display_date').datepicker({
      dateFormat: "dd-M-yy",
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+5'
    });
    // $( ".display_date" ).datepicker({dateFormat: 'dd-M-yy'});


    $("body").on("click","#add_service",function(e){
        var client_id=$(this).attr("data-client_id");
        $("#service_id option:first").attr('selected','selected');
        $("#display_name").val('');                   
        $("#no_of_user option:first").attr('selected','selected');
        $("#price").val('');
        $("#start_date").val('');
        $("#end_date").val('');
        $("#expiry_date").val('');
        $("#service_order_detail_id").val('');
        $("#action").val('');
        $('#add_service_view_modal').modal({backdrop: 'static',keyboard: false});
    });
    

    $("body").on("click",".renew_service",function(e){
        var service_order_detail_id=$(this).attr("data-service_order_detail_id");
        var client_id=$(this).attr("data-client_id");
        var service_id=$(this).attr("data-service_id");        
        var display_name=$(this).attr("data-display_name");
        var no_of_user=$(this).attr("data-no_of_user");
        var price=$(this).attr("data-price");
        var start_date=$(this).attr("data-start_date");
        var end_date=$(this).attr("data-end_date");
        var expiry_date=$(this).attr("data-expiry_date");         
        var renewal_start_date=$(this).attr("data-renewal_start_date");
        var renewal_end_date=$(this).attr("data-renewal_end_date");
        var renewal_expiry_date=$(this).attr("data-renewal_expiry_date"); 
        var service_status=$(this).attr("data-service_status");
        
        
        $("#service_id").val(service_id).attr("selected","selected").attr('disabled','disabled');
        $("#display_name").val(display_name).attr("readonly",true);
        $("#no_of_user").val(no_of_user).attr("selected","selected").attr('disabled','disabled');    
        $("#price").val(price);
        $("#start_date").val(renewal_start_date);
        $("#end_date").val(renewal_end_date);
        $("#expiry_date").val(renewal_expiry_date);
        $("#service_status").val(service_status);        
        $("#service_order_detail_id").val(service_order_detail_id);
        $("#action").val('');
        
        $('#add_service_view_modal').modal({backdrop: 'static',keyboard: false});
    });

    $("body").on("click",".edit_service",function(e){
        var service_order_detail_id=$(this).attr("data-service_order_detail_id");
        var client_id=$(this).attr("data-client_id");
        var service_id=$(this).attr("data-service_id");        
        var display_name=$(this).attr("data-display_name");
        var no_of_user=$(this).attr("data-no_of_user");
        var price=$(this).attr("data-price");
        var start_date=$(this).attr("data-start_date");
        var end_date=$(this).attr("data-end_date");
        var expiry_date=$(this).attr("data-expiry_date");   
        var service_status=$(this).attr("data-service_status");     
        
        $("#service_id").val(service_id).attr("selected","selected").attr('disabled','disabled');
        $("#display_name").val(display_name).attr("readonly",false);
        $("#no_of_user").val(no_of_user).attr("selected","selected").attr('disabled',false);    
        $("#price").val(price);
        $("#start_date").val(start_date);
        $("#end_date").val(end_date);
        $("#expiry_date").val(expiry_date);
        $("#service_status").val(service_status);
        $("#service_order_detail_id").val(service_order_detail_id);
        $("#action").val('edit');        
        $('#add_service_view_modal').modal({backdrop: 'static',keyboard: false});
    });

    $("body").on("click","#add_service_confirm",function(e){

        var base_url=$("#base_url").val();
        var service_id=$("#service_id").val();
        var display_name=$("#display_name").val();                   
        var no_of_user=$("#no_of_user").val();          
        var price=$("#price").val();
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var expiry_date=$("#expiry_date").val();
        var service_status=$("#service_status").val();
        var service_order_detail_id=$("#service_order_detail_id").val();
        if(display_name=='')
        {
            alert("Service Title should not be blank!");
            return false;
        }
        if(price=='')
        {
            alert("Price should not be blank!");
            return false;
        }
        if(start_date=='')
        {
            alert("Start Date should not be blank!");
            return false;
        }
        if(end_date=='')
        {
            alert("End Date should not be blank!");
            return false;
        }
        if(expiry_date=='')
        {
            alert("Expiry Date should not be blank!");
            return false;
        }
        if(service_status=='')
        {
            alert("Service Status should not be blank!");
            return false;
        }


        $.ajax({
            url: base_url + "client/add_client_service_order_ajax",
            data: new FormData($('#clientServiceForm')[0]),
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
                    alert("Successfully Updated.");
                    
                    $("#service_id option:first").attr('selected','selected');
                    $("#display_name").val('');                   
                    $("#no_of_user option:first").attr('selected','selected');
                    $("#price").val('');
                    $("#start_date").val('');
                    $("#end_date").val('');
                    $("#expiry_date").val('');
                    $("#service_order_detail_id").val('');
                }
                else if (result.status == 'fail') {
                    alert(result.msg);
                }
                else
                {
                    alert("Oops! Fail to update.");                    
                }
                $('#add_service_view_modal').modal('hide');
                load_client_service();
            }
        }); 
        });

    $("body").on("click",".get_service_detail_log",function(e){
        var service_order_detail_id=$(this).attr("data-service_order_detail_id");
        var base_url = $("#base_url").val();        
        var data="service_order_detail_id="+service_order_detail_id;
        $.ajax({
        url: base_url + "client/get_client_service_order_detail_log_ajax",
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
                $('#service_order_detail_log_view_html').html(result.html);
                $('#service_order_detail_log_view_modal').modal({backdrop: 'static',keyboard: false});
            }
            else
            {

            
            }
        }
        });
    });
});
function load_client_service()
{
    var base_url = $("#base_url").val();
    var client_id=$("#client_id").val();
    var data="client_id="+client_id;        
    
    $.ajax({
    url: base_url + "client/get_client_service_order_liat_ajax",
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
            $("#client_service_list_response_div").html(result.html);
        }
        else
        {

        
        }
    }
    });
}
</script>
<div id="add_service_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Add Service</h4>
            </div>
            <div class="modal-body" id="add_service_view_html">
                <form id="clientServiceForm" name="clientServiceForm">
                    <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_info->client_id; ?>" />
                    <input type="hidden" name="service_order_detail_id" id="service_order_detail_id" value="" />
                    <input type="hidden" name="action" id="action" value="" />
                    <div class="form-group">
                        <label for="service_id">Service</label>
                        <select class="form-control" id="service_id" name="service_id">
                            <?php if(count($service_list)){ ?>
                                <?php foreach($service_list AS $service){ ?>
                                <option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="display_name">Service Title</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" placeholder="Enter title .." value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="no_of_user">No of user</label>
                                <select class="form-control" id="no_of_user" name="no_of_user">  
                                    <?php for($i=1;$i<=100;$i++){ ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> user<?php echo ($i>1)?'s':''; ?></option>
                                    <?php } ?>                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" id="price" name="price" placeholder="Enter price.." value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="text" class="form-control display_date" readonly="true" id="start_date" name="start_date" placeholder="Enter Start Date.." value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="text" class="form-control display_date" readonly="true" id="end_date" name="end_date" placeholder="Enter End Date.." value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="text" class="form-control display_date" readonly="true" id="expiry_date" name="expiry_date" placeholder="Enter Expiry Date.." value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Service Status</label>
                                <select class="form-control" id="service_status" name="service_status">  
                                    <option value="Y">Enable</option>
                                    <option value="N">Disable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="add_service_confirm">Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="service_order_detail_log_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Service Order Log</h4>
            </div>
            <div class="modal-body" id="service_order_detail_log_view_html">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>