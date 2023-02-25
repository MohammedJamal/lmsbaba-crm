<?php 
$customer_tagged_to_the_number=0;
if($row->cust_id_str){
	$cust_id_arr=explode(",",$row->cust_id_str);
	$cust_contact_person_arr=explode(",",$row->cust_contact_person_str);
	$cust_company_name_arr=explode(",",$row->cust_company_name_str);
	$cust_mobile_arr=explode(",",$row->cust_mobile_str);
	$cust_email_arr=explode(",",$row->cust_email_str);
	$customer_tagged_to_the_number=count($cust_id_arr);
}
if($customer_tagged_to_the_number==0){
	$add_new_lead_class='add_as_lead';
}
else if($customer_tagged_to_the_number==1){
	$add_new_lead_class='add_as_lead_single';
}
else if($customer_tagged_to_the_number>1){
	$add_new_lead_class='add_as_lead_multiple';
}

?>


<div class="bg_white_filt">

    <ul class="filter_ul row" style="width:100%;">
    	<li class="col-md-4">                          
	        <a class="new_filter_btn <?php echo $add_new_lead_class; ?>" href="JavaScript:void(0);" data-id="<?php echo $row->id; ?>" data-cust_id="<?php echo $row->cust_id_str; ?>" data-cust_mobile="<?php echo $row->cust_mobile_str; ?>" data-cust_email="<?php echo $row->cust_email_str; ?>"> <span class="bg_span"><img src="<?php echo assets_url(); ?>images/adduesr_new.png"></span> Add New Lead</a> 
	    </li>
      	<li class="col-md-4">                          
	        <a class="new_filter_btn add_as_other_business" href="JavaScript:void(0);" data-id="<?php echo $row->id; ?>"> <span class="bg_span"><img src="<?php echo assets_url(); ?>images/adduesr_new.png"></span> Add As Business Call</a> 
	    </li>
	    <?php if(count($get_lead_list)){ ?>
	    <li class="col-md-4">                          
	        <a class="new_filter_btn call_history_update_lead" href="JavaScript:void(0);" data-id="<?php echo $row->id; ?>"> <span class="bg_span"><img src="<?php echo assets_url(); ?>images/bulk_update.png"></span> Update Lead</a> 
	    </li>  
	    <?php } ?>                           
    </ul>

    <div>&nbsp;</div>
    <div class="row" style="display:none;" id="add_as_other_business_div">
    	<div class="col-md-12">    
    		<div class="input-group">
				<input type="text" class="form-control" placeholder="Comment for Other Business Calls" aria-label="Comment for Other Business Calls" aria-describedby="basic-addon2" name="status_wise_msg" id="status_wise_msg">
				<div class="input-group-append">
					<button class="btn btn-outline-secondary text-primary" type="button" id="add_as_other_business_confirm_submit">Save</button>
					<button class="btn btn-outline-secondary text-danger" type="button" id="add_as_other_business_confirm_close">Close</button>
				</div>
			</div>
		</div>		
	</div>

	<div>&nbsp;</div>
    <div class="row" style="display:none;" id="call_history_update_lead_div">
    	<div class="col-md-12">    
    		<div class="input-group">
				<select class="custom-select form-control"  name="lead_for_call_update" id="lead_for_call_update">
				    <option value="">Select a Lead to Update</option>
				    <?php if(count($get_lead_list)){ ?>
				    	<?php foreach($get_lead_list AS $lead){ ?>
				    		<option value="<?php echo $lead->id; ?>"><?php echo $lead->title; ?> (#<?php echo $lead->id; ?>)</option>
				    	<?php } ?>
				    <?php } ?>
			  	</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary text-primary" type="button" id="call_history_update_lead_submit">Save</button>
					<button class="btn btn-outline-secondary text-danger" type="button" id="call_history_update_lead_close">Close</button>
				</div>
			</div>
		</div>		
	</div>

	<div>&nbsp;</div>
    <div class="row" style="display:none;" id="call_history_add_lead_multiple_div">
    	<div class="col-md-12">    
    		<div class="input-group">
				<select class="custom-select form-control"  name="cust_id_for_add_new_lead" id="cust_id_for_add_new_lead">
				    <option value="">Select a Customer to Add New Lead</option>
				    <?php if(count($cust_id_arr)){ ?>
				    	<?php 
						$is_blacklist_flag=0;
						$is_blacklist_arr=explode(",",$row->is_blacklist_str);
						for($i=0;$i<count($cust_id_arr);$i++){ 
							$is_blacklist_arr_tmp=explode("#",$is_blacklist_arr[$i]);
							$is_blacklist_id=$is_blacklist_arr_tmp[0];
							$is_blacklist=$is_blacklist_arr_tmp[1];
							if($is_blacklist=='Y' && $is_blacklist_id==$cust_id_arr[$i]){$is_blacklist_flag++;}
							?>
				    		<option value="<?php echo $cust_id_arr[$i]; ?>" data-mobile="<?php echo $cust_mobile_arr[$i]; ?>" data-email="<?php echo $cust_email_arr[$i]; ?>" <?php if($is_blacklist=='Y' && $is_blacklist_id==$cust_id_arr[$i]){echo 'disabled';} ?>><?php echo $cust_contact_person_arr[$i]; ?> <?php echo ($cust_id_arr[$i])?'#'.$cust_id_arr[$i]:''; ?><?php echo ($cust_company_name_arr[$i])?' ('.$cust_company_name_arr[$i].')':''; ?></option>
				    	<?php } ?>
				    <?php } ?>
			  	</select>
				<div class="input-group-append">
					<?php if(count($cust_id_arr)!=$is_blacklist_flag){ ?>
					<button class="btn btn-outline-secondary text-primary" type="button" id="add_as_lead_multiple_submit">Add</button>
					<?php } ?>
					<button class="btn btn-outline-secondary text-danger" type="button" id="add_as_lead_multiple_close">Close</button>
				</div>
			</div>
		</div>		
	</div>

</div>