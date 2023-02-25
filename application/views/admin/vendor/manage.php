<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?>    
  </head>
  <body>
    <div class="app full-width expanding">    
        <div class="off-canvas-overlay" data-toggle="sidebar"></div>
        <div class="sidebar-panel">       
          <?php $this->load->view('admin/includes/left-sidebar'); ?>
        </div> 
        <div class="app horizontal top_hader_dashboard">
              <?php $this->load->view('admin/includes/header'); ?>
        </div>      

        <div class="main-panel">
            <div class="min_height_dashboard"></div>
            <div class="main-content">              
              <div class="content-view">
                <div class="row m-b-1">
                <div class="col-sm-3 pr-0">
                  <div class="bg_white back_line">
                     <h4>Manage <?php echo $menu_label_alias['menu']['vendor']; ?></h4>
                  </div>
                </div>
                <div class="col-sm-9 pleft_0">
                  <div class="bg_white_filt">
                     <ul class="filter_ul">                    
                        
                     </ul>
                  </div>
                </div>
              </div>
                    <div class="card">
                        <div class="">
                            <h5 class="lead_board col-md-8"><?php echo $menu_label_alias['menu']['vendor']; ?> List</h5>
                            <?php if(is_method_available('vendor','add')==TRUE){ ?>
                                <div class="col-md-4">
                                    <a class="pull-right" href="<?php echo base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/add">
                                        <button type="button" class="btn btn-primary m-r-xs m-b-xs btn-round-shadow mb-0 mt-14">
                                            Add <?php echo $menu_label_alias['menu']['vendor']; ?>
                                        </button>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clear"></div>
                        <div class="card-block">
                            <div class="no-more-tables">
                                <?php if($vendor){     ?>
                                    <table class="table table-bordered m-b-0 th_color" id="vendorList" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Company</th>
                                                <th>Contact person</th>
                                                <th class="numeric">Mobile</th>
                                                <th>Website</th>
                                                <th class="numeric">Products</th>
                                                <th>City</th>
                                                <th>Country</th>
                                                <th>Status</th>
                                                <th class="no-sort">Action</th>
                                            </tr>
                                        </thead>
                                        <?php $key = 0;
                                        foreach($vendor as $vendor_data) {?>
                                            <tr id="tcontent">
                                                <td>
                                                    <a href="JavaScript:void(0);" class="get_detail_modal" data-id="<?php echo $vendor_data['id'];?>">
                                                    <?php print (stripslashes($vendor_data['company_name']))?stripslashes($vendor_data['company_name']):'-';?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php print stripslashes($vendor_data['contact_person']);?>
                                                </td>
                                                <td>
                                                    <?php print stripslashes($vendor_data['mobile']);?>
                                                </td>
                                                <td>
                                                    <?php print stripslashes($vendor_data['website']);?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if(is_method_available('product','manage')==TRUE){ ?>
                                                        <button type="button" class="btn btn-link p-0 " onclick="get_product_list('<?php echo $vendor_data['id']?>')">
                                                        <span id="pcount_<?php echo $vendor_data['id']?>"><?php echo ($vendor_data['product_tagged_count'])?$vendor_data['product_tagged_count']:'0'; ?></span>

                                                        </button>
                                                        <br>
                                                        <a href="JavaScript:void(0);" class="get_product_list_for_tagged" data-vid="<?php echo $vendor_data['id']?>" data-pcount="<?php echo ($vendor_data['product_tagged_count'])?$vendor_data['product_tagged_count']:'0'; ?>"  title="Tag new Product" ><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                                                    <?php }else{ ?>N/A
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php print stripslashes($vendor_data['city_name']);?>
                                                </td>
                                                <td>
                                                    <?php print stripslashes($vendor_data['country_name']);?>
                                                </td>
                                                <td>
                                                    <?php print stripslashes(status_text($vendor_data['status']));?>
                                                </td>
                                                <td>
                                                    <a href="JavaScript:void(0);" class="get_detail_modal" data-id="<?php echo $vendor_data['id'];?>"><i class="fa fa-eye" style="font-size: 15px; color:#008AC9;"></i></a>&nbsp;&nbsp;

                                                    <a href="JavaScript:void(0);" class="send_mail_to_vendor_modal" data-id="<?php echo $vendor_data['id'];?>" data-email="<?php echo $vendor_data['email'];?>"><i class="fa fa-envelope" style="font-size: 15px; color:#008AC9;"></i></a>&nbsp;&nbsp;

                                                    <?php if(is_method_available('vendor','edit')==true){ ?>
                                                        <a href="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/edit/<?php echo $vendor_data['id']?>" data-original-title="Edit" data-toggle="tooltip" data-placement="left"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                    <?php }else{ ?>
                                                            <i class="fa fa-pencil" style="text-decoration: line-through;" data-original-title="Not Applicable" data-toggle="tooltip" data-placement="left"></i>
                                                    <?php } ?>
                                                    <!--             &nbsp;&nbsp;&nbsp;
                                                    <?php if(is_method_available('vendor','delete')==true){ ?>
                                                        <a href="#" onclick="return confirm_delete('<?php echo $vendor_data['id']?>');" data-original-title="Delete" data-toggle="tooltip" data-placement="left"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    <?php }else{ ?>
                                                        <i class="fa fa-trash" aria-hidden="true" style="text-decoration: line-through;" data-toggle="tooltip" data-placement="left" title="" data-original-title="Not Applicable"></i>
                                                    <?php } ?> -->
                                                </td>
                                            </tr>
                                            <?php  }?>
                                    </table>
                                <?php } else {
                                    echo'No Vendor Found';
                                }?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="content-footer">
              <?php $this->load->view('admin/includes/footer'); ?>
            </div>
        </div>
    </div>
    <?php $this->load->view('admin/includes/modal-html'); ?>
    <?php $this->load->view('admin/includes/app.php'); ?> 
  </body>
</html>
<input type="hidden" id="edit_vendor_id" value="">
<style>
            table.dataTable thead > tr > th.sorting_asc,
            table.dataTable thead > tr > th.sorting_desc,
            table.dataTable thead > tr > th.sorting,
            table.dataTable thead > tr > td.sorting_asc,
            table.dataTable thead > tr > td.sorting_desc,
            table.dataTable thead > tr > td.sorting {
                padding-right: 10px !important;
            }
            
            table.dataTable thead .sorting::after,
            table.dataTable thead .sorting_asc::after {
                content: "";
            }
            
            table.dataTable thead .sorting_desc::after {
                content: "";
            }
            
            table.dataTable thead .sorting::before,
            table.dataTable thead .sorting_asc::before {
                content: "";
            }
            
            table.dataTable thead .sorting_desc::before {
                content: "";
            }
        </style>

        <!-- end page scripts -->
        <!-- <script src="<?=assets_url();?>vendor/sweetalert/dist/sweetalert.min.js"></script> -->
        <!-- <script src="<?php echo assets_url();?>js/common_functions.js"></script> -->
        <!-- <script src="<?=assets_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script> -->
        <script src="<?php echo assets_url();?>vendor/datatables/media/js/jquery.dataTables.js"></script>
        <script src="<?php echo assets_url();?>vendor/datatables/media/js/dataTables.bootstrap4.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('#datatable').DataTable({
            "lengthChange": false,
            "searching": false,
            //"order": [[ 0, "desc" ]]
        });
        //$('#datatable').DataTable({ });
        $("body").on("click",".get_detail_modal",function(e){
            var id=$(this).attr('data-id');        
            $("#vendor_details_modal").modal({
                backdrop: 'static',
                keyboard: false,
                callback:fn_rander_vendor_details(id)
            });   
        });
        $("body").on("click",".send_mail_to_vendor_modal",function(e){
            var id=$(this).attr('data-id');  
            var email=$(this).attr('data-email');  
            $("#vdr_id").val(id);
            if(email)
            {
                $("#vdr_to_email").val(email);
                $("#vdr_to_email").attr("readonly",true);
            }
            else
            {
                $("#vdr_to_email").attr("readonly",false);
            }
            
            $("#send_mail_to_vendor").modal();   
        });
        $("body").on("click","#mail_send_to_vendor_confirm",function(e){
            var base_URL = $("#base_url").val();
            var vdr_id=$("#vdr_id").val();
            var vdr_to_email_obj=$("#vdr_to_email");
            var vdr_from_email_obj=$("#vdr_from_email");
            var vdr_mail_subject_obj=$("#vdr_mail_subject");
            var vdr_mail_body_obj=$("#vdr_mail_body");
            
            if(vdr_to_email_obj.val()=="")
            {
                vdr_to_email_obj.focus();           
                $("#vdr_to_email_error").html("Oops! to mail should not be blank.");
                return false;
            }
            else
            {           
                $("#vdr_to_email_error").html("");
            }
            
            if(vdr_from_email_obj.val()=="")
            {
                vdr_from_email_obj.focus();
                $("#vdr_from_email_error").html("Oops! from mail should not be blank.");
                return false;
            }
            else
            {
                $("#vdr_from_email_error").html("");
            }
            
            if(vdr_mail_subject_obj.val()=="")
            {   
                vdr_mail_subject_obj.focus();
                $("#vdr_mail_subject_error").html("Oops! mail subject should not be blank.");
                return false;
            }
            else
            {   
                $("#vdr_mail_subject_error").html("");
            }
            
            if(vdr_mail_body_obj.val()=="")
            {
                vdr_mail_body_obj.focus();
                $("#vdr_mail_body_error").html("Oops! mail body should not be blank.");
                return false;
            }
            else
            {
                $("#vdr_mail_body_error").html("");
            }
            
            var data="vendor_id="+vdr_id;  

            $.ajax({
                    url: base_URL+"/vendor/mail_send_to_vendor_ajax",                
                    data: new FormData($('#frmSendMailToVendor')[0]),
                    cache: false,
                    method: 'POST',
                    dataType: "html",
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function( xhr ) { 
                                               
                    },
                    success: function(data){
                        result = $.parseJSON(data);
                        
                        if(result.status=='success')
                        {
                            swal({
                                    title: "Success!",
                                    text: "The mail has been sent to the vendor",
                                     type: "success",
                                    confirmButtonText: "ok",
                                    allowOutsideClick: "false"
                                }, function () { 
                                    location.reload(true); 
                                    //load(1);
                                    
                                });
                        }
                        
                       
                    },
                    complete: function(){
                    
                   },
            });
            
        });

    $("body").on("click",".get_product_list_for_tagged",function(e){
        var vid=$(this).attr('data-vid');
        var pcount=$(this).attr('data-pcount');
        $("#edit_vendor_id").val(vid);        
        get_product_list_for_tagged(vid,'edit');
    });
});

