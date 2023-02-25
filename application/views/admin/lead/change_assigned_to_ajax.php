<!-- <pre><?php print_r($user_list); ?></pre> -->
<form name="lead_assigne_change_frm" id="lead_assigne_change_frm" action="" method="POST">
    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>">
    <div class="row">
        <div class="col-md-12">
             <select class="custom-select select2" name="assigned_to" id="assigned_to" required>
                <option>Select</option>
                <?php
                foreach($user_list as $user_data)
                {
                    ?>
                    <option value="<?php echo $user_data['id']?>" <?php if($user_data['id']==$lead_info->assigned_user_id){ ?> selected="selected" <?php } ?>><?php echo $user_data['name'].' (Emp. ID: '.$user_data['id'].')';?></option>                    
                    <?php
                }
                ?>    
            </select>
        </div>
        <div>&nbsp;</div>
        <div class="col-md-12 text-right">
            <a href="javascript:;" class="btn btn-primary text-right" id="lead_assigne_change_submit">Save</a>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $(".select2").select2();
    });
</script>
<style type="text/css">
    .select2-container .select2-selection{
        min-height: 28px!important;
        line-height: 24px!important;
        height: 100%!important; 
}
.select2-container--default{ width: 100% !important; height: 40px; }
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 40px;
}
</style>