<?php 
$kpi_ids=$get_kpi_setting_info['kpi_ids'];
$kpi_ids_arr=explode(",",$kpi_ids);

$kpi_names=$get_kpi_setting_info['kpi_names'];
$kpi_names_arr=explode(",",$kpi_names);
// print_r($kpi_ids_arr); 
// echo count($kpi_ids_arr);
if($kpi_ids_arr[0]){
    $edit_display='none';
    $view_display='block';
}
else{
    $edit_display='block';
    $view_display='none';
}
?>
<h5><?php echo $name; ?></h5>
<div id="kpi_setting_div_edit" style="display:<?php echo $edit_display; ?>">
    
    <?php if(count($kpi_rows)){ ?>
        <ul class="employee_assign">
        <?php foreach($kpi_rows AS $row){ ?>        
            <li >
                <label class="check-box-sec">
                <input type="checkbox" name="kpi_ids[]" id="" value="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" class="" <?php if(in_array($row['id'],$kpi_ids_arr)){echo'checked';} ?>>
                <span class="checkmark"></span>
                    <?php echo $row['name']; ?>
                </label>                        
            </li>
        <?php } ?>
        </ul>
    <?php } ?>
    <div class="form-group row">
    <div class="col-md-12">
        <a href="javascript:void(0)" class="btn btn-success" id="kpi_submit_confirm">Save</a>
    </div>
    </div>
</div>
<div id="kpi_setting_div_view" style="display:<?php echo $view_display; ?>">
<div class="text-right"><a href="JavaScript:void(0);" class=" icon-btn btn-secondary text-white " id="kpi_setting_edit_action"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
<?php 
if(count($kpi_names_arr)){ ?>
    <ul class="list-group">
    <?php foreach($kpi_names_arr AS $kpi){ ?>    
        <li class="list-group-item"><?php echo $kpi; ?></li>
    <?php } ?>
    </ul>
<?php }?>
</div>