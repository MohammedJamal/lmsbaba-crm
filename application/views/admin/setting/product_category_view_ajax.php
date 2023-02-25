<div class="col-md-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Add New Category:</legend>
        <div class="control-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="pc_group_id">Select Group:</label>
                    <select class="form-control" name="pc_group_id" id="pc_group_id">
                        <option value="">-- Select --</option>
                        <?php if(count($group_list)){ ?>
                        <?php foreach($group_list AS $group){ ?>
                        <option value="<?php echo $group['id']; ?>">
                        <?php echo $group['name']; ?>
                        </option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="pc_name">Name:</label>
                    <input type="text" class="form-control " name="pc_name" id="pc_name" placeholder="" value="" maxlength="255" >
                </div>
                
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn-success mt-25" id="product_category_add_submit">Add</a>
                </div>
            </div>
            
        </div>
    </fieldset>

    
</div>
<div class="col-md-12" >
    <?php if(count($rows)){ ?>
    <ul id="" class="list-group">
    <?php     
    $tmp_id='';
    foreach($rows AS $row){ ?>
        <?php
        if($tmp_id!=$row['group_id'])
        {
            ?>
            <lo class="list-group-item  bg-info" id=""><i class="fa fa-long-arrow-right" aria-hidden="true"></i> <?php  echo $row['group_name']; ?></lo>
           
            <?php
           
        }         
        if($tmp_id!=$row['group_id']){
            $tmp_id=$row['group_id'];            
        }
        ?>
        <li class="list-group-item d-flex justify-content-between align-items-center" id="li_<?php echo $row['id']; ?>">
            <span id="pc_output_div_<?php echo $row['id']; ?>" style="width: 30%;"><?php echo $row['name']; ?></span>
            <span id="pc_input_div_<?php echo $row['id']; ?>" style="width: 50%;" >
                <div class="input-group mb-3" style="display: none;" id="pc_input_div_inner_<?php echo $row['id']; ?>">
                    <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon2" value="<?php echo $row['name']; ?>" name="" id="pc_name_<?php echo $row['id']; ?>" >
                    <div class="input-group-append">
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn text-primary product_category_edit_submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        <a href="JavaScript:void(0);" data-id="<?php echo $row['id']; ?>" class="btn pc_input_div_close text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>         
                    </div>
                </div>
            </span>
            
            <span class="badge badge-primary badge-pill" style="background-color: #fff;" style="width: 20%;">
                <a href="JavaScript:void(0);" class="product_category_edit icon-btn btn-secondary text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;
                <?php if($row['is_product_tagged']=='N'){ ?>
                    <a href="JavaScript:void(0);" class="product_category_delete icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                <?php }else{ ?>
                    <a href="JavaScript:void(0);" class="icon-btn btn-alert text-white" data-id="<?php echo $row['id']; ?>"><i class="fa fa-ban" aria-hidden="true"></i></a>
                <?php } ?>
            </span>
            
        </li>
    <?php  } ?>
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
