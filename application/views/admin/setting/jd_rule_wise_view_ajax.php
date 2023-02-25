<?php
if($rule_id==1)
{
	
	$existing_assign_to_arr=unserialize($row['assign_to']);
	?>
	<div class="form-group row">
	    <div class="col-md-12">
	      	<label for="" class="col-form-label">Select Users :</label>
	      	<select class="form-control custom-select justdial_assign_to" name="justdial_assign_to[]" id="justdial_assign_to" multiple="multiple" >
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
else if($rule_id==2)
{
	$existing_state_arr=array();
	//print_r($rules);
	$cnt=($jd_s_id)?(count($rules)-1):1;
	$rule_activity_count=($jd_s_id)?(count($rules)-1):1;
	if($jd_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}	
	?>
	<div id="jd_outer_div_rule_2"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="jd_add_more_2" class="jd_add_more">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="justdial_assign_to_2_other[]" id="justdial_assign_to_2_other" multiple="multiple" >
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
	$cnt=($jd_s_id)?(count($rules)-1):1;
	$rule_activity_count=($jd_s_id)?(count($rules)-1):1;
	if($jd_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}	
	?>
	<div id="jd_outer_div_rule_3"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="jd_add_more_3" class="jd_add_more">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="justdial_assign_to_3_other[]" id="justdial_assign_to_3_other" multiple="multiple" >
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
	$cnt=($jd_s_id)?(count($rules)-1):1;
	$rule_activity_count=($jd_s_id)?(count($rules)-1):1;
	if($jd_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}	
	?>
	<div id="jd_outer_div_rule_4"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="jd_add_more_4" class="jd_add_more">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class="form-control custom-select" name="justdial_assign_to_4_other[]" id="justdial_assign_to_4_other" multiple="multiple" >
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
	$cnt=($jd_s_id)?(count($rules)-1):1;
	$rule_activity_count=($jd_s_id)?(count($rules)-1):1;
	if($jd_s_id)
	{
		$index=$rule_activity_count;
		$existing_other_assign_to_arr=unserialize($rules[$index]['assign_to']);
	}
	else
	{
		$existing_other_assign_to_arr=array();
	}	
	?>
	<div id="jd_outer_div_rule_5"></div>
	<div class="add_more_bt"><a href="JavaScript:void(0);" id="add_more_5" class="jd_add_more">Add More</a></div>


	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="col-form-label">Assign all other leads to<span class="text-danger">*</span> :</label>
			<select class=" form-control custom-select" name="justdial_assign_to_5_other[]" id="justdial_assign_to_5_other" multiple="multiple" >
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
      <a href="javascript:void(0)" class="btn btn-success fix-w" id="jd_submit_confirm">Save</a>
      <a href="javascript:void(0)" class="btn btn-danger fix-w" id="jd_submit_close">Close</a>
   </div>
</div>
<?php
}
?>
<input type="hidden" id="jd_rule_id" value="<?php echo $rule_id; ?>">
<input type="hidden" id="jd_rule_count" name="jd_rule_count" value="<?php echo $cnt; ?>">
<input type="hidden" id="jd_rule_activity_count" name="jd_rule_activity_count" value="<?php echo $rule_activity_count; ?>">
<input type="hidden" id="jd_s_id" name="jd_s_id" value="<?php echo $jd_s_id; ?>">
<script type="text/javascript">
$(document).ready(function(){
	var ruleid=$("#jd_rule_id").val();
	var rule_count=$("#jd_rule_count").val();
	var s_id_tmp=$("#jd_s_id").val();
	//alert(rule_count+'/'+ruleid)
	var mode=(s_id_tmp)?'edit':'new';
	for(var i=1;i<=rule_count;i++)
	{
		fn_rander_outer_div_jd(i,ruleid,mode);
	}
});
function fn_rander_outer_div_jd(cnt,rule_id,mode)
{	
	var base_URL= $("#base_url").val();
	var jd_s_id=$("#jd_s_id").val();
	var data = "cnt="+cnt+"&rule_id="+rule_id+"&jd_s_id="+jd_s_id+"&mode="+mode;
	// alert(data);
	// return false;
	$.ajax({
	  url: base_URL+"setting/rander_jd_rule_wise_view_outer_div_ajax",
	  data: data,
	  cache: false,
	  method: 'GET',
	  dataType: "html",
	  beforeSend: function( xhr ) {},
	  success:function(res){ 
	     result = $.parseJSON(res);  
	     	$("#jd_outer_div_rule_"+rule_id).append(result.html);
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
	 complete: function(){},
	 error: function(response) {}
	});

}

</script>