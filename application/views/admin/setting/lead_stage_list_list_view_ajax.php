<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Add New Stage:</legend>
        <div class="control-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="lead_stage_name">Stage Name:</label>
                    <input type="text" class="form-control " name="lead_stage_name" id="lead_stage_name" placeholder="" value="" maxlength="255"  oninput="this.value = this.value.toUpperCase()">
                </div>
                <div class="col-md-3">
                    <label for="lead_stage_position">Stage Position:</label>
                    <select class="form-control" name="lead_stage_position" id="lead_stage_position">
                        <option value="">-- Select --</option>
                        <option value="1">After</option>
                        <option value="2">Before</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="lead_stage_id">Stage Stage:</label>
                    <select class="form-control" name="lead_stage_id" id="lead_stage_id">
                        <option value="">-- Select --</option>
                        <?php if(count($rows)){ ?>
                        <?php foreach($rows AS $stage){ ?>
                        <option value="<?php echo $stage['id']; ?>">
                        <?php echo $stage['name']; ?>
                        </option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn-success mt-25" id="lead_stage_add_submit">Add</a>
                </div>
            </div>
            
        </div>
    </fieldset>

    
</div>
<div class="col-md-12 lead_stage_sortable_outer_div" >
    <?php if(count($rows)){ ?>
    <ul id="lead_stage_sortable" class="list-group">
    <?php foreach($rows AS $row){ ?>
        <li class="list-group-item d-flex justify-content-between align-items-center <?php echo ($row['is_system_generated']=='Y')?'ui-state-disabled':''; ?>" id="li_<?php echo $row['id']; ?>">
            <span id="output_div_<?php echo $row['id']; ?>" style="width: 30%; line-height: 28px;"><?php echo $row['name']; ?></span>
            <span id="input_div_<?php echo $row['id']; ?>" style="width: 50%;" >
                <div class="input-group mb-3" style="display: none;" id="input_div_inner_<?php echo $row['id']; ?>">
                    <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" value="<?php echo $row['name']; ?>" name="" id="stage_<?php echo $row['id']; ?>"  oninput="this.value = this.value.toUpperCase()">
                    <div class="input-group-append">
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn text-primary lead_stage_edit_submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>

                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn input_div_close text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>         
                    </div>
                    </div>
            </span>
            <?php if($row['is_system_generated']=='Y'){
                echo'Default';
            }
            else{
            ?>
            <span class="badge badge-primary badge-pill" style="background-color: #fff;" style="width: 20%;">
                <a href="JavaScript:void(0);" class="lead_stage_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a> &nbsp; 

                <a href="JavaScript:void(0);" class="icon-btn btn-alert text-white lead_stage_delete" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
