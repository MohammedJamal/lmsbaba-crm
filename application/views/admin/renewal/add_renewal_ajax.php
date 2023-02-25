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
	  			      	<div class="col-full"><input type="text" class="form-control only_natural_number" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $mobile; ?>" /></div>
	                	<div class="col-sm-2 no_display" id="loader"><img src="<?=base_url();?>images/fetch.gif" alt="" /></div>
	                </div>
	                    
	            	<p id="reset_form" class="file margin_top">
	                 	<input type="button" value="" class="" id="search_company_submit_confirm" />
	                 	<label for="file" class="serach-btn">Save & Continue</label>
	                </p>
            	</div>
            	<div class="col-md-4">
            		<div class="grey-info-bg">
            		Buyer's Email ID or Mobile number is mandetory to add a new renewal/AMC
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
        		<?php foreach($company as $c){ ?>
        		<tr>
        			<td align="center">
        				<!-- <input type="radio" name="tag_company_id" value="<?php //echo $c['id']; ?>" class="tag_company_id"> -->
        				<label class="check-box-sec rounded">
    						<input type="radio" name="tag_company_id" value="<?php echo $c['id']; ?>" class="tag_company_id">
    						<span class="checkmark"></span>
  						</label>
        			</td>
        			<td><?php echo $c['company_name']; ?></td>
        			<!-- <td align="center">
        				<?php //echo ($c['email']==$email)?'Yes':'No'; ?>
        			</td>
        			<td align="center">
        				<?php //echo ($c['mobile']==$mobile)?'Yes':'No'; ?>
        			</td> -->
        			<td align="center"><?php echo $c['lead_count']; ?></td>
        			<td align="center"><a href="JavaScript:void(0);" data-id="<?php echo $c['id']; ?>" class="view_company_detail">View</a></td>
        		</tr>
        		<?php } ?>
        	</table>			            	
	    </div>
	    <p id="reset_form" class="file margin_top">
         	<input type="button" value="" class="" id="get_lead_add_view" />
         	<label for="file" class="serach-btn">Save & Continue </label>
        </p>  
	</div>
</div>
<?php			            	        	
}

