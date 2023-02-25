<?php //print_r($cus_data); ?>
<form id="frmCustomerEdit" name="frmCustomerEdit" onsubmit="return false;" class="full-l">
    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $cus_data->id; ?>">

    <div class="form-group row">
        <div class="col-md-6">
            <label>Company Name</label>
            <input type="text" class="form-control" placeholder="Company Name" name="company_name" id="company_name" value="<?php echo $cus_data->company_name; ?>">  
        </div>
        <div class="col-md-6">
            <label>Country</label>  
            <select class="custom-select form-control model-select2" name="country_id" id="country" onchange="GetStateList(this.value)">
                <option value="">Select</option>
                <?php foreach($country_list as $country_data)
                {
                  ?>
                  <option value="<?php echo $country_data->id;?>" <?php if($cus_data->country_id==$country_data->id){echo'selected';} ?>><?php echo $country_data->name;?></option>
                  <?php
                }
                ?>            
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label>Contact Person</label>
            <input type="text" class="form-control" placeholder="Contact Person" name="contact_person" id="contact_person" value="<?php echo $cus_data->contact_person; ?>">
        </div>
        <div class="col-md-6">
            <label>Designation</label>
            <input type="text" class="form-control" placeholder="Designation" name="designation" id="designation" value="<?php echo $cus_data->designation; ?>">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label>Mobile</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" id="mobile_country_code_div">+<?php echo $cus_data->mobile_country_code; ?></div>
                </div>
                <input type="text" class="form-control only_natural_number" placeholder="Mobile" name="mobile" id="mobile" value="<?php echo $cus_data->mobile; ?>" <?php //if($cus_data->mobile){echo"readonly='true'";}else{} ?> maxlength="10"> 
            </div>
            
        </div>
        <div class="col-md-6">
            <label>Alternate Mobile</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" id="alt_mobile_country_code_div">+<?php echo $cus_data->alt_mobile_country_code; ?></div>
                </div>
                <input type="text" class="form-control only_natural_number" placeholder="Alt. Mobile" name="alt_mobile" id="alt_mobile" value="<?php echo $cus_data->alt_mobile; ?>" maxlength="10"> 
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label>Phone</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text" id="landline_country_code_div">+<?php echo $cus_data->landline_country_code; ?></div>
                </div>
                <div class="input-group-prepend new">
                    <input type="text" name="landline_std_code" id="landline_std_code" class="form-code only_natural_number" placeholder="Area" value="<?php echo $cus_data->landline_std_code; ?>">
                </div>
                <input type="text" class="form-control only_natural_number" placeholder="Phone" name="landline_number" id="landline_number" value="<?php echo $cus_data->landline_number; ?>" >
            </div>
            
        </div>
        <div class="col-md-6">
            <label>Alternate Phone</label>
            <div class="input-group">   
                <div class="input-group-prepend">
                    <div class="input-group-text" id="office_country_code_div">+<?php echo $cus_data->office_country_code; ?></div>
                </div>
                <div class="input-group-prepend new">
                    <input type="text" name="office_std_code" id="office_std_code" class="form-code only_natural_number" placeholder="Area" value="<?php echo $cus_data->office_std_code; ?>">
                </div>
                <input type="text" class="form-control only_natural_number" placeholder="Alt. Phone" name="office_phone" id="office_phone" value="<?php echo $cus_data->office_phone; ?>" >
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label>Email</label>
            <input type="text" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo $cus_data->email; ?>"  <?php //if($cus_data->email){echo"readonly='true'";}else{} ?>> 
            
        </div>
        <div class="col-md-6">
            <label>Alternate Email</label>
            <input type="text" class="form-control" placeholder="Alt. Email" name="alt_email" id="alt_email" value="<?php echo $cus_data->alt_email; ?>"> 
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label>Address</label>
            <input type="text" class="form-control" placeholder="Address" name="address" id="address" value="<?php echo $cus_data->address; ?>">  
            
        </div> 
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label>State</label>    
            <select class="custom-select form-control model-select2" name="state_id" id="state" onchange="GetCityList(this.value)">
                <option value="">Select</option>
                  <?php foreach($state_list as $state_data)
                  {
                    ?>
                    <option value="<?php echo $state_data->id;?>" <?php if($cus_data->state==$state_data->id){?> selected <?php } ?>><?php echo $state_data->name;?></option>
                    <?php
                  }
                  ?>
            </select>
        </div> 
        <div class="col-md-4">
            <label>City</label>   
            <select class="custom-select form-control model-select2" name="city_id" id="city">
                  <option value="">Select</option>
                    <?php foreach($city_list as $city_data)
                    {
                      ?>
                      <option value="<?php echo $city_data->id;?>" <?php if($cus_data->city==$city_data->id){?> selected <?php } ?>><?php echo $city_data->name;?></option>
                      <?php
                    }
                    ?>
            </select>       
        </div>
        
        <div class="col-md-4">
            <label>PIN/ ZIP Code</label>
            <input type="text" class="form-control only_natural_number" placeholder="Zip Code" name="zip" id="zip" value="<?php echo ($cus_data->zip>0)?$cus_data->zip:''; ?>"> 
        </div>
    </div>

    <div class="form-group row">         
        <div class="col-md-6">
            <label>GST Number </label>
            <input type="text" class="form-control" placeholder="Type GST Number" name="gst_number" id="gst_number" value="<?php echo $cus_data->gst_number; ?>">  
            
        </div> 
        <div class="col-md-6">
            <label>Business Type </label>
            <select class="custom-select form-control" name="business_type_id" id="business_type_id">
                  <option value="">Select</option>
                    <?php foreach($business_type_list as $row)
                    {
                      ?>
                      <option value="<?php echo $row['id'];?>" <?php if($cus_data->business_type_id==$row['id']){echo"SELECTED";} ?>><?php echo $row['name'];?></option>
                      <?php
                    }
                    ?>
            </select>
            
        </div> 
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label>Website <small>(E.g. https://www.lmsbaba.com)</small></label>
            <input type="text" class="form-control com_website" placeholder="Type Website" name="website" id="website" value="<?php echo $cus_data->website; ?>" >  
            
        </div> 
        <div class="col-md-6">
            <label>Source </label>
            <select class="custom-select form-control" name="source_id" id="source_id" >
                <option value="">Select</option>
                <?php foreach($source_list as $source)
                {
                ?>
                <option value="<?php echo $source->id;?>" <?php if($cus_data->source_id==$source->id){echo"SELECTED";} ?> ><?php echo ($source->alias_name)?$source->alias_name:$source->name;?></option>
                <?php
                }
                ?>			                      
            </select>            
        </div>        
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label>Profile</label>
            <textarea class="form-control" name="short_description" id="short_description"><?php echo $cus_data->short_description; ?></textarea>     
        </div> 
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <label>Reference</label>
            <input type="text" class="form-control" placeholder="Type reference" name="reference_name" id="reference_name" value="<?php echo $cus_data->reference_name; ?>" maxlength="255">  
            
        </div>        
    </div>
    <div class="form-group text-center">
        <a href="javascript:;" class="btn btn-primary sss" id="update_customer_submit">
        Save
        </a>
    </div>
    
</form>
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
/*
function GetStateList(cont)
{
	var base_url=$("#base_url").val();
	$.ajax({
		  url: base_url+"lead/getstatelist",
		  type: "POST",
		  data: {'country_id':cont},		  
		  success: function (response) 
		  {
		  	if(response!='')
		  	{	//alert(response);
				//document.getElementById('state').innerHTML=response;
				$("#state").html(response);
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
	var base_url=$("#base_url").val();
	
	$.ajax({
		  url: base_url+"lead/getcitylist",
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
*/
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
function isUrl(url) 
{
    var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if(!regexp.test(url)) {
      return false;
    }
    else
    {
      return true;
    }

    
}
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
    // $("body").on("change","#country",function(e){
    //     var cid=$(this).val();
    //     var base_url = $("#base_url").val();
    //     $.ajax({
    //         url: base_url + "lead/get_country_code_ajax",
    //         type: "POST",
    //         data: {
    //             'cid': cid
    //         },
    //         async: true,
    //         success: function(data) {
    //             result = $.parseJSON(data);                
    //             $("#mobile_country_code_div").html('+'+result.country_code);
    //             $("#alt_mobile_country_code_div").html('+'+result.country_code);
    //             $("#landline_country_code_div").html('+'+result.country_code);
    //             $("#office_country_code_div").html('+'+result.country_code);
    //         },
    //         error: function() {
    //             swal({
    //                 title: 'Something went wrong there!',
    //                 text: '',
    //                 type: 'danger',
    //                 showCancelButton: false
    //             }, function() {

    //             });
    //         }
    //     });
    // });
});
</script>