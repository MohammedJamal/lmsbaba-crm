<div class="company-details">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul class="justify-content-between">
                    <li><a href="JavaScript:void(0);" class="open_po_popup_steps btn btn-primary txt-upper pro-form-preview-bt" data-step="1" data-lowp="<?php echo $lowp; ?>" data-lo_id="<?php echo $po_register_info->lead_opportunity_id; ?>" data-lid="<?php echo $po_register_info->lead_id; ?>"><i class="fa fa fa-eye" aria-hidden="true"></i> Preview</a></li>
                  <li><a href="<?php echo base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_pro_forma_inv/'.$po_pro_forma_inv_info->lead_opportunity_wise_po_id);?>" class="btn btn-primary txt-upper pro-form-preview-bt <?php echo($po_pro_forma_inv_info->pro_forma_no)?'':'pfi_no_missing'; ?>" target="_blank" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>