<select class="custom-select form-control" id="functional_area_id" name="functional_area_id">
    <option value="">Select</option>
    <?php foreach($functional_area_key_val as $k=>$val){ ?>
    <option value="<?php echo $k; ?>" <?php if($selected_value==$k){echo'SELECTED';} ?>><?php echo $val; ?></option>
    <?php } ?>
</select>