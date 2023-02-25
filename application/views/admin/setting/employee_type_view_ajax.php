<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Add New User Type:</legend>
        <div class="control-group">
            <div class="form-row">
                <div class="col-md-6">
                    <label for="put_name">Name:</label>
                    <input type="text" class="form-control " name="et_name" id="et_name" placeholder="" value="" maxlength="255" >
                </div>
                
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn-success mt-25" id="employee_type_add_submit">Add</a>
                </div>
            </div>
            
        </div>
    </fieldset>
    
</div>
<div class="col-md-12">
    <?php if(count($rows)){ ?>
    <ul id="" class="list-group">
    <?php foreach($rows AS $row){ ?>
        <li class="list-group-item d-flex justify-content-between align-items-center" id="li_<?php echo $row['id']; ?>">
            <span id="bt_output_div_<?php echo $row['id']; ?>" style="width: 30%;"><?php echo $row['name']; ?></span>
            <span id="bt_input_div_<?php echo $row['id']; ?>" style="width: 60%;" >
                <div class="input-group mb-3" style="display: none;" id="bt_input_div_inner_<?php echo $row['id']; ?>">
                    <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" value="<?php echo $row['name']; ?>" name="" id="bt_name_<?php echo $row['id']; ?>" >
                    <div class="input-group-append">
                        
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn text-primary employee_type_edit_submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn bt_input_div_close text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>         
                        
                    </div>
                </div>
            </span>
            
            <span class="badge badge-primary badge-pill" style="background-color: #fff;" style="width: 10%;">
                <?php if($row['id']!='1'){ ?>
                <a href="JavaScript:void(0);" class="employee_type_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <a href="JavaScript:void(0);" class="employee_type_delete icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                <?php }else{} ?>
            </span>
            
        </li>
    <?php } ?>
    </ul>
    <?php }else{ ?>
        No record found!
    <?php } ?>
</div>

<style type="text/css">
.lead_stage_sortable_outer_div > ul > li {
    cursor: move;
}
</style>
