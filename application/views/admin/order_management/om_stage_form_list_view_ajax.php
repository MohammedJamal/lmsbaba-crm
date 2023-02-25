<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Add New Form:</legend>
        <div class="control-group">
            <div class="form-row">
                <div class="col-md-8">
                    <label for="om_stage_name">Form Name:</label>
                    <input type="text" class="form-control " name="om_form_name" id="om_form_name" placeholder="" value="" maxlength="255" >
                </div>
                <div class="col-md-2">
                    <label for="om_stage_name">Is mandatory:</label>
                    <select class="form-control " name="om_form_is_mandatory" id="om_form_is_mandatory">
                        <option value="N">No</option>
                        <option value="Y">Yes</option>
                    </select>                   
                </div> 
                <div class="col-md-2">
                    <a href="javascript:void(0)" class="btn btn-success mt-25" id="om_stage_form_add_submit">Add</a>
                </div>
            </div>
            
        </div>
    </fieldset>

    
</div>
<div class="col-md-12" >
    <?php if(count($rows)){ ?>
    <ul id="lead_stage_sortable" class="list-group">
    <li class="list-group-item active">Form List</li>
    <?php foreach($rows AS $row){ ?>
        <li class="list-group-item d-flex justify-content-between align-items-center <?php echo ($row['is_system_generated']=='Y')?'ui-state-disabled':''; ?>" id="li_<?php echo $row['id']; ?>">
            <span id="form_output_div_<?php echo $row['id']; ?>" style="width: 30%; line-height: 28px;"><?php echo $row['name']; ?></span>
            <span id="form_input_div_<?php echo $row['id']; ?>" style="width: 50%;" >
                <div class="input-group mb-3" style="display: none;" id="form_input_div_inner_<?php echo $row['id']; ?>">
                    <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" value="<?php echo $row['name']; ?>" name="" id="stage_form_name_<?php echo $row['id']; ?>" >
                    <div class="input-group-append">
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn text-primary om_stage_form_edit_submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>

                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn form_input_div_close text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>         
                    </div>
                    </div>
            </span>
            <?php if($row['is_system_generated']=='Y'){
                echo'Default';
            }
            else{
            ?>
            <span class="badge badge-primary badge-pill" style="background-color: #fff;" style="width: 20%;">
                <?php
                $curr_status=($row['is_mandatory']=='N')?'<i class="fa fa-unlock " aria-hidden="true"></i>':'<i class="fa fa-lock text-danger" aria-hidden="true" style="color:red !important"></i>'; 
                if($row['is_mandatory']=='N')
                {
                    $status_tooltip_text="Click to change mandatory form";
                }
                else
                {
                    $status_tooltip_text="Click to change non-mandatory form";
                }

                
                echo $status_link='<a href="JavaScript:void(0);" class="btn-status-change icon-btn btn-secondary text-white" data-curstatus="'.$row['is_mandatory'].'" data-id="'.$row['id'].'" id="status_'.$row['id'].'" title="'.$status_tooltip_text.'">'.$curr_status.'</a>&nbsp;';
                
                ?>
                <a href="JavaScript:void(0);" class="om_stage_form_field_set_popup icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-cogs" aria-hidden="true"></i></a> &nbsp; 

                <a href="JavaScript:void(0);" class="om_stage_form_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> &nbsp; 

                <a href="JavaScript:void(0);" class="icon-btn btn-alert text-white om_stage_form_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </span>
            <?php } ?>
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
