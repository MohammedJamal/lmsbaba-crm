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
                            <label>Filter Demo</label>
                            <select class="form-control" id="filter_demo_type" name="filter_demo_type">
                            <option value="" >===Select One===</option>        
                                <option value="A" >All Demo</option>
                                <option value="S" selected="selected" >Scheduled Demo</option>
                                <option value="D" >Demo Done</option>
                                <option value="C" >Demo Cancelled</option>
                                <option value="CM" >Demo Confirm</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Year</label>
                            <select class="form-control" id="filter_year" name="filter_year">
                            <option value="" >===Select One===</option>        
                            <?php
                            $current_year=date('Y');
                            for($i=0; $i<10; $i++){
                                $yearlist=$current_year+$i;
                                echo'<option value="'.$yearlist.'" >'.$yearlist.'</option>';
                            } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Month </label>
                            <select class="form-control" id="filter_month" name="filter_month">
                            <option value="" >===Select One===</option>        
                                <option value="01" >January</option>
                                <option value="02" >February</option>
                                <option value="03" >March</option>
                                <option value="04" >April</option>
                                <option value="05" >May</option>
                                <option value="06" >June</option>
                                <option value="07" >July</option>
                                <option value="08" >August</option>
                                <option value="09" >September</option>
                                <option value="10" >October</option>
                                <option value="11" >November</option>
                                <option value="12" >December</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="filter_renewal_confirm">Filter</button>
                        </div>
                    </div>
                </div>

                


              
          	</div>
			<div class="form-group">
		    	<div class="col-sm-12 text-center">

<!-- <div class="container"> -->
<div class="">
  <h2>Scheduled Demo </h2>
  <p></p>
  <button style="float: right;" type="button" class="btn btn-success" id="add_demo"><i class="fa fa-plus" aria-hidden="true"></i> Add New Demo </button>
  <div id="datatable_data"></div>            
  
  <div id="testdata"></div>
</div>
<script>   
// $(document).ready(function(){
//   $( ".display_date" ).datepicker({dateFormat: 'dd-M-yy'});
// });
</script>                

                </div>
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


<script src="<?php echo assets_url();?>vendor/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo assets_url();?>vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" href="<?php echo assets_url();?>plugins/select2/css/select2.min.css">
<script src="<?php echo assets_url(); ?>plugins/select2/js/select2.min.js" type="text/javascript"></script>

</body>
</html>

<script type="text/javascript">
    function load(page=1)
    {
        var base_url = $("#base_url").val();
        var f_is_active='Y';

        var filter_year=$("#filter_year").val();
        var filter_month=$("#filter_month").val();
        // if(filter_year.trim()!="" || filter_month.trim()!=""){
        //     $("#filter_data_type").val('');
        // }
        var filter_demo_type=$("#filter_demo_type").val();
        
        var data="&is_active="+f_is_active+"&filter_demo_type="+filter_demo_type+"&filter_year="+filter_year+"&filter_month="+filter_month+"&page="+page;      
        
        $.ajax({
        url: base_url + "demo/get_demo_list_ajax",
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
           // alert(result.html);	            
            if (result.status == 'success') {
                //$("#testdata").html(result.html);
                
                $("#datatable_data").html(result.html);
                $.unblockUI();
                $('#datatable_list').DataTable();
            }
            else
            {

            
            }
        }
        });
    }

    $(document).ready(function(){
        load();
        
        $('#filter_data_type').on('change', function() {
            $("#filter_year").val('');
            $("#filter_month").val('');
        });

    });

    $("body").on("click","#filter_renewal_confirm",function(e){         
        load();
    });





// 

    
	
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


$("body").on("click","#add_demo",function(e){
    $("#adddemo_view_html").html('');   
    var base_url = $("#base_url").val();
    var data='';
    $.ajax({
    url: base_url + "demo/get_add_demo_view_ajax",
    data: data,
    cache: false,
    method: 'POST',
    dataType: "html",
    beforeSend: function(xhr) {
        $("#adddemo_view_html").html('');
    },
    complete: function(){},
    success: function(data) {
        
        result = $.parseJSON(data);  
        if (result.status == 'success') {
            $("#adddemo_view_html").html(result.html);
            $('#add_new_workorder').modal({backdrop: 'static',keyboard: false}); 
        }
    }
    }); 
});


