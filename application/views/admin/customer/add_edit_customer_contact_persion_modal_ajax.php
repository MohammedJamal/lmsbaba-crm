<?php //print_r($cus_data); ?>
<form id="frmCustomerEdit" name="frmCustomerEdit" onsubmit="return false;" class="full-l">
    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $cus_data->id; ?>">
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">  
    <input type="hidden" name="action" id="action" value="<?php echo (count($contact_persion))?'cp_edit_mode':'cp_add_mode'; ?>" />  
    <input type="hidden" name="action_table" id="action_table" value="<?php echo $action_table; ?>"> 

    <div class="form-group row">
        <div class="col-md-6">
            <label>Contact Person</label>
            <input type="text" class="form-control" placeholder="Contact Person" autocomplete="off" name="name" id="name" value="<?php if($action_table!='c'){echo (count($contact_persion))?$contact_persion['name']:'';}else{echo ($cus_data->contact_person)?$cus_data->contact_person:'';} ?>">
        </div>
        <div class="col-md-6">
            <label>Designation</label>
            <input type="text" class="form-control" placeholder="Designation" autocomplete="off" name="designation" id="designation" value="<?php if($action_table!='c'){echo (count($contact_persion))?$contact_persion['designation']:'';}else{echo ($cus_data->designation)?$cus_data->designation:'';} ?>">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Email</label>
            <input type="text" class="form-control" placeholder="Email" autocomplete="off" name="email" id="email" value="<?php if($action_table!='c'){echo (count($contact_persion))?$contact_persion['email']:'';}else{echo ($cus_data->email)?$cus_data->email:'';} ?>"> 
            
        </div>
        <div class="col-md-6">
            <label>Mobile</label>
            <input type="text" class="form-control only_natural_number" autocomplete="off" placeholder="Mobile" name="mobile" id="mobile" value="<?php if($action_table!='c'){echo (count($contact_persion))?$contact_persion['mobile']:'';}else{echo ($cus_data->mobile)?$cus_data->mobile:'';} ?>"> 
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label>Date Of Birth</label>
            <input type="text" class="form-control display_date" autocomplete="off" placeholder="Date Of Birth" name="dob" id="dob" value="<?php if($action_table!='c'){echo (count($contact_persion))?$contact_persion['dob']:'';}else{echo ($cus_data->dob)?$cus_data->dob:'';} ?>" > 
            
        </div>
        <div class="col-md-6">
            <label>Marriage Anniversary</label>
            <input type="text" class="form-control display_date" autocomplete="off" placeholder="Date of Anniversary" name="doa" id="doa" value="<?php if($action_table!='c'){echo (count($contact_persion))?$contact_persion['doa']:'';}else{echo ($cus_data->doa)?$cus_data->doa:'';} ?>"> 
        </div>
    </div>

    
    <div class="form-group text-center">
        <a href="javascript:;" class="btn btn-primary sss" id="contact_persion_submit">
        Save
        </a>
    </div>
    
</form>
<!-- <link rel="stylesheet" href="<?php echo assets_url(); ?>plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">
<script src="<?php echo assets_url(); ?>plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script> -->
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