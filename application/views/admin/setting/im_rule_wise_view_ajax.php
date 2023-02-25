<?php
if($rule_id==1)
{
	
	$existing_assign_to_arr=unserialize($row['assign_to']);
?>
	<div class="form-group row">
	    <div class="col-md-12">
	      	<label for="" class="col-form-label">Select Users :</label>
	      	<select class="form-control custom-select indiamart_assign_to" name="indiamart_assign_to[]" id="indiamart_assign_to" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>	
				<option value="0" <?php if(in_array(0, $existing_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>					
			</select>


	      <?php /*if(count($user_list)){?>
			<ul class="employee_assign">
				<?php foreach($user_list as $user){ ?>
				<li>
					<label class="check-box-sec">
					<input type="checkbox" name="indiamart_assign_to[]" id="" value="<?php echo $user->id; ?>" class="indiamart_assign_to" <?php if(in_array($user->id, $existing_assign_to_arr)){echo'checked';} ?>>
					<span class="checkmark"></span>
					<?php echo $user->name .'( Emp ID: '.$user->id.')'; ?>
					</label>                        
				</li>
				<?php } ?>
			</ul>
	      	<?php }*/ ?>
	    </div>
	</div>
<?php
}
else if($rule_id==2) // Country
{
	$existing_state_arr=array();
	//print_r($rules);
	$cnt=($im_s_id)?(count($rules)-1):1;
	$rule_activity_count=($im_s_id)?(count($rules)-1):1;
	if($im_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}
?>
	<div id="outer_div_rule_2"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="add_more_2" class="add_more_im">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="indiamart_assign_to_2_other[]" id="indiamart_assign_to_2_other" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_other_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>
					<option value="0" <?php if(in_array(0, $existing_other_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>							
			</select>
		</div>
	</div>
<?php
}
else if($rule_id==3)
{
	$existing_state_arr=array();
	//print_r($rules);
	$cnt=($im_s_id)?(count($rules)-1):1;
	$rule_activity_count=($im_s_id)?(count($rules)-1):1;
	if($im_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}	
?>
	<div id="outer_div_rule_3"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="add_more_3" class="add_more_im">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="indiamart_assign_to_3_other[]" id="indiamart_assign_to_3_other" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_other_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>
					<option value="0" <?php if(in_array(0, $existing_other_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>							
			</select>

		<?php /*if(count($user_list)){?>
			<ul class="employee_assign">
				<?php foreach($user_list as $user){ ?>
				<li>
					<label class="check-box-sec">
					<input type="checkbox" name="indiamart_assign_to_3_other[]" id="" value="<?php echo $user->id; ?>" class="indiamart_assign_to" <?php if(in_array($user->id, $existing_other_assign_to_arr)){echo'checked';} ?>>
					<span class="checkmark"></span>
					<?php echo $user->name .'( Emp ID: '.$user->id.')'; ?>
					</label>                        
				</li>
				<?php } ?>
			</ul>
			<?php }*/ ?>
		</div>
	</div>

<?php
}
else if($rule_id==4)
{
	$existing_state_arr=array();
	//print_r($rules);
	$cnt=($im_s_id)?(count($rules)-1):1;
	$rule_activity_count=($im_s_id)?(count($rules)-1):1;
	if($im_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}	
	?>
	<div id="outer_div_rule_4"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="add_more_4" class="add_more_im">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class=" form-control custom-select" name="indiamart_assign_to_4_other[]" id="indiamart_assign_to_4_other" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_other_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>
					<option value="0" <?php if(in_array(0, $existing_other_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>							
			</select>
		</div>
	</div>
	<?php
}
else if($rule_id==5)
{
	$existing_state_arr=array();
	//print_r($rules);
	$cnt=($im_s_id)?(count($rules)-1):1;
	$rule_activity_count=($im_s_id)?(count($rules)-1):1;
	if($im_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}	
	?>
	<div id="outer_div_rule_5"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="add_more_5" class="add_more_im">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class=" form-control custom-select" name="indiamart_assign_to_5_other[]" id="indiamart_assign_to_5_other" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_other_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>
					<option value="0" <?php if(in_array(0, $existing_other_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>							
			</select>
		</div>
	</div>
	<?php
}
?>
<?php
if($rule_id!='')
{
?>
<div class="form-group row">
   <div class="col-md-12">
      <a href="javascript:void(0)" class="btn btn-success" id="im_submit_confirm">Save</a>
      <a href="javascript:void(0)" class="btn btn-danger" id="im_submit_close">Close</a>
   </div>
</div>
<?php
}
?>
<input type="hidden" id="rule_id" value="<?php echo $rule_id; ?>">
<input type="hidden" id="rule_count" name="rule_count" value="<?php echo $cnt; ?>">
<input type="hidden" id="rule_activity_count" name="rule_activity_count" value="<?php echo $rule_activity_count; ?>">
<input type="hidden" id="im_s_id" name="im_s_id" value="<?php echo $im_s_id; ?>">
<script type="text/javascript">
$(document).ready(function(){
	var ruleid=$("#rule_id").val();
	var rule_count=$("#rule_count").val();
	var im_s_id_tmp=$("#im_s_id").val();
	//alert(rule_count+'/'+ruleid)
	var mode=(im_s_id_tmp)?'edit':'new';
	for(var i=1;i<=rule_count;i++)
	{
		fn_rander_outer_div(i,ruleid,mode);
	}
	
});
	function fn_rander_outer_div(cnt,rule_id,mode)
	{
		var base_URL= $("#base_url").val();
		var im_s_id=$("#im_s_id").val();
		var data = "cnt="+cnt+"&rule_id="+rule_id+"&im_s_id="+im_s_id+"&mode="+mode;
		// alert(data)
		// return false;
		$.ajax({
		url: base_URL+"setting/rander_im_rule_wise_view_outer_div_ajax",
		data: data,
		cache: false,
		method: 'GET',
		dataType: "html",
		beforeSend: function( xhr ) {
			$.blockUI({ 
				message: 'Please wait...', 
				css: { 
					padding: '10px', 
					backgroundColor: '#fff', 
					border:'0px solid #000',
					'-webkit-border-radius': '10px', 
					'-moz-border-radius': '10px', 
					opacity: .5, 
					color: '#000',
					width:'450px',
					'font-size':'14px'
					}
			});
		},
		complete: function(){$.unblockUI();},
		success:function(res){ 
			result = $.parseJSON(res);  
			$("#outer_div_rule_"+rule_id).append(result.html);
			$('.custom-select-auto').select2({
					tags: false,
					ajax: {
					url: "<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']; ?>/setting/get_select2_autocomplete",
					dataType: 'json',
					delay: 250,
					data: function (data) {
						return {
							searchTerm: data.term, // search term
							rule_id:rule_id
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
			$('.custom-select').select2({
					tags: false,                
			});			

		},	 
		error: function(response) {}
		});

	}


</script>
