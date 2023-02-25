<script src="<?=assets_url();?>tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">  
  tinymce.init({
    selector: 'textarea.basic-wysiwyg-editor',
    force_br_newlines : true,
    force_p_newlines : false,
    forced_root_block : '',
    menubar: false,
    statusbar: false,
    toolbar: false,    
    setup: function(editor) {
        editor.on('focusout', function(e) {
          //console.log(editor);          
          // var quotation_id=$("#quotation_id").val();
          // var updated_field_name=editor.id;
          // var updated_content=editor.getContent();
          // fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
          //check_submit();
    })
    }
  });

  tinymce.init({
      selector: 'textarea.moderate-wysiwyg-editor',
      // height: 300,
      menubar: false,
      statusbar:false,
      plugins: ["code,advlist autolink lists link image charmap print preview anchor"],
      toolbar: 'bold italic backcolor | bullist numlist',
      content_css: [],
      setup: function(editor) {
        editor.on('focusout', function(e) {
          //console.log(editor);
          // var quotation_id=$("#quotation_id").val();
          // var updated_field_name=editor.id;
          // var updated_content=editor.getContent();
          // fn_update_quotation_letter(quotation_id,updated_field_name,updated_content);
          //check_submit();
      })
    }
  });  
  
</script>
<?php 
if($is_search_box_show=='Y'){ ?>
<div class="form_bg">            
    <div class="middle_form">
        <form id="searchCompanyFrm" name="searchCompanyFrm">
        	<input type="hidden" name="call_sync_id" value="<?php echo ($call_sync_id)?$call_sync_id:''; ?>">
        	<input type="hidden" name="show_search_box" id="show_search_box" value="N">
            <div class="padding_35">  
            <div class="row">
            	<div class="col-md-8">
            		<div class="form-group">
	                  <label class="full-label">E-mail</label>
	                  <div class="col-full"><input type="text" class="form-control" name="email" id="email" placeholder="E-mail" value="<?php echo $email; ?>" /></div>
	                </div>
	  			    <div class="form-group">
	  			      	<label class="full-label">Mobile</label>
	  			      	<div class="col-full"><input type="text" class="form-control only_natural_number" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $mobile; ?>" maxlength="15" /></div>
	                	<div class="col-sm-2 no_display" id="loader"><img src="<?=base_url();?>images/fetch.gif" alt="" /></div>
	                </div>
	                    
	            	<p id="reset_form" class="file margin_top">
	                 	<input type="button" value="" class="" id="search_company_submit_confirm" />
	                 	<label for="file" class="serach-btn">Save & Continue</label>
	                </p>
            	</div>
            	<div class="col-md-4">
            		<div class="grey-info-bg">
            		Buyer's Email ID or Mobile number is mandetory to add a new lead
            		</div>
            	</div>
            </div>          	
                          
               
	    	</div> 
	    </form>
  	</div>
</div>
<?php }else{ ?>
<?php
if(count($company)>1)
{
?>
<div class="form_bg" id="multiple_company_div">            
	<div class="middle_form">
		<div style="clear: both;"></div>
		<div class="padding_35">
    		<div class="email_infos">Following companies found with the given email id and/or mobile. Please select the desired company to continue:</div>		            
        	<table class="table table-bordered">
        		<tr>
        			<th class="text-center" width="15%">Tag With</th>
        			<th  width="55%">Company Name</th>
        			<!-- <th class="text-center">Email Matched</th>
        			<th class="text-center">Mobile Matched</th> -->
        			<th class="text-center" width="15%">Lead(s)</th>
        			<th class="text-center" width="15%">Details</th>
        		</tr>
        		<?php 
				$is_blacklist_flag=0;
				foreach($company as $c){ ?>
					<?php 
					if($c['is_blacklist']=='Y'){$is_blacklist_flag++;} ?>
        		<tr <?php if($c['is_blacklist']=='Y'){echo'class="table-danger"';} ?>>
        			<td align="center">
						<?php if($c['is_blacklist']=='N'){ ?>
        				<label class="check-box-sec rounded">
    						<input type="radio" name="tag_company_id" value="<?php echo $c['id']; ?>" class="tag_company_id" >
    						<span class="checkmark"></span>
  						</label>
						<?php } ?>
        			</td>
        			<td><?php echo $c['company_name']; ?></td>        			
        			<td align="center"><?php echo $c['lead_count']; ?></td>
        			<td align="center"><a href="JavaScript:void(0);" data-id="<?php echo $c['id']; ?>" class="view_company_detail">View</a></td>
        		</tr>
        		<?php } ?>
        	</table>			            	
	    </div>
		<?php if(count($company)!=$is_blacklist_flag){ ?>
	    <p id="reset_form" class="file margin_top">
         	<input type="button" value="" class="" id="get_lead_add_view" />
         	<label for="file" class="serach-btn">Save & Continue </label>
        </p>  
		<?php } ?>
	</div>
</div>
<?php			            	        	
}
$is_call_sync=(count($call_sync_info)>0)?'Y':'N';
if(count($company)==0)
{ 
	$tmp_company_id='';
	$assigned_user_id=(count($call_sync_info)>0)?$call_sync_info->user_id:'';
	$tmp_com_company_name='';
	$tmp_com_contact_person=(count($call_sync_info)>0)?$call_sync_info->name:'';
	$tmp_com_designation='';
	$tmp_email=$email;
	$tmp_com_alternate_email='';
	$tmp_mobile=$mobile;
	$tmp_com_alternate_mobile='';
	$tmp_com_address='';
	$tmp_com_country_id=(count($call_sync_info)>0)?$call_sync_com_country_id:'';;
	$tmp_com_state_id='';
	$tmp_com_city_id='';
	$tmp_com_zip='';
	$tmp_com_website='';
	$tmp_com_source_id=(count($call_sync_info)>0)?$call_sync_com_source_id:'';
	$tmp_com_short_description='';
}
else if(count($company)==1)
{ 
	
	$tmp_company_id=$company[0]['id'];
	$assigned_user_id=$company[0]['assigned_user_id'];
	$tmp_com_company_name=$company[0]['company_name'];
	$tmp_com_contact_person=$company[0]['contact_person'];
	$tmp_com_designation=$company[0]['designation'];
	$tmp_email=($company[0]['email'])?$company[0]['email']:$email;
	$tmp_com_alternate_email=$company[0]['alt_email'];
	if($company[0]['alt_email']!=''){
		$tmp_com_alternate_email=$company[0]['alt_email'];
	}
	else{
		if($company[0]['email']!='' && ($company[0]['email']!=$email)){
			$tmp_com_alternate_email=$email;
		}
	}	
	$tmp_mobile_country_code=$company[0]['mobile_country_code'];
	$tmp_mobile=($company[0]['mobile'])?$company[0]['mobile']:$mobile;
	$tmp_com_alt_mobile_country_code=$company[0]['alt_mobile_country_code'];
	$tmp_com_alternate_mobile=$company[0]['alt_mobile'];
	if($company[0]['alt_mobile']!=''){
		$tmp_com_alternate_mobile=$company[0]['alt_mobile'];
	}
	else{
		if($company[0]['mobile']!='' && ($company[0]['mobile']!=$mobile)){
			$tmp_com_alternate_mobile=$mobile;
		}
	}	
	
	$tmp_com_landline_country_code=$company[0]['landline_country_code'];
	$tmp_com_landline_std_code=$company[0]['landline_std_code'];
	$tmp_com_landline_number=$company[0]['landline_number'];
	$tmp_com_address=$company[0]['address'];
	$tmp_com_country_id=$company[0]['country_id'];
	$tmp_com_state_id=$company[0]['state'];
	$tmp_com_city_id=$company[0]['city'];
	$tmp_com_zip=($company[0]['zip']!=0)?$company[0]['zip']:'';
	$tmp_com_website=$company[0]['website'];
	$tmp_com_source_id=$company[0]['source_id'];
	$tmp_com_short_description=$company[0]['short_description'];
}
else
{
	$tmp_company_id='';
	$assigned_user_id='';
	$tmp_com_company_name='';
	$tmp_com_contact_person=(count($call_sync_info)>0)?$call_sync_info->name:'';
	$tmp_com_designation='';
	$tmp_email='';
	$tmp_com_alternate_email='';
	$tmp_mobile_country_code='';
	$tmp_mobile='';
	$tmp_com_alt_mobile_country_code='';
	$tmp_com_alternate_mobile='';
	$tmp_com_landline_country_code='';
	$tmp_com_landline_std_code='';
	$tmp_com_landline_number='';
	$tmp_com_address='';
	$tmp_com_country_id='';
	$tmp_com_state_id='';
	$tmp_com_city_id='';
	$tmp_com_zip='';
	$tmp_com_website='';
	$tmp_com_source_id='';
	$tmp_com_short_description='';
}
?>

<div class="form_bg" style="display:<?php if($is_add_a_new_lead_form_show=='Y'){echo 'block';}else{echo 'none';} ?>" id="lead_add_div">            
  <div class="middle_form">

    <form id="frmLeadAdd" name="frmLeadAdd" method="post" action="" class="new-lead-sec-from rounded-form">
    <input type="hidden" name="call_sync_id" value="<?php echo ($call_sync_id)?$call_sync_id:''; ?>">
		<input type="hidden" name="com_company_id" id="com_company_id" value="<?php echo $tmp_company_id; ?>">

    	<div style="clear: both;"></div>
		<div class="padding_35 full-l"> 
			<div class="form-group row">
				<div class="col-sm-9">
					<label class="full-label">Product / Service Required<span class="text-danger">*</span>:</label>
					<select class="custom-product-select-auto form-control" name="product_tags[]" id="product_tags" multiple="multiple" >
						<?php /*foreach($product_list AS $product){ ?>
							<option value="<?php echo $product['name']; ?>~<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></option>
						<?php }*/ ?>
					</select>
					<input type="hidden" class="" name="lead_title" id="lead_title" value=""/>
					<div class="text-danger" id="lead_title_error"></div>
				</div>
				<div class="col-sm-3">
					<label class="full-label">Lead Source<span class="text-danger">*</span>:</label>
					<select class="custom-select form-control" name="com_source_id" id="com_source_id" <?php //if($tmp_com_source_id){echo"disabled='true'";}else{} ?>>
						<option value="">Select</option>
                      <?php foreach($source_list as $source)
                      {
                        ?>
                        <option value="<?php echo $source->id;?>" <?php if($tmp_com_source_id==$source->id){echo"SELECTED";} ?> ><?php echo ($source->alias_name)?$source->alias_name:$source->name;?></option>
                        <?php
                      }
                      ?>			                      
                    </select>
                    <input type="hidden" name="com_existing_source" id="com_existing_source"  value="<?php echo $tmp_com_source_id; ?>" />
                    <div class="text-danger" id="com_source_id_error"></div>
					<?php 
					if($tmp_com_source_id){
					}else{echo '<a href="JavaScript:void(0)" class="add_source_popup">Add Source</a>';} 
					?>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-12">
					<label>Describe Requirements<span class="text-danger">*</span>:</label>
					<textarea rows="1" cols="1" class="form-control basic-wysiwyg-editor" name="lead_requirement" id="lead_requirement" placeholder="Describe Requirements"></textarea>
					<div class="text-danger" id="lead_requirement_error"></div>
				</div>
				<div class="col-sm-12 d-flex mt-10">
					Attach File (if any): &nbsp;&nbsp;<input type="file" name="lead_attach_file" id="lead_attach_file">
				</div>
			</div>
			
			<?php 
			if(!isset($is_customer_basic_data_show) || $is_customer_basic_data_show=='Y'){ 
				$class='show';
			} 
			else{
				$class='hide';
			}
			?>
			<div class="<?php echo $class; ?>">	
			<div class="form-group row">
				<div class="col-sm-6">
					<label class="full-label">Email ID:</label>
					<input type="text" class="form-control" name="com_email" id="com_email" placeholder="Email" value="<?php echo $tmp_email; ?>" <?php if($tmp_email){echo"readonly='true'";}else{} ?> />
					<div class="text-danger" id="com_email_error"></div>
				</div>
				<div class="col-sm-6">
					<label class="full-label">Mobile:</label>
					
                    <input type="text" class="form-control" name="com_mobile" id="com_mobile" placeholder="Mobile" value="<?php echo $tmp_mobile; ?>" <?php if($tmp_mobile){echo"readonly='true'";}else{} ?> />
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-6">
					<label class="full-label">Contact Person<span class="text-danger">*</span>:</label>
					<input type="text" class="form-control" name="com_contact_person" id="com_contact_person" placeholder="Contact Person" value="<?php echo $tmp_com_contact_person; ?>" <?php if($tmp_com_contact_person){echo"readonly='true'";}else{} ?> />
					<div class="text-danger" id="com_contact_person_error"></div>
				</div>
				<div class="col-sm-6">
					<label class="full-label">Company Name:</label>
					<input type="text" class="form-control" name="com_company_name" id="com_company_name" placeholder="Company Name" value="<?php echo $tmp_com_company_name; ?>" <?php if($tmp_com_company_name){echo"readonly='true'";}else{} ?>  />
					<div class="text-danger" id="com_company_name_error"></div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-6">
					<label class="full-label">Select Country<span class="text-danger">*</span>:</label>
					<select class="custom-select form-control" name="com_country_id" id="com_country_id" onchange="GetStateList(this.value,'#com_state_id')" <?php if($tmp_com_country_id){echo"disabled";} ?>>
	                      <option value="">Select</option>
	                      <?php foreach($country_list as $country_data)
	                      {
	                        ?>
	                        <option value="<?php echo $country_data->id;?>" <?php if($tmp_com_country_id==$country_data->id){echo"SELECTED";} ?>><?php echo $country_data->name;?></option>
	                        <?php
	                      }
	                      ?>
	                </select>
	                <input type="hidden" name="com_existing_country" id="com_existing_country"  value="<?php echo $tmp_com_country_id; ?>" />
	                <div class="text-danger" id="com_country_id_error"></div>
				</div>
				<div class="col-sm-6">
					<label class="full-label">Assign To<span class="text-danger">*</span>:</label>
					<?php 
					if($assigned_user_id)
					{
					?>
						<input type="hidden" name="assigned_user_id" name="assigned_user_id" value="<?php echo $assigned_user_id; ?>">
					<?php
					}
					?>
					<select class="custom-select form-control select2" name="assigned_user_id" id="assigned_user_id" required <?php if($assigned_user_id){echo"disabled='disabled'";}else{} ?>>
		                <option value="">Select</option>
		                <?php
		                foreach($user_list as $user_data)
		                {
							//if($user_data['id']!=1)
							//{
		                    ?>
		                    <option value="<?php echo $user_data['id']?>" <?php if($assigned_user_id==$user_data['id']){echo'SELECTED';} ?>><?php echo $user_data['name'].' (Emp. ID: '.$user_data['id'].')';?></option>                    
		                    <?php
							//}
		                }
		                ?>    
		            </select>
	                <div class="text-danger" id="assigned_user_id_error"></div>
	            <?php if($is_user_in_your_under=='N'){ ?>
	            <span class="text-danger"><b>The user is not mapped under you. So this lead will not been displayed on your lead board.</b></span>
	          <?php } ?>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-sm-6">
					<label class="full-label">Select State:</label>					
					<select class="custom-select form-control" name="com_state_id" id="com_state_id" onchange="GetCityList(this.value,'#com_city_id')" <?php if($tmp_com_state_id){echo"disabled";} ?> >
	                      <option value="">Select</option>
	                      <?php foreach($state_list as $state)
	                      {
	                        ?>
	                        <option value="<?php echo $state->id;?>" <?php if($tmp_com_state_id==$state->id){echo"SELECTED";} ?>><?php echo $state->name;?></option>
	                        <?php
	                      }
	                      ?>
	                </select>	                
	                <div class="text-danger" id=""></div>
				</div>
				<div class="col-sm-6">
					<label class="full-label">Select City:</label>
					
					<select class="custom-select form-control" name="com_city_id" id="com_city_id" <?php if($tmp_com_city_id){echo"disabled";} ?> >
	                      <option value="">Select</option>
	                      <?php foreach($city_list as $city)
	                      {
	                        ?>
	                        <option value="<?php echo $city->id;?>" <?php if($tmp_com_city_id==$city->id){echo"SELECTED";} ?>><?php echo $city->name;?></option>
	                        <?php
	                      }
	                      ?>
	                </select>	                
	                <div class="text-danger" id=""></div>
				</div>
			</div>
			
			
			<div class="form-group row">
				<div class="col-sm-6">
					<label class="full-label">Enquiry Received Date<span class="text-danger">*</span>:</label>
					<div class="rela-div">
						<span class="label-set">Select a date</span>
					<input type="text" class="form-control " name="lead_enq_date" id="lead_enq_date" placeholder="Enquiry date" value="<?php echo date_db_format_to_display_format(date('Y-m-d')); ?>" readonly="true" />
					</div>
					<div class="text-danger" id="lead_enq_date_error"></div>
				</div>
				<div class="col-sm-6">
					<label class="full-label">Next Follow Up Date<span class="text-danger">*</span>:</label>
					<div class="rela-div">
						<span class="label-set">Select a date</span>
					<input type="text" class="form-control " name="lead_follow_up_date" id="lead_follow_up_date" placeholder="Follow up date" value="<?php echo date_db_format_to_display_format(date('Y-m-d')); ?>" readonly="true" />
					</div>
					<div class="text-danger" id="lead_follow_up_date_error"></div>
				</div>
			</div>
			</div>
				

		</div>
		                  
      	<div class="col-sm-12 text-center">
        	<p id="sub_form" class="file in-p" >
            <input type="submit" value="" id="add_to_lead_submit_confirm" />
            <label for="file" class="serach-btn">
            	<span class="btn-text">Add Lead<span> 
            	
            </label>
             
        	</p>
      	</div>
        	<input type="hidden" name="com_designation" id="com_designation" value="<?php echo $tmp_com_designation; ?>" <?php if($tmp_com_designation){echo"readonly='true'";}else{} ?> />
        	<input type="hidden" name="com_alternate_email" id="com_alternate_email" value="<?php echo $tmp_com_alternate_email; ?>" <?php if($tmp_com_alternate_email){echo"readonly='true'";}else{} ?> />

        	<input type="hidden" name="com_alt_mobile_country_code" id="com_alt_mobile_country_code" value="<?php echo $tmp_com_alt_mobile_country_code; ?>" <?php if($tmp_com_alt_mobile_country_code){echo"readonly='true'";}else{} ?> />
        	<input type="hidden" name="com_alternate_mobile" id="com_alternate_mobile" value="<?php echo $tmp_com_alternate_mobile; ?>" <?php if($tmp_com_alternate_mobile){echo"readonly='true'";}else{} ?> />
        	<input type="hidden" name="com_landline_country_code" id="com_landline_country_code" value="<?php echo $tmp_com_landline_country_code; ?>" <?php if($tmp_com_landline_country_code){echo"readonly='true'";}else{} ?> />
        	<input type="hidden" name="com_landline_std_code" id="com_landline_std_code" value="<?php echo $tmp_com_landline_std_code; ?>" <?php if($tmp_com_landline_std_code){echo"readonly='true'";}else{} ?> />
			<input type="hidden" name="landline_number" id="landline_number" value="<?php echo $tmp_com_landline_number; ?>" <?php if($tmp_com_landline_number){echo"readonly='true'";}else{} ?> />

			<input type="hidden" name="office_std_code" id="office_std_code" value="" />
			<input type="hidden" name="office_phone" id="office_phone" value="" />


			<input type="hidden" name="com_address" id="com_address" value="<?php echo $tmp_com_address; ?>" <?php if($tmp_com_address){echo"readonly='true'";}else{} ?> />

			<!--
			<select name="com_state_id" id="com_state_id" onchange="GetCityList(this.value)" <?php if($tmp_com_state_id){echo"disabled";} ?> style="display: none;">
              <option value="">Select</option>
            </select>
			-->
                    
			<input type="hidden" name="com_existing_state" id="com_existing_state"  value="<?php echo $tmp_com_state_id; ?>" />
			<!--
			<select name="com_city_id" id="com_city_id" <?php if($tmp_com_city_id){echo"disabled";} ?> style="display: none;">
            	<option value="">Select</option>
            </select>
			-->
			<input type="hidden" name="com_existing_city" id="com_existing_city"  value="<?php echo $tmp_com_city_id; ?>" />

			<input type="hidden" name="com_website" id="com_website" value="<?php echo $tmp_com_website; ?>" <?php if($tmp_com_website){echo"readonly='true'";}else{} ?> />			
			<input type="hidden" name="com_short_description" id="com_short_description" value="<?php echo $tmp_com_short_description; ?>" <?php if($tmp_com_short_description){echo"readonly='true'";}else{} ?> />
			<input type="hidden"  name="com_zip" id="com_zip"  value="<?php echo $tmp_com_zip; ?>" <?php if($tmp_com_zip){echo"readonly='true'";}else{} ?> />
			<input type="hidden" class="" name="com_mobile_country_code" id="com_mobile_country_code" value="<?php echo $tmp_mobile_country_code; ?>" <?php if($tmp_mobile_country_code){echo"readonly='true'";}else{} ?> />
        </form>

        
  </div>
</div> 
<?php } ?>
<script type="text/javascript">
$(document).ready(function() {
    // $('#product_tags').select2({
    // 	tags: true,
    // });

	var base_url = $("#base_url").val();
	$('.custom-product-select-auto').select2({
			tags: true,
			ajax: {
			url: base_url+"lead/get_lead_product_select2_autocomplete",
			dataType: 'json',
			delay: 250,
			data: function (data) {
				return {
					searchTerm: data.term // search term
				};
			},
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});

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

});
$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
    $('#lead_enq_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5',
        maxDate: 0,
    });

    $('#lead_follow_up_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: false,
        changeYear: false,
        yearRange: '-100:+5',
        minDate: 0,
    });
});
</script>
