<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">   
  <head>
   <?php $this->load->view('admin/includes/head'); ?> 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>   
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
                         <h4>Manage <?php echo $menu_label_alias['menu']['vendor']; ?> </h4>
                      </div>
                    </div>
                    <div class="col-sm-9 pleft_0">
                      <div class="bg_white_filt">
                         <ul class="filter_ul">                    
                            
                         </ul>
                      </div>
                    </div>
                  </div>                   
  <div class="row">     
    <div class="col-lg-12">
      <?php
      if($this->session->flashdata('success_msg')) { ?>
      <!--  success message area start  -->
      <div class="alert alert-success alert-alt" style="display:block;" id="notification-success">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-check-circle"></i> Success</h4> <span id="success_msg">
        <?php echo $this->session->flashdata('success_msg'); ?></span>
      </div>
      <!--  success message area end  -->
      <?php } ?>
      <?php if($this->session->flashdata('error_msg') || $error_msg) { ?>
      <!--  error message area start  -->
      <div class="alert alert-danger alert-alt" style="display:block;" id="notification-error">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="fa fa-exclamation-triangle"></i> Error</h4> <span id="error_msg">
        <?php echo ($this->session->flashdata('error_msg'))?$this->session->flashdata('error_msg'):$error_msg; ?></span>
      </div>
      <!--  error message area end  -->
      <?php } ?>
      <div class="panel panel-primary card process-sec user-tab-page">
          <div class="user-title"><?php echo $menu_label_alias['menu']['vendor']; ?> Edit </div>
          <div class="tab_gorup">
                  <div class="tab">
                    <button class="tablinks" onClick="openCity(event, 'official_information')" id="defaultOpen" type="button">Official Informaion</button>
                    <button class="tablinks" onClick="openCity(event, 'product_services')" type="button" id="tab2">Products/Services</button>
                    <button class="tablinks" onClick="openCity(event, 'visiting_card')" type="button" id="tab3">Visiting Card</button>
                  </div>
                
                
<div id="official_information" class="tabcontent card-block">
    <form role="form" class="form-validation" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/edit/<?php echo $vendor_id; ?>" method="post" name="form" id="form" enctype="multipart/form-data">
    <input type="hidden" name="existing_image" value="<?php echo $photo; ?>">
    <input type="hidden" name="command" value="1"/>
    <input type="hidden" name="id" id="vendor_id" value="<?=$vendor_id;?>"/>
    <div>
<?php
  // if($photo!='')
  // {
  // $profile_img_path = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/thumb/".$photo;
  // }
  // else
  // {
  // $profile_img_path = assets_url().'images/user_img_icon.png';
  // }                                  
?>

      <?php /* ?>                         
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="user_image mb-56">
            <a href="#" id="agent_photo_prev">
            <img src="<?php echo $profile_img_path;?>"/>
            </a>
            <div class="change-btn">
            <span class="file">
            <input type="file" name="image" id="photo" onchange="GetImagePreview(this,'agent_photo_prev')">
            <label for="file">Change</label>
            </span>                    
            </div>
            <div style="padding: 5px;" class="text-center" id="delete_div">
            <a href="JavaScript:void(0);" class="image_delete text-primary" data-id="32" data-display="agent_photo_prev">Delete</a>
            </div>
            </div>
          </div>  
          <?php */ ?>                          
    <div class="col-md-12 col-sm-12 col-xs-12">   
              <div class="form-group row">
               <div class="col-md-3">
                <label for="" class="col-form-label">Company<span class="text-danger">*</span> :</label>
                  <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company Name" value="<?php echo $company_name; ?>"/>
                  
                </div>
               <div class="col-md-3">
                <label for="" class="col-form-label">Contact Person<span class="text-danger">*</span> :</label>
                <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Contact Person" value="<?php echo $contact_person; ?>"/>
              </div> 
              <div class=" col-md-3">
                  <label for="" class="col-form-label">Designation<span class="text-danger">*</span> :</label>
                  <input type="text" class="form-control" name="designation" id="designation" placeholder="Designation" value="<?php echo $designation; ?>"/>
              </div>
              <div class="col-md-3">
                <label for="" class="col-form-label">GST Number :</label>
                <input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GST Number" value="<?php echo $gst_number; ?>"/>
              </div>
        </div>




                <div class="form-group row">
                 
                <div class="col-md-4">
                  <label for="" class="col-form-label">Mobile<span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $mobile; ?>" />
                </div> 

                <div class="col-md-4">
                  <label for="" class="col-form-label">Email<span class="text-danger">*</span> :</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Mobile" value="<?php echo $email; ?>" />
                </div> 

                <div class="col-md-4">
                  <label for="" class="col-form-label">Website :</label>
                  <input type="text" class="form-control" name="website" id="website" placeholder="Website" value="<?php echo $website; ?>" />
                  </div>
                   
                         
                </div>

                