$("body").on("click","#add_workorder_confirm",function(e){
    
    var base_url = $("#base_url").val();
    
    $.ajax({
    url: base_url + "demo/update_demo_order",
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
            window.location.href=base_url+"demo";
        }
        else
        {
            $("#addworkorder_errmsg_html").html(result.html);                   
        }
    }
    });   

});


$("body").on("click",".comment_update_row",function(e){
        
        var base_url = $("#base_url").val();
        var id=$(this).attr("data-id");
        //var tid=6;
        var title="Demo Done";
        var lid=$(this).attr("data-lid");
        
        var data="id="+id+"&title="+title+"&lid="+lid;
        $.ajax({
        url: base_url + "demo/get_demo_list_view_ajax",
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
                $('#comment_update').modal({backdrop: 'static',keyboard: false});
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



    $("body").on("click","#edit_done_demo",function(e){
//save_client_edit_ajax
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url + "demo/save_demo_done_update_ajax",
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
            alert("Your Demo Successfully Done.")
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




$("body").on("click",".comment_confirm_row",function(e){
        
        var base_url = $("#base_url").val();
        var id=$(this).attr("data-id");
        //var tid=6;
        var title="Demo Confirm";
        var lid=$(this).attr("data-lid");
        
        var data="id="+id+"&title="+title+"&lid="+lid;
        $.ajax({
        url: base_url + "demo/get_demo_confirm_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function(xhr) {},
        complete: function(){},
        success: function(data) {
            result = $.parseJSON(data); 
            if (result.status == 'success') {
                $("#edit_confirm_view_html").html(result.html);
                $('#demo_confirm_update').modal({backdrop: 'static',keyboard: false});
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



    $("body").on("click","#edit_confirm_demo",function(e){
//save_client_edit_ajax
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url + "demo/save_demo_confirm_update_ajax",
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
            alert("Your Demo Successfully Confirmed.")
            $('#demo_confirm_update').modal('hide');
            load();
        }
        else
        {
            alert(result.status); 
                            
        }
        
        }
    }); 
});





$("body").on("click",".comment_reschedule_row",function(e){
        
        var base_url = $("#base_url").val();
        var id=$(this).attr("data-id");
        //var tid=6;
        var title="Demo Confirm";
        var lid=$(this).attr("data-lid");
        
        var data="id="+id+"&title="+title+"&lid="+lid;
        $.ajax({
        url: base_url + "demo/get_demo_reschedule_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function(xhr) {},
        complete: function(){},
        success: function(data) {
            result = $.parseJSON(data); 
            if (result.status == 'success') {
                $("#edit_reschedule_view_html").html(result.html);
                $('#demo_reschedule_update').modal({backdrop: 'static',keyboard: false});
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

    $("body").on("click","#edit_reschedule_demo",function(e){
//save_client_edit_ajax
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url + "demo/save_demo_confirm_update_ajax",
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
            alert("Your Demo Successfully Confirmed.")
            $('#demo_confirm_update').modal('hide');
            load();
        }
        else
        {
            alert(result.status); 
                            
        }
        
        }
    }); 
});

</script>



<div id="add_new_workorder" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-primary" id="common_view_modal_title">Add New Demo</h4>
                <hr>
            </div>
            <div class="modal-body" id="adddemo_view_html">
            
            </div>
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
                <h4 class="modal-title" id="common_view_modal_title">Demo Done</h4>
            </div>
            <div class="modal-body" id="edit_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="demo_confirm_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Confirm Demo</h4>
            </div>
            <div class="modal-body" id="edit_confirm_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div id="demo_reschedule_update" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Reschedule Demo</h4>
            </div>
            <div class="modal-body" id="edit_reschedule_view_html"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
