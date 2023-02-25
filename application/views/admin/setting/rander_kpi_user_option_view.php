<option value="">Select</option>  
<?php foreach($kpi_user_list as $user){ ?>
    <option value="<?php echo $user['id']; ?>" data-kpi_setting_id="<?php echo $user['kpi_setting_id']; ?>"><?php echo $user['name']; ?></option>  
<?php } ?>