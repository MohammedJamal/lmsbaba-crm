<div class="form-group row">    
    <div class="col-md-12">        
        <select class="custom-select form-control model-select2 multiple-select" name="om_link_assign_user_id_id" id="om_link_assign_user_id_id" multiple data-live-search="true">
            
            <?php foreach($user_list as $row)
            {
                ?>
                <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                <?php
            }
            ?>            
        </select>
    </div>   
</div>

<div class="form-group text-center">
    <a href="javascript:;" class="btn btn-primary sss" id="tag_permission_link_wise_assign_user_popup_confirm" data-permission_link_id="<?php echo $permission_link_id; ?>">
    Save
    </a>
</div>
<script>
    $('.custom-select').select2({
        tags: false,                
    });	
</script>
