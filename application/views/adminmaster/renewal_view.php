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
                            <label>Show Data Type</label>
                            <select class="form-control" id="filter_data_type" name="filter_data_type">
                            <option value="" >===Select One===</option>        
                                <option value="R" >Renewal</option>
                                <option value="PR" >Pending Renewal</option>
                                <option value="ALL" >Show All</option>
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
  <h2>Renewal List </h2>
  <p></p>
  <div id="datatable_data"></div>            
  <!-- <table id="datatable_list" class="table table-bordered" style="width:100%">
    <thead>
      <tr class="info">
        <th class="text-left sort_order asc" data-field="sln" data-orderby="desc">SL No.</th>
        <th>Type</th>
        <th class="text-left sort_order" data-field="companyname" data-orderby="desc">Company</th>
        <th class="text-left sort_order" data-field="module_service" data-orderby="desc">Module & Service</th>
        <th class="text-left sort_order" data-field="taggeduser" data-orderby="desc">No of <br>User(s)</th>
        <th class="text-left sort_order" data-field="amount" data-orderby="desc">Amount</th>
        <th class="text-left sort_order" data-field="lastlogin" data-orderby="desc">Last Login</th>
        <th class="text-left sort_order" data-field="notlogin" data-orderby="desc">Not Logged <br>in Since</th>
        <th class="text-left sort_order" data-field="startdate" data-orderby="desc">Start Date</th>
        <th class="text-left sort_order" data-field="enddate" data-orderby="desc">End Date</th>
        <th class="text-left sort_order" data-field="expirydate" data-orderby="desc">Expiry Date</th>
        <th class="text-left sort_order" data-field="lasttouch" data-orderby="desc">Last Touch</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="datatable_data">

    </tbody>
  </table> -->
  
  <div id="testdata"></div>
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

<style>
    .datatable .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{
        padding: 13px 6px !important;
    }
</style>

</body>
</html>


<script src="<?=assets_url();?>vendor/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=assets_url();?>vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">

    function load(page=1)
    {
        var base_url = $("#base_url").val();
        var f_account_type=1;
        var f_is_active='Y';

        var filter_year=$("#filter_year").val();
        var filter_month=$("#filter_month").val();
        // if(filter_year.trim()!="" || filter_month.trim()!=""){
        //     $("#filter_data_type").val('');
        // }
        var show_data_type=$("#filter_data_type").val();
        
        var data="account_type="+f_account_type+"&is_active="+f_is_active+"&show_data_type="+show_data_type+"&filter_year="+filter_year+"&filter_month="+filter_month+"&page="+page;      
        
        $.ajax({
        url: base_url + "renewal/get_renewal_list_ajax",
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

    // $("body").on("click","#call_done",function(e){         
    //     if ($(this).prop('checked')==true){ 
    //         $("#shownxt_flwdate").hide();
    //     } else {
    //         $("#shownxt_flwdate").show();
    //     }
    // });

$("body").on("click",".tagged_user_list",function(e){

    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "renewal/get_renewal_wise_user_list_ajax",
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

$("body").on("click",".view_comment_list",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "renewal/get_renewal_wise_comment_log_list_ajax",
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

$("body").on("click",".view_services_list",function(e){
    
    
    var base_url = $("#base_url").val();
    var cid=$(this).attr("data-cid");
    var data="cid="+cid;
    
    $.ajax({
    url: base_url + "renewal/client_wise_service_list",
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
            $("#view_services_list_html").html(result.html);
            $('#view_services_list_modal').modal({backdrop: 'static',keyboard: false});
            
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
			
			
				$('#view_services_list_modal').modal('hide');
                $('#service_order_detail_log_view_html').html(result.html);
                $('#service_order_detail_log_view_modal').modal({backdrop: 'static',keyboard: false});
            }
            else
            {

            
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

<div id="view_services_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Client Wise Service List</h4>
            </div>
            <div class="modal-body" id="view_services_list_html"></div>
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
                <h4 class="modal-title" id="common_view_modal_title">Update Renewal Comments</h4>
            </div>
            <div class="modal-body" id="edit_view_html"></div>
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



