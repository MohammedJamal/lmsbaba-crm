<div class="form-group row">    
    <div class="col-md-12">
        <label>SMS API</label>
        <select class="custom-select form-control model-select2" name="sms_api_id" id="sms_api_id" onchange="GetSmsTemplateList(this.value,'','#template_id')">
            <option value="">Select</option>
            <?php foreach($api_list as $row)
            {
                ?>
                <option value="<?php echo $row['id'];?>" <?php if($row['id']==$template_row->sms_api_id){echo'selected';} ?>><?php echo $row['name'];?></option>
                <?php
            }
            ?>            
        </select>
    </div>   
</div>
<div class="form-group row">    
   
    <div class="col-md-12">
        <label>Template</label>
        <select class="custom-select form-control model-select2" name="template_id" id="template_id">
            <option value="">Select</option>                      
        </select>
    </div>
</div>
<div class="form-group text-center">
    <a href="javascript:;" class="btn btn-primary sss" id="update_sms_template_change_confirm" data-id="<?php echo $edit_id; ?>" data-field="<?php echo $field; ?>">
    Save
    </a>
</div>
<script>
    // alert(<?php echo $template_row->sms_api_id; ?>+'/'+<?php echo $selected_tid; ?>)
    GetSmsTemplateList('<?php echo $template_row->sms_api_id; ?>','<?php echo $selected_tid; ?>','#template_id');
</script>