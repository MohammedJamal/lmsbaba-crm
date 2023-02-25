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
<div class="layout-md b-b">
<div class="layout-column-md">
<div class="p-a-1 wizards">
<div class="tsf-wizard tsf-wizard-1 tsf-content-shadow">
<div class="tsf-container">
<form class="tsf-content-new" action="<?php echo base_url()?><?php echo $this->session->userdata['admin_session_data']['lms_url']; ?>/customer/edit" method="post" name="frmCompanyEdit" id="frmCompanyEdit">
<input type="hidden" name="customer_id" id="cust_id" value="<?php echo $cus_data->id; ?>" />
<div id="error"></div>
<div class="">
<fieldset>
<h5 class="lead_board">Edit Company</h5>
<div class="row">
<div class="tsf-step-content card-block">
<div class="col-lg-12">
<div class="group_from">

  <div class="form-group col-md-12">
      <label class="label-text">Company Name<span class="text-danger">*</span>:</label>

      <input type="text" class="form-control" name="com_company_name" id="com_company_name" placeholder="Company Name" value="<?php echo $cus_data->company_name; ?>" />
      <div class="text-danger" id="com_company_name_error"></div>
  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Contact Person<span class="text-danger">*</span>:</label>

    <input type="text" class="form-control" name="com_contact_person" id="com_contact_person" placeholder="Contact Person" value="<?php echo $cus_data->contact_person; ?>" />
    <div class="text-danger" id="com_contact_person_error"></div>
  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Designation:</label>

    <input type="text" class="form-control" name="com_designation" id="com_designation" placeholder="Designation" value="<?php echo $cus_data->designation; ?>"  />
    <div class="text-danger" id="com_designation_error"></div>

  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Email:<span class="text-danger">*</span></label>

    <input type="text" class="form-control" name="com_email" id="com_email" placeholder="Email" value="<?php echo $cus_data->email; ?>"  />
    <div class="text-danger" id="com_email_error"></div>

  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Alternate Email:</label>

    <input type="text" class="form-control" name="com_alternate_email" id="com_alternate_email" placeholder="Alternate Email" value="<?php echo $cus_data->alt_email; ?>"  />
    <div class="text-danger" id="com_alternate_email_error">
    </div>
  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Mobile:<span class="text-danger">*</span></label>

    <input type="text" class="form-control only_natural_number" name="com_mobile" id="com_mobile" placeholder="Mobile" value="<?php echo $cus_data->mobile; ?>"  maxlength="10" />
    <div class="text-danger" id="com_mobile_error"></div>
  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Alternate Mobile:</label>

    <input type="text" class="form-control only_natural_number" name="com_alternate_mobile" id="com_alternate_mobile" placeholder="Alternate Mobile" value="<?php echo $cus_data->alt_mobile; ?>"  />
  </div>

  <div class="form-group col-md-12">
    <label class="label-text">Address:</label>

    <input type="text" class="form-control" name="com_address" id="com_address" placeholder="Address" value="<?php echo $cus_data->address; ?>"  />
  </div>

  <div class="form-group col-md-3">
    <label class="label-text">Country<span class="text-danger">*</span>:</label>

    <select class="custom-select form-control" name="com_country_id" id="com_country_id" onchange="GetStateList(this.value)" <?php if($tmp_com_country_id){echo"disabled";} ?>>
    <option value="">Select</option>
    <?php foreach($country_list as $country_data)
    {
    ?>
    <option value="<?php echo $country_data->id;?>" <?php if($cus_data->country_id==$country_data->id){echo"SELECTED";} ?>><?php echo $country_data->name;?></option>
    <?php
    }
    ?>
    </select>
    <input type="hidden" name="com_existing_country" id="com_existing_country" value="<?php echo $cus_data->country_id; ?>" />
    <div class="text-danger" id="com_country_id_error"></div>

  </div>

  <div class="form-group col-md-3">
    <label class="label-text">State<span class="text-danger">*</span>:</label>

    <select class="custom-select form-control" name="com_state_id" id="com_state_id" onchange="GetCityList(this.value)">
    <option value="">Select</option>
    </select>
    <div class="text-danger" id="com_state_id_error"></div>
    <input type="hidden" name="com_existing_state" id="com_existing_state" value="<?php echo $cus_data->state; ?>" />

  </div>

  <div class="form-group col-md-3">
    <label class="label-text">City:</label>

    <select class="custom-select form-control" name="com_city_id" id="com_city_id">
    <option value="">Select</option>
    </select>
    <input type="hidden" name="com_existing_city" id="com_existing_city" value="<?php echo $cus_data->city; ?>" />

  </div>

  <div class="form-group col-md-3">
    <label class="label-text">Zip:</label>

    <input type="text" class="form-control" name="com_zip" id="com_zip" placeholder="Zip" value="<?php echo $cus_data->zip; ?>"  />

  </div>
  <div class="form-group col-md-3">
    <label class="label-text">GST Number:</label>

    <input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GST Number" value="<?php echo $cus_data->gst_number; ?>"  />

  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Website (E.g. https://www.lmsbaba.com):</label>

    <input type="text" class="form-control com_website" name="com_website" id="com_website" placeholder="Website" value="<?php echo $cus_data->website; ?>" data-role="tagsinput" />

  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Source<span class="text-danger">*</span>:</label>

    <select class="custom-select form-control" name="com_source_id" id="com_source_id" >
    <option value="">Select</option>
    <?php foreach($source_list as $source)
    {
    ?>
    <option value="<?php echo $source->id;?>" <?php if($cus_data->source_id==$source->id){echo"SELECTED";} ?>><?php echo $source->name;?></option>
    <?php
    }
    ?>
    </select>
    <input type="hidden" name="com_existing_source" id="com_existing_source" value="<?php echo $tmp_com_source_id; ?>" />
    <div class="text-danger" id="com_source_id_error"></div>
  </div>


  <div class="form-group col-md-12">
    <label class="label-text">Short Description<span class="text-danger">*</span>:</label>

    <textarea rows="5" cols="5" class="form-control" name="com_short_description" id="com_short_description" placeholder="Short Description" ><?php echo $cus_data->short_description; ?></textarea>
    <div class="text-danger" id="com_short_description_error"></div>

  </div>

  <div class="form-group col-md-6">
    <label class="label-text">Business Type:</label>

    <select class="custom-select form-control" name="com_business_type_id" id="com_business_type_id">
    <option value="">Select Business Type</option>
    <?php foreach($cus_business_type_list as $row)
    {
    ?>
    <option value="<?php echo $row['id'];?>" <?php if($cus_data->business_type_id==$row['id']){echo"SELECTED";} ?>><?php echo $row['name'];?></option>
    <?php
    }
    ?>
    </select>
  </div>
</div>
</div>
</div>
</div>
</fieldset>
</div>
</form>
</div>
<!-- BEGIN CONTROLS-->
<div class="tsf-controls ">
<button class="btn btn-right tsf-wizard-btn" id="add_company_submit_confirm">SUBMIT</button>
</div>
<!-- END CONTROLS-->
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
<script src="<?php echo assets_url(); ?>plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script> 
<script src="<?php echo assets_url();?>js/custom/company/edit.js"></script>



<link rel="stylesheet" href="<?php echo assets_url(); ?>plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$('.com_website').on('beforeItemAdd', function(event) {
var r=is_url(event.item);
if(r==false)
{
event.cancel=true;
}
else
{
event.cancel=false;
}
// event.item: contains the item
// event.cancel: set to true to prevent the item getting added
});
});
// $(document).ready(function () {

