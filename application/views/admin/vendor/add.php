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
              <div class="user-title"><?php echo $menu_label_alias['menu']['vendor']; ?> Add </div>
              <div class="tab_gorup">
                  <div class="tab">
                    <button class="tablinks" onClick="openCity(event, 'official_information')" id="defaultOpen" type="button">Official Informaion</button>
                    

                    <button class="tablinks" onClick="alert_msg('First of all You have to complete Official Information step')" type="button">Products/Services</button>
                    <button class="tablinks" onClick="alert_msg('First of all You have to complete Official Information step')" type="button">Visiting Card</button>
                  </div>
                
                
                  <div id="official_information" class="tabcontent card-block">
                    <form role="form" class="form-validation" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/add" method="post" name="form" id="form" enctype="multipart/form-data">
                      <div>
                          <?php /* ?>
                          <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="user_image mb-56">
                                <a href="#" id="agent_photo_prev"><img src="<?=assets_url('images/user_img_icon.png');?>"/></a>
                                  <div class="change-btn" style="padding-bottom: 15px;">
                                    <span class="file">
                                      <input type="file" name="image" id="photo" onchange="GetImagePreview(this,'agent_photo_prev')" />
                                      <label for="file">Change</label>
                                    </span>
                                  </div>
                              </div>
                          </div>
                          <?php */ ?>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                  
                                  <div class="form-group row">
                                    <div class="col-md-3">
                                      <label for="" class="col-form-label">Company<span class="text-danger">*</span> :</label>
                                      <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company Name" value="<?php echo $this->input->post('company_name'); ?>"/>
                                    </div>
                                    <div class="col-md-3">
                                      <label for="" class="col-form-label">Contact Person<span class="text-danger">*</span> :</label>
                                      <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Contact Person" value="<?php echo $this->input->post('contact_person'); ?>"/>
                                    </div>
                                    <div class="col-md-3">
                                      <label for="" class="col-form-label">Designation<span class="text-danger">*</span> :</label>
                                      <input type="text" class="form-control" name="designation" id="designation" placeholder="Designation" value="<?php echo $this->input->post('designation'); ?>"/>
                                    </div>
                                    <div class="col-md-3">
                                      <label for="" class="col-form-label">GST Number :</label>
                                      <input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GST Number" value="<?php echo $this->input->post('gst_number'); ?>"/>
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    
                                    <div class="col-md-4">
                                      <label for="" class="col-form-label">Mobile<span class="text-danger">*</span> :</label>
                                      <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $this->input->post('mobile'); ?>" />
                                    </div>
                                    <div class="col-md-4">
                                      <label for="" class="col-form-label">Email<span class="text-danger">*</span> :</label>
                                      <input type="text" class="form-control" name="email" id="email" placeholder="E-mail" value="<?php echo $this->input->post('email'); ?>" />
                                    </div>
                                    <div class="col-md-4">
                                      <label for="" class="col-form-label">Website :</label>
                                      <input type="text" class="form-control" name="website" id="website" placeholder="Website" value="<?php echo $this->input->post('website'); ?>" />
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <div class="col-md-12">
                                      <label for="" class="col-form-label">Address<span class="text-danger">*</span> :</label>
                                      <textarea class="form-control" name="address" id="address"><?php echo $this->input->post('address'); ?></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <div class="col-md-4">
                                      <label for="" class="col-form-label">Country<span class="text-danger">*</span> :</label>
                                      <select class="custom-select form-control" name="country_id" id="country_id" onchange="GetStateList(this.value)">
                                            <option value="">Select</option>
                                            <?php foreach($country_list as $country_data)
                                            {
                                              ?>
                                              <option value="<?php echo $country_data->id;?>" ><?php echo $country_data->name;?></option>
                                              <?php
                                            }
                                            ?>
                                            
                                      </select>
                                    </div>
                                    <div class="col-md-4">
                                      <label for="" class="col-form-label">State<span class="text-danger">*</span> :</label>
                                      <select class="custom-select form-control" name="state" id="state" onchange="GetCityList(this.value)">
                                            <option value="">Select</option>
                                          </select>
                                    </div>
                                  
                                    <div class="col-md-4">
                                      <label for="" class="col-form-label">City<span class="text-danger">*</span> :</label>
                                      <select class="custom-select form-control" name="city" id="city">
                                        <option value="">Select</option>
                                      </select>
                                    </div>

                                  </div>

                                  <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                      <button type="submit" class="btn btn-default border_blue btn-primary">Submit</button>
                                      <a href="<?=base_url();?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/vendor/manage" class="btn btn-default border_blue btn-primary">Back</a>
                                    </div>
                                  </div>

                                </div>

                                
                      </div>

                      <input type="hidden" name="command" value="1"/>
                    </form>
                  </div>
                
                  <div id="product_services" class="tabcontent">
                    <div class="two_button_right">
                          <a href="#">Add a New Product</a>
                          <a href="#">Select Products from Product Library</a>
                    </div>
                    <div class="padding_table">
                      <table class="table table-bordered">
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
                        <tbody>
                          <tr id="">
                              <td style="width: 24px;">1</td>                        
                              <td style="width:160px;">Samsung8-2</td>
                              <td style="width:76px;">
                                <div class="form-group col-md-12 padding0">
                                  <input type="text" class="form-control" id="unit_price_0" name="unit_price[]" value="300.00" onkeyup="">
                                </div>
                              </td>                        
                              <td style="width: 76px;">
                                     <select id="disc_0" name="disc[]" class="form-control" onchange="">
                                        <option value="inr">INR</option>
                                        <option value="USD">USD</option>
                                      </select>                            
                              </td>                        
                              <td style="width:146px;">
                                  <select id="" name="" class="form-control" onchange="" style="width:35%; float:left;">
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                  </select>
                                  <select id="" name="" class="form-control" onchange="" style="width:62%; float:right;">
                                      <option value="1">Days</option>
                                      <option value="2">Months</option>
                                      <option value="3">Years</option>
                                  </select>
                              </td>
                              
                              <td style="width:32px;">
                                <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                              </td>
                          </tr>

                         
                        </tbody>
                      </table>                              
                    </div>
                  </div> 

                  <div id="visiting_card" class="tabcontent">
                    sdfvsdfvd vds
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
    <!-- <script src="<?=assets_url();?>vendor/jquery/dist/jquery.js"></script>
    <script src="<?=assets_url();?>vendor/pace/pace.js"></script>
    <script src="<?=assets_url();?>vendor/tether/dist/js/tether.js"></script> -->
    <!--<script src="<?=assets_url();?>vendor/bootstrap/dist/js/bootstrap.js"></script>-->
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
    
    <!-- endbuild -->

    <!-- page scripts -->

    <!-- end page scripts -->

    <!-- initialize page scripts -->
    
<script type="text/javascript">


       $(document).ready(function () {

   $('#form').validate({
   
        rules: { 
            company_name: {
                required: true
            },
            contact_person: {
                required: true
            },
            designation: {
                required: true
            },
            mobile: {
                required: true,
                minlength: '10',
                number: true               
            },                        
            email: {
                required: true,
                email: true
            },            
            address: {
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
        },
        // Specify validation error messages
    messages: {
      company_name: "Please enter company name",
      contact_person: "Please enter contact person",
      designation: "Please enter your designation",
      mobile: "Please enter mobile no (Length - 10)", 
      email: "Please enter a valid email address",      
      address: "Please enter your address",     
      country_id: "Please select country",
      state: "Please select state",
      city: "Please select city"      
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
  function openCity(evt, cityName) {
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
  
  // Get the element with id="defaultOpen" and click on it
  document.getElementById("defaultOpen").click();


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
  alert("Oops! "+msg);    
}
</script>