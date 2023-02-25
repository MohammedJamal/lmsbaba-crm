<select class="custom-select form-control" id="designation_id" name="designation_id">
    <option value="">Select</option>
    <?php foreach($designation_key_val as $k=>$val){ ?>
    <option value="<?php echo $k; ?>" <?php if($selected_value==$k){echo'SELECTED';} ?>><?php echo $val; ?></option>
    <?php } ?>
</select>