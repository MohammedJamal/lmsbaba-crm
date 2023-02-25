<?php //print_r($gmail_data); ?>
<form id="frmCompanyAdd" name="frmCompanyAdd" method="post" action="" class="new-lead-sec-from">                    
    <input type="hidden" name="com_company_id" id="com_company_id" value="">

    <h5> COMPANY DETAILS</h5> 
    <div style="clear: both;"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Company Name<span class="text-danger">*</span>:</label>
                <input type="text" class="form-control" name="com_company_name" id="com_company_name" placeholder="Company Name" value="<?php echo $gmail_data['h_from_personal']; ?>"   />
                <div class="text-danger" id="com_company_name_error"></div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Contact Person<span class="text-danger">*</span>:</label>
            
                    <input type="text" class="form-control" name="com_contact_person" id="com_contact_person" placeholder="Contact Person" value="<?php echo $gmail_data['h_from_personal']; ?>" />
                    <div class="text-danger" id="com_contact_person_error"></div>
                
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group ">
                <label>Designation:</label>                
                <input type="text" class="form-control" name="com_designation" id="com_designation" placeholder="Designation" value="Manager" />
                <div class="text-danger" id="com_designation_error"></div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">
            <div class="form-group">
            <label>Email:</label>                
            <input type="text" class="form-control" name="com_email" id="com_email" placeholder="Email" value="<?php echo $gmail_data['h_from_mailbox'].'@'.$gmail_data['h_from_host']; ?>" readonly="true" />
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Alternate Email:</label>                
                <input type="text" class="form-control" name="com_alternate_email" id="com_alternate_email" placeholder="Alternate Email" value=""  />
                <div class="text-danger" id="com_alternate_email_error"></div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Mobile:</label>
                <div class="clear"></div>
               <div class="col-sm-3 p-0 border-right-0">
                    <input type="text" class="form-control" name="com_mobile_country_code" id="com_mobile_country_code" placeholder="Country Code" value=""  />
                </div>
                <div class="col-sm-9 p-0">
                    

                    <input type="text" class="form-control" name="com_mobile" id="com_mobile" placeholder="Mobile" value=""  />
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Alternate Mobile:</label>
                <div class="clear"></div>
                <div class="col-sm-3 p-0 border-right-0">
                    <input type="text" class="form-control" name="com_alt_mobile_country_code" id="com_alt_mobile_country_code" placeholder="Country Code" value=""  />
                </div>
                <div class="col-sm-9 p-0">
                    <input type="text" class="form-control" name="com_alternate_mobile" id="com_alternate_mobile" placeholder="Alternate Mobile" value=""  />
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">
            <div class="form-group" style="display:inline-block; width:100%;">
                <label>Landline Number:</label>
                <div>
                <div class="col-sm-2 p-0 border-right-0">
                    <input type="text" class="form-control" name="com_landline_country_code" id="com_landline_country_code" placeholder="Country Code" value=""  />
                </div>
                <div class="col-sm-2 p-0 border-right-0">
                    <input type="text" class="form-control" name="com_landline_std_code" id="com_landline_std_code" placeholder="Std Code" value=""  />
                </div>
                <div class="col-sm-8 p-0">
                    <input type="text" class="form-control" name="landline_number" id="landline_number" placeholder="Landline Number" value="" />
                </div>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">            
            <div class="form-group">
                <label>Address:</label>                
                <input type="text" class="form-control" name="com_address" id="com_address" placeholder="Address" value=""  />                
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Country<span class="text-danger">*</span>:</label>
                    <select class="custom-select form-control" name="com_country_id" id="com_country_id" onchange="GetStateList(this.value)" >
                          <option value="">Select</option>
                          <?php foreach($country_list as $country_data)
                          {
                            ?>
                            <option value="<?php echo $country_data->id;?>"><?php echo $country_data->name;?></option>
                            <?php
                          }
                          ?>
                    </select>
                    <input type="hidden" name="com_existing_country" id="com_existing_country"  value="<?php echo $tmp_com_country_id; ?>" />
                    <div class="text-danger" id="com_country_id_error"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>State:</label>
                <select class="custom-select form-control" name="com_state_id" id="com_state_id" onchange="GetCityList(this.value)">
                      <option value="">Select</option>
                    </select>
                <div class="text-danger" id="com_state_id_error"></div>
                <input type="hidden" name="com_existing_state" id="com_existing_state"  value="" />           
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label>City:</label>            
                <select class="custom-select form-control" name="com_city_id" id="com_city_id">
                    <option value="">Select</option>
                </select>
                <input type="hidden" name="com_existing_city" id="com_existing_city"  value="" />
            </div>        
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Zip:</label>                
                <input type="text" class="form-control" name="com_zip" id="com_zip" placeholder="Zip" value=""  />
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Website:</label>            
                <input type="text" class="form-control" name="com_website" id="com_website" placeholder="Website" value=""  />
            </div>        
        </div>
        <div class="col-md-6">
            <div class="form-group ">
                <label>Source<span class="text-danger">*</span>:</label>
                <select class="custom-select form-control" name="com_source_id" id="com_source_id" <?php if($tmp_com_source_id){echo"disabled='true'";}else{} ?>>
                    <option value="">Select</option>
                  <?php foreach($source_list as $source)
                  {
                    ?>
                    <option value="<?php echo $source->id;?>" <?php if($tmp_com_source_id==$source->id){echo"SELECTED";} ?>><?php echo $source->name;?></option>
                    <?php
                  }
                  ?>                                  
                </select>
                <input type="hidden" name="com_existing_source" id="com_existing_source"  value="" />
                <div class="text-danger" id="com_source_id_error"></div>
                                        
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Company Short Description:</label>                
                <textarea rows="5" cols="5" class="form-control" name="com_short_description" id="com_short_description" placeholder="Company Short Description" ></textarea>
            </div>
        </div>    
        <div style="clear: both;"></div>            
        <div class="col-md-12">
            <p id="sub_form" class="file margin_top_right_arrow " >
                <input type="submit" value="" id="add_to_company_submit_confirm" />
                <label for="file" class="btn_enabled"><span class="btn-text">Submit</span> <i class="fa fa-angle-right" aria-hidden="true"></i></label>
            </p>
        </div>   
    </div> 

</form>
<script type="text/javascript">
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
        {
          document.getElementById('com_state_id').innerHTML=response;
          $("#com_state_id").val($("#com_existing_state").val());
          GetCityList($("#com_state_id option:selected").val());
        }
          
      },
      error: function () 
      {
       //$.unblockUI();
       //alert('Something went wrong there');
        swal({
                title: "Danger!",
                text: "Something went wrong there",
                type: "danger",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
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
          document.getElementById('com_city_id').innerHTML=response;
          $("#com_city_id").val($("#com_existing_city").val())
        }
          
      },
      error: function () 
      {
       //$.unblockUI();
       //alert('Something went wrong there');
       swal({
                title: "Danger!",
                text: "Something went wrong there",
                type: "danger",
                confirmButtonText: "ok",
                allowOutsideClick: "false"
            });
      }
     });
}
</script>