//     $('#validate').validate({ // initialize the plugin

//         rules: {
//             first_name: {
//                 required: true,
//             },
//             last_name: {
//                 required: true,
//             },             
//             email: {
//                 required: true,
//                 email: true
//             },
//             mobile: {
//                 required: true,
//                 minlength: '10'

//             },
//             address: {
//                 required: true,
//             },
//             city: {
//                 required: true,
//             },

//             state: {
//                 required: true,
//             },
//             zip: {
//                 required: true,
//                 minlength: '6'

//             },
//             country_id: {
//                 required: true,

//             },
//             company_name: {
//                 required: true,             
//             },
//             office_phone: {
//                 required: true,         
//             },
//             website: {
//                 required: true,         
//             }

//         },
//         // Specify validation error messages
//     messages: {
//       first_name: "Please enter first name",
//       last_name: "Please enter last name",
//       email: "Please enter a valid email address",
//       mobile: "Please enter Mobile no (Length - 10)",
//       address: "Please enter address",
//       city: "Please enter city",
//       state: "Please enter state",
//       zip: "Please enter zip",
//       country_id: "Please enter country",
//       company_name: "Please enter company name",
//       office_phone: "Please enter office phone",
//       website: "Please enter website"

//     },

//     });

// });


// function form_submit()
// {
//  $('#validate').submit();
// }
</script>