function get_product_list_for_tagged(vendor_id,method_name)
{
    // var vendor_id = $("#vendor_id").val();
    // var method_name = '<?php echo $this->router->fetch_method(); ?>';
    // alert(vendor_id+'/'+method_name)
    $.ajax({
      url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/get_prodlist_for_tag_ajax",
      type: "POST",
      data: {"vendor_id":vendor_id,"method_name":method_name},       
      async:true,     
      success: function (response) 
      {
        $('#product_list').html(response);          
        $('#product_list_modal').modal(); 
      },
      error: function () 
      {
       //$.unblockUI();
        alert('Something went wrong there');
      }
    });
}
function add_prod()
{ 
    var total=$('input[type="checkbox"]:checked').length;      
    if(total>0)
    {
        var product = [];
        $.each($("input[name='product']:checked"), function(){
            product.push($(this).val());
        });
        var product_str = product.join();    
        var vendor_id = $("#edit_vendor_id").val(); 
        
        $.ajax({
                url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url'];?>/vendor/select_product_tagged_ajax",
                type: "POST",
                data: {'product_str':product_str,'vendor_id':vendor_id},       
                async:true,     
                success: function (response) 
                {
                    // alert(response)
                    $("#product_list_modal").modal('hide');
                    $("#pcount_"+vendor_id).text(response);
                    
                },
                error: function () 
                {
                 //$.unblockUI();
                  console.log('Something went wrong there');
                }
        });
      
    }
    else
    {
       swal('Oops','No product checked to tag','error'); 
    }

}
</script>
        <script type="text/javascript">
            function confirm_delete(id) {
                swal({
                    title: 'Are you sure?',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, delete it!',
                    closeOnConfirm: false
                }, function() {

                    window.location.href = "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/delete/" + id;
                });
                return false;
            }

            function get_product_list(vid) 
            {
                var vendor_id = vid;
                var method_name = '<?php echo $this->router->fetch_method(); ?>';

                $.ajax({
                    url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/get_prodlist_for_tag_ajax",
                    type: "POST",
                    data: {
                        "vendor_id": vendor_id,
                        "method_name": method_name
                    },
                    async: true,
                    success: function(response) {
                        $('#product_list').html(response);
                        $('#product_list_modal').modal();
                    },
                    error: function() {
                        //$.unblockUI();
                        alert('Something went wrong there');
                    }
                });
            }

            $(document).ready(function() {
                $('#vendorList').DataTable({
                    "pageLength": 30,
                    "columnDefs": [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }]
                });
                /*$('[data-toggle="tooltip"]').tooltip({
                    html: true
                });*/
            });
        </script>

        <!-- PRODUCT VIEW -->
        <script type="text/javascript">
            function GetSKUList(parent_id) 
            {
                return false;
                $.ajax({
                    url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/getskulist_ajax",
                    type: "POST",
                    data: {
                        'parent_id': parent_id
                    },
                    async: true,
                    success: function(response) {
                        $('#sku_list_prod').html(response);
                        $('#sku_list').modal();
                    },
                    error: function() {
                        //$.unblockUI();
                        alert('Something went wrong there');
                    }
                });
            }

            function GetSKUDetail(varient_id) {

                $.ajax({
                    url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/getskudata_ajax",
                    type: "POST",
                    data: {
                        'varient_id': varient_id
                    },
                    async: true,
                    success: function(response) {
                        $('#sku_data_prod').html(response);
                        $('#sku_data').modal();
                    },
                    error: function() {
                        //$.unblockUI();
                        alert('Something went wrong there');
                    }
                });
            }

            function remove_product(id) {

                swal({
                    title: 'Confirm Delete',
                    text: 'This product has variants. If you delete this main product all the variants will be removed as well. Want to continue?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No!'
                }, function(isConfirm) {
                    if (isConfirm) {

                        $.ajax({
                            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/del_prod_ajax",
                            type: "POST",
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                window.location = "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/manage/";
                            },
                            error: function() {
                                swal('Cancelled', 'Something went worng!', 'error');
                            }
                        });

                    }
                    return false;
                });
            }

            function remove_variant(id) {

                swal({
                    title: 'Confirm Delete',
                    text: 'Are you sure?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No!'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/del_variant_ajax",
                            type: "POST",
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $("#dynamic_section").html(response);

                            },
                            error: function() {

                            }
                        });
                    }
                    return false;
                });
            }

            function remove_variant_data(id) {
                swal({
                    title: 'Confirm Delete',
                    text: 'Are you sure?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No!'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/del_variant_ajax",
                            type: "POST",
                            data: {
                                'id': id
                            },
                            success: function(response) {
                                $('#sku_data').modal('toggle');
                                $("#dynamic_section").html(response);

                            },
                            error: function() {

                            }
                        });
                    }
                    return false;
                });
            }
        </script>
        <!-- PRODUCT VIEW -->

        <!--  PRODUCT LIST MODAL -->
        <div id="product_list_modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg modal_margin_top">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tagged Product List</h4>

                    </div>
                    <div class="modal-body" id="product_list"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!--  PRODUCT LIST MODAL -->

        <!--  SKU LIST MODAL -->
        <div id="sku_list" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg modal_margin_top">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">View SKUs</h4>

                    </div>
                    <div class="modal-body" id="sku_list_prod"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!--  SKU LIST MODAL -->

        <!--  SKU DETAIL LIST MODAL -->
        <div id="sku_data" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg modal_margin_top">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">View SKU Data</h4>

                    </div>
                    <div class="modal-body" id="sku_data_prod"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!--  SKU DETAIL LIST MODAL -->