<div class="form-group row">
<div class="col-md-12">
<label for="" class="col-form-label">Address<span class="text-danger">*</span> :</label>
<textarea class="form-control" name="address" id="address"><?php echo $address; ?></textarea>
</div>

</div>
             <div class="form-group row">
               <div class="col-md-6">
                  <label for="" class="col-form-label">Country<span class="text-danger">*</span> :</label>
                   <select class="custom-select form-control" name="country_id" id="country_id" onchange="GetStateList(this.value)">
                                          <option value="">Select</option>
                                          <?php foreach($country_list as $country_data)
                                          {
                                            ?>
                                            <option value="<?php echo $country_data->id;?>" <?php if($country_id==$country_data->id){echo'selected';} ?>><?php echo $country_data->name;?></option>
                                            <?php
                                          }
                                          ?>
                                          
                                        </select>
                    </div>
                <div class="col-md-6">
                <label for="" class="col-form-label">State<span class="text-danger">*</span> :</label> 
                <select class="custom-select form-control" name="state" id="state" onchange="GetCityList(this.value)">
                <option value="">Select</option>
                <?php foreach($state_list as $state_data)
                {
                ?>
                 <option value="<?php echo $state_data->id;?>" <?php if($state==$state_data->id){?> selected <?php } ?>><?php echo $state_data->name;?></option>
                <?php
                }
                ?>
               </select>   
                </div>
                </div>

                <div class="form-group row">
                 <div class="col-md-6">
                  <label for="" class="col-form-label">City<span class="text-danger">*</span> :</label>
                <select class="custom-select form-control" name="city" id="city">
                                          <option value="">Select</option>
                                            <?php foreach($city_list as $city_data)
                                            {
                                              ?>
                                              <option value="<?php echo $city_data->id;?>" <?php if($city==$city_data->id){?> selected <?php } ?>><?php echo $city_data->name;?></option>
                                              <?php
                                            }
                                            ?>
                                        </select>
                </div>
                <div class="col-md-6">
                  <label for="" class="col-form-label">Status :</label>
              
                    <select class="custom-select form-control" name="status" id="status">
                    <option value="0" 
                    <?php if($status==0){?> selected <?php } ?>>
                    Approved   
                    </option>
                    <option value="1" 
                    <?php if($status==1){?> selected <?php } ?>>
                    Rejected   
                    </option>
                    <option value="2" 
                    <?php if($status==2){?> selected <?php } ?>>
                    Blacklisted   
                    </option>
                   </select>
                
                </div>
                

                </div>

                


                

<div class="form-group row">

    <div class="col-sm-12 text-right">
    <button type="submit" class="btn btn-right btn-primary btn-round-shadow border_blue step_one">Submit</button>
    
    </div>        
   </div>
</div>                            
</div>
<input type="hidden" name="command" value="1"/>
</form>
</div>
                
<div id="product_services" class="tabcontent">
<div class="card-block">
  <div class="row">
    <div class="col-md-6">
      <h3 class="company-text">Company : <?php echo $company_name; ?></h3>
    </div>
    <div class="col-md-6">
      <div class="text-right">
        <a class="btn-primary btn-round-shadow" href="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/product/add/v_<?=$vendor_id;?>">Add a New Product</a>
        <a href="JavaScript:void(0);" class="btn-primary btn-round-shadow" onclick="get_product_list();">Select Products from Product Library</a>
      </div>
    </div>
  </div>
  <div style="clear: both;">&nbsp;</div>
<div class="table-hold">
  <table class="table table-bordered th_color" id="productTaggedList">
      <thead>
        <tr>
        <th>SL</th>
        <th>Product Name</th>
        <th>Cost</th>
        <th>Rs./US$</th>
        <th>Delivery Time</th>
        <th>Action</th>
        </tr>
      </thead>
      <tbody id="tcontent">
        <tr>
          <td colspan="6" align="center">No Record Found..</td>
        </tr>
      </tbody>                          
  </table>
  <div id="page" style=""></div>                              
</div>
</div>                    
</div> 

