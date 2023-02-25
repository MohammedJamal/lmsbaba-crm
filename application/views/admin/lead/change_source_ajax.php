<!-- <pre><?php //print_r($user_list); ?></pre> -->
<form name="lead_source_change_frm" id="lead_source_change_frm" action="" method="POST">
    <input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>">
    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $lead_id; ?>">

    <div class="row">
        <div class="col-md-12">
            <h4 class="modal-title">Change Lead Source</h4>
            <select class="custom-select select2" name="lead_source" id="lead_source" required>
                <option value="">Select</option>
                <?php
                foreach($source_list as $source)
                {
                    ?>
                    <option value="<?php echo $source->id;?>" <?php if($source->id==$currsource){ ?> selected="selected" <?php } ?>><?php echo $source->name;?></option>                    
                    <?php
                }
                ?>    
            </select>
            <div class="text-danger" id="lead_source_error"></div>
        </div>
        <div>&nbsp;</div>
        <div class="col-md-12 text-right">
            <a href="javascript:;" class="btn btn-primary text-right" id="lead_source_change_submit">Save</a>
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