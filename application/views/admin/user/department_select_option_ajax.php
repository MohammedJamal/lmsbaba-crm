 <option value="">Select</option>
  <?php foreach($department_key_value as $k=>$val){ ?>
  <?php
  $val_arr=explode("#", $val);
  $val_tmp = $val_arr[0];
  $haschild=$val_arr[1];
  ?>
 <option value="<?php echo $k; ?>" <?php if($selected_value==$k){echo'SELECTED';} ?> data-id="<?php echo $haschild; ?>"><?php echo $val_tmp; ?></option>
  <?php } ?>