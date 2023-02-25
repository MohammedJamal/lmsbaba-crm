<select class="custom-select form-control" id="manager_id" name="manager_id">
    <option value="">Select</option>
    <?php foreach($department_wise_employee_key_val as $k=>$val){ ?>
    <option value="<?php echo $k; ?>" <?php if($selected_value==$k){echo'SELECTED';} ?>><?php echo $val; ?></option>
    <?php } ?>
</select>