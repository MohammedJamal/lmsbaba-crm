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
		    	<div class="col-sm-12 text-center">


                <div class="">
  <h2>Inactive Client List </h2>
  <p></p>            
  <table id="datatable_list" class="table table-bordered" style="width:100%">
    <thead>
      <tr class="info">
        <th class="text-left sort_order asc" data-field="sln" data-orderby="desc">SL No.</th>
        <th>Type</th>
        <th class="text-left sort_order" data-field="companyname" data-orderby="desc">Company Details</th>
        <th class="text-left sort_order" data-field="lastlogin" data-orderby="desc">Last Login</th>
        <th class="text-left sort_order" data-field="notlogin" data-orderby="desc">Not Logged<br>in Since</th>
        <th class="text-left sort_order" data-field="startdate" data-orderby="desc">Start Date</th>
        <th class="text-left sort_order" data-field="enddate" data-orderby="desc">End Date</th>
        <th class="text-left sort_order" data-field="expirydate" data-orderby="desc">Expiry Date</th>
        <th class="text-left sort_order" data-field="lasttouch" data-orderby="desc">Last Touch</th>
        <th>Assigned To</th>
        <th>Client Activity<br>Status</th>
        <th>Call<br>Status & Update</th>
        <th>Next Follow-up</th>
        
      </tr>
    </thead>
    <tbody id="datatable_data">

    </tbody>
  </table>
  
  
</div>
<script>
$(document).ready(function(){
  $( ".display_date" ).datepicker({dateFormat: 'dd-M-yy'});
});
    
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
</body>
</html>
<style>
    .datatable .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{
        padding: 13px 6px !important;
    }
</style>

<script src="<?=assets_url();?>vendor/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=assets_url();?>vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    function load(page=1)
    {
        var base_url = $("#base_url").val();
        var f_account_type=1;
        var f_is_active='Y';
        var data="account_type="+f_account_type+"&is_active="+f_is_active+"&page="+page;      
        
        $.ajax({
        url: base_url + "inactive_clients/get_inactive_client_list_ajax",
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

});

// $("body").on("click","#call_done",function(e){         
//     if ($(this).prop('checked')==true){ 
//         $("#shownxt_flwdate").hide();
//     } else {
//         $("#shownxt_flwdate").show();
//     }
// });

$("body").on("click",".sort_order",function(e){
      var tmp_field=$(this).attr('data-field');
      var curr_orderby=$(this).attr('data-orderby');
      var new_orderby=(curr_orderby=='asc')?'desc':'asc';
      $(this).attr('data-orderby',new_orderby);
      $(".sort_order").removeClass('asc');
      $(".sort_order").removeClass('desc');
      $(this).addClass(curr_orderby);
      $("#filter_sort_by").val(tmp_field+'-'+curr_orderby);
      //load(1);
      //alert(tmp_field+'/'+curr_orderby+'/'+new_orderby)
    });

$("body").on("click",".comment_update_row",function(e){
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var tid=$(this).attr("data-tid");
    var title=$(this).attr("data-title");
    var scid=$(this).attr("data-scid");
    var data="cid="+cid+"&tid="+tid+"&title="+title+"&scid="+scid;

    $.ajax({
    url: base_url + "inactive_clients/get_client_comment_view_ajax",
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
$("body").on("click",".tagged_user_list",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "inactive_clients/get_inactive_client_wise_user_list_ajax",
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


$("body").on("click",".renewal_comment_update_row",function(e){
        
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
$("body").on("click",".view_comment_list",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "inactive_clients/get_inactive_client_wise_comment_log_list_ajax",
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

$("body").on("click","#inactive_edit_comment_confirm",function(e){
    var base_url=$("#base_url").val();
    $.ajax({
        url: base_url + "inactive_clients/save_comment_update_ajax",
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

<div id="user_list_view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="common_view_modal_title">Tagged User List</h4>
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
                <h4 class="modal-title" id="common_view_modal_title">Update Inactive Client Comments</h4>
            </div>
            <div class="modal-body" id="edit_view_html"></div>
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