<div id="visiting_card" class="tabcontent">
<div class="card-block">
<form role="form" class="form-validation" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/edit_visiting_card" method="post" name="form" id="form" enctype="multipart/form-data">

              <input type="hidden" name="command" value="1"/>
              <input type="hidden" name="id" id="vendor_id" value="<?=$vendor_id;?>"/>
              <input type="hidden" name="existing_visiting_card_font" value="<?php echo $visiting_card_font; ?>">
              <input type="hidden" name="existing_visiting_card_back" value="<?php echo $visiting_card_back; ?>">
              <input type="hidden" name="step" value="3">

              <div style="padding-bottom: 5px;"><h3 class="company-text"> Company : <?php echo $company_name; ?></h3>
             </div>
              <div class="form-group">

                <?php
                if($visiting_card_font!='')
                {
                  $vc_font = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/".$visiting_card_font;
                }
                else
                {
                  $vc_font = assets_url().'images/no-image.png';
                }

                if($visiting_card_back!='')
                {
                  $vc_back = assets_url()."uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/".$visiting_card_back;
                }
                else
                {
                  $vc_back = assets_url().'images/no-image.png';
                }
                
                ?>
              <div class="form-group row">
                <div class="col-sm-3">
                  <div class="thired_tab">
                    <a href="#" id="agent_photo_prev_2">
                      <img src="<?php echo $vc_font;?>"/>
                    </a>
                        <div class="">
                          <span class="file">
                            <input type="file" name="visiting_card_font" id="visiting_card_font" onchange="GetImagePreview(this,'agent_photo_prev_2')" />
                            <label for="file">Change Visiting Card - Front</label>
                          </span>
                        </div>
                  </div>                                         
                  
                </div>

                <div class="col-sm-3">   
                  <div class="thired_tab">
                      <a href="#" id="agent_photo_prev_3">
                        <img src="<?php echo $vc_back;?>"/>
                      </a>
                          <div>
                            <span class="file">
                              <input type="file" name="visiting_card_back" id="visiting_card_back" onchange="GetImagePreview(this,'agent_photo_prev_3')" />
                              <label for="file">Change Visiting Card - Back</label>
                            </span>
                          </div>
                    </div>                                         
                  
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-round-shadow border_blue">Submit</button>
                
                <a href="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage" class="btn btn-primary btn-round-shadow border_blue">Back</a>
              </div>

              
                  </div>
                </form>
              </div>
              </div>
                  </div>              
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

<style type="text/css">
  .thired_tab {
    width: 100%;
    height: 156px;
    float: left;
    padding: 4px;
    border:1px dashed #d9d9d9;
    background: #f5f5f6;
    position: relative;
}

.thired_tab img {
    width: 100%;
    height:147px;
    object-fit: cover;
}

.thired_tab .file {
    position: absolute;
    bottom: 0;
    width: 100%;
    float: left;
    text-align: center;
}

.thired_tab .file input {
    position: absolute;
    display: inline-block;
    left: 0;
    top: 0;
    opacity: 0.01;
    cursor: pointer;
}

.thired_tab .file label {
    background: #c1c2c4;
    padding: 2px 13px;
    color: #000;
    font-weight: bold;
    font-size: .9em;
    transition: all .4s;
}
</style>

      


          
<script type="text/javascript">
      window.paceOptions = {
        document: true,
        eventLag: true,
        restartOnPushState: true,
        restartOnRequestAfter: true,
        ajax: {
          trackMethods: [ 'POST','GET']
        }
      };
    </script>
    
    
    <!-- build:js({.tmp,app}) scripts/app.min.js -->
    <!-- <script src="<?=assets_url();?>vendor/jquery/dist/jquery.js"></script> -->
    <!-- <script src="<?=assets_url();?>vendor/pace/pace.js"></script> -->
    <!-- <script src="<?=assets_url();?>vendor/tether/dist/js/tether.js"></script> -->
     <!-- <script src="<?=assets_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script>-->
    <script src="<?=assets_url();?>vendor/fastclick/lib/fastclick.js"></script>
    <script src="<?=assets_url();?>scripts/constants.js"></script>
    <script src="<?=assets_url();?>scripts/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload.css"/>
    <link rel="stylesheet" href="<?=assets_url();?>vendor/blueimp-file-upload/css/jquery.fileupload-ui.css"/>
    <!-- endbuild -->
<!-- page scripts -->
    <script src="<?php echo assets_url();?>vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <!-- end page scripts -->    
    <!-- initialize page scripts -->
<script type="text/javascript" src="<?php echo assets_url(); ?>js/custom/vendor/get.js"></script>
<!-- plupload-2.3.1 - production -->
<script type="text/javascript" src="<?php echo assets_url(); ?>js/plupload-2.3.1/js/plupload.full.min.js"></script>


<!-- Tostr message alert -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<!-- Tostr message alert -->


<script type="text/javascript">
$(document).ready(function(){
  load(1);  
});

