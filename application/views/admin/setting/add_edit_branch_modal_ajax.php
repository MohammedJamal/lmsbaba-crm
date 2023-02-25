<?php //print_r($cus_data); ?>
<form id="frmBranch" name="frmBranch" onsubmit="return false;" class="full-l">
    <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $id; ?>">  

    <div class="form-group row">
        
        <div class="col-md-6">
            <label>Branch Name</label>
            <input type="text" class="form-control" placeholder="Enter Branch Name" autocomplete="off" name="branch_name" id="branch_name" value="<?php echo (count($row))?$row['name']:''; ?>">
        </div>
        <div class="col-md-6">
            <label>Contact Person</label>
            <input type="text" class="form-control" placeholder="Enter Contact Person" autocomplete="off" name="branch_contact_person" id="branch_contact_person" value="<?php echo (count($row))?$row['contact_person']:''; ?>">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Email</label>
            <input type="text" class="form-control" placeholder="Enter Email" autocomplete="off" name="branch_email" id="branch_email" value="<?php echo (count($row))?$row['email']:''; ?>"> 
            
        </div>
        <div class="col-md-6">
            <label>Mobile</label>
            <input type="text" class="form-control only_natural_number" autocomplete="off" placeholder="Enter Mobile" name="branch_mobile" id="branch_mobile" value="<?php echo (count($row))?$row['mobile']:''; ?>"> 
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Pin</label>
            <input type="text" class="form-control" autocomplete="off" placeholder="Enter Pin" name="branch_pin" id="branch_pin" value="<?php echo (count($row))?$row['pin']:''; ?>" > 
            
        </div>
        <div class="col-md-6">
            <label>GST</label>
            <input type="text" class="form-control" autocomplete="off" placeholder="Enter GST" name="branch_gst" id="branch_gst" value="<?php echo (count($row))?$row['gst']:''; ?>"> 
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label>Country</label>
            <select class="custom-select form-control model-select2" name="branch_country_id" id="branch_country_id" onchange="GetStateList(this.value,'#branch_state_id','')" >
                <option value="">Select</option>
                <?php foreach($country_list as $country_data)
                {
                  ?>
                  <option value="<?php echo $country_data->id;?>" <?php if(count($row)){if($row['country_id']==$country_data->id){echo'selected';}else if($row['cs_country_id']==$country_data->id){echo 'selected';}} ?>><?php echo $country_data->name;?></option>
                  <?php
                }
                ?>            
            </select>
            
        </div>
        <div class="col-md-4">
            <label>State</label>
            <select class="custom-select form-control model-select2" name="branch_state_id" id="branch_state_id" onchange="GetCityList(this.value,'#branch_city_id','')">
                <option value="">Select</option>
                  <?php foreach($state_list as $state_data)
                  {
                    ?>
                    <option value="<?php echo $state_data->id;?>" <?php if(count($row)){if($row['state_id']==$state_data->id){echo'selected';}else if($row['cs_state_id']==$state_data->id){echo 'selected';}} ?>><?php echo $state_data->name;?></option>
                    <?php
                  }
                  ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>City</label>
            <select class="custom-select form-control model-select2" name="branch_city_id" id="branch_city_id">
                  <option value="">Select</option>
                    <?php foreach($city_list as $city_data)
                    {
                      ?>
                      <option value="<?php echo $city_data->id;?>" <?php if(count($row)){if($row['city_id']==$city_data->id){echo'selected';}else if($row['cs_city_id']==$city_data->id){echo 'selected';}} ?>><?php echo $city_data->name;?></option>
                      <?php
                    }
                    ?>
            </select>   
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label>Address</label>
            <textarea name="branch_address" id="branch_address" class="form-control" placeholder="Enter Address"><?php echo (count($row))?$row['address']:''; ?></textarea>
        </div>
    </div>

    
    <div class="form-group text-center">
        <a href="javascript:;" class="btn btn-primary" id="branch_submit">
        Save
        </a>
    </div>
    
</form>
<?php $this->load->view('admin/includes/app.php'); ?> 
<link rel="stylesheet" href="<?php echo assets_url(); ?>plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
//====================================================================
// Get email validate
function validateEmail($email) 
{
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if( !emailReg.test( $email ) ) {
    return false;
  } else {
    return true;
  }
}

function is_email_validate(email) 
{
    var filter = /^([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))$/;
    return filter.test(email);
}
// Get email validate
//====================================================================

$(document).ready(function(){
    //====================================================================
    // Namutal number
    $('.only_natural_number').keyup(function(e)
    { 
       if (/\D/g.test(this.value))
          {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
          }                 
    });
    // Namutal number
    //====================================================================
    $('.display_date').datepicker({
      dateFormat: "dd-M-yy",
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+5'
    });    
});
</script>