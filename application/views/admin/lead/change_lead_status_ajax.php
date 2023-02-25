<form name="lead_status_change_frm" id="lead_status_change_frm" action="" method="POST">
    <input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>">
    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>">

    <div class="row">
        <div class="col-md-12">
            <h4 class="modal-title">Change Status</h4>
            <select class="custom-select select2" name="status_id" id="status_id" required>
                <option value="">Select</option>
                <?php
                foreach($status_list as $status)
                {
                    ?>
                    <option value="<?php echo $status['id']?>" <?php if($status['id']==$curr_status_id){ ?> selected="selected" <?php } ?>><?php echo $status['name'];?></option>                    
                    <?php
                }
                ?>    
            </select>
            <div class="text-danger" id="status_id_error"></div>
        </div>  
        <div>&nbsp;</div>
        <div class="col-md-12 text-right">
            <a href="javascript:;" class="btn btn-primary text-right" id="lead_status_change_submit">Save</a>
        </div>
    </div>
</form>