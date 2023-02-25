<option value="">Select</option>
<?php foreach($user_list as $row){ ?>
<option value="<?php echo $row['id']; ?>" data-kpi_setting_id="<?php echo $kpi_setting_id; ?>"><?php echo $row['name']; ?></option>  
<?php } ?>