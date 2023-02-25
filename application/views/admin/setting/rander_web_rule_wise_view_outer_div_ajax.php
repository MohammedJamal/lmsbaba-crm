<?php 
if($rule_id=='2')
{
	$index=($cnt-1);

	if($mode=='edit')
	{
		$existing_find_to_arr=unserialize($rules[$index]['find_to']);
		$existing_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
	}
	
	if($existing_find_to_arr=='other')
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
	}
	
	?>
	<div class="web_inner_div_loop card" id="web_inner_div_<?php echo $rule_id; ?>_<?php echo $cnt; ?>">
	<div class="del_div_bt"><a href="JavaScript:void(0);" class="web_del_div" data-cnt="<?php echo $cnt; ?>" data-ruleid="<?php echo $rule_id; ?>">X</a></div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Select Country<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select-auto" name="country_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="country_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" multiple="multiple" >
				<?php foreach($country_list AS $country){ ?>
					<option value="<?php echo $country->id; ?>" <?php if(in_array($country->id, $existing_find_to_arr)){echo'selected';} ?>><?php echo $country->name; ?></option>
				<?php } ?>						
			</select>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Select Users<span class="text-danger">*</span> :</label>
			<select class=" form-control custom-select" name="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>
					<option value="0" <?php if(in_array(0, $existing_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>						
			</select>
		</div>
	</div>
	</div>
	<?php	
}
else if($rule_id=='3')
{
	$index=($cnt-1);

	if($mode=='edit')
	{
		$existing_find_to_arr=unserialize($rules[$index]['find_to']);
		$existing_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
	}
	
	if($existing_find_to_arr=='other')
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
	}
	
	?>
	<div class="web_inner_div_loop card" id="web_inner_div_<?php echo $rule_id; ?>_<?php echo $cnt; ?>">
	<div class="del_div_bt"><a href="JavaScript:void(0);" class="web_del_div" data-cnt="<?php echo $cnt; ?>" data-ruleid="<?php echo $rule_id; ?>">X</a></div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Select Indian State<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select-auto" name="web_state_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="web_state_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" multiple="multiple" >
				<?php foreach($state_list AS $state){ ?>
					<option value="<?php echo $state->id; ?>" <?php if(in_array($state->id, $existing_find_to_arr)){echo'selected';} ?>><?php echo $state->name; ?></option>
				<?php } ?>						
			</select>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Select Users<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" multiple="multiple" >
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
					<input type="checkbox" name="tradeindia_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="" value="<?php echo $user->id; ?>" class="tradeindia_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" <?php if(in_array($user->id, $existing_assign_to_arr)){echo'checked';} ?>>
					<span class="checkmark"></span>
					<?php echo $user->name .'( Emp ID: '.$user->id.')'; ?>
					</label>                        
				</li>
				<?php } ?>
			</ul>
			<?php }*/ ?>
		</div>
	</div>
	</div>
	<?php	
}
else if($rule_id=='4')
{
	$index=($cnt-1);

	if($mode=='edit')
	{
		$existing_find_to_arr=unserialize($rules[$index]['find_to']);
		$existing_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
	}
	
	if($existing_find_to_arr=='other')
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
	}
	
	?>
	<div class="web_inner_div_loop card" id="web_inner_div_<?php echo $rule_id; ?>_<?php echo $cnt; ?>">
	<div class="del_div_bt"><a href="JavaScript:void(0);" class="web_del_div" data-cnt="<?php echo $cnt; ?>" data-ruleid="<?php echo $rule_id; ?>">X</a></div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Select Indian City<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select-auto" name="city_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="city_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" multiple="multiple" >
				<?php foreach($city_list AS $city){ ?>
					<option value="<?php echo $city->id; ?>" <?php if(in_array($city->id, $existing_find_to_arr)){echo'selected';} ?>><?php echo $city->name; ?></option>
				<?php } ?>						
			</select>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Select Users<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>
					<option value="0" <?php if(in_array(0, $existing_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>						
			</select>
			
		</div>
	</div>
	</div>
	<?php	
}
else if($rule_id=='5') // keyword
{
	$index=($cnt-1);

	if($mode=='edit')
	{
		$existing_find_to_arr=unserialize($rules[$index]['find_to']);
		$existing_assign_to_arr=unserialize($rules[$index]['assign_to']);
		$existing_find_to_str=implode(',',$existing_find_to_arr);
	}
	else
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
		$existing_find_to_str='';
	}
	
	if($existing_find_to_arr=='other')
	{
		$existing_find_to_arr=array();
		$existing_assign_to_arr=array();
		$existing_find_to_str='';
	}
	?>
	<div class="web_inner_div_loop card" id="web_inner_div_<?php echo $rule_id; ?>_<?php echo $cnt; ?>">
	<div class="del_div_bt"><a href="JavaScript:void(0);" class="web_del_div" data-cnt="<?php echo $cnt; ?>" data-ruleid="<?php echo $rule_id; ?>">X</a></div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Keyword<span class="text-danger">*</span> :</label>
			<input type="text" name="keyword_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" id="keyword_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" value="<?php echo $existing_find_to_str; ?>" class="form-control custom-tag" data-role="tagsinput" />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Select Users<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>[]" id="website_assign_to_<?php echo $rule_id; ?>_<?php echo $cnt; ?>" multiple="multiple" >
				<?php foreach($user_list AS $user){ ?>
					<option value="<?php echo $user->id; ?>" <?php if(in_array($user->id, $existing_assign_to_arr)){echo'selected';} ?>><?php echo $user->name .'( Emp ID: '.$user->id.')'; ?></option>
				<?php } ?>	
				<option value="0" <?php if(in_array(0, $existing_assign_to_arr)){echo'selected';} ?>>Common Lead Pool</option>					
			</select>
		</div>
	</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<?php	
}
?>
