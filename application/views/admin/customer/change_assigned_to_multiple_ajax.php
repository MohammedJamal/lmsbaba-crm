<!-- <pre><?php //print_r($user_list); ?></pre> -->
<?php //echo $tmp_u_ids_str; ?>
<form name="company_assigne_change_frm" id="company_assigne_change_frm" action="" method="POST">
    <input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>">  
    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>">
    <div class="row">
        <div class="col-md-12">
            <h4 class="modal-title">Change Lead Assignee</h4>
            <select class="custom-select select2" name="assigned_to" id="assigned_to" required>
                <option value="">Select</option>
                <?php
                foreach($user_list as $user_data)
                {
                    ?>
                    <option value="<?php echo $user_data['id']?>"><?php echo $user_data['name'].' (Emp. ID: '.$user_data['id'].')';?></option>                    
                    <?php
                }
                ?>    
            </select>
            <div class="text-danger" id="assigned_to_error"></div>
        </div>
        <div>&nbsp;</div>

        <div class="col-md-12">
            <h4 class="modal-title">Change Lead Observer</h4>
            <select class="custom-select select2" name="observer" id="observer" required>
                <option value="">Select</option>
                <?php
                foreach($user_list as $user_data)
                {
                    ?>
                    <option value="<?php echo $user_data['id']?>"><?php echo $user_data['name'].' (Emp. ID: '.$user_data['id'].')';?></option>                    
                    <?php
                }
                ?>    
            </select>
            <div class="text-danger" id="observer_error"></div>
        </div>
        <div>&nbsp;</div>
        <div class="col-md-12" style="display: none;">
            <label class="check-box-sec">
			    <input type="checkbox" name="is_mail_send_to_client" id="is_mail_send_to_client" class="" value="Y" checked="checked">
			    <span class="checkmark"></span>
			</label>
			Send relationship manager change intimation mail to client.
        </div>
        <div>&nbsp;</div>
        <div class="col-md-12 text-right">
            <a href="javascript:;" class="btn btn-primary text-right" id="company_assigne_change_multiple_submit">Save</a>
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