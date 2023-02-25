<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Add New Lead Source:</legend>
        <div class="control-group">
            <div class="form-row">
                <div class="col-md-6">
                    <label for="ls_name">Name:</label>
                    <input type="text" class="form-control " name="ls_name" id="ls_name" placeholder="" value="" maxlength="255" >
                </div>
                
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn-success mt-25" id="lead_source_add_submit">Add</a>
                </div>
            </div>
            
        </div>
    </fieldset>
    
</div>
<div class="col-md-12" >
    <?php if(count($rows)){ ?>
    <ul id="" class="list-group">
    <?php foreach($rows AS $row){ ?>
        <li class="list-group-item d-flex justify-content-between align-items-center" id="li_<?php echo $row['id']; ?>">
            <span id="ls_output_div_<?php echo $row['id']; ?>" style="width: 30%;"><?php echo $row['name']; ?></span>
            <span id="ls_input_div_<?php echo $row['id']; ?>" style="width: 50%;" >
                <div class="input-group mb-3" style="display: none;" id="ls_input_div_inner_<?php echo $row['id']; ?>">
                    <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" value="<?php echo $row['name']; ?>" name="" id="ls_name_<?php echo $row['id']; ?>" >
                    <div class="input-group-append">
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn text-primary lead_source_edit_submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn ls_input_div_close text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>         
                    </div>
                </div>
            </span>
            
            <span class="badge badge-primary badge-pill" style="background-color: #fff;" style="width: 20%;">
                <a href="JavaScript:void(0);" class="lead_source_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <?php if($row['is_lead_tagged']=='N'){ ?>
                    <a href="JavaScript:void(0);" class="lead_source_delete icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                <?php }else{ ?>
                    <a href="JavaScript:void(0);" class="icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-ban" aria-hidden="true"></i></a>
                <?php } ?>
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