if(count($company)==0)
{            	
	$tmp_company_id='';
	$tmp_com_company_name='';
	$tmp_com_contact_person='';
	$tmp_com_designation='';
	$tmp_email=$email;
	$tmp_com_alternate_email='';
	$tmp_mobile=$mobile;
	$tmp_com_alternate_mobile='';
	$tmp_com_address='';
	$tmp_com_country_id='';
	$tmp_com_state_id='';
	$tmp_com_city_id='';
	$tmp_com_zip='';
	$tmp_com_website='';
	$tmp_com_source_id='';
	$tmp_com_short_description='';
}
else if(count($company)==1)
{ 
	$tmp_company_id=$company[0]['id'];
	$assigned_user_id=$company[0]['assigned_user_id'];
	$tmp_com_company_name=$company[0]['company_name'];
	$tmp_com_contact_person=$company[0]['contact_person'];
	$tmp_com_designation=$company[0]['designation'];
	$tmp_email=$company[0]['email'];
	$tmp_com_alternate_email=$company[0]['alt_email'];
	$tmp_mobile_country_code=$company[0]['mobile_country_code'];
	$tmp_mobile=$company[0]['mobile'];
	$tmp_com_alt_mobile_country_code=$company[0]['alt_mobile_country_code'];
	$tmp_com_alternate_mobile=$company[0]['alt_mobile'];
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
	$tmp_com_contact_person='';
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
        
		<input type="hidden" name="com_company_id" id="com_company_id" value="<?php echo $tmp_company_id; ?>">

    	<div style="clear: both;"></div>
		<div class="padding_35 full-l">
			<?php /* ?> 
			<div class="form-group row">
				<div class="col-sm-9">
					<label class="full-label">Product / Service Required<span class="text-danger">*</span>:</label>
					<select class="js-example-basic-multiple form-control" name="product_tags[]" id="product_tags" multiple="multiple" >
						<?php foreach($product_list AS $product){ ?>
							<option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
						<?php } ?>
					</select>					
					<div class="text-danger" id="product_tags_error"></div>
				</div>
				<div class="col-sm-3">
					<label class="full-label">Renewal Amount<span class="text-danger">*</span>:</label>
					<input type="text" class="form-control double_digit" name="renewal_amount" id="renewal_amount" placeholder="" value="" >
					<div class="text-danger" id="renewal_amount_error"></div>
				</div>				
			</div>
			<?php */ ?>
			<input type="hidden" id="selected_product_str" value="">
			<div class="form-group row">
				<div class="col-sm-11">
					<label class="full-label">Product / Service Required<span class="text-danger">*</span>:</label>
					<select class="form-control" name="" id="product_to_add" >
						<option value="">Type Product / Service.</option>
						<?php foreach($product_list AS $product){ ?>
							<option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
						<?php } ?>
					</select>
					<div class="text-danger" id="product_tags_error"></div>	
				</div>	
				<div class="col-sm-1">
					<label class="full-label">&nbsp;</label>
					<a href="JavaScript:void(0)" id="add_product_btn" class="btn btn-primary">Add</a>
				</div>							
			</div>
			<div class="form-group row" >
				<div class="col-sm-12">
					<div id="selected_product_html_div"></div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-12">
					<label>Describe Renewal/ AMC Type<span class="text-danger">*</span>:</label>
					<textarea rows="1" cols="1" class="form-control basic-wysiwyg-editor" name="renewal_requirement" id="renewal_requirement" placeholder="Describe Requirements"></textarea>
					<div class="text-danger" id="renewal_requirement_error"></div>
				</div>
				<div class="col-sm-12 d-flex mt-10">
					Attach File (if any): &nbsp;&nbsp;<input type="file" name="renewal_attach_file" id="renewal_attach_file">
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
					<label class="full-label">Buyer’s Email ID:</label>
					<input type="text" class="form-control" name="com_email" id="com_email" placeholder="Email" value="<?php echo $tmp_email; ?>" <?php if($tmp_email){echo"readonly='true'";}else{} ?> />
					<div class="text-danger" id="com_email_error"></div>
				</div>
				<div class="col-sm-6">
					<label class="full-label">Buyer’s Mobile:</label>
					
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
				<div class="col-sm-4">
					<label class="full-label">Select Country<span class="text-danger">*</span>:</label>
					<select class="custom-select form-control" name="com_country_id" id="com_country_id" <?php if($tmp_com_country_id){echo"disabled";} ?>>
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
				<div class="col-sm-4">
					<label class="full-label">Assign To<span class="text-danger">*</span>:</label>
					<?php 
					//if($assigned_user_id)
					//{
					?>
						<input type="hidden" name="existing_assigned_user_id" name="existing_assigned_user_id" value="<?php echo $assigned_user_id; ?>">
					<?php
					//}
					?>
					<select class="custom-select form-control select2" name="assigned_user_id" id="assigned_user_id" required <?php if($assigned_user_id!='' && $assigned_user_id>0){echo"disabled='disabled'";}else{} ?>>
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
				<div class="col-sm-4">
					<input type="hidden" name="com_existing_source" id="com_existing_source"  value="<?php echo $tmp_com_source_id; ?>" />
					<label class="full-label">Source<span class="text-danger">*</span>:</label>
					<select class="custom-select form-control select2" name="com_source_id" id="com_source_id" required <?php if($tmp_com_source_id!='' && $tmp_com_source_id>0){echo"disabled='disabled'";}else{} ?>>
		                <option value="">Select</option>
		                <?php
		                foreach($source_list as $source)
		                {							
		                    ?>
		                    <option value="<?php echo $source->id;?>" <?php if($tmp_com_source_id==$source->id){echo"SELECTED";} ?> ><?php echo $source->name;?></option>                    
		                    <?php
							
		                }
		                ?>    
		            </select>
	              <div class="text-danger" id="com_source_id_error"></div>
	            
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-6">
					<label class="full-label">Renewal Date<span class="text-danger">*</span>:</label>
					<div class="rela-div">
						<span class="label-set">Select a date</span>
					<input type="text" class="form-control " name="renewal_date" id="renewal_date" placeholder="" value="<?php echo date_db_format_to_display_format(date('Y-m-d')); ?>" readonly="true" />
					</div>
					<div class="text-danger" id="renewal_date_error"></div>
				</div>
				<div class="col-sm-6">
					<label class="full-label">Next Follow Up Date<span class="text-danger">*</span>:</label>
					<div class="rela-div">
						<span class="label-set">Select a date</span>
					<input type="text" class="form-control " name="renewal_follow_up_date" id="renewal_follow_up_date" placeholder="Follow up date" value="<?php echo date_db_format_to_display_format(date('Y-m-d')); ?>" readonly="true" />
					</div>
					<div class="text-danger" id="renewal_follow_up_date_error"></div>
				</div>
			</div>
			</div>
				

		</div>
		                  
      	<div class="col-sm-12 text-center">
        	<p id="sub_form" class="file in-p" >
            <input type="submit" value="" id="add_to_renewal_submit_confirm" />
            <label for="file" class="serach-btn">
            	<span class="btn-text">Add Renewal/AMC<span> 
            	
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


			<select name="com_state_id" id="com_state_id" onchange="GetCityList(this.value)" <?php if($tmp_com_state_id){echo"disabled";} ?> style="display: none;">
              <option value="">Select</option>
            </select>
                    
			<input type="hidden" name="com_existing_state" id="com_existing_state"  value="<?php echo $tmp_com_state_id; ?>" />
					
			<select name="com_city_id" id="com_city_id" <?php if($tmp_com_city_id){echo"disabled";} ?> style="display: none;">
            	<option value="">Select</option>
            </select>
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
    // 	tags: false,
    // 	//tokenSeparators: [',', ' ']
    // }); 

    $('#product_to_add').select2({
    	tags: false
    });  


    
  //   $('#product_to_add').on('select2:select', function (e) {

	 //    var data = e.params.data;	    
	 //    var str = '';
	 //    if($("#selected_product_str").val()){
	 //    	existing_str=$("#selected_product_str").val();
	 //    	str=existing_str+','+data.id;
	 //    }
	 //    else{
	 //    	str=data.id;
	 //    }
		// 	$("#selected_product_str").val(str);
		// 	$("#selected_product_div").text('aaaa')
	 //  });

	  

		// $('#product_to_add').on('select2:unselect', function (e) {

			
  //     		var data = e.params.data;
		// 	    var newId=data.id;
		// 	    var str = '';
		// 	    if($("#selected_product_str").val())
		// 	    {
		// 	    	existing_str=$("#selected_product_str").val();
		// 	    	const arr = existing_str.split(",");
		// 	    	if(!arr.includes(newId)){}else{
		// 			    arr.splice(arr.indexOf(newId), 1);  
		// 			    var new_str=arr.join(',');
		// 				}
		// 	    }
		// 			$("#selected_product_str").val(new_str);
      
	    
	 //  });



    $(".double_digit").keydown(function(e) {
        debugger;
        var charCode = e.keyCode;
        if (charCode != 8) {
            //alert($(this).val());
            if (!$.isNumeric($(this).val()+e.key)) {
                return false;
            }
        }
    return true;
  });
});
$( function() {
    // $( "#datepicker" ).datepicker();
    // $( "#datepicker2" ).datepicker();    
    // $( ".datepicker_display_format" ).datepicker({dateFormat: "dd/mm/yy"});
    $('#renewal_date').datepicker({
        dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5',
        // maxDate: 0,
        minDate: 0,
        beforeShow: function( el ){
		       // alert($(el).val())
		    },
        onSelect: function(selectedDate) {
        	 
			    // custom callback logic here
			  //   var renewal_follow_up_date=$("#renewal_follow_up_date").val();
			   
					// var d = new Date(renewal_follow_up_date);
					// var dateString = [
					//   d.getFullYear(),
					//   ('0' + (d.getMonth() + 1)).slice(-2),
					//   ('0' + d.getDate()).slice(-2)
					// ].join('-');
					// var follow_date = dateString.substring(1);

					// var d = new Date(selectedDate);
					// var dateString = [
					//   d.getFullYear(),
					//   ('0' + (d.getMonth() + 1)).slice(-2),
					//   ('0' + d.getDate()).slice(-2)
					// ].join('-');
					// var renewal_date = dateString.substring(1);
			    
			  //   if(renewal_date<follow_date)
			  //   {
			  //   	alert("oops! Renewal date should be equal or greather than Next Follow Up Date");
			  //   	return false;
			  //   }
			  //   else
			  //   {
			  //   	$("#renewal_date").val(selectedDate);
			  //   }

			  },
			  onClose: function() {},
    });

    $('#renewal_follow_up_date').datepicker({
    		dateFormat: "dd-M-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+5',
        // maxDate: 0,
        minDate: 0,
        onSelect: function(selectedDate) {
			    // var renewal_follow_up_date=$("#renewal_follow_up_date").val();
			    // alert('renewal_follow_up_date:'+selectedDate+'/ renewal_date:'+renewal_date);
			  }
    });
});
</script>
