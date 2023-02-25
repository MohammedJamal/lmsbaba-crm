<div>&nbsp;</div>
<div class="col-md-12 blue-title">Select Sub Department</div>  	
	<div class="col-sm-12" >
	    <select class="custom-select form-control get_chield set_name" id="select_rander_<?php echo $parent_id; ?>" onchange="get_chield__(this)" data-level=<?php echo($level+1); ?>  >
	          <option value="">Select</option>
	          <?php foreach($department_key_value as $k=>$val){ ?>
	          <?php
	          $val_arr=explode("#", $val);
	          $val_tmp = $val_arr[0];
	          $haschild=$val_arr[1];
	          ?>
	          <option value="<?php echo $k; ?>" <?php if($selected_value==$k){echo'SELECTED';} ?> data-id="<?php echo $haschild; ?>"><?php echo $val_tmp; ?></option>
	          <?php } ?>
	    </select>
	    <?php if($emp_id==''){ ?>         
		
		<!-- <a href="JavaScript:void(0);" class="add_department_ajax new-right" data-formaction="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']."/".$controller."/add_department_ajax"; ?>" data-pid="0">Add New</a> -->

		<a href="JavaScript:void(0);" class="add_department_ajax new-right" data-formaction="<?php echo base_url().$this->session->userdata['admin_session_data']['lms_url']."/".$controller."/add_department_ajax"; ?>" data-pid="<?php echo $parent_id; ?>" data-level=<?php echo($level+1); ?>>Add New</a>
		<?php } ?>
	</div>