$(document).ready(function () {
   $('#form').validate({   
        rules: {                              
            email: {
                required: true,
                email: true
            },            
            mobile: {
                required: true,
                minlength: '10',
                number: true               
            },
            contact_person: {
                required: true
            },
            company_name: {
                required: true
            },
            country_id: {
                required: true
            },
            state: {
                required: true
            },
            city: {
                required: true
            },
            zip: {
                required: true
            },
            
            
        },
        // Specify validation error messages
    messages: {
      email: "Please enter a valid email address",
      contact_person: "Please enter contact person",
      mobile: "Please enter mobile no (Length - 10)",     
      country_id: "Please select country",
      state: "Please select state",
      city: "Please select city",  
      zip: "Please select zip"
      },     
    });
});
function GetStateList(cont)
{
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getstatelist",
        type: "POST",
        data: {'country_id':cont},      
        success: function (response) 
        {
          if(response!='')
          {
          document.getElementById('state').innerHTML=response;
        }
            
        },
        error: function () 
        {
         //$.unblockUI();
         alert('Something went wrong there');
        }
       });
}
function GetCityList(state)
{
    $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/lead/getcitylist",
        type: "POST",
        data: {'state_id':state},     
        success: function (response) 
        {
          if(response!='')
          {
          document.getElementById('city').innerHTML=response;
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
<script>
function openCity(evt, cityName) 
{
    var i, tabcontent, tablinks;
    
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
    
}
<?php if(isset($_GET['step'])){ ?>
<?php
$step = $_GET['step'];
if($step == 2)
{
  $openTab='product_services';
  $tab='tab2';
}
else if($step ==3)
{
  $openTab='visiting_card';
  $tab='tab3';
}
else
{
  $openTab='official_information';
  $tab='defaultOpen';
}

?>
document.getElementById("<?php echo $tab; ?>").click();
<?php }else{ ?>
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
<?php } ?>
//====================================================================
// Get Image Preview
function GetImagePreview(input,displayDiv)
{
   if (input.files && input.files[0]) 
   {
      var reader = new FileReader();
      reader.onload = function (e) {
        var strHtml = '<img src="'+e.target.result+'" width="300">'; 
        $('#'+displayDiv).html(strHtml);  
      };
      reader.readAsDataURL(input.files[0]);
   }
}
// Get Image Preview
//====================================================================

function alert_msg(msg) {
  swal("Oops!", msg);    
  }


function get_product_list()
{
    var vendor_id = $("#vendor_id").val();
    var method_name = '<?php echo $this->router->fetch_method(); ?>';
    
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
        //alert(product_str);
        //console.log(product+' / '+product.length); return false;
        //var prod_id=document.getElementById('selected_prod_id').value;
        var vendor_id = $("#vendor_id").val();
        //console.log(prod_id); return false;
      $.ajax({
        url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url'];?>/vendor/select_product_tagged_ajax",
        type: "POST",
        data: {'product_str':product_str,'vendor_id':vendor_id},       
        async:true,     
        success: function (response) 
        {
          
          //$('#product_list').html(response);
          $('#product_list_modal').modal('toggle');
          load(1);

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
      $('#err_prod').show();
    }

  }

$(document).ready(function(){
  $("body").on("click",".vendor_tag_save",function(e){
    var id = $(this).attr("data-id");
    var delivery_time = $("#delivery_time_"+id).val();
    var delivery_time_unit = $("#delivery_time_unit_"+id).val();
    //alert(id+' / '+delivery_time+' / '+delivery_time_unit);
    $.ajax({
      url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/update_vendor_product_tag_ajax",
      type: "POST",
      data: {"id":id,"delivery_time":delivery_time,"delivery_time_unit":delivery_time_unit},       
      async:true,     
      success: function (response) 
      {
        toastr.success('The record successfully updated!');
        //$('#product_list').html(response);          
        //$('#product_list_modal').modal(); 
      },
      error: function () 
      {
       //$.unblockUI();
        console.log('Something went wrong there');
      }
     });


  });

  $("body").on("click",".vendor_tag_delete",function(e){
    var id = $(this).attr("data-id");    
    swal({
      title: 'Are you sure?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: true
    }, function() {
      
          $.ajax({
                url: "<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/delete_vendor_product_tag_ajax",
                type: "POST",
                data: {"id":id},       
                async:true,     
                success: function (response) 
                {
                  toastr.success('The tagged product has been deleted successfully!');
                  load(1);
                  //alert(response);
                  //$('#product_list').html(response);          
                  //$('#product_list_modal').modal(); 
                },
                error: function () 
                {
                 //$.unblockUI();
                  alert('Something went wrong there');
                }
               });
    });

  });
});

</script>

<!--  PRODUCT LIST -->
<div id="product_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal_margin_top" >

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Product List</h4>
            
          </div>
          <div class="modal-body" id="product_list"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

    </div>
</div>
<!--  PRODUCT LIST -->
<input type="hidden" id="base_URL" value="<?php echo base_url(); ?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/">
