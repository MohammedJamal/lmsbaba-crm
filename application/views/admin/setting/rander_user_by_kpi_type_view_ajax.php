<div>&nbsp;</div>
<input type="hidden" id="tmp_kpi_target_by" value="<?php echo $kpi_target_by; ?>" />
<div class="row">
    <?php if($kpi_target_by!='U'){ ?>
    <div class="col-md-6">
        <lable><b><?php echo ($kpi_target_by=='F')?'Functional Area':'Department'; ?> </b></lable>
        <select class="form-control" name="get_user_by_kpi_target_by" id="get_user_by_kpi_target_by">
            <option value="">Select</option>  
            <?php foreach($kpi_target_by_list as $row){ ?>
            <option value="<?php echo $row['id']; ?>" data-kpi_setting_id="<?php echo $row['kpi_setting_id']; ?>"><?php echo $row['name']; ?></option>  
            <?php } ?>
        </select>
    </div> 
    <?php } ?>
    <div class="col-md-6">
        <lable><b>User </b></lable>
        <select class="form-control" name="set_kpi_user_id" id="set_kpi_user_id">
            <option value="">Select</option>  
            <?php foreach($user_list as $row){ ?>
            <option value="<?php echo $row['id']; ?>" data-kpi_setting_id="<?php echo $row['kpi_setting_id']; ?>"><?php echo $row['name']; ?></option>  
            <?php } ?>
        </select>
    </div> 
</div>  